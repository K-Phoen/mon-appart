<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Entity\Offer;
use App\Search\Criteria;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

class Blocket implements Crawler
{
    // divide a SEK amount by this rate to get the price in EUR
    private const SEK_TO_EUR_RATE = 10.2704;
    private const USER_AGENT = 'Mozilla/5.0 (X11; Linux x86_64; rv:61.0) Gecko/20100101 Firefox/61.0';

    private $httpClient;
    private $httpRequestFactory;

    public function __construct(HttpClient $httpClient, RequestFactory $httpRequestFactory)
    {
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
    }

    /**
     * @param Criteria[] $criteria
     *
     * @return Offer[]
     */
    public function resultsFor(array $criteria): iterable
    {
        $url = 'https://www.blocket.se/bostad/uthyres/stockholm/stockholms-stad?cg_multi=3020&sort=&ss=1&se=&ros=&roe=&bs=&be=&mre=12000&q=&q=&q=&is=1&save_search=1&l=0&md=th&f=p&f=c&f=b';
        $request = $this->httpRequestFactory->createRequest('POST', $url)
            ->withAddedHeader('User-Agent', self::USER_AGENT);
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Search failed');
        }

        $content = $response->getBody()->getContents();

        foreach ($this->extractOffers($content) as $offer) {
            yield $offer;
        }
    }

    private function extractOffers(string $source): iterable
    {
        $domCrawler = new DomCrawler($source);

        return $domCrawler->filter('.item_row')->each(function (DomCrawler $node) {
            return $this->domNodeToOffer($node);
        });
    }

    private function domNodeToOffer(DomCrawler $node): Offer
    {
        $title = trim($node->filter('.xiti_ad_heading')->text());
        $url = $node->filter('.item_link')->attr('href');
        $price = $this->parsePrice($node->filter('.monthly_rent')->text());

        $tz = new \DateTimeZone(' 	Europe/Stockholm');
        $publishedAt = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $node->filter('.jlist_date_image')->attr('datetime'), $tz);

        try {
            $rooms = $this->parseRooms($node->filter('.rooms')->text());
        } catch (\InvalidArgumentException $e) {
            // not all offers have this information
            $rooms = 1; // assume a single room
        }

        try {
            $area = $this->parseArea($node->filter('.size')->text());
        } catch (\InvalidArgumentException $e) {
            // not all offers have this information
            $area = 0;
        }

        return Offer::createFromArray([
            'title' => $title,
            'description' => $this->fetchDescription($url),
            'url' => $url,
            'thumb_url' => $this->extractThumbUrl($node),
            'price' => (int) ($price / self::SEK_TO_EUR_RATE),
            'location' => [
                'latitude' => 0,
                'longitude' => 0,
            ],
            'rooms' => $rooms,
            'area' => $area,
            'created_at' => $publishedAt,
            'is_furnished' => false, // don't know, so assume it's not
            'is_charges_included' => false, // don't know, so assume it's not
        ]);
    }

    private function fetchDescription(string $offerUrl): string
    {
        $request = $this->httpRequestFactory->createRequest('GET', $offerUrl)
            ->withAddedHeader('User-Agent', self::USER_AGENT);

        $response = $this->httpClient->sendRequest($request);

        $domCrawler = new DomCrawler($response->getBody()->getContents());

        return $domCrawler->filter('.object-text')->text();
    }

    private function extractThumbUrl(DomCrawler $node): string
    {
        try {
            $mediaObject = $node->filter('.sprite_list_placeholder > a.media-object');
            $style = $mediaObject->attr('style');
        } catch (\InvalidArgumentException $e) {
            return ''; // no thumb
        }

        $pos = strpos($style, 'url(');

        return substr($style, $pos+4, -2);
    }

    private function parsePrice(string $rawPrice): int
    {
        $price = explode('kr', trim($rawPrice))[0];

        return (int) str_replace(' ', '', $price);
    }

    private function parseArea(string $rawArea): int
    {
        $area = explode('m', trim($rawArea))[0];

        return (int) str_replace(' ', '', $area);
    }

    private function parseRooms(string $rawRooms): int
    {
        $rooms = explode('rum', trim($rawRooms))[0];

        return (int) str_replace(' ', '', $rooms);
    }
}

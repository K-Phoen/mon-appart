<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Crawler\Crawler;
use App\Entity\Offer;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;

class Leboncoin implements Crawler
{
    private $httpClient;
    private $httpRequestFactory;

    public function __construct(HttpClient $httpClient, RequestFactory $httpRequestFactory)
    {
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
    }

    public function resultsFor(array $criteria): iterable
    {
        $request = $this->httpRequestFactory->createRequest('POST', 'https://api.leboncoin.fr/finder/search')
            ->withAddedHeader('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64; rv:61.0) Gecko/20100101 Firefox/61.0')
            ->withAddedHeader('api_key', 'ba0c2dad52b3ec')
            ->withBody(\guzzlehttp\psr7\stream_for('{"limit":35,"limit_alu":3,"filters":{"category":{"id":"10"},"enums":{"real_estate_type":["2"],"ad_type":["offer"]},"location":{"city_zipcodes":[{"city":"Lyon","label":"Lyon (toute la ville)"}],"regions":["22"]},"keywords":{},"ranges":{"square":{"min":40,"max":60}}}}'));
        $response = $this->httpClient->sendRequest($request);

        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        foreach ($payload['ads'] as $offer) {
            yield Offer::createFromArray($this->offerToArray($offer));
        }
    }

    private function offerToArray(array $offer): array
    {
        return [
            'title' => $offer['subject'],
            'description' => $offer['body'],
            'url' => $offer['url'],
            'thumb_url' => $offer['images']['thumb_url'] ?? '',
            'price' => $offer['price'][0],
            'location' => [
                'latitude' => $offer['location']['lat'],
                'longitude' => $offer['location']['lng'],
            ],
            'is_furnished' => $this->extractAttr($offer, 'furnished', false) === '1',
            'is_charges_included' => $this->extractAttr($offer, 'charges_included', false) === '1',
            'rooms' => $this->extractAttr($offer, 'rooms', 0),
            'area' => $this->extractAttr($offer, 'square', 0),
        ];
    }

    private function extractAttr(array $offer, string $attrName, $default = null)
    {
        $attribute = current(array_filter($offer['attributes'], function (array $attr) use ($attrName) {
            return $attr['key'] === $attrName;
        }));

        if (empty($attribute)) {
            return $default;
        }

        return $attribute['value'];
    }
}

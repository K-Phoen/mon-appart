<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Crawler\Crawler;
use App\Entity\Offer;
use App\Search\Criteria;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;

class Leboncoin implements Crawler
{
    private $httpClient;
    private $httpRequestFactory;
    private $criteriaConverter;

    public function __construct(HttpClient $httpClient, RequestFactory $httpRequestFactory, LeboncoinCriteriaConverter $criteriaConverter)
    {
        $this->httpClient = $httpClient;
        $this->httpRequestFactory = $httpRequestFactory;
        $this->criteriaConverter = $criteriaConverter;
    }

    /**
     * @param Criteria[] $criteria
     *
     * @return Offer[]
     */
    public function resultsFor(array $criteria): iterable
    {
        $request = $this->httpRequestFactory->createRequest('POST', 'https://api.leboncoin.fr/finder/search')
            ->withAddedHeader('User-Agent', 'Mozilla/5.0 (X11; Linux x86_64; rv:61.0) Gecko/20100101 Firefox/61.0')
            ->withAddedHeader('api_key', 'ba0c2dad52b3ec')
            ->withBody(\guzzlehttp\psr7\stream_for(json_encode($this->buildSearch($criteria))));
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('Search failed');
        }

        $content = $response->getBody()->getContents();
        $payload = json_decode($content, true);

        foreach ($payload['ads'] as $offer) {
            yield Offer::createFromArray($this->offerToArray($offer));
        }
    }

    private function buildSearch(array $criteria): array
    {
        $search = [
            'limit' => 35,
            'limit_alu' => 3,
            'filters' => [
                'category' => ['id' => '10'],
                'enums' => [
                    'real_estate_type' => ['2'], // flat
                    'ad_type' => ['offer'],
                ],
                'location' => [
                    'city_zipcodes' => [
                        [
                            'city' => 'Lyon',
                            'label' => 'Lyon (69003)',
                            'zipcode' => '69003',
                        ],
                        [
                            'city' => 'Lyon',
                            'label' => 'Lyon (69007)',
                            'zipcode' => '69007',
                        ],
                    ],
                    'regions' => ['22'],
                ],
                'ranges' => [], // to be filled by the criteria
            ],
        ];

        foreach ($criteria as $rawCriteria) {
            $search['filters']['ranges'] += $this->criteriaConverter->criteriaToArray($rawCriteria);
        }

        return $search;
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

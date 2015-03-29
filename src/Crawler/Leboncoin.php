<?php

namespace Crawler;

use Symfony\Component\DomCrawler\Crawler;

class Leboncoin
{
    private $criteriaMap = [
        'area_min'   => 'buildAreaMinCriteria',
        'price_max'  => 'buildPriceMaxCriteria',
        'rooms_min'  => 'buildRoomsMinCriteria',
        'locations'  => 'buildLocationCriteria',
        'type'       => 'buildTypeCriteria',
    ];

    private $paramsMap = [
        'Ville :'               => 'city',
        'Code postal :'         => 'zip_code',
        'Charges comprises :'   => 'including_charges',
        'Type de bien :'        => 'type',
        'Pièces :'              => 'rooms',
        'Meublé / Non meublé :' => 'includes_furnitures',
        'Surface :'             => 'area',
    ];

    public function fetchResultsLinks(array $criteria)
    {
        $searchUrl = $this->buildSearchUrl($criteria);
        $crawler   = new Crawler($this->fetchUrlContent($searchUrl));

        $links = $crawler->filter('.list-lbc a');

        $resultLinks = [];
        foreach ($links as $link) {
            // skip "alerts" links
            if (strpos($link->getAttribute('class'), 'alertsLink') !== false) {
                continue;
            }

            $resultLinks[] = $link->getAttribute('href');
        }

        return $resultLinks;
    }

    public function fetchLinks(array $links)
    {
        $results = [];

        foreach ($links as $link) {
            $results[] = $this->fetchLink($link);
        }

        return $results;
    }

    public function fetchLink($link)
    {
        $html    = $this->fetchUrlContent($link);
        $crawler = new Crawler($html);
        $data    = [
            'id'     => $link,
            'origin' => 'leboncoin.fr',
        ];

        // title
        $data['title'] = trim(str_replace(' - leboncoin.fr', '', $crawler->filter('title')->text()));

        // price
        $data['price'] = intval($crawler->filter('span.price')->text());

        // params
        $crawler->filter('.lbcParams tr')->each(function($node) use (&$data) {
            $header = trim($node->filter('th')->text());

            if (!empty($this->paramsMap[$header])) {
                $data[$this->paramsMap[$header]] = $node->filter('td')->text();
            }
        });

        // fix a few params
        $data['area']                = isset($data['area']) ? intval($data['area']) : null;
        $data['rooms']               = isset($data['rooms']) ? intval($data['rooms']) : null;
        $data['includes_furnitures'] = isset($data['includes_furnitures']) ? $data['includes_furnitures'] === 'Meublé' : null;
        $data['including_charges']   = isset($data['including_charges']) ? $data['including_charges'] === 'Oui' : null;

        // main picture
        $data['thumb'] = $crawler->filter('meta[property="og:image"]')->attr('content');

        // pictures
        $data['pictures'] = [];

        if (preg_match_all('`aImages\[\d+\] = "([^"]+)";`', $html, $matches)) {
            $data['pictures'] = $matches[1];
        }

        return array_filter($data);
    }

    private function buildSearchUrl(array $criteria)
    {
        $region = $criteria['region'];
        unset($criteria['region']);

        $filters = [];
        foreach ($criteria as $name => $value) {
            if (!isset($this->criteriaMap[$name])) {
                throw new \LogicException(sprintf('Unknown criteria "%s"', $name));
            }

            $filters = array_merge($filters, call_user_func([$this, $this->criteriaMap[$name]], $value));
        }

        return sprintf(
            'http://www.leboncoin.fr/locations/offres/%s/?f=a&th=1&%s',
            $region,
            http_build_query($filters)
        );
    }

    private function buildTypeCriteria($type)
    {
        $typesMap = [
            'house'   => 1,
            'flat'    => 2,
            'field'   => 3,
            'parking' => 4,
            'other'   => 5,
        ];

        if (!isset($typesMap[$type])) {
            throw new \LogicException('Invalid type given: '.$type);
        }

        return ['ret' => $typesMap[$type]];
    }

    private function buildAreaMinCriteria($area)
    {
        $areaMap = array_flip([
            0,
            20, 25, 30, 35, 40, 50, 60, 70, 80, 90,
            100, 110, 120, 150, 300,
        ]);

        if (!isset($areaMap[$area])) {
            throw new \LogicException('Invalid area given: '.$area);
        }

        return ['sqs' => $areaMap[$area]];
    }

    private function buildPriceMaxCriteria($price)
    {
        return ['mre' => $price];
    }

    private function buildRoomsMinCriteria($rooms)
    {
        return ['ros' => $rooms];
    }

    private function buildLocationCriteria($location)
    {
        return [
            'location' => is_array($location) ? implode(',', $location) : $location,
        ];
    }

    private function fetchUrlContent($url)
    {
        return file_get_contents($url);
    }
}

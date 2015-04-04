<?php

namespace Crawler;

use Symfony\Component\DomCrawler\Crawler;

class AVendreALouer implements OfferCrawler
{
    private $criteriaMap = [
        'locations'  => 'buildLocationCriteria',
        'type'       => 'buildTypeCriteria',
    ];

    public function fetchResultsLinks(array $criteria)
    {
        $searchUrl = $this->buildSearchUrl($criteria);
        $crawler   = new Crawler($this->fetchUrlContent($searchUrl));

        $links = $crawler->filter('#result-list li .details a.linkCtnr');

        $resultLinks = [];
        foreach ($links as $link) {
            $resultLinks[] = 'http://www.avendrealouer.fr' . $link->getAttribute('href');
        }

        return $resultLinks;
    }

    public function fetchLink($link)
    {
        $html    = $this->fetchUrlContent($link);
        $crawler = new Crawler($html);
        $data    = [
            'url'    => $link,
            'origin' => 'avendrealouer.fr',
        ];

        // title
        $data['title'] = trim($crawler->filter('.search-header h1')->text());

        // description
        $data['description'] = trim($crawler->filter('#propertyDesc .more')->html());

        // zip code
        $data['zip_code'] = $this->regexSearch('`"TownCode":"([^"]+)"`', $html);

        // city
        $data['city'] = $this->regexSearch('`"TownName":"([^"]+)"`', $html);

        // rooms
        $data['rooms'] = $this->regexSearch('`"RoomCount":(\d+)`', $html);

        // surface
        $data['area'] = $this->regexSearch('`"Surface":(\d+)`', $html);

        // price
        $data['price'] = $this->regexSearch('`"Price":(\d+)`', $html);

        // including charges
        $priceTag = $crawler->filter('.topSummary .display-price')->text();
        $data['including_charges'] = strpos($priceTag, 'CC') !== false;

        // pictures
        $data['pictures'] = $crawler->filter('.slideCtnr img')->each(function($node) {
            return $node->attr('src');
        });
        $data['thumb'] = empty($data['pictures']) ? null : $data['pictures'][0];

        return $data;
    }

    private function buildSearchUrl(array $criteria)
    {
        $params = [
            'price_max' => 'maximumPrice',
            'area_min'  => 'minimumSurface',
            'rooms_min' => 'roomComfortIds',
        ];
        $httpParams = [];

        foreach ($criteria as $name => $value) {
            if (empty($params[$name])) {
                continue;
            }

            $httpParams[$params[$name]] = $value;
            unset($criteria[$name]);
        }

        foreach ($criteria as $name => $value) {
            if (!isset($this->criteriaMap[$name])) {
                throw new \LogicException(sprintf('Unknown criteria "%s"', $name));
            }

            $httpParams = array_merge($httpParams, call_user_func([$this, $this->criteriaMap[$name]], $value));
        }

        return sprintf(
            'http://www.avendrealouer.fr/recherche.html?pageIndex=1&sortPropertyName=ReleaseDate&sortDirection=Descending&searchTypeID=2&transactionId=2&%s',
            http_build_query($httpParams)
        );
    }

    private function buildTypeCriteria($type)
    {
        $typesMap = [
            'flat' => 47,
        ];

        if (!isset($typesMap[$type])) {
            throw new \LogicException('Invalid type given: '.$type);
        }

        return ['typeGroupCategoryID' => 6, 'typeGroupIds' => $typesMap[$type]];
    }

    private function buildLocationCriteria($locations)
    {
        $map = [
            'Lyon 69002' => '4-33436',
            'Lyon 69003' => '4-33437',
        ];
        $locs = [];

        foreach ($locations as $location) {
            if (!isset($map[$location])) {
                throw new \LogicException('Invalid location given: '.$location);
            }

            $locs[] = $map[$location];
        }

        return ['localityIds' => implode(',', $locs)];
    }

    private function fetchUrlContent($url)
    {
        return file_get_contents($url);
    }

    private function regexSearch($regex, $content, $default = null)
    {
        if (preg_match($regex, $content, $matches)) {
            return $matches[1];
        }

        return $default;
    }
}

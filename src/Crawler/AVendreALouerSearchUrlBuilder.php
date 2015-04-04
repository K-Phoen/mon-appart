<?php

namespace Crawler;

use Symfony\Component\DomCrawler\Crawler;

class AVendreALouerSearchUrlBuilder
{
    private $criteriaMap = [
        'locations'  => 'buildLocationCriteria',
        'type'       => 'buildTypeCriteria',
    ];

    public function buildUrl(array $criteria)
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
}

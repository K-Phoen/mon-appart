<?php

namespace Crawler;

class LeboncoinSearchUrlBuilder
{
    private $criteriaMap = [
        'area_min'   => 'buildAreaMinCriteria',
        'price_max'  => 'buildPriceMaxCriteria',
        'rooms_min'  => 'buildRoomsMinCriteria',
        'locations'  => 'buildLocationCriteria',
        'type'       => 'buildTypeCriteria',
    ];

    public function buildUrl(array $criteria)
    {
        $filters = [];
        foreach ($criteria as $name => $value) {
            if (!isset($this->criteriaMap[$name])) {
                throw new \LogicException(sprintf('Unknown criteria "%s"', $name));
            }

            $filters = array_merge($filters, call_user_func([$this, $this->criteriaMap[$name]], $value));
        }

        return sprintf(
            'http://www.leboncoin.fr/locations/offres/rhone_alpes/?f=a&th=1&%s',
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
}

<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Search\Criteria;
use App\Search\CriteriaType;

class LeboncoinCriteriaConverter
{
    private const CONVERSION_MAP = [
        CriteriaType::AREA => 'square',
        CriteriaType::ROOMS => 'rooms',
    ];

    private const CONVERSION_FUNCS = [
        CriteriaType::AREA => 'convertMin',
        CriteriaType::ROOMS => 'convertMin',
        CriteriaType::LOCATION => 'convertLocation',
    ];

    private const LOCATIONS = [
        CriteriaType::LYON_3 => [
            'city' => 'Lyon',
            'label' => 'Lyon (69003)',
            'zipcode' => '69003',
        ],
        CriteriaType::LYON_7 => [
            'city' => 'Lyon',
            'label' => 'Lyon (69007)',
            'zipcode' => '69007',
        ],
    ];

    public function buildSearch(array $criteria): array
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
                'location' => [], // to be filled by the location criteria
                'ranges' => [], // to be filled by the criteria
            ],
        ];

        /** @var Criteria $rawCriteria */
        foreach ($criteria as $rawCriteria) {
            if ($rawCriteria->type()->getValue() === CriteriaType::LOCATION) {
                $search['filters']['location'] = $this->convertLocation($rawCriteria);
                continue;
            }

            $search['filters']['ranges'] += $this->convertMin($rawCriteria);
        }

        return $search;
    }

    public function criteriaToArray(Criteria $criteria): array
    {
        $criteriaType = $criteria->type()->getValue();

        if (!isset(self::CONVERSION_FUNCS[$criteriaType])) {
            throw new \LogicException(sprintf('Unknown criteria type "%s" for Leboncoin', $criteriaType));
        }

        return call_user_func([$this, self::CONVERSION_FUNCS[$criteriaType]], $criteria);
    }

    private function convertMin(Criteria $criteria): array
    {
        $criteriaType = $criteria->type()->getValue();

        return [
            self::CONVERSION_MAP[$criteriaType] => ['min' => $criteria->value()],
        ];
    }

    private function convertLocation(Criteria $criteria): array
    {
        $result = [
            'city_zipcodes' => [],
            'regions' => ['22'],
        ];

        foreach ($criteria->value() as $locationCode) {
            $result['city_zipcodes'][] = self::LOCATIONS[$locationCode];
        }

        return $result;
    }
}

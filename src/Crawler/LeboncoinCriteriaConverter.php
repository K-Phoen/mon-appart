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

    public function criteriaToArray(Criteria $criteria): array
    {
        $criteriaType = $criteria->type()->getValue();

        if (!isset(self::CONVERSION_MAP[$criteriaType])) {
            throw new \LogicException(sprintf('Unknown criteria type "%s" for Leboncoin', $criteriaType));
        }

        return [
            self::CONVERSION_MAP[$criteriaType] => ['min' => $criteria->value()],
        ];
    }
}

<?php

declare(strict_types=1);

namespace App\Search;

class Request
{
    /**
     * @return Criteria[]
     */
    public static function criteria(): array
    {
        return [
            new Criteria(CriteriaType::AREA(), 50),
            new Criteria(CriteriaType::ROOMS(), 3),
        ];
    }
}

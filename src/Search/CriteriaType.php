<?php

declare(strict_types=1);

namespace App\Search;

use MyCLabs\Enum\Enum;

class CriteriaType extends Enum
{
    public const ROOMS = 'rooms';
    public const AREA = 'area';
}

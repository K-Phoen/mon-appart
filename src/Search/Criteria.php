<?php

declare(strict_types=1);

namespace App\Search;

class Criteria
{
    /** @var CriteriaType */
    private $type;

    /** @var string|int|array */
    private $value;

    public function __construct(CriteriaType $type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    public function type(): CriteriaType
    {
        return $this->type;
    }

    public function value()
    {
        return $this->value;
    }
}

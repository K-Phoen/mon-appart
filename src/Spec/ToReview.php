<?php

declare(strict_types=1);

namespace App\Spec;

use RulerZ\Spec\AbstractSpecification;

class ToReview extends AbstractSpecification
{
    public function getRule()
    {
        return 'ignored = false';
    }
}

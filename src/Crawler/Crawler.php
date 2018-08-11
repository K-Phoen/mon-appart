<?php

declare(strict_types=1);

namespace App\Crawler;

interface Crawler
{
    public function resultsFor(array $criteria): iterable;
}

<?php

declare(strict_types=1);

namespace app\Domain\Contracts;

use app\Domain\DTO\RankingBreakdown;

interface RankingCalculatorInterface
{
    public function score(array $row, array $filters = []): RankingBreakdown;
}

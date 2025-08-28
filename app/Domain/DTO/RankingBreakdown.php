<?php

namespace app\Domain\DTO;

final class RankingBreakdown
{
    public function __construct(
        public readonly float $score,

        /** @var array<string,float|int|bool|string|null> */
        public readonly array $components
    ) {}
}

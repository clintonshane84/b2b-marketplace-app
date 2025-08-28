<?php

declare(strict_types=1);

namespace app\Domain\Services;

use app\Domain\Contracts\RankingCalculatorInterface;
use app\Domain\DTO\RankingBreakdown;

final class WeightedRankingCalculator implements RankingCalculatorInterface
{
    public function __construct(
        private readonly array $weights,
        private readonly array $norm
        ) {}
        
        public function score(array $row, array $filters = []): RankingBreakdown
        {
            $w = $this->weights;
            $n = $this->norm;
            
            $reviewAvg = max(0.0, min(5.0, (float)($row['review_avg'] ?? 0)));
            $reviewAvgNorm = $reviewAvg / 5.0;
            
            $reviewCount = (int)($row['review_count'] ?? 0);
            $reviewCountNorm = min(1.0, log($reviewCount + 1) / log($n['max_review_count'] + 1));
            
            $verified = (bool)($row['is_verified'] ?? false);
            $verifiedScore = $verified ? 1.0 : 0.0;
            
            $latestAwardYear = (int)($row['latest_award_year'] ?? 0);
            $year = (int)date('Y');
            $ageYears = $latestAwardYear > 0 ? max(0, $year - $latestAwardYear) : $n['awards_recency_horizon_years'];
            $awardsRecentNorm = 1.0 - min(1.0, $ageYears / $n['awards_recency_horizon_years']);
            
            $caseStudies = (int)($row['case_studies_count'] ?? 0);
            $caseStudiesNorm = min(1.0, $caseStudies / max(1, $n['max_case_studies']));
            
            $responseMinutes = (int)($row['response_time_minutes'] ?? $n['max_response_minutes']);
            $responseNorm = 1.0 - min(1.0, $responseMinutes / $n['max_response_minutes']);
            
            // Budget fit (prefer agencies within client budget)
            $budgetMin = $filters['budget_min'] ?? null;
            $budgetMax = $filters['budget_max'] ?? null;
            $agencyMin = $row['hourly_rate_min'] ?? null;
            $agencyMax = $row['hourly_rate_max'] ?? null;
            
            $budgetFit = 0.0;
            if ($budgetMin !== null && $budgetMax !== null && $agencyMin !== null && $agencyMax !== null) {
                // Overlap of ranges / union of ranges
                $overlap = max(0, min($budgetMax, $agencyMax) - max($budgetMin, $agencyMin));
                $union   = max($budgetMax, $agencyMax) - min($budgetMin, $agencyMin);
                $budgetFit = $union > 0 ? ($overlap / $union) : 0.0;
            }
            
            $components = [
                'review_avg'    => round($reviewAvgNorm, 4),
                'review_count'  => round($reviewCountNorm, 4),
                'verified'      => $verifiedScore,
                'awards_recent' => round($awardsRecentNorm, 4),
                'case_studies'  => round($caseStudiesNorm, 4),
                'response_time' => round($responseNorm, 4),
                'budget_fit'    => round($budgetFit, 4),
            ];
            
            $score = 0.0;
            foreach ($components as $k => $v) {
                $score += ($w[$k] ?? 0) * $v;
            }
            
            return new RankingBreakdown(score: round($score, 6), components: $components);
        }
}
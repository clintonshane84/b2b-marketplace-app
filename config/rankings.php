<?php

return [
    'cache_ttl' => 600, // seconds
    'weights' => [
        'review_avg' => 0.40,  // 0..5 normalized
        'review_count' => 0.15,  // log scale
        'verified' => 0.10,  // boolean boost
        'awards_recent' => 0.10,  // recency (years)
        'case_studies' => 0.10,  // normalized count
        'response_time' => 0.10,  // lower is better
        'budget_fit' => 0.05,  // match client budget range
    ],
    'normalization' => [
        'max_case_studies' => 25,
        'max_review_count' => 1000,
        'max_response_minutes' => 1440, // cap at 24h
        'awards_recency_horizon_years' => 5,
    ],
];

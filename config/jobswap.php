<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Post lifecycle
    |--------------------------------------------------------------------------
    */
    'post_lifetime_days' => 30,
    'expiry_reminder_days_before' => 3,

    /*
    |--------------------------------------------------------------------------
    | Swap fee
    |--------------------------------------------------------------------------
    */
    'swap_fee_cents' => (int) env('SWAP_FEE_CENTS', 500),

    /*
    |--------------------------------------------------------------------------
    | Employer approval link lifetime
    |--------------------------------------------------------------------------
    */
    'employer_link_lifetime_days' => 14,

    /*
    |--------------------------------------------------------------------------
    | Dropdown options (value => [lv label, en label])
    |--------------------------------------------------------------------------
    */
    'regions' => [
        'riga' => ['lv' => 'Rīga', 'en' => 'Riga'],
        'pieriga' => ['lv' => 'Pierīga', 'en' => 'Pieriga (Riga region)'],
        'vidzeme' => ['lv' => 'Vidzeme', 'en' => 'Vidzeme'],
        'kurzeme' => ['lv' => 'Kurzeme', 'en' => 'Kurzeme'],
        'zemgale' => ['lv' => 'Zemgale', 'en' => 'Zemgale'],
        'latgale' => ['lv' => 'Latgale', 'en' => 'Latgale'],
    ],

    'availability' => [
        'immediately' => ['lv' => 'Uzreiz', 'en' => 'Immediately'],
        '2_weeks' => ['lv' => '2 nedēļas', 'en' => '2 weeks notice'],
        '1_month' => ['lv' => '1 mēnesis', 'en' => '1 month notice'],
        '2_months' => ['lv' => '2 mēneši', 'en' => '2 months notice'],
        'negotiable' => ['lv' => 'Vienojoties', 'en' => 'Negotiable'],
    ],

];

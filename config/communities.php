<?php

return [

    // Fallback used when a meetup's community slug isn't in the list below.
    'default' => [
        'label' => 'Community',
        'mono' => 'MU',
        'color' => '#7d7263',
        'color_dark' => '#a89e8a',
        'topic' => 'Tech · Mauritius',
    ],

    // Single source of truth for communities.
    // Keyed by the slug stored in meetups.community and user.admin.
    // `label` drives the Filament admin dropdown; the rest drive frontend styling.
    'list' => [
        'mscc' => [
            'label' => 'MSCC',
            'mono' => 'MS',
            'color' => '#5B8DEF',
            'color_dark' => '#7BA8F5',
            'topic' => 'Development · Software',
        ],
        'cloudnativemu' => [
            'label' => 'Cloud Native MU',
            'mono' => 'CN',
            'color' => '#8A5BB8',
            'color_dark' => '#A97ED1',
            'topic' => 'Cloud · Kubernetes',
        ],
        'frontendmu' => [
            'label' => 'Frontend MU',
            'mono' => 'FE',
            'color' => '#D4A72C',
            'color_dark' => '#EEC25C',
            'topic' => 'Web · UI',
        ],
        'laravelmoris' => [
            'label' => 'LaravelMoris',
            'mono' => 'LM',
            'color' => '#C64545',
            'color_dark' => '#E06868',
            'topic' => 'PHP · Laravel',
        ],
        'pymug' => [
            'label' => 'PYMUG',
            'mono' => 'PY',
            'color' => '#3E8F6B',
            'color_dark' => '#5EB088',
            'topic' => 'Python · Data',
        ],
        'nugm' => [
            'label' => 'NUGM',
            'mono' => 'NU',
            'color' => '#2F6FB5',
            'color_dark' => '#5B9BDD',
            'topic' => '.NET · Microsoft',
        ],
        'gophersmu' => [
            'label' => 'Gophers MU',
            'mono' => 'GO',
            'color' => '#00ADD8',
            'color_dark' => '#4BCBEF',
            'topic' => 'Go · Backend',
        ],
        'gdg' => [
            'label' => 'GDG',
            'mono' => 'GDG',
            'color' => '#F57F34',
            'color_dark' => '#F58945',
            'topic' => 'Google · Development',
        ],
        'mobilehorizon' => [
            'label' => 'Mobile Horizon',
            'mono' => 'MH',
            'color' => '#9C3D6E',
            'color_dark' => '#C5658F',
            'topic' => 'Mobile · iOS/Android',
        ],
        'pydata' => [
            'label' => 'PyData MU',
            'mono' => 'PD',
            'color' => '#4E9A4E',
            'color_dark' => '#7AC07A',
            'topic' => 'Data · ML',
        ],
        'dodocore' => [
            'label' => 'DodoCore',
            'mono' => 'DD',
            'color' => '#E07856',
            'color_dark' => '#F49374',
            'topic' => 'Systems · Low-level',
        ],
        'standalone' => [
            'label' => 'Standalone',
            'mono' => 'ST',
            'color' => '#7d7263',
            'color_dark' => '#a89e8a',
            'topic' => 'One-off event',
        ],
    ],

];

<?php
return [
    'extExceptions' => ['svg'],
    'directories'   => [
        'work' => [
            'small' => [
                'params'  => ['w' => 818,  'q' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 414px)'
                ]
            ],
            'medium' => [
                'params'  => ['w' => 1818,  'q' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 1200px)'
                ]
            ],
        ],
        'news' => [
            'small' => [
                'params'  => ['w' => 818,  'q' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 414px)'
                ]
            ],
            'medium' => [
                'params'  => ['w' => 1818,  'q' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 1200px)'
                ]
            ],
        ]
    ]
];

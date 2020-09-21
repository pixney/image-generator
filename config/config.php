<?php
return [
    'extExceptions' => ['svg'],
    'directories' =>
    [
        'work' => [
            'small' => [
                'params' => ['width' => 818,  'quality' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 414px)'
                ]
            ],
            'medium' => [
                'params' => ['width' => 1818,  'quality' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 1200px)'
                ]
            ],
        ],
        'news' => [
            'small' => [
                'params' => ['width' => 818,  'quality' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 414px)'
                ]
            ],
            'medium' => [
                'params' => ['width' => 1818,  'quality' => 90, 'fit' => 'crop'],
                'formats' => ['jpg', 'webp'],
                'queries' => [
                    'media' => '(max-width: 1200px)'
                ]
            ],
        ]
    ]
];

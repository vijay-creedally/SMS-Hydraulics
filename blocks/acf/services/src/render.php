<?php
/**
 * Block Template: Services
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'services position-relative',
]);

$allowed_blocks = [
    'core/group',
    'core/columns',
    'core/column',
    'core/heading', 
    'core/paragraph',
    'core/list', 
    'core/list-item',
];

$template = [
    [
        'core/group',
        [
            'className' => 'services__wrapper',
        ],
        [
            [
                'core/columns',
                [
                  'className' => 'services__inner-wrapper',
                  'style' => [
                    'spacing' => [
                      'margin' => '1.25rem'
                    ]
                  ]
                ],
                [
                    [
                        'core/column',
                        [
                            'className' => 'services__info',
                        ],
                        [
                            [
                                'core/heading',
                                [
                                    'level' => 3,
                                    'placeholder' => 'Enter Services Title…',
                                    'className' => 'services__title ani-top ani-fade',
                                ]
                            ],
                            [
                                'core/paragraph',
                                [
                                    'placeholder' => 'Enter services description…',
                                    'className' => 'services__description ani-top ani-fade',
                                ]
                            ]
                        ]
                    ],

                    [
                        'core/column',
                        [
                            'className' => 'services__list-wrapper',
                        ],
                        [
                            [
                                'core/list',
                                [
                                    'className' => 'services__items',
                                ],
                                [
                                    [
                                        'core/list-item',
                                        [
                                            'placeholder' => 'Service Item…',
                                            'className' => 'services__item ani-top ani-fade'
                                        ]
                                    ]
                                ]
                            ],

                            [
                                'core/list',
                                [
                                    'className' => 'services__items',
                                ],
                                [
                                    [
                                        'core/list-item',
                                        [
                                            'placeholder' => 'Service Item…',
                                            'className' => 'services__item ani-top ani-fade'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];

$video_file = get_field('video_file');
?>

<section <?= $attrs; ?>>

    <?php if (!empty($video_file)) : ?>
        <video class="services__video-bg" autoplay muted loop playsinline>
            <source src="<?php echo esc_url($video_file['url']); ?>" type="video/mp4">
        </video>
    <?php endif; ?>

    <div class="services__overlay"></div>

    <div class="container">
        <InnerBlocks
            allowedBlocks="<?php echo esc_attr( wp_json_encode($allowed_blocks) ); ?>"
            template="<?php echo esc_attr( wp_json_encode($template) ); ?>"
        />
    </div>

</section>

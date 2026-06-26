<?php
/**
 * Block Template: Testimonials (Fully Core Blocks)
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'testimonial',
]);

$allowed_blocks = [
    'core/group',
    'core/image',
    'core/heading',
    'core/paragraph',
];

$template = [
    [
        'core/group',
        [
            'className' => 'testimonial__wrapper',
        ],
        [
            [
                'core/image',
                [
                    'className' => 'testimonial__icon ani-top ani-fade',
                    'alt'       => 'Testimonial Icon',
                ]
            ],
            [
                'core/heading',
                [
                    'level'     => 3,
                    'className' => 'testimonial__label',
                ]
            ],
            [
                'core/heading',
                [
                    'level'       => 2,
                    'className'   => 'testimonial__heading ani-top ani-fade',
                ]
            ],
            [
                'core/heading',
                [
					'level'       => 5,
                    'className'   => 'testimonial__text ani-top ani-fade',
                ]
            ],
            [
                'core/group',
                [
                    'className' => 'testimonial__author ani-top ani-fade',
                ],
                [
                    [
                        'core/heading',
                        [
                            'level'       => 5,
                            'className'   => 'testimonial__author-name',
                        ]
                    ],
                    [
                        'core/paragraph',
                        [
                            'className'   => 'testimonial__author-role',
                        ]
                    ]
                ]
            ]
        ]
    ]
];
?>

<section <?= $attrs; ?>>
    <div class="container">
        <InnerBlocks
            allowedBlocks="<?php echo esc_attr( wp_json_encode($allowed_blocks) ); ?>"
            template="<?php echo esc_attr( wp_json_encode($template) ); ?>"
        />
    </div>
</section>

<?php
/**
 * Block Template: overview
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'overview position-relative',
]);

$allowed_blocks = [
    'core/columns',
    'core/column',
    'core/heading',
    'core/paragraph',
    'core/image'
];

$template = [
    [
        'core/columns',
        [
            'className' => 'overview__columns'
        ],
        [
            [
                'core/column',
                [
                    'width' => '60%',
                    'className' => 'overview__content'
                ],
                [
                    [
                        'core/heading',
                        [
                            'level' => 3,
                            'content' => 'Overview',
                            'className' => 'overview__title ani-top ani-fade',
                            'style' => [
                                'typography' => [
                                    'textTransform' => 'uppercase',
                                    'lineHeight' => '1.4'
                                ],
                                'spacing' => [
                                    'margin' => [
                                        'top' => '0px',
                                        'bottom' => '1rem'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        'core/paragraph',
                        [
                            'className' => 'overview__text ani-top ani-fade',
                        ]
                    ]
                ]
            ],

            [
                'core/column',
                [
                    'width' => '40%',
                    'className' => 'overview__image-wrapper'
                ],
                [
                    [
                        'core/image',
                        [
                            'className' => 'overview__image ani-top',
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

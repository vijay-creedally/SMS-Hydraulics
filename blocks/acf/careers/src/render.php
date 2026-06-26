<?php
/**
 * Block Template: Career block.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'career',
    'id' => !empty( $block['anchor'] ) ? $block['anchor'] : ''
]);

$allowed_blocks = [
    'core/image',
    'core/heading',
    'core/paragraph',
    'core/list',
    'core/list-item',
    'core/group',
    'core/columns',
    'core/column',
    'core/separator'
];

$template = [
  [
    'core/columns',
    [
      'className' => 'career__columns'
    ],
    [
      [
        'core/column',
        [
          'width' => '50%',
          'className' => 'career__left'
        ],
        [
          [
            'core/image',
            [
              'className' => 'career__icon ani-top ani-fade',
            ]
          ],
          [
            'core/heading',
            [
              'level' => 1,
              'className' => 'career__title ani-top ani-fade',
              'placeholder' => 'Join Us'
            ]
          ],
          [
            'core/paragraph',
            [
              'className' => 'career__text ani-top ani-fade',
              'placeholder' => 'Add description about your company culture and opportunity.'
            ]
          ]
        ]
      ],
      [
        'core/column',
        [
          'width' => '50%',
          'className' => 'career__right'
        ],
        [
          [
            'core/heading',
            [
              'level' => 3,
              'className' => 'career__subtitle ani-top ani-fade',
              'placeholder' => 'We’re always seeking:'
            ]
          ],
          [
            'core/list',
            [
              'className' => 'career__list',
            ],
            [
              ['core/list-item', [ 'className' => 'career__list-item ani-top ani-fade', 'content' => 'Estimators' ]],
              ['core/list-item', [ 'className' => 'career__list-item ani-top ani-fade', 'content' => 'Project Managers' ]],
              ['core/list-item', [ 'className' => 'career__list-item ani-top ani-fade', 'content' => 'Project Delivery Managers' ]],
              ['core/list-item', [ 'className' => 'career__list-item ani-top ani-fade', 'content' => 'Buyers' ]],
            ]
          ],
          [
            'core/paragraph',
            [
              'className' => 'career__note ani-top ani-fade',
              'placeholder' => '*With the relevant marine experience.'
            ]
          ],
          [
            'core/paragraph',
            [
              'className' => 'career__contact-text ani-top ani-fade',
            ]
          ],
          [
            'core/paragraph',
            [
              'className' => 'career__contact-text ani-top ani-fade',
            ]
          ],
          [
            'core/group',
            [
              'className' => 'career__email-box ani-top ani-fade',
            ],
            [
              [
                'core/paragraph',
                [
                  'className' => 'career__email-box-label',
                  'placeholder' => 'Email:'
                ]
              ],
              [
                'core/paragraph',
                [
                  'className' => 'career__email-box-link',
                  'placeholder' => 'careers@smsmarineinteriors.com'
                ]
              ]
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
    <div class="career__wrap">
      <InnerBlocks 
        allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
        template="<?= esc_attr(wp_json_encode($template)); ?>"
      />
    </div>
  </div>
</section>

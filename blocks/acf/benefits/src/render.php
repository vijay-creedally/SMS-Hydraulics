<?php
/**
 * Block Template: Benefits
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'benefits position-relative',
]);

$allowed_blocks = ['core/paragraph', 'core/heading', 'core/list', 'core/buttons'];

$template = [
  ['core/paragraph', [
    'className'   => 'benefits__label has-text-align-center ani-top ani-fade',
    'style' => [
      'color' => ['text' => '#020007'],
      'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
      'typography' => [
        'textTransform' => 'uppercase',
        'lineHeight'    => '1.4',
        'letterSpacing' => '0.25rem',
        'fontWeight'    => '300',
      ],
      'spacing' => [
        'margin' => [
          'top'    => '0px',
          'right'  => '0px',
          'bottom' => '0px',
          'left'   => '0px',
        ],
      ],
    ],
  ]],
  ['core/heading', [
    'level'       => 2,
    'className'   => 'benefits__heading has-text-align-center ani-top ani-fade',
    'style' => [
      'color' => ['text' => '#020007'],
      'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
      'typography' => [
        'textTransform' => 'uppercase',
        'lineHeight'    => '1.4',
        'fontWeight'    => '600',
      ],
      'spacing' => [
        'margin' => [
          'top'    => '0px',
          'right'  => '0px',
          'bottom' => '0px',
          'left'   => '0px',
        ],
      ],
    ],
  ]],
  ['core/group', [
    'className' => 'benefits__card position-relative ani-top ani-fade'
  ], [
    ['core/list', [
      'className' => 'benefits__list ani-top ani-fade',
    ], [
      ['core/list-item', [
        'placeholder' => 'Benefit 1',
        'className'   => 'benefits__item position-relative ani-top ani-fade',
        'style' => [
          'color' => ['text' => '#020007'],
          'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
          'typography' => [
            'lineHeight'    => '1.7',
            'letterSpacing' => '0.125rem',
            'fontWeight'    => '300',
          ],
          'spacing' => [
            'margin' => [
              'top'    => '0px',
              'right'  => '0px',
              'bottom' => '0px',
              'left'   => '0px',
            ],
          ],
        ],
      ]],
    ]],
  ]],
  ['core/buttons', [
    'className' => 'benefits__buttons is-content-justification-center ani-top ani-fade',
    'style' => [
      'spacing' => [
        'margin' => [
          'top'    => '5rem',
          'right'  => '0px',
          'bottom' => '0px',
          'left'   => '0px',
        ],
      ],
    ],
  ], [
    ['core/button', [
      'className' => 'is-style-outline introduction__button position-relative text-uppercase overflow-hidden d-inline-block',
      'style' => [
        'color' => ['text' => '#FFFFFF'],
        'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
        'typography' => [
          'textTransform' => 'uppercase',
          'lineHeight'    => '1.5',
          'letterSpacing' => '0.4rem',
          'fontWeight'    => '800',
        ],
        'spacing' => [
          'padding' => [
            'top'    => '0px',
            'right'  => '0px',
            'bottom' => '0px',
            'left'   => '0px',
          ],
        ],
      ],
    ]],
  ]],
];
?>

<section <?= $attrs; ?>>
  <div class="container text-center">
    <InnerBlocks
      allowedBlocks="<?php echo esc_attr( wp_json_encode($allowed_blocks) ); ?>"
      template="<?php echo esc_attr( wp_json_encode($template) ); ?>"
    />
  </div>
</section>

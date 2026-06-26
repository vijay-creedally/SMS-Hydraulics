<?php
/**
 * Block Template: Credibility
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML (empty).
 * @param bool       $is_preview True during backend preview render.
 * @param int|string $post_id The post ID the block is rendering content against.
 */

$hide_top_curve = get_field('hide_top_curve', $block['id']);
$hide_curve_class = !empty( $hide_top_curve ) ? 'hide-curve': '';

$attrs = get_block_wrapper_attributes([
'class' => 'credibility position-relative w-100 ' . $hide_curve_class,
]);

$allowed_blocks = ['core/heading', 'core/paragraph'];

$template = [
  ['core/heading', [
    'level' => 2,
    'textAlign' => 'left',
    'className' => 'credibility__title ani-top ani-fade',
    'style' => [
      'color' => ['text' => '#000'],
      'typography' => [
        'textTransform' => 'uppercase',
        'letterSpacing' => '0.6875rem',
        'fontWeight'    => '300',
        'lineHeight'	=> '1.7'
      ],
      'spacing' => [
        'margin' => [
          'top' => '0px',
          'right' => '0px',
          'bottom' => '0px',
          'left' => '0px',
        ],
      ],
    ],
  ]],
  ['core/paragraph', [
    'align'   => 'left',
    'className' => 'credibility__text ani-top ani-fade',
    'style' => [
      'color' => ['text' => '#000'],
      'typography' => [
        'fontWeight'    => '300',
      ],
      'spacing' => [
        'margin' => [
          'top' => '0px',
          'left' => '0px',
          'bottom' => '0px',
          'right' => '0px',
        ],
      ],
    ],
  ]],
];
?>

<section <?= $attrs; ?>>
  <div class="container text-center">
    <div class="credibility__wrapper position-relative z-1 mx-auto">

      <InnerBlocks
        allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
        template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>"
      />

      <?php if ( have_rows('stats') ): ?>
        <div class="credibility__stats">
          <?php while ( have_rows('stats') ): the_row(); 
            $value = get_sub_field('value') ? get_sub_field('value') : '';
            $numeric_value = preg_replace('/[^0-9.]/', '', $value);
            $unit = preg_replace('/[0-9.\s]/', '', $value);
            $preffix = get_sub_field('preffix') ? get_sub_field('preffix') : '';
            $suffix = get_sub_field('suffix') ? get_sub_field('suffix') : '';
            $label  = get_sub_field('label')  ? get_sub_field('label')  : '';
            ?>
            <div class="credibility__stat text-center">
              <div class="credibility__value">
                <?php if (!empty( $preffix )): ?>
                  <span class="credibility__prefix"><?php echo esc_html( $preffix ); ?></span>
                <?php endif; ?>
                  <span class="credibility__number" data-target="<?php echo esc_attr( $numeric_value ); ?>" data-unit="<?php echo esc_attr( $unit ); ?>"><?php echo esc_html($value); ?></span>
                <?php if ( $suffix ): ?>
                  <span class="credibility__suffix"><?php echo esc_html( $suffix ); ?></span>
                <?php endif; ?>
              </div>
              <p class="credibility__label mb-0"><?php echo esc_html( $label ); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
    </div>

    <?php if ( have_rows('logos') ): ?>
      <div class="credibility__logos">
        <?php while ( have_rows('logos') ): the_row(); 
          $image = get_sub_field('image');
          $alt   = get_sub_field('alt') ?: $image['alt'];
          ?>
          <?php if ( $image ): ?>
            <div class="credibility__logo ani-top ani-fade"><img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $alt ); ?>"></div>
          <?php endif; ?>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

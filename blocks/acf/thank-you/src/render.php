<?php
/**
 * Render Template: Mission Section using core blocks
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'thank-you',
]);

$allowed_blocks = ['core/paragraph', 'core/heading', 'core/buttons','core/button'];

$template = [
    ['core/heading', [
        'level'     => 2,
        'className' => 'thank-you__title ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'className' => 'thank-you__text ani-top ani-fade',
    ]],
];

?>

<section <?= $attrs; ?>>
  <div class="container">
    <div class="thank-you__content">
      <InnerBlocks 
      allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
      template="<?= esc_attr(wp_json_encode($template)); ?>"
      />
    </div>
  </div>
</section>

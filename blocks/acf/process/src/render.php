<?php
/**
 * Render Template: Mission Section using core blocks
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'process',
]);

$process_cards = get_field('process_cards');

$allowed_blocks = ['core/paragraph', 'core/heading'];

$template = [
    ['core/heading', [
        'level'     => 2,
        'className' => 'process__title ani-top ani-fade',
    ]],
];

?>

<section <?= $attrs; ?>>
  <div class="container">
    <div class="process__wrap">
      <InnerBlocks 
        allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
        template="<?= esc_attr(wp_json_encode($template)); ?>"
      />

      <div class="process__content-wrap">
          <?php 
          if( !empty( $process_cards ) && is_array( $process_cards ) )  {

            foreach( $process_cards as $process_card ) {

              $image = !empty( $process_card['image'] ) ? $process_card['image'] : '';
              $title = !empty( $process_card['title'] ) ? $process_card['title'] : '';
              $description = !empty( $process_card['description'] ) ? $process_card['description'] : '';
              $placement = !empty( $process_card['placement'] ) ? $process_card['placement'] : '';
              ?>
              <div class="process__content <?php echo 'process__content-'.$placement; ?>" >
                <div class="process__image">
                  <?php if( !empty($image['url'])) ?>
                  <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_html($title); ?>"/>
                  <div class="process__info ani-fade ani-top">
                  <h3 class="process__info-title"><?php echo esc_html($title); ?></h3>
                  <p class="process__info-text"><?php echo esc_textarea($description); ?></p>
                  </div>
                </div>
              </div>
              <?php
            }
          }
          ?>
      </div>
    </div>
  </div>
</section>

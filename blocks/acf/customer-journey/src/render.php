<?php
/**
 * Render Template: Mission Section using core blocks
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'customer-journey',
]);

$journey_cards = get_field('journey_cards');

$allowed_blocks = ['core/paragraph', 'core/heading'];

$template = [
  ['core/heading', [
    'level'     => 2,
    'className' => 'customer-journey__title ani-top ani-fade',
  ]],
  ['core/paragraph',[
    'className' => 'customer-journey__subtitle ani-top ani-fade',
  ]],
];
?>

<section <?= $attrs; ?>>
  <div class="container">
    <div class="customer-journey__wrap">
      <InnerBlocks 
        allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
        template="<?= esc_attr(wp_json_encode($template)); ?>"
      />

    <div class="customer-journey__steps">
      <div class="customer-journey__step">
        <div class="customer-journey__icon">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/flag.svg" alt="Flag Icon">
        </div>
      </div>

      <?php
      if( !empty( $journey_cards ) && is_array( $journey_cards ) ) {

        foreach( $journey_cards as $journey_card ) {

          $image_url = !empty($journey_card['image']['url'] ) ? $journey_card['image']['url'] : '';
          $title = !empty($journey_card['title'] ) ? $journey_card['title'] : '';
          $description = !empty($journey_card['description'] ) ? $journey_card['description'] : '';
          ?>
          <div class="customer-journey__step ani-top ani-fade">
            <div class="customer-journey__icon">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_html($title); ?>">
            </div>
            <div class="customer-journey__content">
            <h3 class="customer-journey__step-title"><?php echo esc_html($title); ?></h3>
            <p class="customer-journey__step-text"><?php echo esc_textarea( $description ); ?></p>
            </div>
          </div>
          <?php 
        }
      }
      ?>

      <div class="customer-journey__step">
        <div class="customer-journey__icon">
           <img src="<?php echo get_template_directory_uri(); ?>/assets/images/location.svg" alt="Location Icon">
        </div>
      </div>
    </div>
  </div>
</section>

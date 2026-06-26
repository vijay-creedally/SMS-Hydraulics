<?php
/**
 * Render Template: Mission Section using core blocks
 *
 * @param array $block The block settings and attributes.
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'mission position-relative',
]);

$allowed_blocks = ['core/paragraph', 'core/heading'];

$template = [
    ['core/paragraph', [
        'className' => 'mission__subtitle text-align-left ani-top ani-fade',
    ]],
    ['core/heading', [
        'level'     => 2,
        'className' => 'mission__title text-align-left ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'className' => 'mission__description text-align-left ani-top ani-fade',
    ]],
];

$tagline_heading  = get_field('tagline_heading') ? : null;
$background_image = get_field('background_image') ? : null;
$blue_vector      = esc_url( get_stylesheet_directory_uri() . '/assets/images/vector-blue.svg' );
$red_vector       = esc_url( get_stylesheet_directory_uri() . '/assets/images/vector-red.svg' );
$no_visuals       = (!$background_image && !$blue_vector && !$red_vector);

?>

<section <?= $attrs; ?>>
  <div class="mission__container">
    <div class="container">
      <div class="mission__content text-align-left <?php echo $no_visuals ? 'non-visuals' : ''; ?>">
        <InnerBlocks
          allowedBlocks="<?= esc_attr( wp_json_encode($allowed_blocks) ); ?>"
          template="<?= esc_attr( wp_json_encode($template) ); ?>"
        />
        <?php if(!empty($tagline_heading)): ?>
          <div class="mission__highlight">
            <span class="mission__line"></span>
            <h4 class="mission__tagline"><?php echo esc_html( $tagline_heading ); ?></h4>
          </div> 
        <?php endif; ?>
      </div>
    </div>
    <?php if($background_image || $blue_vector || $red_vector ) : ?>
      <div class="mission__visuals ani-top ani-fade">
        <?php if ($background_image): ?>
          <div class="mission__image">
            <img src="<?= esc_url($background_image['url']); ?>" alt="<?= esc_attr($background_image['alt']); ?>">
          </div>
        <?php endif; ?>

        <?php if ($blue_vector): ?>
          <div class="mission__shape mission__shape--blue">
            <img src="<?= esc_url($blue_vector); ?>" alt="Blue Vector">
          </div>
        <?php endif; ?>

        <?php if ($red_vector): ?>
          <div class="mission__shape mission__shape--red">
            <img src="<?= esc_url($red_vector); ?>" alt="Red Vector">
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

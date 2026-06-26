<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$image_url     = !empty($args['image_url'])     ?  $args['image_url'] : '';
$image_alt     = !empty($args['image_alt'])     ?  $args['image_alt'] : '';
$title         = !empty($args['title'])         ?  $args['title'] : '';
$description   = !empty($args['description'])   ?  $args['description'] : '';
$link          = !empty($args['link'])          ?  $args['link']  : '#';
$readmore_icon = !empty($args['readmore_icon']) ?  $args['readmore_icon'] : get_template_directory_uri() . '/assets/images/case-studies-arrow.svg';
?>

<article class="case-studies__card">
    <?php if ( $image_url ) : ?>
        <div class="case-studies__image">
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
        </div>
    <?php endif; ?>

    <div class="case-studies__content">
        <?php if ( $title ) : ?>
            <h4 class="case-studies__name">
                <?php echo esc_html( $title ); ?>
            </h4>
        <?php endif; ?>

        <?php if ( $description ) : ?>
            <p class="case-studies__desc">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>
    </div>
    <a href="<?php echo esc_url( $link ); ?>" class="case-studies__link">
        <?php echo esc_html__( 'Read', 'sms' ); ?>
        <?php if ( $readmore_icon ) : ?>
            <img src="<?php echo esc_url( $readmore_icon ); ?>" 
                 alt="<?php echo esc_attr__( 'Arrow', 'sms' ); ?>" 
                 class="case-studies__arrow">
        <?php endif; ?>
    </a>
</article>

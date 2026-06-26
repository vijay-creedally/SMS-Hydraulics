<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$image_url     = !empty($args['image_url'])     ?  $args['image_url'] : '';
$image_alt     = !empty($args['image_alt'])     ?  $args['image_alt'] : '';
$title         = !empty($args['title'])         ?  $args['title'] : '';
$description   = !empty($args['description'])   ?  $args['description'] : '';
$link          = !empty($args['link'])          ?  $args['link']  : '#'
?>

<article class="project-list__card">
    <?php if ( $image_url ) : ?>
        <div class="project-list__image">
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
        </div>
    <?php endif; ?>

    <div class="project-list__content">
        <?php if ( $title ) : ?>
            <h4 class="project-list__name">
                <?php echo esc_html( $title ); ?>
            </h4>
        <?php endif; ?>

        <?php if ( $description ) : ?>
            <p class="project-list__desc">
                <?php echo esc_html( $description ); ?>
            </p>
        <?php endif; ?>
    </div>

    <a href="<?php echo esc_url( $link ); ?>" class="project-list__link">
        <?php echo esc_html__( 'Read', 'sms' ); ?>
        <svg class="project-list__arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 12H19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 5L19 12L12 19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </a>
</article>
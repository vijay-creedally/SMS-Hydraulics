<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


$country_code = !empty($args['country_code']) ? sanitize_text_field($args['country_code']) : '';
$country = !empty($args['country']) ? sanitize_text_field($args['country']) : '';
$project = !empty( $args['project'] ) ? $args['project']: 0;

$flag_icon_url = get_flag_icon_url_by_country_code( $country_code );
$image_url = get_the_post_thumbnail_url( $project );
$image_url = !empty( $image_url ) ? $image_url : get_stylesheet_directory_uri().'/assets/images/placeholder-390X230.png';
$project_title = get_the_title( $project );
$description = wp_trim_words( get_the_excerpt( $project ), 20, '...' );
$project_link = get_the_permalink( $project );
?>

<article class="project__card">
    <?php if ( $image_url ) : ?>
        <div class="project__image">
            <img width="100%" height="100%" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $project_title ); ?>">
        </div>
    <?php endif; ?>

    <div class="project__content">
        <div class="project__flag">
            <img width="36" height="24" src="<?php echo esc_url( $flag_icon_url ); ?>" alt="<?php echo esc_attr( $country ); ?> flag" />
            <a href="javascript:void(0);" class="project__flag--close">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M18 6L6 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M6 6L18 18" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
        <?php if ( $project_title ) : ?>
            <h4 class="project__name"><?php echo esc_html( $project_title ); ?></h4>
        <?php endif; ?>
        
        <p class="project__desc"><?php echo !empty( $description ) ? esc_html( $description ) : ''; ?></p>

        <a class="project__link" href="<?php echo esc_url( $project_link ); ?>">
            <?php echo esc_html__( 'VIEW PROJECT', 'sms' ); ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 12H19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 5L19 12L12 19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</article>
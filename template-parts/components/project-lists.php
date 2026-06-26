<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$country_code = !empty($args['country_code']) ? sanitize_text_field($args['country_code']) : '';
$country = !empty($args['country']) ? sanitize_text_field($args['country']) : '';
$projects = !empty( $args['projects'] ) ? $args['projects']: [];

$flag_icon_url = get_flag_icon_url_by_country_code( $country_code );
?>

<h3 class="project-details--title">
    <img width="36" height="24" src="<?php echo esc_url( $flag_icon_url ); ?>" alt="<?php echo esc_attr( $country ); ?> flag" />
    <span><?php echo esc_html( $country ); ?></span>
</h3>
<ul class="project-details--list">
    <?php foreach ( $projects as $project_id ) : 
        $project_title = get_the_title( $project_id );
        $project_link = get_the_permalink( $project_id );  ?>
        <li class="project-details--list__item">
            <h4 class="project-details--list__item-label"><?php echo esc_html( $project_title ); ?></h4>
            <a class="project-details--list__item-link" href="<?php echo esc_url( $project_link ); ?>"><?php echo esc_html__( 'VIEW PROJECT', 'sms' ); ?><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M5 12H19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 5L19 12L12 19" stroke="#D4121D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
        </li>
    <?php endforeach; ?>
</ul>
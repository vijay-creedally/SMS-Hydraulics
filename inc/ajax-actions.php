<?php 

add_action('wp_ajax_fetch_project_details', 'fetch_project_details');
add_action('wp_ajax_nopriv_fetch_project_details', 'fetch_project_details');

function fetch_project_details() {
    $country_code = isset($_GET['country_code']) ? sanitize_text_field($_GET['country_code']) : '';
    $country = isset($_GET['country']) ? sanitize_text_field($_GET['country']) : '';

    if (empty($country_code)) {
        wp_send_json_error([
            'message' => __( 'Country code is required.', 'sms')
        ]);
        wp_die();
    }

    $args = [
        'posts_per_page' => 10,
        'meta_query' => [
            [
                'key'     => 'country',
                'value'   => $country_code,
                'compare' => 'LIKE',
            ],
        ],
    ];

    $all_projects = get_project_lists($args);

    $projects = !empty( $all_projects['posts'])? $all_projects['posts'] : [];

    if ( !empty($projects) ) {

        if( count($projects) > 1 ) {
            
            ob_start();

            get_template_part(
                'template-parts/components/project-lists',
                null,
                [
                    'country_code' => $country_code,
                    'country'      => $country,
                    'projects'     => $projects,
                ]
            );

            $html = ob_get_clean();

        } else {
            ob_start();

            get_template_part(
                'template-parts/components/project-card',
                null,
                [
                    'country_code' => $country_code,
                    'country'      => $country,
                    'project'     => !empty($projects[0]) ? $projects[0] : 0,
                ]
            );

            $html = ob_get_clean();
        }


        wp_send_json_success($html);
    } else {
        wp_send_json_error([
            'message' => sprintf(__('No projects are available for %s at the moment.','sms'), $country),
        ]);
    }

    wp_die();
}
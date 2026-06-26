<?php 

/**
 * Get the URL of a country flag SVG by its country code.
 *
 * This function checks whether a flag SVG file exists in the theme's
 * `/assets/images/flags/` directory. If it exists, the function returns
 * the correct URL. Otherwise, it returns an empty string.
 *
 * @param string $country_code ISO country code (e.g., "US", "IN", "GB").
 *
 * @return string URL of the SVG flag icon, or empty string if not found.
 */
function get_flag_icon_url_by_country_code( $country_code ) {

    if ( empty( $country_code ) ) {
        return '';
    }

    // Normalize filename
    $country_code = strtolower( trim( $country_code ) );

    // Paths
    $relative_path = '/assets/images/flags/' . $country_code . '.svg';
    $file_path     = get_template_directory() . $relative_path;
    $file_url      = get_template_directory_uri() . $relative_path;

    // Check if file exists on server
    if ( file_exists( $file_path ) ) {
        return esc_url( $file_url );
    }

    return '';
}


/**
 * 
 */
function get_lat_long_by_country_code( $country_code ) {

    $country_coords = [
        "AF" => ["lat" => 33.93911, "lng" => 67.709953],
        "AL" => ["lat" => 41.153332, "lng" => 20.168331],
        "DZ" => ["lat" => 28.033886, "lng" => 1.659626],
        "AD" => ["lat" => 42.506285, "lng" => 1.521801],
        "AO" => ["lat" => -11.202692, "lng" => 17.873887],
        "AR" => ["lat" => -38.416097, "lng" => -63.616672],
        "AM" => ["lat" => 40.069099, "lng" => 45.038189],
        "AU" => ["lat" => -25.274398, "lng" => 133.775136],
        "AT" => ["lat" => 47.516231, "lng" => 14.550072],
        "AZ" => ["lat" => 40.143105, "lng" => 47.576927],
        "BH" => ["lat" => 26.0667, "lng" => 50.5577],
        "BD" => ["lat" => 23.684994, "lng" => 90.356331],
        "BY" => ["lat" => 53.709807, "lng" => 27.953389],
        "BE" => ["lat" => 50.503887, "lng" => 4.469936],
        "BZ" => ["lat" => 17.189877, "lng" => -88.49765],
        "BJ" => ["lat" => 9.30769, "lng" => 2.315834],
        "BT" => ["lat" => 27.514162, "lng" => 90.433601],
        "BO" => ["lat" => -16.290154, "lng" => -63.588653],
        "BA" => ["lat" => 43.915886, "lng" => 17.679076],
        "BW" => ["lat" => -22.328474, "lng" => 24.684866],
        "BR" => ["lat" => -14.235004, "lng" => -51.92528],
        "BN" => ["lat" => 4.535277, "lng" => 114.727669],
        "BG" => ["lat" => 42.733883, "lng" => 25.48583],
        "BF" => ["lat" => 12.238333, "lng" => -1.561593],
        "BI" => ["lat" => -3.373056, "lng" => 29.918886],
        "KH" => ["lat" => 12.565679, "lng" => 104.990963],
        "CM" => ["lat" => 3.848, "lng" => 11.5021],
        "CA" => ["lat" => 56.130366, "lng" => -106.346771],
        "CL" => ["lat" => -35.675147, "lng" => -71.542969],
        "CN" => ["lat" => 35.86166, "lng" => 104.195397],
        "CO" => ["lat" => 4.570868, "lng" => -74.297333],
        "CR" => ["lat" => 9.748917, "lng" => -83.753428],
        "HR" => ["lat" => 45.1, "lng" => 15.2],
        "CU" => ["lat" => 21.521757, "lng" => -77.781167],
        "CY" => ["lat" => 35.126413, "lng" => 33.429859],
        "CZ" => ["lat" => 49.817492, "lng" => 15.472962],
        "DK" => ["lat" => 56.26392, "lng" => 9.501785],
        "EG" => ["lat" => 26.820553, "lng" => 30.802498],
        "EE" => ["lat" => 58.595272, "lng" => 25.013607],
        "FI" => ["lat" => 61.92411, "lng" => 25.748151],
        "FR" => ["lat" => 46.227638, "lng" => 2.213749],
        "DE" => ["lat" => 51.165691, "lng" => 10.451526],
        "GR" => ["lat" => 39.074208, "lng" => 21.824312],
        "HK" => ["lat" => 22.3193, "lng" => 114.1694],
        "IN" => ["lat" => 20.593684, "lng" => 78.96288],
        "ID" => ["lat" => -0.789275, "lng" => 113.921327],
        "IR" => ["lat" => 32.427908, "lng" => 53.688046],
        "IQ" => ["lat" => 33.223191, "lng" => 43.679291],
        "IE" => ["lat" => 53.142367, "lng" => -7.692054],
        "IL" => ["lat" => 31.046051, "lng" => 34.851612],
        "IT" => ["lat" => 41.87194, "lng" => 12.56738],
        "JP" => ["lat" => 36.204824, "lng" => 138.252924],
        "KE" => ["lat" => -0.023559, "lng" => 37.906193],
        "KR" => ["lat" => 35.907757, "lng" => 127.766922],
        "MY" => ["lat" => 4.210484, "lng" => 101.975766],
        "MX" => ["lat" => 23.634501, "lng" => -102.552784],
        "MA" => ["lat" => 31.791702, "lng" => -7.09262],
        "NP" => ["lat" => 28.394857, "lng" => 84.124008],
        "NL" => ["lat" => 52.132633, "lng" => 5.291266],
        "NZ" => ["lat" => -40.900557, "lng" => 174.885971],
        "NG" => ["lat" => 9.081999, "lng" => 8.675277],
        "NO" => ["lat" => 60.472024, "lng" => 8.468946],
        "PK" => ["lat" => 30.375321, "lng" => 69.345116],
        "PH" => ["lat" => 12.879721, "lng" => 121.774017],
        "PL" => ["lat" => 51.919438, "lng" => 19.145136],
        "PT" => ["lat" => 39.399872, "lng" => -8.224454],
        "QA" => ["lat" => 25.354826, "lng" => 51.183884],
        "RO" => ["lat" => 45.943161, "lng" => 24.96676],
        "RU" => ["lat" => 61.52401, "lng" => 105.318756],
        "SA" => ["lat" => 23.885942, "lng" => 45.079162],
        "SG" => ["lat" => 1.352083, "lng" => 103.819836],
        "ZA" => ["lat" => -30.559482, "lng" => 22.937506],
        "ES" => ["lat" => 40.463667, "lng" => -3.74922],
        "SE" => ["lat" => 60.128161, "lng" => 18.643501],
        "CH" => ["lat" => 46.818188, "lng" => 8.227512],
        "TH" => ["lat" => 15.870032, "lng" => 100.992541],
        "TR" => ["lat" => 38.963745, "lng" => 35.243322],
        "UA" => ["lat" => 48.379433, "lng" => 31.16558],
        "AE" => ["lat" => 23.424076, "lng" => 53.847818],
        "GB" => ["lat" => 55.378051, "lng" => -3.435973],
        "US" => ["lat" => 37.09024, "lng" => -95.712891],
        "VN" => ["lat" => 14.058324, "lng" => 108.277199]
    ];


    if ( array_key_exists( $country_code, $country_coords ) ) {
        return $country_coords[ $country_code ];
    }

    return [];
}


/**
 * Get all projects.
 *
 * @param array $args Arguments for WP_Query.
 * @param int   $page Current page number.
 * @param array $results Stores collected projects from previous calls.
 *
 * @return array All projects retrieved through recursion.
 */
function get_all_projects( $args = [], $page = 1, $results = [] ) {
    
    $query_args = wp_parse_args( $args, [
        'post_type'      => 'projects',
        'posts_per_page' => 100,
        'paged'          => $page,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);
    
    $query = new WP_Query( $query_args );

    if ( ! empty( $query->posts ) ) {
        $results = array_merge( $results, $query->posts );
    }

    if ( $query->max_num_pages > $page ) {
        return get_all_projects( $args, $page + 1, $results );
    }

    return $results;
}


function get_project_lists( $args = [] ) {

     $query_args = wp_parse_args( $args, [
        'post_type'      => 'projects',
        'posts_per_page' => 100,
        'paged'          => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);
    
    $query = new WP_Query( $query_args );

    if ( empty( $query->posts ) ) {
        return [];
    }

    return [
        'posts' => $query->posts,
        'max_num_pages' => $query->max_num_pages,
    ];
}

function get_all_team_members( $args = [], $page = 1, $results = [] ) {
    
    $query_args = wp_parse_args( $args, [
        'post_type'      => 'teams',
        'posts_per_page' => 100,
        'paged'          => $page,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);
    
    $query = new WP_Query( $query_args );

    if ( ! empty( $query->posts ) ) {
        $results = array_merge( $results, $query->posts );
    }

    if ( $query->max_num_pages > $page ) {
        return get_all_team_members( $args, $page + 1, $results );
    }

    return $results;
}

function get_team_members( $args = []) {

    $query_args = wp_parse_args( $args, [
        'post_type'      => 'teams',
        'posts_per_page' => 100,
        'paged'          => 1,
        'post_status'    => 'publish',
        'fields'         => 'ids',
    ]);
    
    $query = new WP_Query( $query_args );

    if ( empty( $query->posts ) ) {
        return [];
    }

    return [
        'posts' => $query->posts,
        'max_num_pages' => $query->max_num_pages,
    ];
}
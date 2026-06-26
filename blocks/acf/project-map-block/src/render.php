<?php
/**
 * Block Template: Block Name
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'project-map-block']);


$projects = get_all_projects([
	'order' => 'DESC',
	'orderby' => 'date',
]);

$projects_data = [];
if( !empty( $projects ) ) {

	foreach ( $projects as $project_id ) {

		$country = get_field( 'country', $project_id );
		$country_label = !empty( $country['label'] ) ? $country['label'] : '';
		$country_code = !empty( $country['value'] ) ? $country['value'] : '';
		$flag_icon_url = get_flag_icon_url_by_country_code( $country_code );
		$coords = get_lat_long_by_country_code( $country_code );
		$latitude = !empty( $coords['lat'] ) ? $coords['lat'] : '';
		$longitude = !empty( $coords['lng'] ) ? $coords['lng'] : '';

		$existing_codes = array_column( $projects_data, 'country_code');

		if (! in_array($country_code, $existing_codes)) {
			$projects_data[] = [
				'name' => $country_label,
				'coords' => [
					$latitude,
					$longitude,
				],
				'country_code' => $country_code,
			];
		}
	}
}

if(empty($projects_data)) {
	return false;
}

$latest_project_id = !empty($projects[0]) ? $projects[0] : 0;
$latest_project_country = !empty($latest_project_id ) ? get_field( 'country', $latest_project_id ) : '';
$latest_project_country_label = !empty( $latest_project_country['label'] ) ? $latest_project_country['label'] : '';
$latest_project_country_code = !empty( $latest_project_country['value'] ) ? $latest_project_country['value'] : '';
$country_projects = get_project_lists( [
	'posts_per_page' => 10,
		'meta_query' => [
		[
			'key'     => 'country',
			'value'   => $latest_project_country_code,
			'compare' => 'LIKE',
		],
	],
]);

$country_project_lists = !empty( $country_projects['posts'])? $country_projects['posts'] : [];

?>

<div <?= $attrs ?>>
	<div class="container">
		<div class="row">
			<div class="col-12 project-map-wrapper">
				<?php  if(is_admin()) { echo "<h1>". esc_html__("Project map block", 'sms' ). "</h1"; } ?>
				<div class="project-map" id="map-<?= rand(); ?>" data-map="<?= htmlspecialchars(json_encode($projects_data), ENT_QUOTES, 'UTF-8'); ?>" data-default-country-label="<?= $latest_project_country_label; ?>" data-default-country-code="<?= $latest_project_country_code; ?>"></div>
				<div class="project-details">
					<?php 
					if ( !empty($country_project_lists) ) {

						if( count($country_project_lists) > 1 ) {
							
							get_template_part(
								'template-parts/components/project-lists',
								null,
								[
									'country_code' => $latest_project_country_code,
									'country'      => $latest_project_country_label,
									'projects'     => $country_project_lists,
								]
							);

						} else {
							get_template_part(
								'template-parts/components/project-card',
								null,
								[
									'country_code' => $latest_project_country_code,
									'country'      => $latest_project_country_label,
									'project'     => !empty($country_project_lists[0]) ? $country_project_lists[0] : 0,
								]
							);
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
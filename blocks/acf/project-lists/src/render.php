<?php
/**
 * Block Template: Block Name
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'project-lists-block']);

$per_page = get_field('per_page', $block['id']);
$enable_pagination = get_field('enable_pagination', $block['id']);

$show_button = get_field('show_button', $block['id']);
$button_label = get_field('button_label', $block['id']);
$button_url = get_field('button_url', $block['id']);

$paged = 1;
if ( !empty( get_query_var('paged') ) ) {
	$paged = get_query_var('paged');
} elseif( !empty( get_query_var('page') ) ) {
    $paged = get_query_var('page');
}

$projects = get_project_lists([
	'posts_per_page' => !empty($per_page) ? $per_page : 6,
	'paged' => $paged,
]);

$projects_lists = !empty($projects['posts']) ? $projects['posts'] : [];
$total_pages = !empty($projects['max_num_pages']) ? $projects['max_num_pages'] : 0;

if( empty( $projects_lists ) ) {
	return '';
}

$allowed_blocks = ['core/heading'];

$template = [
    ['core/heading', [
    'level'       => 2,
    'className'   => 'project-list__title has-text-align-left ani-fade ani-top',
    'style' => [
      'color' => ['text' => '#fff'],
      'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
      'typography' => [
        'textTransform' => 'uppercase',
        'lineHeight'    => '1.4',
        'letterSpacing' => '0.6875rem',
        'fontWeight'    => '300',
      ],
      'spacing' => [
        'margin' => [
          'top'    => '0px',
          'right'  => '0px',
          'bottom' => '0px',
          'left'   => '0px',
        ],
      ],
    ],
  ]],
];

?>

<div <?= $attrs ?>>
	<div class="container">
		<InnerBlocks
    	    allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
    	    template="<?php echo esc_attr(wp_json_encode($template)); ?>"
    	/>

		<div class="project-list__cards">
			<?php 
			foreach( $projects_lists as $projects_list ) {

				$image_url      = get_the_post_thumbnail_url($projects_list, 'large');
				$image_id       = get_post_thumbnail_id($projects_list);
				$image_alt      = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
				$title          = get_the_title($projects_list);
				$description    = wp_trim_words(get_the_excerpt($projects_list), 15, '...');;
				$link           = get_the_permalink($projects_list);

				get_template_part(
					'template-parts/components/project-list-card',
					null,
					[
						'image_url'     => $image_url,
						'image_alt'     => $image_alt ?: $title,
						'title'         => $title,
						'description'   => $description,
						'link'          => $link,
						'readmore_icon' => ''
					]
				);
			}
			?>
		</div>
		<?php if( !empty( $enable_pagination ) ) { ?>
			<div class="project-list__pagination pagination">
				<?php
				echo paginate_links( array(
					'total'		=> $total_pages,
					'type'		=> 'list',
					'current'	=> $paged,
					'prev_text'	=> __('Prev', 'sms'),
					'next_text'	=> __('Next', 'sms'),
				) );
				?>
			</div>
		<?php } ?>

		<?php if( $show_button && $button_label && $button_url ) { ?>

			<div class="project-list__button-wrap">
		        <a href="<?= esc_url($button_url); ?>" class="project-list__button btn btn-primary">
		            <?= esc_html($button_label); ?>
		        </a>
		    </div>
		<?php } ?>
	</div>
</div>
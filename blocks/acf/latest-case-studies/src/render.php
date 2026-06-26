<?php
if (!defined('ABSPATH')) {
    exit;
}

$attrs = get_block_wrapper_attributes([
    'class' => 'case-studies',
]);
$button = get_field('button_group') ?: [];
$show_button  = $button['show_button'] ?? true;
$button_label = $button['button_label'] ?? '';
$button_url   = $button['button_url'] ?? '';

$selected = !empty(get_field('selected_case_studies')) ? get_field('selected_case_studies') : [];
$selected = array_slice($selected, 0, 3);

$selected_count = count($selected);
$max_items      = 3;

if ($selected_count < $max_items) {
    $need = $max_items - $selected_count;

    $latest = get_posts([
        'post_type'      => 'case-studies',
        'posts_per_page' => $need,
        'post__not_in'   => wp_list_pluck($selected, 'ID'),
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);
} else {
    $latest = [];
}

$case_studies = array_merge($selected, $latest);

$allowed_blocks = ['core/heading'];

$template = [
    ['core/heading', [
    'level'       => 3,
    'className'   => 'case-studies__title has-text-align-left',
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

<section <?= $attrs; ?>>
    <div class="container">

		<InnerBlocks
    	    allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>"
    	    template="<?php echo esc_attr(wp_json_encode($template)); ?>"
    	/>

        <div class="case-studies__grid">
            <?php foreach ($case_studies as $post): ?>
                <?php
				if(!empty($post)) {
					$image_url      = get_the_post_thumbnail_url($post, 'large');
                	$image_id       = get_post_thumbnail_id($post);
                	$image_alt      = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : '';
                	$title          = get_the_title($post);
                	$description    = wp_trim_words(get_the_excerpt($post), 15, '...');;
                	$link           = get_permalink($post);

                	get_template_part(
                	    'template-parts/components/case-study-card',
                	    null,
                	    [
                	        'image_url'     => $image_url,
                	        'image_alt'     => $image_alt ?: $title,
                	        'title'         => $title,
                	        'description'   => $description,
                	        'link'          => $link,
                	        'readmore_icon' => get_template_directory_uri() . '/assets/images/case-studies-arrow.svg'
                	    ]
                	);
				}
                ?>
            <?php endforeach; ?>
        </div>
		<?php if ($show_button && $button_label && $button_url): ?>
		    <div class="case-studies__button">
		        <a href="<?= esc_url($button_url); ?>" class="btn btn-primary">
		            <?= esc_html($button_label); ?>
		        </a>
		    </div>
		<?php endif; ?>
    </div>
</section>

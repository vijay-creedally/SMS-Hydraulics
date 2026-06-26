<?php
/**
 * Block Template: Introduction
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML (empty).
 * @param bool       $is_preview True during backend preview render.
 * @param int|string $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'gallery gallery-with-text position-relative overflow-hidden w-100']);

$galleries = get_field('galleries', $block['id']);
$allowed_blocks = ['core/paragraph', 'core/buttons'];
$template = [
    ['core/paragraph', [
        'align'   => 'center',
        'className' => 'gallery__text ani-top ani-fade',
		'style' => [
    	    'typography' => [
    	        'textTransform' => 'uppercase',
    	        'lineHeight'    => '1.4',
				'letterSpacing'=> '0.5625rem',
    	    ],
			'spacing' => [
        	    'padding' => [
        	        'bottom' => '2.5rem',
        	    ],
        	],
    	],
    ]],
];

$total_gallery_item = 'gallery__three';
if( !empty( $galleries ) && count( $galleries ) == 1 ) {
	$total_gallery_item = 'gallery__one';
} elseif( !empty( $galleries ) && count( $galleries ) == 2 ) {
	$total_gallery_item = 'gallery__two';
}
?>

<section <?= $attrs; ?>>
    <div class="container">
        <div class="gallery__content position-relative z-1 text-center py-5">
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>" 
                template="<?php echo esc_attr(wp_json_encode($template)); ?>"
            />
        </div>
		<?php if( !empty( $galleries ) && is_array( $galleries ) ) { ?>
			<div class="gallery__wrap <?php echo $total_gallery_item; ?>">
				<?php 
				$count = count($galleries);
				foreach( $galleries as $index => $gallery) {
					if( !empty( $gallery['image']['url'] ) ) {
						$title = !empty( $gallery['image']['title'] ) ? $gallery['image']['title'] : '';
						$alt = !empty( $gallery['image']['title'] ) ? $gallery['image']['title'] : $title;
					?>
					<div class="gallery__item ani-fade ani-top">
						<img src="<?php echo $gallery['image']['url']; ?>" alt="<?php echo $alt; ?>" />
					</div>
				<?php } 
				} ?>
			</div>
		<?php } ?>
    </div>
</section>

<?php
/**
 * Block Template: Block Name
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'sms-block']);
$allowed_blocks = ['core/heading', 'core/paragraph'];
$template = [
	['core/heading', [
		'level' => 2,
		'content' => 'Heading',
	]],
	['core/paragraph', [
		'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc.'
	]],
];
?>

<div <?= $attrs ?>>
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-6">
				<InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>" />
			</div>
			<div class="col-12 col-md-6">
				<?php
				$img = get_field('image');
				// get attachment image
				if ( $img ) {
					echo wp_get_attachment_image($img, 'large', false, ['class' => 'img-fluid']);
				}
				?>
			</div>
		</div>
	</div>
</div>

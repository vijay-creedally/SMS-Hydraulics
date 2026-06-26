<?php
/**
 * Block Template: Hero block Secondary
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$media		= get_field('background_cover', $block['id']);
$is_overlay = get_field('is_overlay', $block['id']);
$media_url 	= !empty($media) ? $media['url'] : '';
$media_type = !empty($media) ? $media['mime_type'] : '';

$attrs = get_block_wrapper_attributes(['class' => 'hero-secondary position-relative overflow-hidden d-flex']);

$allowed_blocks = ['core/heading', 'core/post-title', 'core/paragraph', 'core/post-excerpt'];

$template = [
	['core/heading', [
		'level' => 2,
		'content' => 'Heading',
		'className' => 'hero-secondary__title ani-fade ani-top',
	]],
];
?>

<section <?= $attrs ?>>
	<?php if ($media_url): ?>
        <?php if (strpos($media_type, 'video') !== false): ?>
            <video class="hero-secondary__video" autoplay muted loop playsinline>
                <source src="<?= esc_url($media_url); ?>" type="<?= esc_attr($media_type); ?>">
            </video>
        <?php else: ?>
            <div class="hero-secondary__image" style="background-image: url('<?= esc_url($media_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
        <?php endif; ?>
    <?php endif; ?>
	<?php if( $is_overlay ){ ?>
		<div class="hero-secondary__overlay"></div>
	<?php } ?>
  <div class="hero-secondary__content container">
		<InnerBlocks 
			allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
			template="<?= esc_attr(wp_json_encode($template)); ?>"
		/>
  </div>
</section>
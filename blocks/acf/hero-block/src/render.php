<?php
/**
 * Block Template: Hero block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

$media 		= get_field('background_cover', $block['id']);
$is_overlay = get_field('is_overlay', $block['id']);
$media_url 	= !empty($media) ? $media['url'] : '';
$media_type = !empty($media) ? $media['mime_type'] : '';
$attrs      = get_block_wrapper_attributes(['class' => 'hero position-relative overflow-hidden d-flex']);

$allowed_blocks = ['core/heading', 'core/post-title', 'core/paragraph', 'core/post-excerpt', 'core/buttons'];

$template = [
	['core/heading', [
		'level' => 1,
		'content' => 'Heading',
		'className' => 'hero__title has-text-align-center ani-fade ani-top',
	]],
	['core/paragraph', [
		'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc. Nullam ac ante mollis, fermentum nunc in, ultricies nunc.',
		'className' => 'hero__text has-text-align-center  ani-fade ani-top',
	]],
	['core/buttons', [
        'layout' => [
            'type' => 'flex',
            'justifyContent' => 'center',
			'gap' => '20px',
			'className' => 'hero__buttons is-layout-flex wp-block-buttons-is-layout-flex is-content-justification-center  ani-fade ani-top',
        ],
    ], [
        ['core/button', [
            'text' => 'ABOUT',
            'url'  => '#',
			'className' => 'hero__button hero__button--primary',
        ]],
        ['core/button', [
            'text' => 'CONTACT',
            'url'  => '#',
			'className' => 'hero__button hero__button--secondary',
        ]],
    ]],
];
?>

<section <?= $attrs ?>>
	<?php if ($media_url): ?>
        <?php if (strpos($media_type, 'video') !== false): ?>
            <video class="hero__video" autoplay muted loop playsinline>
                <source src="<?= esc_url($media_url); ?>" type="<?= esc_attr($media_type); ?>">
            </video>
        <?php else: ?>
            <div class="hero__image" style="background-image: url('<?= esc_url($media_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
        <?php endif; ?>
    <?php endif; ?>
	<?php if( $is_overlay ){ ?>
		<div class="hero__overlay"></div>
	<?php } ?>
	<div class="hero__content container" >
		<div class="hero__wrapper">
			<InnerBlocks 
                allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
                template="<?= esc_attr(wp_json_encode($template)); ?>"
            />
		</div>
	</div>
</section>

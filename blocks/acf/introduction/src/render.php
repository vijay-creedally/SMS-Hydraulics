<?php
/**
 * Block Template: Introduction
 *
 * @param array      $block The block settings and attributes.
 * @param string     $content The block inner HTML (empty).
 * @param bool       $is_preview True during backend preview render.
 * @param int|string $post_id The post ID the block is rendering content against.
 */

$attrs = get_block_wrapper_attributes(['class' => 'introduction position-relative overflow-hidden w-100']);

$allowed_blocks = ['core/heading', 'core/buttons'];
$template = [
    ['core/heading', [
		'level' => 3,
        'textAlign'   => 'center',
        'className' => 'ntroduction__text ani-top ani-fade',
    ]],
    ['core/buttons', [
		'className' => 'is-content-justification-center ani-top ani-fade',
	], [
        ['core/button', [
            'className' => 'is-style-outline introduction__button position-relative text-uppercase overflow-hidden d-inline-block',
			'style' => [
				'color' => ['text' => '#FFFFFF'],
				'border' => ['radius' => '0px', 'width' => '0px', 'style' => 'none'],
				'typography' => ['textTransform' => 'uppercase','lineHeight' => '1.5','letterSpacing' => '0.4rem', 'fontWeight' => '800'],
				'spacing' => ['padding' => ['top' => '0px', 'right' => '0px', 'bottom' => '0px', 'left' => '0px']],
			],
        ]],
    ]],
];
?>

<section <?= $attrs; ?>>
    <div class="container text-center">
        <div class="introduction__content position-relative z-1 py-7 mx-auto">
            <InnerBlocks 
                allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>" 
                template="<?php echo esc_attr(wp_json_encode($template)); ?>"
            />
        </div>
    </div>
</section>

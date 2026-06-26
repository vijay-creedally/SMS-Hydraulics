<?php
/**
 * Block Template: Certifications Block
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 */

// Block wrapper classes
$attrs = get_block_wrapper_attributes([
    'class' => 'certifications'
]);
$allowed_blocks = ['core/heading'];
$template = [
    ['core/heading', [
		'level' => 3,
		'content' => 'Heading',
		'className' => 'certifications__title has-text-align-left ani-top ani-fade',
        'style' => [
            'color' => [
                'text' => '#020007',
            ],
            'typography' => [
                'fontWeight'    => '300',
                'lineHeight'    => '1.4',
                'letterSpacing' => '0.6875rem',
                'textTransform' => 'uppercase',
            ],
        ]
	]],
];
?>

<section <?= $attrs ?>>
    <div class="container">
        <InnerBlocks 
            allowedBlocks="<?php echo esc_attr(wp_json_encode($allowed_blocks)); ?>" 
            template="<?php echo esc_attr(wp_json_encode($template)); ?>"
        />
        <div class="certifications__grid">

            <?php if (have_rows('certifications_cards')) : ?>
                <?php while (have_rows('certifications_cards')) : the_row(); ?>

                    <?php
                    $icon             = get_sub_field('icon') ?: [];
                    $card_title       = get_sub_field('title') ?: '';
                    $description      = get_sub_field('description') ?: '';
                    $icon_url         = $icon['url'] ?? '';
                    $title_class      = $icon_url ? 'has-icon' : '';
                    $desc_class       = $card_title ? 'has-title' : '';
                    if (empty($icon_url) && empty($card_title) && empty($description)) {
                        continue;
                    }
                    ?>

                    <div class="certifications__card ani-top ani-fade">
                        <?php if (!empty($icon_url)) : ?>
                            <div class="certifications__icon">
                                <img src="<?= esc_url($icon_url); ?>"
                                    alt="<?= esc_attr($card_title); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($card_title) : ?>
                            <h4 class="certifications__card-title <?php echo esc_attr( $title_class ); ?>">
                                <?= esc_html($card_title); ?>
                            </h4>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <p class="certifications__card-description <?php echo esc_attr( $desc_class ); ?>">
                                <?= esc_html($description); ?>
                            </p>
                        <?php endif; ?>

                    </div>

                <?php endwhile; ?>
            <?php endif; ?>

        </div>

    </div>
</section>

<?php
/**
 * Block Template: Bespoke Content Block
 *
 * Renders the Bespoke Content block with:
 * - Core heading block for title
 * - Core paragraph block for intro and description
 * - ACF fields for images
 */

// Block wrapper classes
$attrs = get_block_wrapper_attributes([
    'class' => 'bespoke'
]);

$allowed_blocks = ['core/heading', 'core/paragraph', 'core/list', 'core/list-item'];

$template = [
    ['core/heading', [
        'level' => 2,
        'className' => 'bespoke__title has-text-align-left ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'content' => 'Intro text',
        'className' => 'bespoke__intro ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'content' => 'Details text',
        'className' => 'bespoke__details ani-top ani-fade',
    ]],
];

$blue_vector = esc_url( get_stylesheet_directory_uri() . '/assets/images/vector-blue.svg' );
$red_vector = esc_url( get_stylesheet_directory_uri() . '/assets/images/vector-red.svg' );
$masked_image = get_field('masked_image');
$is_image_align_right = get_field('image_align_right');
?>

<section <?= $attrs ?>>
    
    <div class="container">

        <div class="bespoke__wrap <?php echo !empty( $is_image_align_right  ) ? 'bespoke__wrap--right':''; ?>">
            <?php if( !empty( $is_image_align_right ) ) { ?>

                <div class="bespoke__content">
                    <InnerBlocks 
                        allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
                        template="<?= esc_attr(wp_json_encode($template)); ?>"
                    />
                </div>
            <?php } ?>

            <div class="bespoke__visuals">
                <div class="bespoke__vectors">
                    <div class="bespoke__vector bespoke__vector--blue"><img src="<?= esc_url($blue_vector); ?>" alt="Blue Vector"></div>
                    <div class="bespoke__vector bespoke__vector--red"><img src="<?= esc_url($red_vector); ?>" alt="Red Vector"></div>
                </div>
                <?php if ($masked_image && !empty($masked_image['url'])) : ?>
                    <div class="bespoke__images">
                        <div class="bespoke__images-wrapper">
                            <img src="<?= esc_url($masked_image['url']); ?>" alt="<?= esc_attr($masked_image['alt'] ?? 'Bespoke Interior'); ?>" class="bespoke__masked-img" >
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if( empty( $is_image_align_right ) ) { ?>

                <div class="bespoke__content">
                    <InnerBlocks 
                        allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)); ?>" 
                        template="<?= esc_attr(wp_json_encode($template)); ?>"
                    />
                </div>
            <?php } ?>
        </div>
    </div>

</section>

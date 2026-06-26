<?php
/**
 * Block Template: Challenges Block
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'challenges'
]);
?>

<section <?php echo $attrs; ?>>
    <div class="container">
        <div class="challenges__inner">

            <div class="challenges__wrapper challenges__col">
                <?php
                $intro       = get_field('challenges_intro') ? get_field('challenges_intro') : [];
                $intro_title = !empty($intro['intro_title']) ? $intro['intro_title'] : '';
                $intro_text  = !empty($intro['intro_text'])  ? $intro['intro_text']  : '';
                ?>

                <div class="challenges__intro">
                <?php echo ($intro_title ? '<h3 class="challenges__title">' . esc_html($intro_title) . '</h3>' : ''); ?>
                <?php echo ($intro_text ? '<p class="challenges__text">' . esc_html($intro_text) . '</p>' : ''); ?>
                </div>
            </div>

            <div class="challenges__content challenges__col">
                <?php if (have_rows('challenge_items')) : $i = 0; ?>
                    <?php while (have_rows('challenge_items')) : the_row(); ?>

                        <?php
                        $card             = get_sub_field('challenge_card') ? get_sub_field('challenge_card') : [];
                        $card_title       = !empty($card['card_title']) ? $card['card_title'] : '';
                        $card_description = !empty($card['card_description']) ? $card['card_description'] : '';
                        $is_solution      = get_sub_field('is_solution');
                        ?>

                        <div data-index="<?php echo esc_attr($i); ?>" class="challenges__card ani-top ani-fade <?php echo ($is_solution) ? 'challenges__card--solution' :''; ?> <?php echo (!is_admin() && $i === 0 ? 'active' : ''); ?>">
                            <span class="challenges__dot"></span>
                            <?php echo ($card_title ? '<h3 class="challenges__card-title">' . esc_html($card_title) . '</h3>' : ''); ?>
                            <?php echo ($card_description ? '<p class="challenges__card-text">' . esc_html($card_description) . '</p>' : ''); ?>
                        </div>

                        <?php $i++; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

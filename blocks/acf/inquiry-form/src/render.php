<?php
/**
 * Inquiry Block – Render Template
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'inquiry position-relative',
]);
$media 		= get_field('background_cover', $block['id']);
$is_overlay = get_field('is_overlay', $block['id']);
$media_url 	= !empty($media) ? $media['url'] : '';
$media_type = !empty($media) ? $media['mime_type'] : '';

$telephone_icon   = get_field('telephone_icon') ? get_field('telephone_icon') : null;
$telephone_label  = get_field('telephone_label') ? get_field('telephone_label') : null;
$telephone_number = get_field('telephone_number') ? get_field('telephone_number') : null;

$email_icon       = get_field('email_icon') ? get_field('email_icon') : null;
$email_label      = get_field('email_label') ? get_field('email_label') : null;
$email_id         = get_field('email_id') ? get_field('email_id') : null;

$form_title       = get_field('form_title') ? get_field('form_title') : null;
$form_shortcode   = get_field('form_shortcode') ? get_field('form_shortcode') : null;


$allowed_blocks = ['core/heading', 'core/paragraph'];

$template = [
    ['core/heading', [
        'level'     => 4,
        'className' => 'inquiry__title'
    ]],
    ['core/paragraph', [
        'className' => 'inquiry__heading'
    ]],
    ['core/paragraph', [
        'className' => 'inquiry__desc'
    ]]
];
?>

<section <?php echo $attrs; ?>>
  <?php if ($media_url): ?>
    <?php if (strpos($media_type, 'video') !== false): ?>
      <video class="inquiry__video" autoplay muted loop playsinline>
            <source src="<?= esc_url($media_url); ?>" type="<?= esc_attr($media_type); ?>">
          </video>
        <?php else: ?>
            <div class="inquiry__image" style="background-image: url('<?= esc_url($media_url); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>
      <?php endif; ?>
    <?php endif; ?>
  <div class="inquiry__overlay"></div>
  <div class="container">
    <div class="inquiry__inner">

      <div class="inquiry__left">

        <InnerBlocks
          allowedBlocks="<?php echo esc_attr(json_encode($allowed_blocks)); ?>"
          template="<?php echo esc_attr(json_encode($template)); ?>"
        />

        <div class="inquiry__col inquiry__col--inquiry">

          <?php if ($telephone_icon || $telephone_label || $telephone_number): ?>
            <div class="inquiry__col--inquiry-item">
                <?php if (!empty($telephone_icon['url'])): ?>
                    <span class="inquiry__col--inquiry-icon">
                        <img src="<?php echo esc_url($telephone_icon['url']); ?>" alt="Telephone Icon">
                    </span>
                <?php endif; ?>

                <?php if ($telephone_label || $telephone_number): ?>
                  <div class="inquiry__col--inquiry-item-inner">
                    <span class="inquiry__col--inquiry-label">
                        <?php echo esc_html($telephone_label); ?>
                    </span>
                    <a href="tel:<?php echo esc_attr($telephone_number); ?>" class="inquiry__col--inquiry-link">
                        <?php echo esc_html($telephone_number); ?>
                    </a>
                </div>
                <?php endif; ?>

            </div>
          <?php endif; ?>

          <?php if ($email_icon || $email_label || $email_id): ?>
            <div class="inquiry__col--inquiry-item">
                <?php if (!empty($email_icon['url'])): ?>
                    <span class="inquiry__col--inquiry-icon">
                        <img src="<?php echo esc_url($email_icon['url']); ?>" alt="Email Icon">
                    </span>
                <?php endif; ?>

                <?php if ($email_label || $email_id): ?>
                  <div class="inquiry__col--inquiry-item-inner">
                    <span class="inquiry__col--inquiry-label">
                        <?php echo esc_html($email_label); ?>
                    </span>
                    <a href="mailto:<?php echo esc_attr($email_id); ?>" class="inquiry__col--inquiry-link ">
                        <?php echo esc_html($email_id); ?>
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($email_id): ?>
                    
                <?php endif; ?>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <?php if ($form_title || $form_shortcode): ?>
        <div class="inquiry__form">
          <?php if ($form_title): ?>
            <h4 class="inquiry__form-title"><?php echo esc_html($form_title); ?></h4>
          <?php endif; ?>

          <?php if ($form_shortcode): ?>
            <?php echo do_shortcode($form_shortcode); ?>
          <?php endif; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section>

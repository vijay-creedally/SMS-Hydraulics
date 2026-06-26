<?php
/**
 * Block Template: Specifics
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'specifics',
]);

$stats = get_field('specifics');
?>

<section <?= $attrs; ?>>
  <div class="container">
    <?php if (!empty($stats)) : ?>
      <div class="specifics__grid">
          <?php foreach ($stats as $item) : 
              $icon   = !empty($item) ? $item['icon'] : null;
              $title  = !empty($item['title'])  ?  $item['title']  : '';
              $value  = !empty($item['value'])  ?  $item['value']  : '';
              $suffix = !empty($item['suffix']) ? $item['suffix'] : '';
          ?>
          
          <div class="specifics__item ani-top ani-fade">
              <?php if (!empty($icon)) : ?>
                  <div class="specifics__icon">
                      <img 
                          src="<?= esc_url($icon['url']); ?>" 
                          alt="<?= esc_attr($title); ?>"
                          loading="lazy"
                      >
                  </div>
              <?php endif; ?>
              <?php if (!empty($title)) : ?>
                  <p class="specifics__title"><?= esc_html($title); ?></p>
              <?php endif; ?>
              <?php if (!empty($value) || !empty($suffix)) : ?>
                <div class="specifics__value-wrapper">
                    <?php if (!empty($value)) : ?>
                        <p class="specifics__value">
                            <?php echo esc_html(trim($value)); ?>
                              <?php if (!empty($suffix)) : ?>
                                <span class="specifics__suffix specifics__suffix--<?php echo sanitize_title($suffix); ?>">
                                    <?php echo esc_html($suffix); ?>
                                </span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
              <?php endif; ?>
          </div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

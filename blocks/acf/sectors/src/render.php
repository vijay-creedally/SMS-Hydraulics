<?php
/**
 * sectors-panel Block Render Template
 *
 * @var array $block
 */

$sector_items = get_field('sector_items'); // repeater
?>

<section class="sectors-panel">
  <div class="container">
    <?php if (!empty($sector_items)) : ?>
        <div class="sectors-panel__grid">
          <?php foreach ($sector_items as $sector) : ?>
            <?php 
              $sector_item  = !empty($sector) ? $sector['sector_item'] : [];
              $image        = !empty($sector_item) ? $sector_item['image'] : null;
              $name         = !empty($sector_item) ? $sector_item['name'] : '';
            ?>
            <div class="sectors-panel__card">
              <?php if (!empty($image)) : ?>
                <div class="sectors-panel__image">
                  <img 
                    src="<?php echo esc_url($image['url']); ?>" 
                    alt="<?php echo esc_attr($image['alt'] ?: $name); ?>"/>
                </div>
              <?php endif; ?>
              <?php if (!empty($name)) : ?>
                <div class="sectors-panel__content">
                  <h4 class="sectors-panel__name">
                    <?php echo esc_html($name); ?>
                  </h4>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
    <?php endif; ?>
  </div>
</section>

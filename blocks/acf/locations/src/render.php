<?php
/**
 * Block Template: locations
 */

$attrs = get_block_wrapper_attributes([
    'class' => 'locations position-relative',
]);

$allowed_blocks = ['core/paragraph'];

$template = [
    ['core/paragraph', [
        'className' => 'locations__title ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'className' => 'locations__text ani-top ani-fade',
    ]],
    ['core/paragraph', [
        'className' => 'locations__text ani-top ani-fade',
    ]],
];

$location_tabs = get_field('location_tabs');
?>

<section <?php echo $attrs; ?>>
    <div class="container">
        <div class="locations__grid">

            <div class="locations__tabs mobile-tabs">
                <?php if ($location_tabs): ?>
                    <?php foreach ($location_tabs as $index => $tab): ?>
                        <button
                            class="locations__tab <?php echo $index === 0 ? 'locations__tab--active' : ''; ?>"
                            data-tab="tab-<?php echo $index; ?>"
                        >
                            <?php echo esc_html($tab['tab_title']); ?>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="locations__map ani-top ani-fade">
                <?php if (!empty($location_tabs[0]['tab_image'])): ?>
                    <img
                        src="<?php echo esc_url($location_tabs[0]['tab_image']['url']); ?>"
                        alt="Map"
                        class="locations__map-image"
                        id="locationsMap"
                    />
                <?php endif; ?>
            </div>

            <div class="locations__content">
                <InnerBlocks
                    allowedBlocks="<?php echo esc_attr(json_encode($allowed_blocks)); ?>"
                    template="<?php echo esc_attr(json_encode($template)); ?>"
                    templateLock="false"
                />

                <?php if ($location_tabs): ?>
                    <div class="locations__tabs desktop-tabs">
                        <?php foreach ($location_tabs as $index => $tab): ?>
                            <button
                                class="locations__tab <?php echo $index === 0 ? 'locations__tab--active' : ''; ?>"
                                data-tab="tab-<?php echo $index; ?>"
                            >
                                <?php echo esc_html($tab['tab_title']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($location_tabs): ?>
                  <?php foreach ($location_tabs as $index => $tab): ?>
                    <div
                      class="locations__panel <?php echo $index === 0 ? 'locations__panel--active' : ''; ?>"
                      id="tab-<?php echo esc_attr($index); ?>"
                      data-image="<?php echo !empty($tab['tab_image']['url']) ? esc_url($tab['tab_image']['url']) : ''; ?>"
                    >
                    <?php if(!empty($tab['location_title']) || !empty($tab['location_address']) || !empty($tab['phone_icon']) || !empty($tab['phone_label']) || !empty($tab['phone_number'])) : ?>        
                      <div class="locations__card">
                        <?php if (!empty($tab['location_title'])): ?>
                            <p class="locations__card-title">
                                <?php echo esc_html($tab['location_title']); ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!empty($tab['location_address'])): ?>
                          <p class="locations__card-address">
                            <?php echo nl2br(esc_html($tab['location_address'])); ?>
                          </p>
                        <?php endif; ?>
                          <?php if(!empty($tab['phone_icon']) || !empty($tab['phone_label']) || !empty($tab['phone_number'])) : ?>
                            <div class="locations__contact">
                              <?php if (!empty($tab['phone_icon'])): ?>
                                  <span class="locations__icon">
                                      <img src="<?php echo esc_url($tab['phone_icon']['url']); ?>" alt="Phone Icon" />
                                  </span>
                              <?php endif; ?>
                              <?php if(!empty($tab['phone_label']) || !empty($tab['phone_number'])) : ?>
                                <div class="locations__details">
                                  <?php if (!empty($tab['phone_label'])): ?>
                                    <span class="locations__label">
                                      <?php echo esc_html($tab['phone_label']); ?>
                                    </span>
                                  <?php endif; ?>
                                  <?php if (!empty($tab['phone_number'])): ?>
                                    <a href="tel:<?php echo esc_attr($tab['phone_number']); ?>" class="locations__value">
                                      <?php echo esc_html($tab['phone_number']); ?>
                                    </a>
                                  <?php endif; ?>
                                </div>
                              <?php endif; ?>
                                  
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

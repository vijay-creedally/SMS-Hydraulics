<?php 
$footer_logo          = get_field('footer_logo', 'option');
$footer_contact       = get_field('footer_contact', 'option');
$footer_newsletter    = get_field('footer_newsletter', 'option');
$footer_bottom_links  = get_field('footer_bottom_links', 'option');
$footer_address       = get_field('footer_address', 'option');
$footer_copyright     = get_field('footer_copyright', 'option');
?>
<div class="footer__divider"></div>

<footer class="footer position-relative">
	<div class="footer__top container position-relative">
		<div class="footer__col footer__col--about position-relative">
			<?php if (!empty($footer_logo['logo'])){ ?>
				<div class="footer__logo footer__col--about-logo <?php echo empty( $footer_logo['description'] ) ?  'footer__col--about-logo-bottom-space' :''; ?>">
					<a href="<?= home_url(); ?>">
						<?php echo wp_get_attachment_image($footer_logo['logo'], 'full', false, ['alt' => esc_attr__('Footer Logo', 'textdomain')]); ?>
					</a>
				</div>
			<?php }
	  
			if (!empty($footer_logo['description'])) { ?>
				<p class="footer__desc footer__col--about-desc"><?php echo esc_html($footer_logo['description']); ?></p>
			<?php }
			if (!empty($footer_contact['contact_title'])){ ?>
				<h4 class="footer__title footer__col--about-title"><?php echo esc_html($footer_contact['contact_title']); ?></h4>
			<?php } 
			if (!empty($footer_contact) && is_array($footer_contact)) { ?>
				<div class="footer__contact footer__col--contact d-flex flex-column">
					<?php if (!empty($footer_contact['telephone_number'])) { ?>
						<div class="footer__item footer__col--contact-item d-flex align-items-center">
							<?php if (!empty($footer_contact['telephone_logo'])) { ?>
								<div class="footer__icon footer__col--contact-icon d-flex">
									<?php echo wp_get_attachment_image($footer_contact['telephone_logo'], 'full', false, ['alt' => esc_attr__('Telephone Icon', 'textdomain')]); ?>
								</div>
							<?php } ?>
							<div class="footer__icon footer__col--contact-info d-flex flex-column flex-lg-row">
								<?php if (!empty($footer_contact['telephone_label'])) { ?>
									<span class="footer__label footer__col--contact-label fw-bold"><?php echo esc_html($footer_contact['telephone_label']); ?></span>
								<?php } ?>
								<a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $footer_contact['telephone_number'])); ?>" class="footer__link footer__col--contact-link">
									<?php echo esc_html($footer_contact['telephone_number']); ?>
								</a>
							</div>
						</div>
					<?php } 
					if (!empty($footer_contact['email_address'])) { ?>
						<div class="footer__item footer__col--contact-item  d-flex align-items-center">
							<?php if (!empty($footer_contact['email_logo'])){ ?>
								<div class="footer__icon footer__col--contact-icon  d-flex">
									<?php echo wp_get_attachment_image($footer_contact['email_logo'], 'full', false, ['alt' => esc_attr__('Email Icon', 'textdomain')]); ?>
								</div>
							<?php } ?>
							<div class="footer__icon footer__col--contact-info  d-flex flex-column flex-lg-row ">
								<?php if (!empty($footer_contact['email_label'])){ ?>
									<span class="footer__label footer__col--contact-label fw-bold"><?php echo esc_html($footer_contact['email_label']); ?></span>
								<?php } ?>
								<a href="mailto:<?php echo esc_attr($footer_contact['email_address']); ?>" class="footer__link footer__col--contact-link d-flex">
									<?php echo esc_html($footer_contact['email_address']); ?>
								</a>
							</div>
						</div>
					<?php } ?>
				</div>
      		<?php } ?>
    	</div>

    	<div class="footer__col footer__col--links d-flex <?php echo ( !empty( $footer_newsletter['hide_newsletter_section'] ) ) ? 'footer__col--links-hide-border': '' ?>">
			<?php wp_nav_menu(array(
			'theme_location' => 'footer-menu',
			'menu_class'     => 'footer__menu footer__col--links-menu d-flex flex-column',
			'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'container'      => false,
			'fallback_cb'    => false,
			)); ?>
    	</div>

		<?php if ( empty( $footer_newsletter['hide_newsletter_section'] ) ) : ?>
			<div class="footer__col footer__col--newsletter d-flex flex-column position-relative">
				<?php if (!empty($footer_newsletter['newsletter_heading'])) { ?>
					<h4 class="footer__heading footer__col--newsletter-heading">
						<?php echo esc_html($footer_newsletter['newsletter_heading']); ?>
					</h4>
				<?php } 
				if (!empty($footer_newsletter['newsletter_description'])) { ?>
					<p class="footer__text footer__col--newsletter-text">
					<?php echo esc_html($footer_newsletter['newsletter_description']); ?>
					</p>
				<?php }
				if (!empty($footer_newsletter['gravity_form_shortcode'])) {
					echo do_shortcode(wp_kses_post($footer_newsletter['gravity_form_shortcode']));
				} ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="footer__bottom">
		<div class="container">
			<?php if (!empty($footer_copyright['copyright'])) { ?>
				<p><?php echo esc_html($footer_copyright['copyright']); ?></p>
			<?php } 
			if (!empty($footer_copyright['address'])) { ?>
				<p><?php echo esc_html($footer_copyright['address']); ?></p>
			<?php }
			if (!empty($footer_bottom_links) && is_array($footer_bottom_links)) {
				foreach ($footer_bottom_links as $link) {
					if (!empty($link['link_text']) && !empty($link['link_url'])) { ?>
						<a href="<?php echo esc_url($link['link_url']); ?>"target="_blank">
							<?php echo esc_html($link['link_text']); ?>
						</a>
					<?php }
				}
			} ?>
    	</div>
  	</div>
</footer>
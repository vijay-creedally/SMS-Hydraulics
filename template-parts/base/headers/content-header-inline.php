<?php
$logo_url = get_template_directory_uri() . '/assets/images/White.png';
?>

<header class="header">
	<div class="header__continer">
		<div class="header__container_wrapper">
			<div class="header__overlay"></div>
			<div class="header__content container">
				<div class="header__wrapper">
					<div class="header__logo">
						<a href="<?php echo esc_url(home_url('/')); ?>">
							<?php 
							if (function_exists('get_field')) {
								$logo_id = get_field('logo', 'option');
								if ($logo_id) {
									echo wp_get_attachment_image($logo_id, 'full', false, ['alt' => get_bloginfo('name')]);
								} else {
									echo '<img src="' . esc_url( $logo_url ) . '" alt="'. get_bloginfo('name') . '">';
								}
							} else {
								echo '<img src="' . esc_url( $logo_url ) . '" alt="'. get_bloginfo('name') . '">';
							} ?>
						</a>
						<div class="header__logo-line"></div>
					</div>
					<button class="header__toggle" aria-label="Toggle Menu" aria-expanded="false">
						<svg
							id="menu-icon"
							class="menu-icon"
							xmlns="http://www.w3.org/2000/svg"
							width="48"
							height="35"
							viewBox="0 0 48 35"
							fill="none"
							onclick="this.classList.toggle('active')"
						>
							<line class="line-1" x1="0" y1="5.5" x2="48" y2="5.5" />
							<line class="line-2" x1="12" y1="17.5" x2="48" y2="17.5" />
							<line class="line-3" x1="24" y1="29.5" x2="48" y2="29.5" />
						</svg>
					</button>
				</div>
				<nav class="header__nav" role="navigation" aria-label="Main Menu">
					<?php
						wp_nav_menu(array(
						'theme_location' => 'main-menu',
						'menu_class'     => 'header__menu',
						'items_wrap'     => '<ul id="%1$s" class="%2$s nav-wrapper">%3$s</ul>',
						'container'      => false,
						));
					?>
				</nav>
			</div>
		</div>
	</div>
</header>

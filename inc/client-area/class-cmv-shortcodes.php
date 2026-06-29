<?php
/**
 * Client Media Vault — Portal Frontend Shortcodes
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CMV_Shortcodes {

	public static function init() {
		add_shortcode( 'cmv_login',           [ __CLASS__, 'sc_login' ] );
		add_shortcode( 'cmv_forgot_password', [ __CLASS__, 'sc_forgot' ] );
		add_shortcode( 'cmv_reset_password',  [ __CLASS__, 'sc_reset' ] );
		add_shortcode( 'cmv_media_portal',    [ __CLASS__, 'sc_portal' ] );
	}

	/* ════════════════════════════════════════════════════════════
	   [cmv_login]
	   ════════════════════════════════════════════════════════════ */

	public static function sc_login() {
		if ( is_user_logged_in() && current_user_can( 'client' ) ) {
			wp_safe_redirect( home_url( '/client-media-vault' ) );
			exit;
		}

		$flash = CMV_Auth::get_flash( 'login' );
		ob_start(); ?>
		<div class="client-login py-5">
		    <div class="client-login__card p-4 mx-auto rounded-3 position-relative">
		        <div class="client-login__header text-center mb-4">
		            <h2 class="client-login__title fw-bold text-dark mb-1">
		                <?php echo esc_html( 'Client Login' ); ?>
		            </h2>

		            <p class="client-login__subtitle text-muted fs-6">
		                <?php echo esc_html( 'Sign in to access your files' ); ?>
		            </p>
		        </div>

				<?php if ( ! empty( $flash ) ) : ?>
				<div class="client-login__alert client-login__alert--<?php echo esc_attr( $flash['type'] ); ?> mb-3">
        			<?php echo esc_html( $flash['message'] ); ?>
				</div>
			<?php endif; ?>

		        <form method="post" class="client-login__form d-flex flex-column gap-3" id="client-login-form" novalidate>

		            <?php wp_nonce_field( 'cmv_login', 'cmv_login_nonce' ); ?>

		            <div class="client-login__field d-flex flex-column">
		                <label class="fw-bold text-dark mb-1 small text-uppercase" for="client-login__username">
		                    Username or Email
		                </label>

		                <input
		                    type="text"
		                    id="username"
		                    name="username"
		                    class="client-login__input rounded-2 py-2 px-3"
		                    value="<?php echo esc_attr( $_POST['username'] ?? '' ); ?>"
		                    autocomplete="username"
		                    required
		                >

		                <span class="client-login__error text-danger small" id="err-user"></span>
		            </div>

		            <div class="client-login__field d-flex flex-column">
		                <label class="fw-bold text-dark mb-1 small text-uppercase" for="client-login__password">
		                    Password
		                </label>

		                <div class="client-login__password position-relative">

		                    <input
		                        type="password"
		                        id="password"
		                        name="password"
		                        class="client-login__input rounded-2 py-2 px-3 pe-5"
		                        autocomplete="current-password"
		                        required
		                    >

		                    <button type="button" class="client-login__toggle bg-transparent border-0" aria-label="Show password"></button>

		                </div>

		                <span class="client-login__error text-danger small" id="err-pass"></span>
		            </div>

		            <div class="client-login__options d-flex justify-content-start align-items-center mt-1">

		                <div class="flex-fill">
							<label class="client-login__remember d-flex align-items-center gap-2 small text-muted">

		                	    <input
		                	        type="checkbox"
		                	        name="remember"
		                	        value="1"
		                	        class="mt-0"
		                	    >
		                	    <?php echo esc_html("Remember me"); ?>
		                	</label>
						</div>	
						<div class="flex-fill text-end">
							<a href="<?php echo esc_url( sms_cmv_page_url( 'forgot-password' ) ); ?>"
								class="client-login__link small text-decoration-none">
								Forgot password?
							</a>
						</div>

		            </div>

		            <button
		                type="submit"
		                class="client-login__button client-login__button--primary btn text-white py-2 rounded-2 fw-bold w-100 mt-2"
		            >
		                <?php echo esc_html( 'Log In' ); ?>
		            </button>

		        </form>

		    </div>
		</div>
		<?php return ob_get_clean();
	}

	/* ════════════════════════════════════════════════════════════
	   [cmv_forgot_password]
	   ════════════════════════════════════════════════════════════ */

	public static function sc_forgot() {
		$flash = CMV_Auth::get_flash( 'forgot' );
		ob_start(); ?>
		<div class="client-forgot-password py-5">
			<div class="client-forgot-password__card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
				<div class="client-forgot-password__header text-center mb-4">
					<div class="client-forgot-password__logo rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white">
						&#128274;
					</div>
					<h2 class="client-forgot-password__title fw-bold text-dark mb-1">Forgot Password</h2>
					<p class="cmv-card-subtitle text-muted fs-6">Enter your email and we'll send a reset link.</p>
				</div>
				<?php if ( $flash ) : ?>
					<div class="client-forgot-password__alert client-forgot-password__alert--<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
						<?php echo esc_html( $flash['message'] ); ?>
					</div>
				<?php endif; ?>
				<form method="post" class="client-forgot-password__form d-flex flex-column gap-3" novalidate>
					<?php wp_nonce_field( 'cmv_forgot', 'cmv_forgot_nonce' ); ?>
					<div class="client-forgot-password__field d-flex flex-column gap-1">
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_email">Email Address</label>
						<input type="email" id="cmv_email" name="cmv_email"
							   class="rounded-2 py-2 px-3"
							   value="<?php echo esc_attr( $_POST['cmv_email'] ?? '' ); ?>"
							   autocomplete="email" required>
					</div>
					<button type="submit" class="client-forgot-password__button client-forgot-password__button--primary btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2">Send Reset Link</button>
					<p class="client-forgot-password__footer text-center mt-3 mb-0 small">
						<a href="<?php echo esc_url( CMV_Auth::page_url( 'client-login' ) ); ?>" class="cmv-link text-decoration-none fw-bold">&larr; Back to login</a>
					</p>
				</form>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	/* ════════════════════════════════════════════════════════════
	   [cmv_reset_password]
	   ════════════════════════════════════════════════════════════ */

	public static function sc_reset() {
		$key   = sanitize_text_field( $_GET['key']   ?? '' );
		$login = sanitize_text_field( $_GET['login'] ?? '' );
		$flash = CMV_Auth::get_flash( 'reset' );

		if ( empty( $key ) || empty( $login ) ) {
			return '<div class="cmv-wrap py-5"><div class="cmv-card shadow-lg p-4 mx-auto rounded-3 border-danger">Invalid reset link. Please <a href="' . esc_url( CMV_Auth::page_url( 'forgot-password' ) ) . '" class="cmv-link fw-bold">request a new one</a>.</div></div></div>';
		}
		ob_start(); ?>
		<div class="client-forgot-password py-5">
			<div class="client-forgot-password__card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
				<div class="client-forgot-password__header text-center mb-4">
					<div class="client-forgot-password__logo rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px; background-color: var(--wp--preset--color--primary, #0b3f33); font-size: 1.5rem;">
						&#128274;
					</div>
					<h2 class="client-forgot-password__title fw-bold text-dark mb-1"><?php echo esc_html("Set New Password"); ?></h2>
					<p class="client-forgot-password__subtitle text-muted fs-6"><?php echo esc_html("Choose a strong password (min. 8 characters)."); ?></p>
				</div>
				<?php if ( $flash ) : ?>
					<div class="client-forgot-password__alert client-forgot-password__alert--<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
						<?php echo esc_html( $flash['message'] ); ?>
					</div>
				<?php endif; ?>
				<form method="post" class="client-forgot-password__form d-flex flex-column gap-3" novalidate>
					<?php wp_nonce_field( 'cmv_reset', 'cmv_reset_nonce' ); ?>
					<input type="hidden" name="cmv_key"   value="<?php echo esc_attr( $key ); ?>">
					<input type="hidden" name="cmv_login" value="<?php echo esc_attr( $login ); ?>">
					<div class="client-forgot-password__field d-flex flex-column gap-1">
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass1"><?php echo esc_html("New Password"); ?></label>
						<div class="client-forgot-password__pw-row position-relative">
							<input type="password" id="cmv_pass1" name="cmv_pass1"
								   class="form-control rounded-2 py-2 px-3 pe-5"
								   autocomplete="new-password" required minlength="8">
							<button type="button" class="cmv-toggle-pw btn position-absolute end-0 top-50 translate-middle-y border-0 text-muted" aria-label="Show password">&#128065;</button>
						</div>
						<div class="client-forgot-password__strength-bar rounded-1 mt-2">
							<div class="client-forgot-password__strength-fill h-100" id="cmv-sf"></div>
						</div>
						<span class="client-forgot-password__strength-lbl small mt-1" id="cmv-sl"></span>
					</div>
					<div class="client-forgot-password__field d-flex flex-column gap-1">
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass2"><?php echo esc_html("Confirm Password");?></label>
						<input type="password" id="cmv_pass2" name="cmv_pass2"
							   class="client-forgot-password__input rounded-2 py-2 px-3"
							   autocomplete="new-password" required>
						<span class="text-danger small" id="err-p2"></span>
					</div>
					<button type="submit" class="client-forgot-password__button client-forgot-password__button--primary btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2">Reset Password</button>
				</form>
			</div>
		</div>
		<?php return ob_get_clean();
	}

	public static function sc_portal() {
		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( add_query_arg( 'redirect_to', urlencode( get_permalink() ), CMV_Auth::page_url( 'client-login' ) ) );
			exit;
		}

		$user     = wp_get_current_user();
		$uid      = $user->ID;
		$can_dl   = CMV_Roles::user_can_download( $uid );
		$cat_id   = isset( $_GET['cmv_cat'] )  ? absint( $_GET['cmv_cat'] )            : 0;
		$paged    = isset( $_GET['cmv_page'] ) ? max( 1, absint( $_GET['cmv_page'] ) ) : 1;
		$per_page = 8;

		$query    = CMV_Meta_Fields::get_user_attachments( $uid, $cat_id ?: null, $per_page, $paged );
		$total    = $query->found_posts;
		$pages    = (int) ceil( $total / $per_page );
		$start = ($paged - 1) * $per_page + 1;
		$end   = min($start + $query->post_count - 1, $total);	
		$showing = $total > 0 ? "{$start}-{$end}" : "0";
		$all_cats = self::get_user_categories( $uid );

		$logout_url = wp_nonce_url( add_query_arg( 'cmv_action', 'logout', home_url( '/' ) ), 'cmv_logout' );
		$base_url   = get_permalink();

		ob_start(); ?>
		<div class="media-portal py-4">

			<div class="media-portal__header p-4 mb-4 shadow">
				<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
					<div class="media-portal__header--brand d-flex align-items-center gap-3">
						<div class="rounded-3 d-flex align-items-center justify-content-center text-white">&#128194</div>
						<div>
							<h1 class="h3 text-uppercase fw-bold mb-0"><?php echo esc_html("My Files"); ?></h1>
							<p class="text-black-50 small mb-0"><?php echo esc_html("Welcome back, ") . esc_html( $user->display_name ); ?></p>
						</div>
						<div>
							<?php if ( $can_dl ) : ?>
								<span class=" media-portal__header--badge media-portal__header--badge-download rounded-2">&#128200; <?php echo esc_html("Download Access"); ?></span>
							<?php else : ?>
								<span class="media-portal__header--badge media-portal__header--badge-view rounded-2">&#128065; <?php echo esc_html("View Only"); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<div class="media-portal__header--right d-flex align-items-end gap-2 flex-wrap">
						<a href="<?php echo esc_url( $logout_url ); ?>" class="media-portal__button--primary btn btn-outline-light btn-sm px-3 rounded-2">Sign Out</a>
					</div>
				</div>
			</div>
						
			<?php if ( ! empty( $all_cats ) ) : ?>
				<div class="media-portal__tabs d-flex flex-wrap gap-2 mb-4 justify-content-center justify-content-md-start">
					<a href="<?php echo esc_url( $base_url ); ?>"
					   class="media-portal__tabs--link btn btn-sm px-3 rounded-pill <?php echo ! $cat_id ? 'btn-primary active' : 'btn-outline-secondary'; ?>">
						<?php echo esc_html( 'All Files' ); ?>
					</a>

					<?php foreach ( $all_cats as $cat ) :
						$is_active = ( $cat_id === (int) $cat->term_id );
					?>

						<a href="<?php echo esc_url( add_query_arg( 'cmv_cat', $cat->term_id, $base_url ) ); ?>"
						   class="media-portal__tabs--link btn btn-sm px-3 rounded-pill <?php echo $is_active ? 'btn-primary active' : 'btn-outline-secondary'; ?>">
					
							<?php echo esc_html( $cat->name ); ?>
					
							<span class="badge ms-1 <?php echo $is_active ? 'bg-white text-dark' : 'bg-secondary'; ?>">
								<?php echo (int) $cat->count; ?>
							</span>
					
						</a>
					
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
				
			<div class="media-portal__files--count text-muted small mb-3 px-2">
				Found <?php echo (int) $total; ?> file<?php echo $total !== 1 ? 's' : ''; ?>
			</div>
				
			<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 media-portal__files">
				<?php if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post();
						$att_id   = get_the_ID();
						$mime     = get_post_mime_type( $att_id );
						$is_img   = strpos( $mime, 'image/' ) === 0;
						$cats_lst = wp_get_object_terms( $att_id, 'media_category', [ 'fields' => 'names' ] );
						$view_url = CMV_Secure_Download::get_view_url( $att_id, $uid );
						$dl_url   = CMV_Secure_Download::get_download_url( $att_id, $uid );
				
						if     ( $mime === 'application/pdf' )                                                   $badge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded">PDF</span>';
						elseif ( strpos( $mime, 'video/' ) === 0 )                                               $badge = '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded">VIDEO</span>';
						elseif ( in_array( $mime, [ 'application/zip', 'application/x-zip-compressed' ] ) )     $badge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded text-dark">ZIP</span>';
						elseif ( strpos( $mime, 'application/vnd' ) === 0 || strpos( $mime, 'text/' ) === 0 )   $badge = '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded">DOC</span>';
						else                                                                                      $badge = '<span class="fs-1 text-muted">&#128196;</span>';
				?>
				<div class="media-portal__files--item col">
					<div class="media-portal__files--card h-100 shadow-sm border border-light rounded-3 overflow-hidden position-relative">
						<div class="media-portal__files--thumb bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative">
							<?php if ( $is_img ) : ?>
								<img src="<?php echo esc_url( $view_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php else : ?>
								<?php echo $badge; ?>
							<?php endif; ?>
						</div>
						<div class="media-portal__files--body p-3 d-flex flex-column">
							<h6 class="media-portal__files--title text-dark fw-bold mb-1 text-truncate" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h6>
							<p class="media-portal__files--date text-muted small mb-0 mt-auto"><?php echo esc_html( get_the_date() ); ?></p>
						</div>
						<div class="media-portal__files--footer bg-white border-top-0 p-3 pt-0 d-grid gap-2">
							<a href="<?php echo esc_url( $view_url ); ?>" target="_blank"
							   class="media-portal__files--view-btn btn btn-outline-dark btn-sm flex-grow-1 py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1">
							   &#128065; View
							</a>
							<?php if ( $can_dl ) : ?>
								<a href="<?php echo esc_url( $dl_url ); ?>"
								   class="media-portal__files--download-btn btn text-white btn-sm flex-grow-1 py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1">
								   &#11015; Download
								</a>
							<?php else : ?>
								<span class="media-portal__files--download-btn btn btn-light btn-sm flex-grow-1 py-1.5 rounded-2 text-muted d-flex align-items-center justify-content-center gap-1 cursor-not-allowed" title="Download not permitted">
									&#128274; Locked
								</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endwhile; wp_reset_postdata();
				else : ?>
					<div class="col-12 py-5 text-center text-muted media-portal__files--empty">
						<div class="fs-1 opacity-50 mb-3">&#128193;</div>
						<h4 class="text-dark"><?php echo esc_html("No files yet"); ?></h4>
						<p class="mb-0">Files assigned to you will appear here.</p>
					</div>
				<?php endif; ?>
			</div>
				
			<div class="media-portal__pagination--wrapper d-flex align-items-center justify-content-between pt-3 px-1">

			    <span class="media-portal__pagination--info">
			        Showing <?php echo (int) $showing; ?> of <?php echo (int) $total; ?> files
			    </span>

			    <?php if ( $pages > 1 ) : ?>
			        <nav aria-label="File pagination">
			            <ul class="pagination pagination-sm gap-2 mb-0 d-flex">
			                <?php for ( $i = 1; $i <= $pages; $i++ ) :
			                    $isActive = $i === $paged;
			                ?>
			                    <li class="page-item <?php echo $isActive ? 'active' : ''; ?>">
			                        <a href="<?php echo esc_url( add_query_arg( [ 'cmv_cat' => $cat_id, 'cmv_page' => $i ], $base_url ) ); ?>"
			                           class="page-link">
			                            <?php echo (int) $i; ?>
			                        </a>
			                    </li>
			                <?php endfor; ?>
			            </ul>
			        </nav>
			    <?php endif; ?>
							
			</div>
					
		</div>
		<?php return ob_get_clean();
	}


	public static function get_user_categories( $user_id ) {
		global $wpdb;
		$uid     = (int) $user_id;
		$att_ids = $wpdb->get_col(
			$wpdb->prepare(
				"SELECT post_id FROM {$wpdb->postmeta}
				 WHERE meta_key = '_cmv_assigned_users'
				 AND ( meta_value LIKE %s OR meta_value LIKE %s )",
				'%' . $wpdb->esc_like( ';i:' . $uid . ';' ) . '%',
				'%' . $wpdb->esc_like( '"' . $uid . '"' ) . '%'
			)
		);
		if ( empty( $att_ids ) ) {
			return [];
		}
		$terms = wp_get_object_terms( $att_ids, 'media_category', [ 'orderby' => 'name' ] );
		return is_wp_error( $terms ) ? [] : $terms;
	}
}

function sms_cmv_get_user_categories( $user_id ) {
	return CMV_Shortcodes::get_user_categories( $user_id );
}

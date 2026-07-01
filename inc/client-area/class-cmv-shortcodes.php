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
		add_shortcode( 'cmv_document_viewer', [ __CLASS__, 'get_document_viewer' ] );
	}

	/* ════════════════════════════════════════════════════════════
	   [cmv_login]
	   ════════════════════════════════════════════════════════════ */

	public static function sc_login() {
		if ( is_user_logged_in() && current_user_can( 'client' ) ) {
			wp_safe_redirect( CMV_Auth::page_url( 'client-media-vault' ) );
			exit;
		}

		$flash = CMV_Auth::get_flash( 'login' );
		ob_start(); ?>
		<div class="client-login py-5">
		    <div class="client-login__card p-4 mx-auto rounded-3 position-relative">
		        <div class="client-login__header text-center mb-4">
		            <h2 class="client-login__title fw-bold text-dark mb-1">
		                <?php echo esc_html__( 'Client Login', 'sms' ); ?>
		            </h2>

		            <p class="client-login__subtitle text-muted fs-6">
		                <?php echo esc_html__( 'Sign in to access your files', 'sms' ); ?>
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
				        <label class="fw-bold text-dark mb-1 small text-uppercase" for="username">
				            <?php echo esc_html__( 'Username or Email', 'sms' ); ?>
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
				        <label class="fw-bold text-dark mb-1 small text-uppercase" for="password">
				            <?php echo esc_html__( 'Password', 'sms' ); ?>
				        </label>

				        <div class="client-login__password position-relative ">
				            <input
				                type="password"
				                id="password"
				                name="password"
				                class="client-login__input rounded-2 py-2 px-3"
				                autocomplete="current-password"
				                required
				            >

				            <button
				                type="button"
				                class="client-login__toggle bg-transparent border-0"
				                aria-label="<?php esc_attr_e( 'Show password', 'sms' ); ?>"
				            >
				                <img
				                    src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/eye-off.svg' ); ?>"
				                    alt=""
				                    aria-hidden="true"
				                >
				            </button>
				        </div>

				        <span class="client-login__error text-danger small" id="err-pass"></span>
				    </div>

				    <div class="client-login__options">

					    <div class="flex-fill">
					        <label class="client-login__remember">
					            <input
					                type="checkbox"
					                name="remember"
					                value="1"
					                class="mt-0"
					            >
					            <?php echo esc_html__( 'Remember me', 'sms' ); ?>
					        </label>
					    </div>

					    <div class="flex-fill text-end">
					        <a
					            href="<?php echo esc_url( sms_cmv_page_url( 'forgot-password' ) ); ?>"
					            class="client-login__link small text-decoration-none"
					        >
					            <?php echo esc_html__( 'Forgot password?', 'sms' ); ?>
					        </a>
					    </div>

					</div>

				    <button
				        type="submit"
				        class="client-login__button client-login__button--primary btn text-white py-2 rounded-2 fw-bold w-100 mt-2"
				    >
				        <?php echo esc_html__( 'Log In', 'sms' ); ?>
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
					<h2 class="client-forgot-password__title fw-bold text-dark mb-1"><?php echo esc_html__( 'Forgot Password', 'sms' ); ?></h2>
					<p class="cmv-card-subtitle text-muted fs-6"><?php echo esc_html__( 'Enter your email and we\'ll send a reset link.', 'sms' ); ?></p>
				</div>
				<?php if ( $flash ) : ?>
					<div class="client-forgot-password__alert client-forgot-password__alert--<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
						<?php echo esc_html( $flash['message'] ); ?>
					</div>
				<?php endif; ?>
				<form method="post" class="client-forgot-password__form d-flex flex-column gap-3" novalidate>
					<?php wp_nonce_field( 'cmv_forgot', 'cmv_forgot_nonce' ); ?>
					<div class="client-forgot-password__field d-flex flex-column gap-1">
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_email"><?php echo esc_html__( 'Email Address', 'sms' ); ?></label>
						<input type="email" id="cmv_email" name="cmv_email"
							   class="rounded-2 py-2 px-3"
							   value="<?php echo esc_attr( $_POST['cmv_email'] ?? '' ); ?>"
							   autocomplete="email" required>
					</div>
					<button type="submit" class="client-forgot-password__button client-forgot-password__button--primary btn text-white py-2.5 rounded-2 fw-bold w-100"><?php echo esc_html__( 'Send Reset Link', 'sms' ); ?></button>
					<p class="client-forgot-password__footer text-center mb-0 small">
						<a href="<?php echo esc_url( CMV_Auth::page_url( 'client-login' ) ); ?>" class="cmv-link text-decoration-none fw-bold">&larr;<?php echo esc_html__( 'Back to login', 'sms' ); ?></a>
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
		$login = sanitize_text_field( $_GET['login'] ?? $_GET['email'] ?? '' );
		$flash = CMV_Auth::get_flash( 'reset' );

		if ( empty( $key ) || empty( $login ) ) {
			return sprintf(
				'<div class="cmv-wrap py-5"><div class="cmv-card shadow-lg p-4 mx-auto rounded-3 border-danger">%s <a href="%s" class="cmv-link fw-bold">%s</a>.</div></div>',
				esc_html__( 'Invalid reset link. Please', 'sms' ),
				esc_url( CMV_Auth::page_url( 'forgot-password' ) ),
				esc_html__( 'request a new one', 'sms' )
			);
		}
		ob_start(); ?>
		<div class="client-forgot-password py-5">
			<div class="client-forgot-password__card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
				<div class="client-forgot-password__header text-center mb-4">
					<div class="client-forgot-password__logo rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px; background-color: var(--wp--preset--color--primary, #0b3f33); font-size: 1.5rem;">
						&#128274;
					</div>
					<h2 class="client-forgot-password__title fw-bold text-dark mb-1"><?php echo esc_html__( 'Set New Password', 'sms' ); ?></h2>
					<p class="client-forgot-password__subtitle text-muted fs-6"><?php echo esc_html__( 'Choose a strong password (min. 8 characters).', 'sms' ); ?></p>
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
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass1"><?php echo esc_html__( 'New Password', 'sms' ); ?></label>
						<div class="client-forgot-password__pw-row position-relative">
							<input type="password" id="cmv_pass1" name="cmv_pass1"
								   class="rounded-2 py-2 px-3 pe-5"
								   autocomplete="new-password" required minlength="8">
							<button type="button" class="client-forgot-password__toggle bg-transparent border-0" aria-label="<?php echo esc_attr__( 'Show password', 'sms' ); ?>"></button>
						</div>
						<div class="client-forgot-password__strength-bar rounded-1 mt-2">
							<div class="client-forgot-password__strength-fill h-100" id="cmv-sf"></div>
						</div>
						<span class="client-forgot-password__strength-lbl small mt-1" id="cmv-sl"></span>
					</div>
					<div class="client-forgot-password__field d-flex flex-column gap-1">
						<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass2">
							<?php echo esc_html__( 'Confirm Password', 'sms' ); ?>
						</label>
									
						<div class="client-forgot-password__pw-row position-relative">
							<input
								type="password"
								id="cmv_pass2"
								name="cmv_pass2"
								class="client-forgot-password__input rounded-2 py-2 px-3 pe-5"
								autocomplete="new-password"
								required
							>
									
							<button
								type="button"
								class="client-forgot-password__toggle bg-transparent border-0"
								aria-label="<?php echo esc_attr__( 'Show password', 'sms' ); ?>"
							></button>
						</div>
									
						<span class="text-danger small" id="err-p2"></span>
					</div>
					<button type="submit" class="client-forgot-password__button client-forgot-password__button--primary btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2"><?php echo esc_html__( 'Reset Password', 'sms' ); ?></button>
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
							<h1 class="h3 text-uppercase fw-bold mb-0"><?php echo esc_html__( 'My Files', 'sms' ); ?></h1>
							<p class="text-black-50 small mb-0"><?php echo esc_html( sprintf( __( 'Welcome back, %s', 'sms' ), $user->display_name ) ); ?></p>
						</div>
						<div>
							<?php if ( $can_dl ) : ?>
								<span class=" media-portal__header--badge media-portal__header--badge-download rounded-2">&#128200; <?php echo esc_html__( 'Download Access', 'sms' ); ?></span>
							<?php else : ?>
								<span class="media-portal__header--badge media-portal__header--badge-view rounded-2">&#128065; <?php echo esc_html__( 'View Only', 'sms' ); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<div class="media-portal__header--right d-flex align-items-end gap-2 flex-wrap">
						<a href="<?php echo esc_url( $logout_url ); ?>" class="media-portal__button--primary btn btn-outline-light btn-sm px-3 rounded-2"><?php echo esc_html__( 'Sign Out', 'sms' ); ?></a>
					</div>
				</div>
			</div>
						
			<?php if ( ! empty( $all_cats ) ) : ?>
				<div class="media-portal__tabs d-flex flex-wrap gap-2 mb-4 justify-content-center justify-content-md-start">
					<a href="<?php echo esc_url( $base_url ); ?>"
					   class="media-portal__tabs--link btn btn-sm px-3 rounded-pill <?php echo ! $cat_id ? 'btn-primary active' : 'btn-outline-secondary'; ?>">
						<?php echo esc_html__( 'All Files', 'sms' ); ?>
					</a>

					<?php foreach ( $all_cats as $cat ) :
						$is_active = ( $cat_id === (int) $cat->term_id );
						$file_count_query = CMV_Meta_Fields::get_user_attachments(
						$uid,
						$cat->term_id,
							-1,
							1
						);

						$file_count = (int) $file_count_query->found_posts;

					?>

						<a href="<?php echo esc_url( add_query_arg( 'cmv_cat', $cat->term_id, $base_url ) ); ?>"
						   class="media-portal__tabs--link btn btn-sm px-3 rounded-pill <?php echo $is_active ? 'btn-primary active' : 'btn-outline-secondary'; ?>">
					
							<?php echo esc_html( $cat->name ); ?>
					
							<span class="media-portal__tabs--count badge ms-1 <?php echo $is_active ? 'bg-white text-dark' : ''; ?>">
								<?php echo (int) $file_count; ?>
							</span>
					
						</a>
					
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
				
			<div class="media-portal__files--count text-muted small mb-3 px-2">
				<?php echo esc_html( sprintf( _n( 'Found %d file', 'Found %d files', $total, 'sms' ), $total ) ); ?>
			</div>
				
			<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 media-portal__files">
				<?php if ( $query->have_posts() ) :
					while ( $query->have_posts() ) : $query->the_post();
						$att_id   = get_the_ID();
						$mime     = get_post_mime_type( $att_id );
						$is_img   = strpos( $mime, 'image/' ) === 0;
						$is_video = strpos( $mime, 'video/' ) === 0;
						$is_pdf   = ( 'application/pdf' === $mime );

						$is_viewable = $is_img || $is_video || $is_pdf;

						$cats_lst = wp_get_object_terms( $att_id, 'media_category', [ 'fields' => 'names' ] );
						$view_url = CMV_Secure_Download::get_view_url( $att_id, $uid );
						$dl_url   = CMV_Secure_Download::get_download_url( $att_id, $uid );

						$thumb_url = wp_get_attachment_image_url( $att_id, 'medium' );

						if ( ! $thumb_url ) {
							$thumb_url = wp_mime_type_icon( $att_id );
						}

						if ( $is_pdf ) {
							$badge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded">PDF</span>';
						} elseif ( $is_video ) {
							$badge = '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded">VIDEO</span>';
						} elseif ( in_array( $mime, [ 'application/zip', 'application/x-zip-compressed' ], true ) ) {
							$badge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded text-dark">ZIP</span>';
						} elseif ( strpos( $mime, 'application/vnd' ) === 0 || strpos( $mime, 'text/' ) === 0 ) {
							$badge = '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded">DOC</span>';
						} else {
							$badge = '<span class="fs-1 text-muted">&#128196;</span>';
						}
				?>
				<div class="media-portal__files--item col">
					<div class="media-portal__files--card h-100 shadow-sm border border-light rounded-3 overflow-hidden position-relative">
						<div class="media-portal__files--thumb bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative">
							<?php if ( $is_img ) : ?>
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
							<?php else : ?>
								<?php echo $badge; ?>
							<?php endif; ?>
						</div>
						<div class="media-portal__files--body p-3 d-flex flex-column">
							<h6 class="media-portal__files--title text-dark fw-bold mb-1 text-truncate" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h6>
							<p class="media-portal__files--date text-muted small mb-0 mt-auto"><?php echo esc_html( get_the_date() ); ?></p>
						</div>
						<div class="media-portal__files--footer">
							<?php if( $is_viewable ) : ?>	
							<a href="<?php echo esc_url( $view_url ); ?>"
							   target="_blank"
							   class="media-portal__files--view-btn btn btn-outline-dark btn-sm flex-grow-1 py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1">
							    &#128065; <?php echo esc_html__( 'View', 'sms' ); ?>
							</a>
							<?php endif;?>

							<?php if ( $can_dl ) : ?>
								<a href="<?php echo esc_url( $dl_url ); ?>"
								   class="media-portal__files--download-btn btn text-white btn-sm py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1 <?php echo $is_viewable ? 'flex-grow-1' : ''; ?>">
								   &#11015; <?php echo esc_html__( 'Download', 'sms' ); ?>
								</a>
							<?php else : ?>
								<span class="media-portal__files--download-btn btn btn-light btn-sm py-1.5 rounded-2 text-muted d-flex align-items-center justify-content-center gap-1 cursor-not-allowed <?php echo $is_viewable ? 'flex-grow-1' : ''; ?>" title="<?php echo esc_attr__( 'Download not permitted', 'sms' ); ?>">
									&#128274; <?php echo esc_html__( 'Locked', 'sms' ); ?>
								</span>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<?php endwhile; wp_reset_postdata();
				else : ?>
					<div class="col-12 py-5 text-center text-muted media-portal__files--empty">
						<div class="fs-1 opacity-50 mb-3">&#128193;</div>
						<h4 class="text-dark"><?php echo esc_html__( 'No files yet', 'sms' ); ?></h4>
						<p class="mb-0"><?php echo esc_html__( 'Files assigned to you will appear here.', 'sms' ); ?></p>
					</div>
				<?php endif; ?>
			</div>
				
			<div class="media-portal__pagination--wrapper d-flex align-items-center justify-content-between pt-3 px-1">

			    <span class="media-portal__pagination--info">
				        <?php echo esc_html( sprintf( _n( 'Showing %1$s of %2$s file', 'Showing %1$s of %2$s files', $total, 'sms' ), $showing, $total ) ); ?></span>

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

	public static function get_document_viewer() {

    $attachment_id = absint($_GET['cmv_view'] ?? 0);
    $token         = sanitize_text_field($_GET['token'] ?? '');

    if (!$attachment_id || !$token) {
        return '';
    }

    $file_url = CMV_Secure_Download::get_stream_url(
        $attachment_id,
        get_current_user_id()
    );

    if (!$file_url) {
        return '<div class="alert alert-danger">' . esc_html__( 'File not found.', 'sms' ) . '</div>';
	}

    $mime = get_post_mime_type($attachment_id);

    $is_image = strpos($mime, 'image/') === 0;
    $is_video = strpos($mime, 'video/') === 0;
    $is_pdf   = $mime === 'application/pdf';
    $is_docx  = $mime === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    $is_doc   = $mime === 'application/msword'; 

    $js_safe_file_url = esc_url_raw( $file_url );

    ob_start();
    ?>

    <div class="client-media-viewer-wrapper cmv-viewer--<?php echo esc_attr(str_replace('/', '-', $mime)); ?>">

        <?php if ($is_image): ?>

            <img src="<?php echo esc_url($file_url); ?>" draggable="false" oncontextmenu="return false;" />

        <?php elseif ($is_video): ?>

            <video controls controlsList="nodownload noplaybackrate noremoteplayback">
                <source src="<?php echo esc_url($file_url); ?>" type="<?php echo esc_attr($mime); ?>">
            </video>

        <?php elseif ($is_pdf): ?>

            <div id="pdf-viewer" class="pdf-viewer"></div>
            <div id="pdf-error" class="cmv-error"></div>

            <script src="<?php echo get_stylesheet_directory_uri(); ?>/lib/docview/pdf.min.js"></script>
            <script>
            (function() {
                const url = "<?php echo htmlspecialchars_decode(esc_js(esc_url_raw($js_safe_file_url))); ?>";
                
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = "<?php echo get_stylesheet_directory_uri(); ?>/lib/docview/pdf.worker.min.js";

                async function renderPDF() {
                    try {
                        const loadingTask = window.pdfjsLib.getDocument({
                            url: url,
                            disableRange: true,
                            disableStream: true,
                            withCredentials: true
                        });
                        
                        const pdf = await loadingTask.promise;
                        const container = document.getElementById("pdf-viewer");
                        container.innerHTML = ''; 

                        for (let i = 1; i <= pdf.numPages; i++) {
                            const page = await pdf.getPage(i);
                            const viewport = page.getViewport({ scale: 1.5 });
                            const canvas = document.createElement("canvas");
                            canvas.width = viewport.width;
                            canvas.height = viewport.height;
                            canvas.style.pointerEvents = "none";
                            canvas.style.userSelect = "none";
                            container.appendChild(canvas);
                            
                            await page.render({ 
                                canvasContext: canvas.getContext("2d"), 
                                viewport: viewport 
                            }).promise;
                        }
                    } catch (err) {
                        console.error("PDF load failed:", err);
                        document.getElementById("pdf-error").innerText = "Failed to load PDF: " + err.message;
                    }
                }

                if (window.pdfjsLib) { 
                    renderPDF(); 
                } else { 
                    window.addEventListener('DOMContentLoaded', renderPDF); 
                }

                document.getElementById('pdf-viewer').addEventListener('contextmenu', e => e.preventDefault());
            })();
            </script>

        <?php else: ?>

            <div class="cmv-empty">
                Preview is not available for this file type.
            </div>

        <?php endif; ?>

    </div>

    <?php
    return ob_get_clean();
}
}
function sms_cmv_get_user_categories( $user_id ) {
	return CMV_Shortcodes::get_user_categories( $user_id );
}
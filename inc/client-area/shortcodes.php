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

/* ════════════════════════════════════════════════════════════
   [cmv_login]
   ════════════════════════════════════════════════════════════ */
add_shortcode( 'cmv_login', 'sms_cmv_sc_login' );
function sms_cmv_sc_login() {
	if ( is_user_logged_in() ) {
    wp_safe_redirect( home_url('/cmv-portal') );
    exit;
}
	$flash = sms_cmv_get_flash( 'login' );
	ob_start(); ?>
<div class="cmv-wrap py-5">
	<div class="cmv-card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
		<div class="cmv-card-header text-center mb-4">
			<div class="cmv-logo-icon rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px; background-color: var(--wp--preset--color--primary, #0b3f33); font-size: 1.5rem;">
				&#128274;
			</div>
			<h2 class="cmv-card-title fw-bold text-dark mb-1">Client Portal</h2>
			<p class="cmv-card-subtitle text-muted fs-6">Sign in to access your files</p>
		</div>
		<?php if ( $flash ) : ?>
			<div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
				<?php echo esc_html( $flash['message'] ); ?>
			</div>
		<?php endif; ?>
		<form method="post" class="cmv-form d-flex flex-column gap-3" id="cmv-login-form" novalidate>
			<?php wp_nonce_field( 'cmv_login', 'cmv_login_nonce' ); ?>
			<div class="cmv-field d-flex flex-column gap-1">
				<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_username">Username or Email</label>
				<input type="text" id="cmv_username" name="cmv_username"
					   class="form-control rounded-2 py-2 px-3"
					   value="<?php echo esc_attr( $_POST['cmv_username'] ?? '' ); ?>"
					   autocomplete="username" required>
				<span class="text-danger small" id="err-user"></span>
			</div>
			<div class="cmv-field d-flex flex-column gap-1">
				<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_password">Password</label>
				<div class="cmv-pw-row position-relative">
					<input type="password" id="cmv_password" name="cmv_password"
						   class="form-control rounded-2 py-2 px-3 pe-5"
						   autocomplete="current-password" required>
					<button type="button" class="cmv-toggle-pw btn position-absolute end-0 top-50 translate-middle-y border-0 text-muted" aria-label="Show password">&#128065;</button>
				</div>
				<span class="text-danger small" id="err-pass"></span>
			</div>
			<div class="cmv-row-split d-flex justify-content-between align-items-center mt-1">
				<label class="cmv-check-label d-flex align-items-center gap-2 small text-muted cursor-pointer">
					<input type="checkbox" name="cmv_remember" value="1" class="form-check-input mt-0"> Remember me
				</label>
				<a href="<?php echo esc_url( sms_cmv_page_url( 'cmv-forgot' ) ); ?>" class="cmv-link small text-decoration-none fw-bold">Forgot password?</a>
			</div>
			<button type="submit" class="cmv-btn btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2" style="background-color: var(--wp--preset--color--primary, #0b3f33);">Sign In</button>
		</form>
	</div>
</div>
	<?php return ob_get_clean();
}

/* ════════════════════════════════════════════════════════════
   [cmv_forgot_password]
   ════════════════════════════════════════════════════════════ */
add_shortcode( 'cmv_forgot_password', 'sms_cmv_sc_forgot' );
function sms_cmv_sc_forgot() {
	$flash = sms_cmv_get_flash( 'forgot' );
	ob_start(); ?>
<div class="cmv-wrap py-5">
	<div class="cmv-card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
		<div class="cmv-card-header text-center mb-4">
			<div class="cmv-logo-icon rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px; background-color: var(--wp--preset--color--primary, #0b3f33); font-size: 1.5rem;">
				&#128274;
			</div>
			<h2 class="cmv-card-title fw-bold text-dark mb-1">Forgot Password</h2>
			<p class="cmv-card-subtitle text-muted fs-6">Enter your email and we'll send a reset link.</p>
		</div>
		<?php if ( $flash ) : ?>
			<div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
				<?php echo esc_html( $flash['message'] ); ?>
			</div>
		<?php endif; ?>
		<form method="post" class="cmv-form d-flex flex-column gap-3" novalidate>
			<?php wp_nonce_field( 'cmv_forgot', 'cmv_forgot_nonce' ); ?>
			<div class="cmv-field d-flex flex-column gap-1">
				<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_email">Email Address</label>
				<input type="email" id="cmv_email" name="cmv_email"
					   class="form-control rounded-2 py-2 px-3"
					   value="<?php echo esc_attr( $_POST['cmv_email'] ?? '' ); ?>"
					   autocomplete="email" required>
			</div>
			<button type="submit" class="cmv-btn btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2" style="background-color: var(--wp--preset--color--primary, #0b3f33);">Send Reset Link</button>
			<p class="cmv-form-footer text-center mt-3 mb-0 small">
				<a href="<?php echo esc_url( sms_cmv_page_url( 'cmv-login' ) ); ?>" class="cmv-link text-decoration-none fw-bold">&larr; Back to login</a>
			</p>
		</form>
	</div>
</div>
	<?php return ob_get_clean();
}

/* ════════════════════════════════════════════════════════════
   [cmv_reset_password]
   ════════════════════════════════════════════════════════════ */
add_shortcode( 'cmv_reset_password', 'sms_cmv_sc_reset' );
function sms_cmv_sc_reset() {
	$key   = sanitize_text_field( $_GET['key']   ?? '' );
	$login = sanitize_text_field( $_GET['login'] ?? '' );
	$flash = sms_cmv_get_flash( 'reset' );

	if ( empty( $key ) || empty( $login ) ) {
		return '<div class="cmv-wrap py-5"><div class="cmv-card shadow-lg p-4 mx-auto rounded-3 border-danger" style="max-width: 440px;"><div class="alert alert-danger mb-0 rounded-2">Invalid reset link. Please <a href="' . esc_url( sms_cmv_page_url( 'cmv-forgot' ) ) . '" class="cmv-link fw-bold">request a new one</a>.</div></div></div>';
	}
	ob_start(); ?>
<div class="cmv-wrap py-5">
	<div class="cmv-card shadow-lg p-4 mx-auto rounded-3 position-relative" style="max-width: 440px;">
		<div class="cmv-card-header text-center mb-4">
			<div class="cmv-logo-icon rounded-3 mb-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 56px; height: 56px; background-color: var(--wp--preset--color--primary, #0b3f33); font-size: 1.5rem;">
				&#128274;
			</div>
			<h2 class="cmv-card-title fw-bold text-dark mb-1">Set New Password</h2>
			<p class="cmv-card-subtitle text-muted fs-6">Choose a strong password (min. 8 characters).</p>
		</div>
		<?php if ( $flash ) : ?>
			<div class="alert alert-<?php echo $flash['type'] === 'error' ? 'danger' : 'success'; ?> py-2 px-3 mb-4 text-center rounded-2" role="alert">
				<?php echo esc_html( $flash['message'] ); ?>
			</div>
		<?php endif; ?>
		<form method="post" class="cmv-form d-flex flex-column gap-3" novalidate>
			<?php wp_nonce_field( 'cmv_reset', 'cmv_reset_nonce' ); ?>
			<input type="hidden" name="cmv_key"   value="<?php echo esc_attr( $key ); ?>">
			<input type="hidden" name="cmv_login" value="<?php echo esc_attr( $login ); ?>">
			<div class="cmv-field d-flex flex-column gap-1">
				<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass1">New Password</label>
				<div class="cmv-pw-row position-relative">
					<input type="password" id="cmv_pass1" name="cmv_pass1"
						   class="form-control rounded-2 py-2 px-3 pe-5"
						   autocomplete="new-password" required minlength="8">
					<button type="button" class="cmv-toggle-pw btn position-absolute end-0 top-50 translate-middle-y border-0 text-muted" aria-label="Show password">&#128065;</button>
				</div>
				<div class="cmv-strength-bar rounded-1 mt-2" style="height: 4px; background-color: #e2e8f0; overflow: hidden;">
					<div class="cmv-strength-fill h-100" id="cmv-sf" style="width: 0; transition: width .3s, background-color .3s;"></div>
				</div>
				<span class="cmv-strength-lbl small mt-1" id="cmv-sl"></span>
			</div>
			<div class="cmv-field d-flex flex-column gap-1">
				<label class="fw-bold text-dark mb-1 small text-uppercase" for="cmv_pass2">Confirm Password</label>
				<input type="password" id="cmv_pass2" name="cmv_pass2"
					   class="form-control rounded-2 py-2 px-3"
					   autocomplete="new-password" required>
				<span class="text-danger small" id="err-p2"></span>
			</div>
			<button type="submit" class="cmv-btn btn text-white py-2.5 rounded-2 fw-bold w-100 mt-2" style="background-color: var(--wp--preset--color--primary, #0b3f33);">Reset Password</button>
		</form>
	</div>
</div>
	<?php return ob_get_clean();
}

/* ════════════════════════════════════════════════════════════
   [cmv_media_portal]
   ════════════════════════════════════════════════════════════ */
add_shortcode( 'cmv_media_portal', 'sms_cmv_sc_portal' );
function sms_cmv_sc_portal() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( add_query_arg( 'redirect_to', urlencode( get_permalink() ), sms_cmv_page_url( 'cmv-login' ) ) );
		exit;
	}
	$user     = wp_get_current_user();
	$uid      = $user->ID;
	$can_dl   = sms_cmv_user_can_download( $uid );
	$cat_id   = isset( $_GET['cmv_cat'] )  ? absint( $_GET['cmv_cat'] )          : 0;
	$paged    = isset( $_GET['cmv_page'] ) ? max( 1, absint( $_GET['cmv_page'] ) ) : 1;
	$per_page = 12;

	$query     = sms_cmv_get_user_attachments( $uid, $cat_id ?: null, $per_page, $paged );
	$total     = $query->found_posts;
	$pages     = (int) ceil( $total / $per_page );
	$all_cats  = sms_cmv_get_user_categories( $uid );

	$logout_url = wp_nonce_url( add_query_arg( 'cmv_action', 'logout', home_url( '/' ) ), 'cmv_logout' );
	$base_url   = get_permalink();

	ob_start(); ?>
<div class="cmv-portal-wrap py-4">

	<!-- Header Panel -->
	<div class="cmv-ph rounded-3 p-4 mb-4 text-white shadow" style="background: linear-gradient(135deg, var(--wp--preset--color--primary, #0b3f33) 0%, #175447 100%);">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
			<div class="cmv-ph-brand d-flex align-items-center gap-3">
				<div class="cmv-ph-icon rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 48px; height: 48px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); font-size: 1.3rem;">
					&#128194;
				</div>
				<div>
					<h1 class="h3 fw-bold text-white mb-0">My Files</h1>
					<p class="text-white-50 small mb-0">Welcome back, <?php echo esc_html( $user->display_name ); ?></p>
				</div>
			</div>
			<div class="cmv-ph-right d-flex align-items-center gap-2 flex-wrap">
				<?php if ( $can_dl ) : ?>
					<span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50 px-3 py-2 rounded-pill small">✓ Download Enabled</span>
				<?php else : ?>
					<span class="badge bg-secondary bg-opacity-25 text-white-50 border border-secondary border-opacity-50 px-3 py-2 rounded-pill small">View Only</span>
				<?php endif; ?>
				<a href="<?php echo esc_url( $logout_url ); ?>" class="btn btn-outline-light btn-sm px-3 rounded-2">Sign Out</a>
			</div>
		</div>
	</div>

	<!-- Category Filter Tabs -->
	<?php if ( ! empty( $all_cats ) ) : ?>
	<div class="cmv-tabs-row d-flex flex-wrap gap-2 mb-4 px-2">
		<a href="<?php echo esc_url( $base_url ); ?>"
		   class="btn btn-sm px-3 rounded-pill <?php echo ! $cat_id ? 'btn-primary active' : 'btn-outline-secondary'; ?>"
		   style="<?php echo ! $cat_id ? 'background-color: var(--wp--preset--color--primary, #0b3f33); border-color: var(--wp--preset--color--primary, #0b3f33); color:#fff;' : ''; ?>">
		   All Files
		</a>
		<?php foreach ( $all_cats as $cat ) :
			$isActive = $cat_id === (int) $cat->term_id;
		?>
			<a href="<?php echo esc_url( add_query_arg( 'cmv_cat', $cat->term_id, $base_url ) ); ?>"
			   class="btn btn-sm px-3 rounded-pill <?php echo $isActive ? 'btn-primary active' : 'btn-outline-secondary'; ?>"
			   style="<?php echo $isActive ? 'background-color: var(--wp--preset--color--primary, #0b3f33); border-color: var(--wp--preset--color--primary, #0b3f33); color:#fff;' : ''; ?>">
				<?php echo esc_html( $cat->name ); ?>
				<span class="badge ms-1 <?php echo $isActive ? 'bg-white text-dark' : 'bg-secondary'; ?>"><?php echo (int) $cat->count; ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>

	<!-- Count -->
	<div class="cmv-stats-row text-muted small mb-3 px-2">
		Found <?php echo (int) $total; ?> file<?php echo $total !== 1 ? 's' : ''; ?>
	</div>

	<!-- Grid Layout -->
	<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4 cmv-grid">
		<?php if ( $query->have_posts() ) :
			while ( $query->have_posts() ) : $query->the_post();
				$att_id   = get_the_ID();
				$mime     = get_post_mime_type( $att_id );
				$is_img   = strpos( $mime, 'image/' ) === 0;
				$cats_lst = wp_get_object_terms( $att_id, 'media_category', [ 'fields' => 'names' ] );
				$view_url = sms_cmv_get_view_url( $att_id, $uid );
				$dl_url   = sms_cmv_get_download_url( $att_id, $uid );

				// Determine badge & styling
				if     ( $mime === 'application/pdf' )                              $badge = '<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded">PDF</span>';
				elseif ( strpos( $mime, 'video/' ) === 0 )                          $badge = '<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded">VIDEO</span>';
				elseif ( in_array( $mime, [ 'application/zip','application/x-zip-compressed' ] ) ) $badge = '<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2 rounded text-dark">ZIP</span>';
				elseif ( strpos( $mime, 'application/vnd' ) === 0 || strpos( $mime, 'text/' ) === 0 ) $badge = '<span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded">DOC</span>';
				else                                                                 $badge = '<span class="fs-1 text-muted">&#128196;</span>';
		?>
		<div class="col">
			<div class="card h-100 shadow-sm border border-light rounded-3 overflow-hidden position-relative cmv-card-file">
				<div class="cmv-thumb bg-light d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 160px;">
					<?php if ( $is_img ) : ?>
						<img src="<?php echo esc_url( $view_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" class="w-100 h-100 object-fit-cover transition">
					<?php else : ?>
						<?php echo $badge; ?>
					<?php endif; ?>
				</div>
				<div class="card-body p-3 d-flex flex-column">
					<h6 class="cmv-fname text-dark fw-bold mb-1 text-truncate" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></h6>
					<?php if ( ! empty( $cats_lst ) && ! is_wp_error( $cats_lst ) ) : ?>
						<p class="cmv-fcats small fw-bold mb-1" style="color: var(--wp--preset--color--primary, #0b3f33);"><?php echo esc_html( implode( ', ', $cats_lst ) ); ?></p>
					<?php endif; ?>
					<p class="cmv-fdate text-muted small mb-0 mt-auto"><?php echo esc_html( get_the_date() ); ?></p>
				</div>
				<div class="card-footer bg-white border-top-0 p-3 pt-0 d-flex gap-2">
					<a href="<?php echo esc_url( $view_url ); ?>" target="_blank"
					   class="btn btn-outline-dark btn-sm flex-grow-1 py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1">
					   &#128065; View
					</a>
					<?php if ( $can_dl ) : ?>
						<a href="<?php echo esc_url( $dl_url ); ?>"
						   class="btn text-white btn-sm flex-grow-1 py-1.5 rounded-2 d-flex align-items-center justify-content-center gap-1"
						   style="background-color: var(--wp--preset--color--primary, #0b3f33);">
						   &#11015; Download
						</a>
					<?php else : ?>
						<span class="btn btn-light btn-sm flex-grow-1 py-1.5 rounded-2 text-muted d-flex align-items-center justify-content-center gap-1 cursor-not-allowed" title="Download not permitted">
							&#128274; Locked
						</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php endwhile; wp_reset_postdata();
		else : ?>
			<div class="col-12 py-5 text-center text-muted cmv-empty">
				<div class="fs-1 opacity-50 mb-3">&#128193;</div>
				<h4 class="text-dark">No files yet</h4>
				<p class="mb-0">Files assigned to you will appear here.</p>
			</div>
		<?php endif; ?>
	</div>

	<!-- Pagination -->
	<?php if ( $pages > 1 ) : ?>
	<nav class="d-flex justify-content-center mt-5">
		<ul class="pagination pagination-sm gap-2">
			<?php for ( $i = 1; $i <= $pages; $i++ ) :
				$isActive = $i === $paged;
			?>
				<li class="page-item <?php echo $isActive ? 'active' : ''; ?>">
					<a href="<?php echo esc_url( add_query_arg( [ 'cmv_cat' => $cat_id, 'cmv_page' => $i ], $base_url ) ); ?>"
					   class="page-link rounded-2 border border-secondary border-opacity-25 text-center font-weight-bold"
					   style="<?php echo $isActive ? 'background-color: var(--wp--preset--color--primary, #0b3f33); border-color: var(--wp--preset--color--primary, #0b3f33); color:#fff; width:38px; height:38px; line-height:22px;' : 'color: var(--wp--preset--color--primary, #0b3f33); width:38px; height:38px; line-height:22px;'; ?>">
						<?php echo (int) $i; ?>
					</a>
				</li>
			<?php endfor; ?>
		</ul>
	</nav>
	<?php endif; ?>

</div>
	<?php return ob_get_clean();
}

/* ── Helper: categories that have files assigned to a user ── */
function sms_cmv_get_user_categories( $user_id ) {
	global $wpdb;
	$uid = (int) $user_id;
	// Match serialized PHP arrays: ;i:UID;  OR legacy JSON: "UID"
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

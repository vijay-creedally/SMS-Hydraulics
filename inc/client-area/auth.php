<?php
/**
 * Client Media Vault — Client authentication forms handler
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ══════════════════════════════════════════════════════════
   REDIRECT logged-in clients away from wp-login.php
   ══════════════════════════════════════════════════════════ */
add_action( 'template_redirect', 'sms_cmv_redirect_logged_in_clients' );
function sms_cmv_redirect_logged_in_clients() {
	if ( ! is_user_logged_in() ) {
		return;
	}
	$user = wp_get_current_user();
	if ( ! in_array( 'client', (array) $user->roles ) ) {
		return;
	}

	// If they hit the login page after already being logged in, send to portal
	if ( is_page( 'cmv-login' ) ) {
		wp_safe_redirect( sms_cmv_page_url( 'cmv-portal' ) );
		exit;
	}
}

/* ══════════════════════════════════════════════════════════
   LOGIN form handler
   ══════════════════════════════════════════════════════════ */
add_action( 'init', 'sms_cmv_handle_login' );
function sms_cmv_handle_login() {
	if ( ! isset( $_POST['cmv_login_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['cmv_login_nonce'], 'cmv_login' ) ) {
		return;
	}

	$username = sanitize_user( $_POST['cmv_username'] ?? '' );
	$password = $_POST['cmv_password'] ?? '';
	$remember = ! empty( $_POST['cmv_remember'] );

	if ( empty( $username ) || empty( $password ) ) {
		sms_cmv_set_flash( 'login', 'error', __( 'Please enter your username/email and password.', 'sms' ) );
		return;
	}

	$creds = [
		'user_login'    => $username,
		'user_password' => $password,
		'remember'      => $remember,
	];
	$user = wp_signon( $creds, is_ssl() );

	if ( is_wp_error( $user ) ) {
		sms_cmv_set_flash( 'login', 'error', __( 'Invalid username or password.', 'sms' ) );
		return;
	}

	// Allow any role — admin can also use this page if they want
	wp_safe_redirect( sms_cmv_page_url( 'cmv-portal' ) );
	exit;
}

/* ══════════════════════════════════════════════════════════
   LOGOUT handler
   ══════════════════════════════════════════════════════════ */
add_action( 'init', 'sms_cmv_handle_logout' );
function sms_cmv_handle_logout() {
	if ( ! isset( $_GET['cmv_action'] ) || $_GET['cmv_action'] !== 'logout' ) {
		return;
	}
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'cmv_logout' ) ) {
		return;
	}
	wp_logout();
	wp_safe_redirect( sms_cmv_page_url( 'cmv-login' ) );
	exit;
}

/* ══════════════════════════════════════════════════════════
   FORGOT PASSWORD handler
   ══════════════════════════════════════════════════════════ */
add_action( 'init', 'sms_cmv_handle_forgot_password' );
function sms_cmv_handle_forgot_password() {
	if ( ! isset( $_POST['cmv_forgot_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['cmv_forgot_nonce'], 'cmv_forgot' ) ) {
		return;
	}

	$login = sanitize_email( $_POST['cmv_email'] ?? '' );
	if ( empty( $login ) ) {
		sms_cmv_set_flash( 'forgot', 'error', __( 'Please enter your email address.', 'sms' ) );
		return;
	}

	$user = get_user_by( 'email', $login );
	if ( ! $user ) {
		// Generic message to prevent user enumeration
		sms_cmv_set_flash( 'forgot', 'success', __( 'If that email exists, a reset link has been sent.', 'sms' ) );
		return;
	}

	// Generate reset key
	$key      = get_password_reset_key( $user );
	if ( is_wp_error( $key ) ) {
		sms_cmv_set_flash( 'forgot', 'error', __( 'Could not generate reset link. Please try again.', 'sms' ) );
		return;
	}

	$reset_url = add_query_arg(
		[ 'key' => $key, 'login' => rawurlencode( $user->user_login ) ],
		sms_cmv_page_url( 'cmv-reset' )
	);

	$subject = __( 'Password Reset', 'sms' );
	$message = sprintf(
		/* translators: 1: user name, 2: reset URL */
		__( "Hi %1\$s,\n\nClick the link below to reset your password:\n\n%2\$s\n\nThis link expires in 24 hours.\n\nIf you did not request a reset, ignore this email.", 'sms' ),
		$user->display_name,
		$reset_url
	);
	wp_mail( $user->user_email, $subject, $message );

	sms_cmv_set_flash( 'forgot', 'success', __( 'If that email exists, a reset link has been sent.', 'sms' ) );
}

/* ══════════════════════════════════════════════════════════
   RESET PASSWORD handler
   ══════════════════════════════════════════════════════════ */
add_action( 'init', 'sms_cmv_handle_reset_password' );
function sms_cmv_handle_reset_password() {
	if ( ! isset( $_POST['cmv_reset_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['cmv_reset_nonce'], 'cmv_reset' ) ) {
		return;
	}

	$key      = sanitize_text_field( $_POST['cmv_key']   ?? '' );
	$login    = sanitize_text_field( $_POST['cmv_login'] ?? '' );
	$pass1    = $_POST['cmv_pass1'] ?? '';
	$pass2    = $_POST['cmv_pass2'] ?? '';

	if ( empty( $pass1 ) || $pass1 !== $pass2 ) {
		sms_cmv_set_flash( 'reset', 'error', __( 'Passwords do not match or are empty.', 'sms' ) );
		return;
	}
	if ( strlen( $pass1 ) < 8 ) {
		sms_cmv_set_flash( 'reset', 'error', __( 'Password must be at least 8 characters.', 'sms' ) );
		return;
	}

	$user = check_password_reset_key( $key, $login );
	if ( is_wp_error( $user ) ) {
		sms_cmv_set_flash( 'reset', 'error', __( 'Invalid or expired reset link. Please request a new one.', 'sms' ) );
		return;
	}

	reset_password( $user, $pass1 );
	sms_cmv_set_flash( 'login', 'success', __( 'Password reset! You can now log in.', 'sms' ) );
	wp_safe_redirect( sms_cmv_page_url( 'cmv-login' ) );
	exit;
}

/* ══════════════════════════════════════════════════════════
   Flash message helpers (transient-based, per session)
   ══════════════════════════════════════════════════════════ */
function sms_cmv_set_flash( $form, $type, $message ) {
	if ( ! session_id() ) {
		@session_start();
	}
	$key = 'cmv_flash_' . md5( session_id() . $form );
	set_transient( $key, [ 'type' => $type, 'message' => $message ], 60 );
	// Store key in cookie for retrieval
	setcookie( 'cmv_flash_key_' . $form, $key, time() + 60, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
}

function sms_cmv_get_flash( $form ) {
	$cookie_name = 'cmv_flash_key_' . $form;
	if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
		return null;
	}
	$key  = sanitize_text_field( $_COOKIE[ $cookie_name ] );
	$data = get_transient( $key );
	if ( $data ) {
		delete_transient( $key );
		setcookie( $cookie_name, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
	}
	return $data ?: null;
}

/* ── Helper: get page URL by slug ───────────────────────── */
function sms_cmv_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

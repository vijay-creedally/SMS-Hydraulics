<?php
/**
 * Client Media Vault — Secure File Stream & signed tokens
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* ══════════════════════════════════════════════════════════
   Secure file download via signed token URL
   ?cmv_download=ATTACHMENT_ID&token=TOKEN
   ══════════════════════════════════════════════════════════ */
add_action( 'template_redirect', 'sms_cmv_handle_secure_download', 1 );
function sms_cmv_handle_secure_download() {
	if ( ! isset( $_GET['cmv_download'] ) ) {
		return;
	}

	$attachment_id = absint( $_GET['cmv_download'] );
	$token         = sanitize_text_field( $_GET['token'] ?? '' );

	/* 1. Must be logged in */
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( sms_cmv_page_url( 'cmv-login' ) );
		exit;
	}

	$user_id = get_current_user_id();

	/* 2. Validate HMAC token (prevents brute-force URL guessing) */
	$expected = sms_cmv_generate_download_token( $attachment_id, $user_id );
	if ( ! hash_equals( $expected, $token ) ) {
		wp_die( esc_html__( 'Invalid or expired download link.', 'sms' ), 403 );
	}

	/* 3. Attachment must be assigned to this user */
	if ( ! sms_cmv_attachment_belongs_to_user( $attachment_id, $user_id ) ) {
		wp_die( esc_html__( 'You do not have access to this file.', 'sms' ), 403 );
	}

	/* 4. User must have download permission */
	if ( ! sms_cmv_user_can_download( $user_id ) ) {
		wp_die( esc_html__( 'You do not have permission to download files.', 'sms' ), 403 );
	}

	/* 5. Stream file */
	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		wp_die( esc_html__( 'File not found.', 'sms' ), 404 );
	}

	$mime      = get_post_mime_type( $attachment_id );
	$filename  = basename( $file_path );

	nocache_headers();
	header( 'Content-Type: ' . $mime );
	header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	header( 'Content-Length: ' . filesize( $file_path ) );
	header( 'X-Robots-Tag: noindex' );

	readfile( $file_path );
	exit;
}

/* ══════════════════════════════════════════════════════════
   Secure VIEW (inline) — same checks, different header
   ?cmv_view=ATTACHMENT_ID&token=TOKEN
   ══════════════════════════════════════════════════════════ */
add_action( 'template_redirect', 'sms_cmv_handle_secure_view', 1 );
function sms_cmv_handle_secure_view() {
	if ( ! isset( $_GET['cmv_view'] ) ) {
		return;
	}

	$attachment_id = absint( $_GET['cmv_view'] );
	$token         = sanitize_text_field( $_GET['token'] ?? '' );

	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( sms_cmv_page_url( 'cmv-login' ) );
		exit;
	}

	$user_id  = get_current_user_id();
	$expected = sms_cmv_generate_view_token( $attachment_id, $user_id );
	if ( ! hash_equals( $expected, $token ) ) {
		wp_die( esc_html__( 'Invalid or expired link.', 'sms' ), 403 );
	}

	if ( ! sms_cmv_attachment_belongs_to_user( $attachment_id, $user_id ) ) {
		wp_die( esc_html__( 'You do not have access to this file.', 'sms' ), 403 );
	}

	$file_path = get_attached_file( $attachment_id );
	if ( ! $file_path || ! file_exists( $file_path ) ) {
		wp_die( esc_html__( 'File not found.', 'sms' ), 404 );
	}

	$mime     = get_post_mime_type( $attachment_id );
	$filename = basename( $file_path );

	nocache_headers();
	header( 'Content-Type: ' . $mime );
	header( 'Content-Disposition: inline; filename="' . $filename . '"' );
	header( 'Content-Length: ' . filesize( $file_path ) );
	header( 'X-Robots-Tag: noindex' );

	readfile( $file_path );
	exit;
}

/* ── Token generators (HMAC, daily-rotated) ─────────────── */
function sms_cmv_generate_download_token( $attachment_id, $user_id ) {
	$secret = wp_salt( 'secure_auth' ) . date( 'Ymd' );
	return hash_hmac( 'sha256', 'download|' . $attachment_id . '|' . $user_id, $secret );
}

function sms_cmv_generate_view_token( $attachment_id, $user_id ) {
	$secret = wp_salt( 'secure_auth' ) . date( 'Ymd' );
	return hash_hmac( 'sha256', 'view|' . $attachment_id . '|' . $user_id, $secret );
}

/* ── Build secure download URL ───────────────────────────── */
function sms_cmv_get_download_url( $attachment_id, $user_id ) {
	return add_query_arg([
		'cmv_download' => $attachment_id,
		'token'        => sms_cmv_generate_download_token( $attachment_id, $user_id ),
	], home_url( '/' ) );
}

function sms_cmv_get_view_url( $attachment_id, $user_id ) {
	return add_query_arg([
		'cmv_view' => $attachment_id,
		'token'    => sms_cmv_generate_view_token( $attachment_id, $user_id ),
	], home_url( '/' ) );
}

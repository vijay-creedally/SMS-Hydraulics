<?php
/**
 * Client Media Vault — Secure File Stream & Signed Tokens
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CMV_Secure_Download {

	public static function init() {
		add_action( 'template_redirect', [ __CLASS__, 'handle_secure_download' ], 1 );
		add_action( 'template_redirect', [ __CLASS__, 'handle_secure_stream' ], 1 );
	}

	/* ══════════════════════════════════════════════════════════
	   Secure file download via signed token URL
	   ?cmv_download=ATTACHMENT_ID&token=TOKEN
	   ══════════════════════════════════════════════════════════ */

	public static function handle_secure_download() {
		if ( ! isset( $_GET['cmv_download'] ) ) {
			return;
		}

		$attachment_id = absint( $_GET['cmv_download'] );
		$token         = sanitize_text_field( $_GET['token'] ?? '' );

		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( CMV_Auth::page_url( 'client-login' ) );
			exit;
		}

		$user_id  = get_current_user_id();
		$expected = self::generate_download_token( $attachment_id, $user_id );

		if ( ! hash_equals( $expected, $token ) ) {
			wp_die( esc_html__( 'Invalid or expired download link.', 'sms' ), 403 );
		}
		if ( ! CMV_Meta_Fields::attachment_belongs_to_user( $attachment_id, $user_id ) ) {
			wp_die( esc_html__( 'You do not have access to this file.', 'sms' ), 403 );
		}
		if ( ! CMV_Roles::user_can_download( $user_id ) ) {
			wp_die( esc_html__( 'You do not have permission to download files.', 'sms' ), 403 );
		}

		self::stream_file( $attachment_id, 'attachment' );
	}

	/* ══════════════════════════════════════════════════════════
	   Secure VIEW (inline) — same checks, different header
	   ?cmv_view=ATTACHMENT_ID&token=TOKEN
	   ══════════════════════════════════════════════════════════ */

	public static function handle_secure_stream() {
		if ( ! isset( $_GET['cmv_stream'] ) ) {
			return;
		}

		$attachment_id = absint( $_GET['cmv_stream'] );
		$token         = sanitize_text_field( $_GET['token'] ?? '' );

		if ( ! is_user_logged_in() ) {
			wp_safe_redirect( CMV_Auth::page_url( 'client-login' ) );
			exit;
		}

		$user_id  = get_current_user_id();
		$expected = self::generate_view_token( $attachment_id, $user_id );

		if ( ! hash_equals( $expected, $token ) ) {
			wp_die( esc_html__( 'Invalid or expired link.', 'sms' ), 403 );
		}
		if ( ! CMV_Meta_Fields::attachment_belongs_to_user( $attachment_id, $user_id ) ) {
			wp_die( esc_html__( 'You do not have access to this file.', 'sms' ), 403 );
		}

		self::stream_file( $attachment_id, 'inline' );
	}

	/* ── Shared file-streaming helper ───────────────────────── */

	private static function stream_file( $attachment_id, $disposition ) {
		$file_path = get_attached_file( $attachment_id );
		if ( ! $file_path || ! file_exists( $file_path ) ) {
			wp_die( esc_html__( 'File not found.', 'sms' ), 404 );
		}

		$mime     = get_post_mime_type( $attachment_id );
		$filename = basename( $file_path );

		nocache_headers();
		header( 'Content-Type: ' . $mime );
		header( 'Content-Disposition: ' . $disposition . '; filename="' . $filename . '"' );
		header( 'Content-Length: ' . filesize( $file_path ) );
		header( 'X-Robots-Tag: noindex' );

		readfile( $file_path );
		exit;
	}


	public static function generate_download_token( $attachment_id, $user_id ) {
		$secret = wp_salt( 'secure_auth' ) . date( 'Ymd' );
		return hash_hmac( 'sha256', 'download|' . $attachment_id . '|' . $user_id, $secret );
	}

	public static function generate_view_token( $attachment_id, $user_id ) {
		$secret = wp_salt( 'secure_auth' ) . date( 'Ymd' );
		return hash_hmac( 'sha256', 'view|' . $attachment_id . '|' . $user_id, $secret );
	}


	public static function get_download_url( $attachment_id, $user_id ) {
		return add_query_arg( [
			'cmv_download' => $attachment_id,
			'token'        => self::generate_download_token( $attachment_id, $user_id ),
		], home_url( '/' ) );
	}

	public static function get_view_url( $attachment_id, $user_id ) {
		return add_query_arg( [
			'cmv_view' => $attachment_id,
			'token'    => self::generate_view_token( $attachment_id, $user_id ),
		], home_url( '/page-doc-view/' ) );
	}

	public static function get_stream_url( $attachment_id, $user_id ) {
		return add_query_arg(
			[
				'cmv_stream' => $attachment_id,
				'token'      => self::generate_view_token( $attachment_id, $user_id ),
			],
			home_url( '/' )
		);
	}
}

/* ── Back-compat global helpers ───────────────────────────── */
function sms_cmv_generate_download_token( $attachment_id, $user_id ) {
	return CMV_Secure_Download::generate_download_token( $attachment_id, $user_id );
}

function sms_cmv_generate_view_token( $attachment_id, $user_id ) {
	return CMV_Secure_Download::generate_view_token( $attachment_id, $user_id );
}

function sms_cmv_get_download_url( $attachment_id, $user_id ) {
	return CMV_Secure_Download::get_download_url( $attachment_id, $user_id );
}

function sms_cmv_get_view_url( $attachment_id, $user_id ) {
	return CMV_Secure_Download::get_view_url( $attachment_id, $user_id );
}

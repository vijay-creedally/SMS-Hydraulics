/**
 * Client Media Vault — Frontend JS Component
 *
 * @package WordPress
 * @subpackage sms
 * @since 1.0.0
 */

(function ($) {
	'use strict';

	$(document).ready(function () {
		/* Password show/hide */
		$(document).on('click', '.cmv-toggle-pw', function () {
			var $input = $(this).closest('.cmv-pw-row').find('input');
			var isVisible = $input.attr('type') === 'text';
			$input.attr('type', isVisible ? 'password' : 'text');
			$(this).html(isVisible ? '&#128065;' : '&#128270;');
		});

		/* Password strength meter */
		$(document).on('input', '#cmv_pass1', function () {
			var val = $(this).val();
			var strength = 0;

			if (val.length >= 8) {
				strength++;
			}
			if (/[A-Z]/.test(val)) {
				strength++;
			}
			if (/[0-9]/.test(val)) {
				strength++;
			}
			if (/[^A-Za-z0-9]/.test(val)) {
				strength++;
			}

			// Red-6, Orange/Amber, Info-green, Lime-accent
			var colors = ['', '#ff555e', '#f59e0b', '#1C8C7B', '#A6CC1D'];
			var labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];

			$('#cmv-sf').css({
				'width': (strength * 25) + '%',
				'background-color': colors[strength] || ''
			});
			$('#cmv-sl').text(val.length ? labels[strength] : '')
				.css('color', colors[strength] || '');
		});

		/* Confirm password check */
		$(document).on('input', '#cmv_pass2', function () {
			var match = $(this).val() === $('#cmv_pass1').val();
			$('#err-p2').text($(this).val() && !match ? 'Passwords do not match.' : '');
		});

		/* Login form validation */
		$(document).on('submit', '#cmv-login-form', function (e) {
			var isValid = true;
			var username = $('#cmv_username').val() || '';
			var password = $('#cmv_password').val() || '';

			if (!username.trim()) {
				$('#err-user').text('Enter your username or email.');
				isValid = false;
			} else {
				$('#err-user').text('');
			}

			if (!password) {
				$('#err-pass').text('Enter your password.');
				isValid = false;
			} else {
				$('#err-pass').text('');
			}

			if (!isValid) {
				e.preventDefault();
			}
		});

		/* Auto-dismiss alert banners */
		setTimeout(function () {
			$('.alert').fadeOut(500);
		}, 6000);
	});

})(jQuery);

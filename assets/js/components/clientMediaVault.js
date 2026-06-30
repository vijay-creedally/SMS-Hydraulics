document.addEventListener('DOMContentLoaded', function () {

	document.addEventListener('click', function (e) {
		const toggleBtn = e.target.closest(
			'.client-login__toggle, .client-forgot-password__toggle'
		);

		if (!toggleBtn) {
			return;
		}

		const wrapper = toggleBtn.closest(
			'.client-login__password, .client-forgot-password__pw-row'
		);

		const input = wrapper ? wrapper.querySelector('input') : null;

		if (!input) {
			return;
		}

		const isHidden = input.type === 'password';

		input.type = isHidden ? 'text' : 'password';
		toggleBtn.classList.toggle('show', isHidden);
		toggleBtn.setAttribute(
			'aria-label',
			isHidden ? 'Hide password' : 'Show password'
		);
	});


	// stop right click and F12 for view-file & download
	document.addEventListener('contextmenu', function (e) {
		if (e.target.closest('.cmv-view-file')) {
			e.preventDefault();
		}
	});

	document.addEventListener('dragstart', function (e) {
		if (e.target.closest('.cmv-view-file')) {
			e.preventDefault();
		}
	});


	document.addEventListener('input', function (e) {
		if (!e.target.matches('#cmv_pass1')) return;

		const val = e.target.value;
		let strength = 0;

		if (val.length >= 8) strength++;
		if (/[A-Z]/.test(val)) strength++;
		if (/[0-9]/.test(val)) strength++;
		if (/[^A-Za-z0-9]/.test(val)) strength++;

		const colors = ['', '#ff555e', '#f59e0b', '#1C8C7B', '#A6CC1D'];
		const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];

		const bar = document.querySelector('#cmv-sf');
		const label = document.querySelector('#cmv-sl');

		if (bar) {
			bar.style.width = (strength * 25) + '%';
			bar.style.backgroundColor = colors[strength] || '';
		}

		if (label) {
			label.textContent = val.length ? labels[strength] : '';
			label.style.color = colors[strength] || '';
		}
	});


	document.addEventListener('input', function (e) {
		if (!e.target.matches('#cmv_pass2')) return;

		const pass1 = document.querySelector('#cmv_pass1');
		const err = document.querySelector('#err-p2');

		if (!pass1 || !err) return;

		const match = e.target.value === pass1.value;

		err.textContent = e.target.value && !match
			? 'Passwords do not match.'
			: '';
	});


	document.addEventListener('submit', function (e) {
		if (!e.target.matches('#cmv-login-form')) return;

		let isValid = true;

		const username = document.querySelector('#cmv_username');
		const password = document.querySelector('#cmv_password');

		const errUser = document.querySelector('#err-user');
		const errPass = document.querySelector('#err-pass');

		if (username && !username.value.trim()) {
			if (errUser) errUser.textContent = 'Enter your username or email.';
			isValid = false;
		} else {
			if (errUser) errUser.textContent = '';
		}

		if (password && !password.value) {
			if (errPass) errPass.textContent = 'Enter your password.';
			isValid = false;
		} else {
			if (errPass) errPass.textContent = '';
		}

		if (!isValid) {
			e.preventDefault();
		}
	});


	setTimeout(function () {
		document.querySelectorAll('.alert').forEach(function (el) {
			el.style.transition = 'opacity 0.5s';
			el.style.opacity = '0';
			setTimeout(() => el.remove(), 500);
		});
	}, 6000);
});

(function () {
	const params = new URLSearchParams(window.location.search);

	const view = params.get('cmv_view');
	const token = params.get('token');

	// Enable protection only when both exist
	if (!view || !token) {
		return;
	}

	document.addEventListener('contextmenu', function (e) {
		e.preventDefault();
	});

	document.addEventListener('keydown', function (e) {
		const key = e.key.toLowerCase();

		if (e.key === 'F12') {
			e.preventDefault();
		}

		if (e.ctrlKey && e.shiftKey && key === 'i') {
			e.preventDefault();
		}

		if (e.ctrlKey && e.shiftKey && key === 'j') {
			e.preventDefault();
		}

		if (e.ctrlKey && e.shiftKey && key === 'c') {
			e.preventDefault();
		}

		if (e.ctrlKey && key === 'u') {
			e.preventDefault();
		}
	});

	console.log('CMV protection enabled');
})();
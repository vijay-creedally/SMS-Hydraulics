document.addEventListener('DOMContentLoaded', function () {

	/* Password show/hide */
	document.addEventListener('click', function (e) {
		const toggleBtn = e.target.closest('.client-login__toggle');
		if (!toggleBtn) return;

		console.log('clicked');

		const wrapper = toggleBtn.closest('.client-login__password');
		const input = wrapper ? wrapper.querySelector('input') : null;

		if (!input) return;

		if (input.type === 'password') {
			input.type = 'text';
			toggleBtn.classList.add('show');
			toggleBtn.setAttribute('aria-label', 'Hide password');
		} else {
			input.type = 'password';
			toggleBtn.classList.remove('show');
			toggleBtn.setAttribute('aria-label', 'Show password');
		}
	});


	/* Password strength meter */
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


	/* Confirm password check */
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


	/* Login form validation */
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


	/* Auto-dismiss alert banners */
	setTimeout(function () {
		document.querySelectorAll('.alert').forEach(function (el) {
			el.style.transition = 'opacity 0.5s';
			el.style.opacity = '0';
			setTimeout(() => el.remove(), 500);
		});
	}, 6000);

});
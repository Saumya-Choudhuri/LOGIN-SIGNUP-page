
$(document).ready(function() {
	$('#loginForm').submit(function(e) {
		e.preventDefault();
		var email = $('#email').val();
		var password = $('#password').val();
		$('#loginMsg').text('');

		$.ajax({
			url: 'php/login.php',
			type: 'POST',
			dataType: 'json',
			data: {
				email: email,
				password: password
			},
			success: function(response) {
				if (response.success) {
					// Store session info in localStorage
					localStorage.setItem('session_id', response.session_id);
					localStorage.setItem('user_email', email);
					window.location.href = 'Sprofile.html';
				} else {
					$('#loginMsg').text(response.message).addClass('text-danger');
				}
			},
			error: function() {
				$('#loginMsg').text('Login failed. Please try again.').addClass('text-danger');
			}
		});
	});
});

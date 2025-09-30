
$(document).ready(function() {
	$('#registerForm').submit(function(e) {
		e.preventDefault();
		var name = $('#name').val();
		var email = $('#email').val();
		var password = $('#password').val();
		var confirm_password = $('#confirm_password').val();
		$('#registerMsg').text('');

		if (password !== confirm_password) {
			$('#registerMsg').text('Passwords do not match.').addClass('text-danger');
			return;
		}

		$.ajax({
			url: 'php/register.php',
			type: 'POST',
			dataType: 'json',
			data: {
				name: name,
				email: email,
				password: password
			},
			success: function(response) {
				if (response.success) {
					$('#registerMsg').text('Registration successful! Please login.').removeClass('text-danger').addClass('text-success');
					setTimeout(function() {
						window.location.href = 'login.html';
					}, 1500);
				} else {
					$('#registerMsg').text(response.message).addClass('text-danger');
				}
			},
			error: function() {
				$('#registerMsg').text('Registration failed. Please try again.').addClass('text-danger');
			}
		});
	});
});

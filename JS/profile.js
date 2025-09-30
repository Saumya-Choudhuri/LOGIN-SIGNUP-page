
$(document).ready(function() {
	// Get user email from localStorage
	var email = localStorage.getItem('user_email');
	if (!email) {
		window.location.href = 'login.html';
		return;
	}

	// Fetch profile details from DB
	$.ajax({
		url: 'php/login.php', // Reuse login.php for fetching user details
		type: 'POST',
		dataType: 'json',
		data: { email: email, fetch_profile: true },
		success: function(response) {
			if (response.success && response.profile) {
				$('#name').val(response.profile.name);
				$('#email').val(response.profile.email);
				$('#age').val(response.profile.age || '');
				$('#dob').val(response.profile.dob || '');
				$('#contact').val(response.profile.contact || '');
			}
		}
	});

	// Update profile
	$('#profileForm').submit(function(e) {
		e.preventDefault();
		var name = $('#name').val();
		var age = $('#age').val();
		var dob = $('#dob').val();
		var contact = $('#contact').val();
		$.ajax({
			url: 'php/update_profile.php',
			type: 'POST',
			dataType: 'json',
			data: {
				email: email,
				name: name,
				age: age,
				dob: dob,
				contact: contact
			},
			success: function(response) {
				if (response.success) {
					$('#profileMsg').text('Profile updated!').removeClass('text-danger').addClass('text-success');
				} else {
					$('#profileMsg').text(response.message).addClass('text-danger');
				}
			},
			error: function() {
				$('#profileMsg').text('Update failed.').addClass('text-danger');
			}
		});
	});
});

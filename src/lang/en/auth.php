<?php
return [
	'welcome_message'  => 'Welcome to our backoffice!',
	'sign_in'          => 'Sign In',
	'sign_out'         => 'Sign Out',
	'sign_in_msg'      => 'Enter your email and password to access your account',
	'email'            => 'Email',
	'password'         => 'Password',
	'confirm_password' => 'Confirm password',
	'remember_me'      => 'Remember me',
	'forgot_password'  => 'Forgot your password?',
	'copyright'        => 'Copyright &copy;' . date('Y') . '. All Rights Reserved',
	'activation' => [
		'title' => 'Resend Activation Email',
		'confirm' => 'An activation link will be sent to the user\'s email address',
		'success' => 'You have activated your account! Please choose your new password.',
		'email-sent' => 'An email was sent to :email with the activation link.',
		'expired' => [
			'title' => 'The activation link is incorrect or has expired.',
			'link' => 'Please, <a href="mailto::email">contact the administrator</a> to get a new one.'
		]
	],
	'reset-password' => [
		'title' => 'Reset password',
		'confirm' => 'A password reset link will be sent to the user\'s email address',
		'email-request' => 'Enter your email to reset your password.',
		'send-email' => 'Send password reset',
		'text' => 'Insert your new password to access your account.',
		'submit' => 'Reset password',
		'email-sent' => 'An email was sent to :email with instructions.',
		'success' => 'Password reset successfully, please login with your new password.'
	],
	'validation'      => [
		'login-required' => 'Login field is required.',
		'password'       => [
			'required' => 'Password field is required.',
			'wrong'    => 'Wrong password, try again.'
		],
		'user' => [
			'not-found'         => 'User was not found.',
			'not-activated'     => 'User is not activated.',
			'already-active'    => 'This user has already been activated.',
			'email-required'    => 'The Email field is required.',
			'password-required' => 'The Password field is required.',
			'user-email-repeated' => 'This user has already been registered',
		],
		'reset-password' => [
			'email' => 'The email field is required',
			'incorrect' => 'The reset password code provided is incorrect.'
		],
		'group' => [
			'name' => 'The Name field is required.'
		]
	],
	'user' => 'User',
	'users' => 'Users',
	'user_name' => ':name :lastname',
	'permission_error' => "You don't have the required permissions to do this.",
	'first_name' => 'First Name',
	'last_name' => 'Last Name',
	'full_name' => 'Fullname',
	'name' => 'Name',
	'activated' => 'Activated',
	'activated_at' => 'Activated At',
	'last_login' => 'Last login',
	'group' => 'Group',
	'groups' => 'Groups',
	'permissions' => 'Permissions'
];
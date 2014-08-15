<?php
return [
	'sign_in'          => 'Sign In',
	'sign_in_msg'      => 'Enter your email and password to access your account',
	'email'            => 'Email',
	'password'         => 'Password',
	'confirm_password' => 'Confirm password',
	'remember_me'      => 'Remember me',
	'forgot_password'  => 'Forgot your password?',
	'copyright'        => 'Copyright &copy;' . date('Y') . '. All Rights Reserved',
	'activation' => [
		'success' => 'You have activated your account! Please choose your new password.',
		'expired' => [
			'title' => 'The activation link is incorrect or has expired.',
			'link' => 'Please, <a href="mailto::email">contact the administrator</a> to get a new one.'
		]
	],
	'reset-password' => [
		'title' => 'Reset password',
		'email-request' => 'Enter your email to reset your password.',
		'send-email' => 'Send password reset',
		'text' => 'Insert your new password to access your account.',
		'submit' => 'Reset password',
		'success' => 'Password reset successfully, please login with your new password.'
	],
	'validation'      => [
		'login-required' => 'Login field is required.',
		'password'       => [
			'required' => 'Password field is required.',
			'wrong'    => 'Wrong password, try again.'
		],
		'user' => [
			'not-found'     => 'User was not found.',
			'not-activated' => 'User is not activated.',
			'already-active' => 'This user has already been activated.'
		],
		'reset-password' => [
			'email' => 'The email field is required',
			'incorrect' => 'The reset password code provided is incorrect.'
		]
	]
];
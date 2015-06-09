<?php
return [
	'welcome_message'  => 'Bienvenido al panel de administración!',
	'sign_in'          => 'Entrar',
	'sign_out'         => 'Salir',
	'sign_in_msg'      => 'Ingrese su correo electrónico y su contraseña',
	'email'            => 'Correo',
	'password'         => 'Contraseña',
	'confirm_password' => 'Confirmar contraseña',
	'remember_me'      => 'Recuérdame',
	'forgot_password'  => '¿Olvidó su contraseña?',
	'copyright'        => 'Copyright &copy;' . date('Y') . '. Todos los derechos reservados',
	'activation' => [
		'title' => 'Reenviar correo de activación',
		'confirm' => 'Un vínculo para activar la cuenta será enviado a su correo',
		'success' => '¡Has activado tu cuenta! Por favor, elige una contraseña.',
		'email-sent' => 'Un correo ha sido enviado a :email con el vínculo de activación.',
		'expired' => [
			'title' => 'El vínculo de activación es incorrecto o ha expirado.',
			'link' => 'Por favor, <a href="mailto::email">contacte al administrador</a> para obtener uno nuevo.'
		]
	],
	'reset-password' => [
		'title' => 'Reiniciar contraseña',
		'confirm' => 'Un vínculo para reiniciar su contraseña será enviado a su correo',
		'email-request' => 'Ingrese su correo para reiniciar su contraseña.',
		'send-email' => 'Enviar pedido',
		'text' => 'Ingrese una nueva contraseña para acceder a su cuenta.',
		'submit' => 'Reiniciar',
		'email-sent' => 'Un correo ha sido enviado a :email con instrucciones.',
		'success' => 'Contraseña reiniciada correctamente. Por favor, ingrese nuevamente con su nueva contraseña'
	],
	'validation'      => [
		'login-required' => 'El campo de correo es requerido.',
		'password'       => [
			'required' => 'La contraseña es requerida.',
			'wrong'    => 'Contraseña inválida, intente de nuevo.'
		],
		'user' => [
			'not-found'         => 'El usuario no ha sido encontrado.',
			'not-activated'     => 'El usuario no está activo.',
			'already-active'    => 'Este usuario ya ha sido activado.',
			'email-required'    => 'El campo correo es requerido.',
			'password-required' => 'El campo contraseña es requerido.',
			'user-email-repeated' => 'Este usuario ya ha sido registrado',
		],
		'reset-password' => [
			'email' => 'El campo correo es requerido',
			'incorrect' => 'El código de reinicio de contraseña es incorrecto.'
		],
		'group' => [
			'name' => 'El nombre del grupo es requerido.'
		]
	],
	'user' => 'Usuario',
	'users' => 'Usuarios',
	'user_name' => ':name :lastname',
	'permission_error' => "No tienes permisos suficientes para hacer esto.",
	'first_name' => 'Nombre',
	'last_name' => 'Apellido',
	'name' => 'Nombre',
	'activated' => 'Activo',
	'activated_at' => 'Activo desde',
	'last_login' => 'Ultimo ingreso',
	'group' => 'Grupo',
	'groups' => 'Grupos',
	'permissions' => 'Permisos'
];
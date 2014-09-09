<?php
/**
 * This should hold the menu tree.
 * The menu tree consists of a title (the key) and an array of menu arrays.
 *
 * Menu arrays:
 * Each key will be the links text, while each value  may be a path (a url)
 * or an array of configurations.
 * Each configuration array requires one and only one of the following keys to work:
 *      path     => href of the link (same as if given as value)
 *      action   => Controller @ method syntax url
 *      route    => Named route url
 *      children => array of links to work as a dropdown menu
 *
 * Additional configuration keys:
 *      icon     => FontAwesome icon for the menu
 */
return [
	'menu' => [
		'Backoffice' => [
			'System settings' => [
				'icon' => 'cogs',
				'children' => [
					'Backoffice users' => [
						'icon'   => 'user',
						'action' => 'Digbang\L4Backoffice\Auth\UserController@index'
					],
					'Backoffice groups' => [
						'icon'   => 'group',
						'action' => 'Digbang\L4Backoffice\Auth\GroupController@index'
					]
				]
			]
		]
	]
];
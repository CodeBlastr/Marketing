<?php
App::uses('NotificationsAppModel', 'Notifications.Model');

class Notification extends NotificationsAppModel {
	var $name = 'Notification';

	var $belongsTo = array(
		'NotifieeType' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'notifiee_type_id',
			'conditions' => array('NotifieeType.type' => 'NOTIFIEETYPE'),
			'fields' => '',
			'order' => ''
		),
		'NotificationTemplate' => array(
			'className' => 'Notifications.NotificationTemplate',
			'foreignKey' => 'notification_template_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Creator' => array(
			'className' => 'Users.User',
			'foreignKey' => 'creator_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Modifier' => array(
			'className' => 'Users.User',
			'foreignKey' => 'modifier_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>
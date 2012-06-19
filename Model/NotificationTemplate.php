<?php
App::uses('NotificationsAppModel', 'Notifications.Model');

class NotificationTemplate extends NotificationsAppModel {

	public $name = 'NotificationTemplate';

	public $hasMany = array(
		'Notification'=>array(
			'className'=>'Notifications.Notification',
			'foreignKey'=>'notification_template_id',
			'dependent' => false,
		),
	);

	public $belongsTo = array(
		'NotifieeType' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'notifiee_type_id',
			'conditions' => array('NotifieeType.type' => 'NOTIFIEE_TYPE'),
			'fields' => '',
			'order' => ''
		),
		'NotifieeField' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'notifiee_field_id',
			'conditions' => array('NotifieeField.type' => 'NOTIFIEE_FIELD'),
			'fields' => '',
			'order' => ''
		),
		'NotificationRecipientLookup' => array(
			'className' => 'Enumeration',
			'foreignKey' => '',
			'conditions' => array('NotificationRecipientLookup.type' => 'NOTIFICATION_RECIPIENT_LOOKUP'),
			'fields' => '',
			'order' => ''
		),
		'DateType' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'date_type_id',
			'conditions' => array('DateType.type' => 'NOTIFICATION_DATE_TYPE'),
			'fields' => '',
			'order' => ''
		),
		'DateField' => array(
			'className' => 'Enumeration',
			'foreignKey' => 'date_field_id',
			'conditions' => array('DateField.type' => 'NOTIFICATION_DATE_FIELD'),
			'fields' => '',
			'order' => ''
		),
		'Condition' => array(
			'className' => 'Condition',
			'foreignKey' => 'condition_id',
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


	/**
	 * Create an unsent notification
	 *
	 * @todo  Right now this looks like data duplication.  Each of those fields could have a function act on them in the future so it is necessary for the time being to have this structure.
	 */
	public function templateNotification($data) {
		if (!empty($data['NotificationTemplate']['id'])) {
			# find the template to make a notification from
			$notificationTemplate = $this->find('first', array('conditions'=>array('id'=>$data['NotificationTemplate']['id'])));
			if (!empty($notificationTemplate)) {
				# setup notification data
				$notification['Notification']['notification_template_id'] = $notificationTemplate['NotificationTemplate']['id'];
				$notification['Notification']['is_sent'] = 0;
				$notification['Notification']['send_date'] = null;
				$notification['Notification']['data_array'] = serialize($data);
				$notification['Notification']['name'] = $notificationTemplate['NotificationTemplate']['name'];
				$notification['Notification']['from_name'] = $notificationTemplate['NotificationTemplate']['from_name'];
				$notification['Notification']['from_email'] = $notificationTemplate['NotificationTemplate']['from_email'];
				$notification['Notification']['html'] = $notificationTemplate['NotificationTemplate']['html'];
				$notification['Notification']['text'] = $notificationTemplate['NotificationTemplate']['text'];
				$notification['Notification']['replacement_arrays'] = $notificationTemplate['NotificationTemplate']['replacement_arrays'];
				$notification['Notification']['notifiee_type_id'] = $notificationTemplate['NotificationTemplate']['notifiee_type_id'];
				$notification['Notification']['notifiee_field_id'] = $notificationTemplate['NotificationTemplate']['notifiee_field_id'];
				$notification['Notification']['notifiee_value'] = $notificationTemplate['NotificationTemplate']['notifiee_value'];
				$notification['Notification']['recipients_array'] = $notificationTemplate['NotificationTemplate']['recipients_array'];
				$notification['Notification']['date_array'] = $notificationTemplate['NotificationTemplate']['date_array'];

				if ($this->Notification->save($notification)) {
					#echo 'this is in the notificaton template model';
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

}
?>
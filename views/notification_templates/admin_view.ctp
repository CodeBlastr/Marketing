<div class="notification_templates view">
<h2>Notification Template</h2>

	 <div class="notifications_template data">
		<h6><?php echo __('Default Data') ?></h6>
		<ul class="default datalist">
        <li>Subject : <?php echo $notificationTemplate['NotificationTemplate']['name'];  ?></li>
		<li>HTML Content : <?php echo $notificationTemplate['NotificationTemplate']['html'];  ?></li>
		<li>Plain Text Content : <?php echo nl2br($notificationTemplate['NotificationTemplate']['text']);  ?></li>
       	</ul>
    </div>
</div>

<?php 

echo $this->Html->link(__('Edit Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1, $notificationTemplate['NotificationTemplate']['id']));
echo $this->Html->link(__('Delete Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'delete', 'admin' => 1, $notificationTemplate['NotificationTemplate']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notificationTemplate['NotificationTemplate']['id']));
echo  $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1));
echo $this->Html->link(__('List Templates', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'index', 'admin' => 1));
echo $this->Html->link(__('List Notifications', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'index', 'admin' => 1));
echo $this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1));
// set the contextual menu items
/*
echo $this->Element('context_menu', array('menus' => array(
	array(
		'heading' => 'Notification Templates',
		'items' => array(
			 $this->Html->link(__('Edit Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1, $notificationTemplate['NotificationTemplate']['id'])),			 
			 $this->Html->link(__('Delete Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'delete', 'admin' => 1, $notificationTemplate['NotificationTemplate']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notificationTemplate['NotificationTemplate']['id'])),
			 $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1)),
			 $this->Html->link(__('List Templates', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'index', 'admin' => 1)),
			)
		),
	array(
		'heading' => 'Notifications',
		'items' => array(
			$this->Html->link(__('List Notifications', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'index', 'admin' => 1)),
			$this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1)),
			 )
		),
	))); */
?>


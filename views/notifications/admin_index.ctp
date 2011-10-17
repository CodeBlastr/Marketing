<div class="notifications index">
<h2><?php __('Notifications');?></h2>
<p>
<?php
echo $paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $paginator->sort('id');?></th>
	<th><?php echo $paginator->sort('is_sent');?></th>
	<th><?php echo $paginator->sort('send_date');?></th>
	<th><?php echo $paginator->sort('name');?></th>
	<th><?php echo $paginator->sort('created');?></th>
</tr>
<?php
$i = 0;
foreach ($notifications as $notification):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $notification['Notification']['id']; ?>
		</td>
		<td>
			<?php echo $notification['Notification']['is_sent']; ?>
		</td>
		<td>
			<?php echo $notification['Notification']['send_date']; ?>
		</td>
		<td>
			<?php echo $notification['Notification']['name']; ?>
		</td>
		<td>
			<?php echo $notification['Notification']['created']; ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php echo $this->element('paging'); ?>




<?php 
echo $this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1));
echo $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1));
echo $this->Html->link(__('List Templates', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'index', 'admin' => 1));
// set the contextual menu items 
/*
$this->Menu->setValue(array(
	array(
		'heading' => 'Notifications',
		'items' => array(
			$this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1)),
			 )
		),
	array(
		'heading' => 'Notification Templates',
		'items' => array(
			 $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1)),
			 $this->Html->link(__('List Templates', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'index', 'admin' => 1)),
			)
		),
	)
); */
?>
<div class="notification_templates index">
	<h2><?php echo __('NotificationTemplates');?></h2>
	<p>
		<?php
			echo $this->Paginator->counter(array(
			'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
			));
		?>
	</p>
	<table cellpadding="0" cellspacing="0">
	<tr>
		<th><?php echo $this->Paginator->sort('id');?></th>
		<th><?php echo $this->Paginator->sort('name');?></th>
		<th><?php echo $this->Paginator->sort('text');?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	$i = 0;
	foreach ($notificationTemplates as $notificationTemplate):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $notificationTemplate['NotificationTemplate']['id']; ?>
		</td>
		<td>
			<?php echo $notificationTemplate['NotificationTemplate']['name']; ?>
		</td>
		<td>
			<?php
				echo substr($notificationTemplate['NotificationTemplate']['text'],0,30)."...";
			?>
		</td>
		<td class="actions">

			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $notificationTemplate['NotificationTemplate']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $notificationTemplate['NotificationTemplate']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $notificationTemplate['NotificationTemplate']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $notificationTemplate['NotificationTemplate']['id'])); ?>
		</td>
	</tr>
	<?php endforeach; ?>
	</table>
</div>
<?php echo $this->element('paging'); ?>
<?php 

echo $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1));
echo $this->Html->link(__('List Notifications', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'index', 'admin' => 1));
echo $this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1));
// set the contextual menu items
/*
$this->set('context_menu', array('menus' => array(
	array(
		'heading' => 'Notification Templates',
		'items' => array(
			 $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1)),
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
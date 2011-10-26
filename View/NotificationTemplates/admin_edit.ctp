<div class="notification_templates form">
<?php echo $this->Form->create('NotificationTemplate',array("action" => "edit"));?>
<?php echo $this->Form->input('NotificationTemplate.id'); ?>
    
<p>This tool is meant to be used by someone with limited knowledge of the database construction and CakePHP naming conventions.  <strong>It is not an end user tool. </strong></p>
	<fieldset>
	<legend><?php echo __('Who');?></legend>
	<p>Who is this notification coming from?</p>
	<?php echo $this->Form->input('NotificationTemplate.from_name'); ?>
	<?php echo $this->Form->input('NotificationTemplate.from_email'); ?>
    </fieldset>
	<fieldset>
	<legend><?php echo __('What');?></legend>
	<?php echo $this->Form->input('NotificationTemplate.name', array('label' => 'Subject')); ?>
	<?php echo $this->Form->input('NotificationTemplate.replacement_arrays', array('after' => '[REPLACE]~SelectModel.select_field~FromModel.from_field~WhereModel.where_field')); ?>
	<?php echo $this->Form->input('NotificationTemplate.html', array('type' => 'richtext', 'ckeSettings' => array('buttons' => array('Bold','Italic','Underline','FontSize','TextColor','BGColor','-','NumberedList','BulletedList','Blockquote','JustifyLeft','JustifyCenter','JustifyRight','-','Link','Unlink','-', 'Image')))); ?>
	<?php echo $this->Form->input('NotificationTemplate.text', array('label' => 'Plain Text Version')); ?>
	</fieldset>
    <fieldset>
    <legend><?php echo __('Where'); ?></legend>
	<p>Where are we sending this notification? Looking it up in the database, or a specifying the address? (Add additional after saves)</p>
    <?php echo $this->Form->input('Usability.recipient_type', array('type' => 'radio', 'options' => array('Specify Address', 'Lookup Address'))); ?>
	<?php echo $this->Form->input('NotificationTemplate.notification_recipient_lookup', array('empty' => true, 'label' => $this->Html->link(__('Lookup Method', true), array('plugin' => null, 'controller' => 'enumerations', 'action' => 'index', 'filter' => 'NOTIFICATION_RECIPIENT_ARRAY', 'admin' => 1), array('class' => 'dialog', 'title' => 'Edit Recipient Model List')))); ?>
	<?php echo $this->Form->input('NotificationTemplate.notification_recipient_specified', array('label' => 'Email')); ?>
	<?php echo $this->Form->input('NotificationTemplate.recipients_array'); ?>
    
    <?php 
	if ($this->Form->value('NotificationTemplate.recipients_array') != '') { 
	?>
    	<h4>Current Recipients</h4>
    <?php
		$recipients = explode(',', $this->Form->value('NotificationTemplate.recipients_array')); 
		foreach ($recipients as $recipient) {
	?>
    		<div class="notification-template-recipient"><?php echo $recipient; ?> (to do : add delete button)</div>
    <?php
		}
	}
	?>
    
    </fieldset>
    <fieldset>
    <legend><?php echo __('When'); ?></legend>
	<?php echo $this->Form->input('NotificationTemplate.date_array'); ?>
    </fieldset>
    <fieldset>
    <legend><?php echo __('Why'); ?></legend>
	<p>A brief description of the notification so that you, or the next person who edits this condition can remember why you set this notification up in the first place.</p>
	<?php echo $this->Form->input('Condition.description'); ?>
    </fieldset>
    <!--fieldset>
	<legend><?php echo __('How'); ?></legend>
	<p>We're sending the notication when these save conditions are met on a record. For example, if someone saves a task, we would use tasks, as the plugin, tasks, as the controller, and the action probably as admin_edit.  The extra condition field is used if it should also match something like a type of record.  Like an task which is "Follow Up" type.</p>
	<?php #echo $this->Form->input('Condition.id'); ?>
	<?php #echo $this->Form->input('Condition.plugin', array('empty' => true)); ?>
	<?php #echo $this->Form->input('Condition.controller', array('empty' => true)); ?>
	<?php #echo $this->Form->input('Condition.action'); ?>
	<?php #echo $this->Form->input('Condition.condition', array('label' => 'Extra Condition', 'after' => 'Model.field.operator.value (available operators = != <= >= < >)')); ?>
    </fieldset-->
<?php echo $this->Form->end('Save Template');?>
</div>


<?php 
echo $this->Html->link(__('New Template', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'edit', 'admin' => 1));
echo $this->Html->link(__('List Templates', true), array('plugin' => 'notifications', 'controller' => 'notification_templates', 'action' => 'index', 'admin' => 1));
echo $this->Html->link(__('List Notifications', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'index', 'admin' => 1));
echo $this->Html->link(__('Run Queue', true), array('plugin' => 'notifications', 'controller' => 'notifications', 'action' => 'run_queue', 'admin' => 1));
// set the contextual menu items
/*
echo $this->Element('context_menu', array('menus' => array(
	array(
		'heading' => 'Notification Templates',
		'items' => array(
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




<script type="text/javascript">
$(function() {
	// BEING USED
	
	$("#NotificationTemplateNotificationRecipientLookup").parent().hide();
	$("#NotificationTemplateNotificationRecipientSpecified").parent().hide();
	
	$("#UsabilityRecipientType0, #UsabilityRecipientType1").click(function() {
		var $notifeeRecipientModel = $(this).val();
		if ($notifeeRecipientModel == 0) {
			$("#NotificationTemplateNotificationRecipientLookup").parent().hide();
			$("#NotificationTemplateNotificationRecipientSpecified").parent().show();
		} else {
			$("#NotificationTemplateNotificationRecipientLookup").parent().show();
			$("#NotificationTemplateNotificationRecipientSpecified").parent().hide();
		}
	});
	
	
	$("#NotificationTemplateNotifieeValue").parent().hide();
	$("#NotificationTemplateNotifieeTypeId").change(function() {
		var notifieeType = $("#NotificationTemplateNotifieeTypeId").val();
		if (notifieeType == '11') {
			  $("#NotificationTemplateNotifieeFieldId").parent().hide();
			  $("#NotificationTemplateNotifieeValue").parent().show();
		} else {
			  $("#NotificationTemplateNotifieeFieldId").parent().show();
			  $("#NotificationTemplateNotifieeValue").parent().hide();
		}			
	});
		$("#NotificationTemplateDateValueMonth").parent().hide();
		$("#NotificationTemplateDateTypeId").change(function() {
		var dateType = $("#NotificationTemplateDateTypeId").val();
		if (dateType == '19') {
			  $("#NotificationTemplateDateFieldId").parent().hide();
			  $("#NotificationTemplateDateRelative").parent().hide();
			  $("#NotificationTemplateDateValueMonth").parent().show();
		} else {
			  $("#NotificationTemplateDateFieldId").parent().show();
			  $("#NotificationTemplateDateRelative").parent().show();
			  $("#NotificationTemplateDateValueMonth").parent().hide();
		}			
	});
	/*
	$('#ConditionController').change(function() {
		var pluginName = $("#ConditionPlugin").val();
		var plugin = pluginName + ".";
		var controller = $("#ConditionController").val();
		$("#loadingimg").show();
		$.ajax({
		    url:'/admin/notifications/notification_templates/get_methods.json',
			dataType: 'json',
		    type: 'POST',
		    data:'data[Condition][controller]='+plugin+controller,	
		    success: function(result){		
				var action = '<label for="ConditionAction">Action</label><select id="ConditionAction" name="data[Condition][action]">';
				$.each(result, function(i, item) {
           			action += '<option value="' + result[i] + '">' + result[i] + '<\/option>';
				});				
				action += '</select>';
				$("#ConditionAction").parent().html(action); 
				$("#loadingimg").hide();
		    }
		});
	});	*/	
});
</script> 


<?php #debug($template); ?>
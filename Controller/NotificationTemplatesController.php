<?php
class NotificationTemplatesController extends NotificationsAppController {

	var $name = 'NotificationTemplates';
	var $components = array('PluginList', 'ControllerList');
	var $helpers = array('Cke');
	var $paginate = array('limit' => 10, 'order' => array('NotificationTemplate.created' => 'desc'));

	function admin_index() {
		$notificationTemplates = $this->NotificationTemplate->find('all');
		$this->set('notificationTemplates', $this->paginate());
	}
	
	function admin_view($id = null) {
		if (!$id) {
			$this->flash(__('Invalid NotificationTemplate', true), array('action'=>'index'));
		}
		$notificationTemplate = $this->NotificationTemplate->find("first", array("conditions" => array( "id" => $id ) ) );
		if(!empty($notificationTemplate) && is_array($notificationTemplate))
		{
			$this->set('notificationTemplate', $notificationTemplate);
		}
		else
		{
			$this->Session->setFlash(__('Invalid NotificationTemplate', true));
			$this->redirect(array('action'=>'index'));
		}
	}
	
	function admin_edit($id = null)	{
		if (!empty($this->request->data)) {
			if ($this->NotificationTemplate->save($this->request->data))	{
				$this->Session->setFlash(__('The NotificationTemplate has been updated.', true));
				$this->redirect(array('action'=>'index'));
			} else {
				$this->Session->setFlash(__('The NotificationTemplate could not be saved. Please, try again.', true));
			}
		}
		
		if (empty($this->request->data)) {
			$this->request->data = $this->NotificationTemplate->find('first', array(
				'conditions' => array(
					'NotificationTemplate.id' => $id
					),
				'contain' => array(
					'Condition'
					)
				)
			);
			$this->set('template', $this->request->data);
			
			$notificationRecipientLookups = $this->NotificationTemplate->NotificationRecipientLookup->find('list', array('conditions' => array('NotificationRecipientLookup.type' => 'NOTIFICATION_RECIPIENT_LOOKUP')));
			$notifieeTypes = $this->NotificationTemplate->NotifieeType->find('list', array('conditions' => array('NotifieeType.type' => 'NOTIFIEE_TYPE')));
			$notifieeFields = $this->NotificationTemplate->NotifieeField->find('list', array('conditions' => array('NotifieeField.type' => 'NOTIFIEE_FIELD')));
			$dateTypes = $this->NotificationTemplate->DateType->find('list', array('conditions' => array('DateType.type' => 'NOTIFICATION_DATE_TYPE')));
			$dateFields = $this->NotificationTemplate->DateField->find('list', array('conditions' => array('DateField.type' => 'NOTIFICATION_DATE_FIELD')));
			$plugins = $this->PluginList->get();
			$controllers = $this->ControllerList->get();
			$this->set(compact('notifieeTypes', 'notifieeFields', 'dateTypes', 'dateFields', 'plugins', 'controllers', 'notificationRecipientLookups'));
		}
	}
	

	function __getClassMethods($ctrlName = null) {
		#find base methods for later removal
		App::uses('File', 'Utility');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if ($appIndex !== false ) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		#$baseMethods[] = 'build_acl';
		
		App::import('Controller', $ctrlName);
		if (strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num+1);
		}
		
		if (strpos($ctrlName, '_')) {
			$ctrlName = Inflector::camelize($ctrlName);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		# clean the methods. to remove those in Controller and private actions.
		foreach ($methods as $k => $method) {
			if (strpos($method, '_', 0) === 0) {
				unset($methods[$k]);
				continue;
			}
			if (in_array($method, $baseMethods)) {
				unset($methods[$k]);
				continue;
			}
		}
		return $methods;
	}
	
	function admin_get_methods() {
		$ctrlName = $this->request->data['Condition']['controller'];
		$methods = $this->__getClassMethods($ctrlName);
		$this->set('methods', $methods);
	}

}
?>
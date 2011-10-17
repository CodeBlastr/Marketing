<?php
/** 
 * @todo 			Add documentation
 * @todo			Move much of this to the model.
 * @todo			Make the view much better with fake fields, that fill the actual template data values.
 * @todo			Make notification belong to notification template, and stop the double up of information.  You should only have fields in notification which have to be calculated from the template (ie. send date, recipients, actual message even).
 */
class NotificationsController extends NotificationsAppController {
	var $name = 'Notifications';
	var $allowedActions = array('admin_run_queue');



	function admin_index() {
		$this->Notification->recursive = 0;
		$this->set('notifications', $this->paginate());
	}
	
	
	/**
	 * This runs notifications
	 */
	function admin_run_queue() {
		$this->render(false);
		# select notifcations from the queue which haven't been sent, and the send date is now or older
		$notifications = $this->Notification->find('all',array(
			'conditions' => array(
				'Notification.is_sent' => 0,
				'OR' => array(
					'Notification.send_date' => null, 
					'Notification.send_date <' => date('Y-m-d H:i:s'),
					)
				),
			'order' => array(
				'Notification.send_date'
				),
			'limit' => 100,
			));	
		foreach ($notifications as $notification) {
			# I believe this was originally conceived if you need to send the same notification
			# on multiple dates, and is not actually supported
			$dateArray = unserialize($notification['Notification']['date_array']);
			# This variable contains $this->data from when the notification was saved.
			# All the information possible to draw from is in this data_array.
			$thisData = unserialize($notification['Notification']['data_array']);
			# This variable contains all of the recipients who should receive the mail
			# To do : This is complex and needs documentation
			# thisDataLookup = where in thisData to find the starting id
			# find = the query to run
			# recipient = the field we were looking for from the find
			$recipientData = print_rReverse($notification['Notification']['recipients_array']);
			#next up, change replacements to an actual array
			//$replacementsData = _print_rReverse($notification['Notification']['replacements_array']);
			if (empty($notification['Notification']['send_date'])) {
				$this->_calculateSendDate($notification['Notification']['id'], $dateArray, $thisData);
			} else {
				if ($notification['Notification']['send_date'] < date('Y-m-d H:i:s')) {
					$this->__setupMail($notification, $thisData, $recipientData);	
				} else {
					# no error notification here, it would be crazy
					echo 'no mail to send';
				}
			}
		}
	}
	
	
	/** 
	 * Calculates the date that a notification should be sent.
	 * 
	 * @param {int}		The numeric id of the notifcation to update
	 * @param {array}	An array which can specify send date settings (has specific keys)
	 * @param {array}	The data you might compare the date array against to find relative send dates.
	 * @todo			Spell out the specific keys for date array
	 * @todo			User unserialize instead of the print r reverse thing
	 * @todo			Move as much as possible to the model
	 */
	function _calculateSendDate($id, $dateArray, $thisData) {		
		if (!empty($dataArray['schedule'])) {
			$modelName = $dateArray['schedule']['model'];
			$fieldName = $dateArray['schedule']['field'];
			$delay = $dateArray['schedule']['delay'];
			# holds the date to compare in here ( the date to add or subtract days from )
			$compareDate = strtotime($thisData[$modelName][$fieldName]);
			# the calculated send date
			$sendDate = date('Y-m-d H:i:s', strtotime('+'.$delay.' day', $compareDate));
			# save the 
			$this->data['Notification']['id'] = $id;
			$this->data['Notification']['send_date'] = $sendDate;
			if ($this->Notification->save($this->data)) {
				echo 'notification send date updated';
			}
		} else {
			# if send date array is blank send date is now
			$this->data['Notification']['id'] = $id;
			$this->data['Notification']['send_date'] = date('Y-m-d H:i:s');
			if ($this->Notification->save($this->data)) {
				echo 'notification send date updated';
			}
		}
	}
		
	
	/*
	 * Private function for allowing text replacement variables in the notifications
	 * @TODO The replacement array thing is a mess, needs to be cleaned and made easier to read and input.
	 * 
	 */
	function __buildMessage($notification = null, $thisData) {
		if(!empty($notification)) {
			$message['html'] = $notification['Notification']['html'];
			$message['text'] = $notification['Notification']['text'];
			$message['subject'] = $notification['Notification']['name'];
			# create data replacement fields and replace
			if (!empty($notification['Notification']['replacement_arrays'])) {
				$data = explode(',',$notification['Notification']['replacement_arrays']);
				foreach ($data as $dat) {
					$arr[] = explode('~',$dat);
				}
				# array format = [FIRSTNAME], 'User', 'first_name', 'User', 'user_id', 'Invite', 'creator_id'
				# array format = what to replace  - SELECT Model.field from Model WHERE - Model.field = Model.field value;
				# array format = What to replace - SELECT User.first_name from UserProfile WHERE User.user_id = 10
				# the value for Invite.creator_id example comes from $thisData variable.
				foreach ($arr as $replacement) {
					# test to see if its referring to a plugin
					if(strpos($replacement[1], '.')) {
						App::import('Model', $replacement[1]);
						$models = explode('.', $replacement[1]);
						$replacement[1] = $models[1];
						$this->$replacement[1] = new $replacement[1]();	
					} else {
						App::import('Model', $replacement[1]);
						$this->$replacement[1] = new $replacement[1]();	
					}
					
					if (!empty($replacement[3])) {
						# need to find some data
						$replaces[] = array(
							'find' => $replacement[0],
							'replace' => $this->$replacement[1]->field($replacement[2], array(
									  $replacement[4] => $thisData[$replacement[5]][$replacement[6]]
									)
								));
					} else {
						# the data already exists in the data array no need to look it up
						$replaces[] = array('find' => $replacement[0], 'replace' => $thisData[$models[1]][$replacement[2]]);
					}
					
				}
				# do the replacement in the message
				foreach ($replaces as $replace) {
					$message = str_replace($replace['find'], $replace['replace'], $message);
				}
			}
			return $message;
		} else {
			$this->Session->setFlash(__('Notification Non-Existent', true));
			return false;
		}
	}
	
	function __setupMail($notification, $thisData, $recipientData) {		
		$to = $this->__buildRecipients($notification, $thisData, $recipientData);
		$message = $this->__buildMessage($notification, $thisData);
		$subject = $message['subject'];
		$from = $notification['Notification']['from_email'];
		if (!empty($to)) {
			if ($this->__sendMail($to, $subject, $message, $template, $from)) {
				# mail was sent update it as sent so we don't send it again
				$this->Notification->set(array(
					'id' => $notification['Notification']['id'],
					'is_sent' => 1,
					'sent_date' => date('Y-m-d H:i:s'),
					'sent_to' => print_r($to, true),
					));
				$this->Notification->save();					
			} else {
				# there was an error
			}
			# there was no recipient
		}
    } 
	
	
	/**
	 * Actual email is sent.  DEPRECATED 6/12/2011
	 * 
	 * @todo 		This needs to be deleted and make use of SwiftMailer vendor in app controller instead.
	 
	function __send($email, $subject, $message, $from = null) {
		if (!empty($email['to'])) { 
			$emailsTo = '';
			foreach ($email['to'] as $emailTo) {
				$emailsTo .= $emailTo.',';
			}
			$this->Email->to = $emailsTo;
		}
		if (!empty($email['cc'])) { 
   			$this->Email->cc = $email['cc'];  
		}
		if (!empty($email['bcc'])) { 
   			$this->Email->bcc = $email['bcc'];  
		}
    	$this->Email->subject = $subject;
	    $this->Email->replyTo = $from;
	    $this->Email->from = $from;
	    $this->Email->template = 'default'; 
	    $this->Email->sendAs = 'both'; 
	    $this->set('message', $message);
		#$this->Email->delivery = 'debug';
		#$this->Email->send();
		# uncomment commented lines and comment out the following lines if you want to see debug readout
	    if ($this->Email->send()) {
			$this->Email->reset();
			return true;
		} else {
			$this->Email->reset();
			return false;
		}
		#debug($this->Session->read('Message.email'));
		#break;
	}*/
	
	
	function __buildRecipients($notification = null, $thisData = null, $recipientData = null) {
		if (!empty($recipientData)) {
			foreach ($recipientData as $recipient) {
				if (is_array($recipient['to']) || is_array($recipient['cc']) || is_array($recipient['bcc'])) {	
					if (!empty($recipient['to'])) {
						$to['to'][] = $this->__lookupEmail($notification, $thisData, $recipient['to']);
					}
					if (!empty($recipient['cc'])) {
						$to['cc'][] = $this->__lookupEmail($notification, $thisData, $recipient['cc']);
					}
					if (!empty($recipient['bcc'])) {
						$to['bcc'][] = $this->__lookupEmail($notification, $thisData, $recipient['bcc']);
					}
				} else {
					if (!empty($recipient['to'])) {
						$to['to'][] = $recipient['to'];
					}
					if (!empty($recipient['cc'])) {
						$to['cc'][] = $recipient['cc'];
					}
					if (!empty($recipient['bcc'])) {
						$to['bcc'][] = $recipient['bcc'];
					}
				}
			}
			return $to;
		} else {
			return false;
		}
	}
		
	
	function __lookupEmail($notification, $thisData, $recipientLookupArray) {
		# find the look up id for the first model with the right id to look for from thisData		
		$lookupId = $thisData[trim($recipientLookupArray['thisDataLookup']['model'])][trim($recipientLookupArray['thisDataLookup']['field'])];
		
		$find = $recipientLookupArray['find'];
		$importModel = array_keys($find);
		$findQ = $find[$importModel[0]];
		$lookupField = array_search('thisData', $findQ['conditions']);
		$findQ['conditions'][$lookupField] = $lookupId;
		# check if its a plugin
		if (!empty($findQ['plugin'])) {
			App::import('Model', trim($findQ['plugin']).'.'.$importModel[0]);
		} else {
			App::import('Model', $importModel[0]);
		}
		$this->$importModel[0] = new $importModel[0];
		
		$recipientLookup = $this->$importModel[0]->find('first', $findQ); 
		
		if (is_array($recipientLookup[$recipientLookupArray['recipient']['model']][0])) {
			# to handle hasMany relationship
			$email = $recipientLookup[$recipientLookupArray['recipient']['model']][0][$recipientLookupArray['recipient']['field']];
		} else {
			# to handle belongsTo relationhip
			$email = $recipientLookup[$recipientLookupArray['recipient']['model']][$recipientLookupArray['recipient']['field']];
		}
		return $email;
	}

	
}
?>
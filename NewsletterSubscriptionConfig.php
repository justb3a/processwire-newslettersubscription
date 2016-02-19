<?php

/**
 * Class NewsletterSubscriptionConfig
 */
class NewsletterSubscriptionConfig extends ModuleConfig {

  /**
   * array Default config values
   */
  public function getDefaults() {
    return array(
      'formfields' => 'email',
      'namefields' => '',
      'unsubscribe' => false,
      'mailfrom' => 'noreply@server.com',
      'periodOfValidity' => 5,
      'messageSubscribe' => "Hey %name%\n\nPlease click the following link to confirm your subscription: %link%",
      'messageUnsubscribe' => "Hey %name%\n\nPlease click the following link to end your subscription: %link%",
      'notifyAdmin' => 0,
      'notifyAdminMailto' => 'mailto@server.com',
      'notifyAdminSubscribeMailfrom' => 'noreply@server.com',
      'notifyAdminUnsubscribeMailfrom' => 'noreply@server.com',
      'notifyAdminMessageSubscribe' => "New newsletter subscription",
      'notifyAdminMessageUnsubscribe' => "New newsletter unsubscription",
    );
  }

  /**
   * Retrieves the list of config input fields
   * Implementation of the ConfigurableModule interface
   *
   * @return InputfieldWrapper
   */
  public function getInputfields() {
    $formfields = $this->data['formfields'];
    $unsubscribe = $this->data['unsubscribe'];
    $notifyAdmin = $this->data['notifyAdmin'];
    $inputfields = parent::getInputfields();

    // select fields
    $field = $this->modules->get('InputfieldAsmSelect');
    $field->description = __('Add all fields which should be attached to the subscription form.') . PHP_EOL .
      __('You have to add all needed fields to the user template first¹.');
    $field->addOption('', '');
    $field->label = __('Select form fields for subscription');
    $field->attr('name', 'formfields');
    $field->required = true;
    $field->columnWidth = 50;
    foreach (wire('templates')->get('user')->fields as $f) {
      $field->addOption($f->name, $f->name);
    }
    $inputfields->add($field);

    // select name fields
    $field = $this->modules->get('InputfieldAsmSelect');
    $field->description = __('Add the fields which should be used as username.') . PHP_EOL .
      __('Please save the chosen form fields first to get a list to select from.');
    $field->label = __('Select name fields');
    $field->attr('name', 'namefields');
    $field->columnWidth = 50;
    if (is_array($formfields)) {
      foreach ($formfields as $formfield) {
        $f = wire('fields')->get($formfield);
        $field->addOption($f->name, $f->name);
      }
    }
    $inputfields->add($field);

    // help - how to add fields to the user template
    $help = $this->modules->get('InputfieldMarkup');
    $helpLink = 'https://processwire.com/talk/topic/1156-custom-user-fields/?p=10161';
    $helpContent = '<p><a  target="_blank" href="' . $helpLink . '"><sup>1</sup> How to add fields to the user template?</a></p>';
    $help->value = $helpContent;
    $inputfields->add($help);

    // unsubscribe field
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'unsubscribe';
    $field->label = 'Unsubscribe option';
    $field->description = __('Should the form contain an unsubscribe option?');
    $field->value = 1;
    $field->attr('checked', $unsubscribe ? 'checked' : '');
    $field->columnWidth = 33;
    $inputfields->add($field);

    // mailserver field
    $field = $this->modules->get('InputfieldText');
    $field->name = 'mailfrom';
    $field->label = __('Email From Address');
    $field->description = __('Sender Address');
    $field->columnWidth = 33;
    $inputfields->add($field);

    // periodOfValidity field
    $field = $this->modules->get('InputfieldInteger');
    $field->name = 'periodOfValidity';
    $field->label = __('Period of Validity');
    $field->description = __('Number of days confirmation links are valid.');
    $field->columnWidth = 34;
    $inputfields->add($field);

    // new fieldset containing messages
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = $this->_('Email Messages');
    $fieldset->collapsed = Inputfield::collapsedYes;
    $fieldset->icon = 'send';

    $field = $this->modules->get('InputfieldTextarea');
    $field->name = 'messageSubscribe';
    $field->label = __('Subscribe Email Message');
    $field->description = __('Use %fieldName% as placeholder, for example %email%');
    $field->rows = 8;
    $field->columnWidth = 50;
    $fieldset->add($field);

    $field = $this->modules->get('InputfieldTextarea');
    $field->name = 'messageUnsubscribe';
    $field->label = __('Unsubscribe Email Message');
    $field->description = __('Use %fieldName% as placeholder, for example %email%');
    $field->rows = 8;
    $field->columnWidth = 50;
    $fieldset->add($field);

    $inputfields->add($fieldset);

    // new fieldset containing admin notification
    $fieldset = $this->modules->get('InputfieldFieldset');
    $fieldset->label = $this->_('Notify Admin');
    $fieldset->collapsed = Inputfield::collapsedYes;
    $fieldset->icon = 'comment';

    // notify admin field
    $field = $this->modules->get('InputfieldCheckbox');
    $field->name = 'notifyAdmin';
    $field->label = 'Notify Admin';
    $field->description = __('Should the admin be notified by email?');
    $field->value = 1;
    $field->columnWidth = 50;
    $field->attr('checked', $notifyAdmin ? 'checked' : '');
    $fieldset->add($field);

    // notify admin - mailto field
    $field = $this->modules->get('InputfieldText');
    $field->name = 'notifyAdminMailto';
    $field->label = __('Admin Notification Email');
    $field->description = __('Mailto Address');
    $field->columnWidth = 50;
    $field->showIf = "notifyAdmin=1";
    $fieldset->add($field);

    // notify admin - subscribtion - mailserver field
    $field = $this->modules->get('InputfieldText');
    $field->name = 'notifyAdminSubscribeMailfrom';
    $field->label = __('Subscription - Email From Address');
    $field->description = __('Sender Address');
    $field->columnWidth = 50;
    $field->showIf = "notifyAdmin=1";
    $fieldset->add($field);

    // notify admin - unsubscribtion - mailserver field
    $field = $this->modules->get('InputfieldText');
    $field->name = 'notifyAdminUnsubscribeMailfrom';
    $field->label = __('Unsubscription - Email From Address');
    $field->description = __('Sender Address');
    $field->columnWidth = 50;
    $field->showIf = "notifyAdmin=1";
    $fieldset->add($field);

    // notify admin - subscription - message field
    $field = $this->modules->get('InputfieldTextarea');
    $field->name = 'notifyAdminMessageSubscribe';
    $field->label = __('Subscribe Email Message');
    $field->description = __('Use %fieldName% as placeholder, for example %email%');
    $field->rows = 8;
    $field->columnWidth = 50;
    $field->showIf = "notifyAdmin=1";
    $fieldset->add($field);

    // notify admin - unsubscription - message field
    $field = $this->modules->get('InputfieldTextarea');
    $field->name = 'notifyAdminMessageUnsubscribe';
    $field->label = __('Unsubscribe Email Message');
    $field->description = __('Use %fieldName% as placeholder, for example %email%');
    $field->rows = 8;
    $field->columnWidth = 50;
    $field->showIf = "notifyAdmin=1";
    $fieldset->add($field);

    $inputfields->add($fieldset);

    return $inputfields;
  }

}

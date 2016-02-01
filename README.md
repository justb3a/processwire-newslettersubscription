# ProcessWire newsletter subscription

Allow users to subscribe and unsubscribe to a newsletter.

## Subscription flow

* user enters his/her email and some other information (if you want to)
* a mail is sent to the given address containing a confirmation link (double opt-in)
* the user is added with status hidden
* after visiting the link the user is subscribed (status active)
* user gets role newsletter

## Unsubscription flow

### A) using form

* user enters his/her email and selects unsubscribe
* a mail is sent to the given address (if address exists) containing a confirmation link
* after visiting the link the user is unsubscribed (the user will be deleted)

### B) link inside newsletter

* the user is unsubscribed after visiting the link immediately
* you have to generate the link depending on email and userAuthSalt

## Usage

* install the module
* create fields which you want to add to the newsletter subscription form
  * if the field should be required, add this in the field settings
  * field email is mandatory and already preselected
* assign these fields to the user template 
  * [How to assign fields to the user template](https://processwire.com/talk/topic/1156-custom-user-fields/?p=10161)
* fill module settings
  * select fields which should be added to the form - save module config
  * select fields which should be used as username
  * if you want to provide an unsubscribe option, activate the according checkbox
  * enter email from address
  * enter how many days the confirmation links should be valid (default 5 days)
  * define email text messages
* call module
  
  ```php
  echo $modules->get('NewsletterSubscription')->render();
  ```
* if you want to add own classes and/or markup, you can pass an option array as parameter

  ```php
  $options = array (
    'markup' => array(
      'InputfieldSelect' => array(
        'item' : "{out}"
      )
    ),
    'classes' => array(
      'form' => 'form  form__super-special-class',
      'InputfieldRadios' => array(
        'item' => 'form__item--options'
      )
    )
  );

  echo $modules->get('NewsletterSubscription')->render($options);
  ```
## How to overwrite classes and markup

* Below is the list of all available customization options
  Copied from [ProcessWire master 2.7](https://github.com/ryancramerdesign/ProcessWire/blob/master/wire/core/InputfieldWrapper.php) 

```php
$defaultMarkup = array(
  'list' => "\n<ul {attrs}>\n{out}\n</ul>\n",
  'item' => "\n\t<li {attrs}>\n{out}\n\t</li>", 
  'item_label' => "\n\t\t<label class='InputfieldHeader ui-widget-header{class}' for='{for}'>{out}</label>",
  'item_label_hidden' => "\n\t\t<label class='InputfieldHeader InputfieldHeaderHidden ui-widget-header{class}'><span>{out}</span></label>",
  'item_content' => "\n\t\t<div class='InputfieldContent ui-widget-content{class}'>\n{out}\n\t\t</div>", 
  'item_error' => "\n<p class='InputfieldError ui-state-error'><i class='fa fa-fw fa-flash'></i><span>{out}</span></p>",
  'item_description' => "\n<p class='description'>{out}</p>", 
  'item_head' => "\n<h2>{out}</h2>", 
  'item_notes' => "\n<p class='notes'>{out}</p>",
  'item_icon' => "<i class='fa fa-{name}'></i> ",
  'item_toggle' => "<i class='toggle-icon fa fa-angle-down' data-to='fa-angle-down fa-angle-right'></i>", 
  // ALSO: 
  // InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
);

$defaultClasses = array(
  'form' => '', // additional clases for InputfieldForm (optional)
  'list' => 'Inputfields',
  'list_clearfix' => 'ui-helper-clearfix', 
  'item' => 'Inputfield {class} Inputfield_{name} ui-widget',
  'item_label' => '', // additional classes for InputfieldHeader (optional)
  'item_content' => '',  // additional classes for InputfieldContent (optional)
  'item_required' => 'InputfieldStateRequired', // class is for Inputfield
  'item_error' => 'ui-state-error InputfieldStateError', // note: not the same as markup[item_error], class is for Inputfield
  'item_collapsed' => 'InputfieldStateCollapsed',
  'item_column_width' => 'InputfieldColumnWidth',
  'item_column_width_first' => 'InputfieldColumnWidthFirst',
  'item_show_if' => 'InputfieldStateShowIf',
  'item_required_if' => 'InputfieldStateRequiredIf'
  // ALSO: 
  // InputfieldAnything => array( any of the properties above to override on a per-Inputifeld basis)
);
```

## Notify admin via email

* if you want to notify any person via email if an user has subscribed/unsubscribed,
  check the according checkbox in module settings
* as soon as you activated this field, some more fields will appear
* in these fields you can specify further email messages as well as email receiver and sender
* as you are used to you be able to use placeholders like `%email%`
  - even for the receiver email addresses
* after the user has visited the link the admin gets notified

<?php

/**
 * Helpdesk Module Entry Point
 * 
 * @package    Joomla
 * @subpackage Modules
 * @license    MIT
 *     
 */
 
// No direct access
defined('_JEXEC') or die;

// Find category ID
if(JRequest::getVar('option')=='com_content'){
  
  if(JRequest::getVar('view')=='article'){
    $catid = JRequest::getInt('catid');
  }

  elseif(JRequest::getVar('view')=='category'){
    $catid = JRequest::getInt('id');
  }
}

if($catid !== 20){
  return false;
}

// Load vendors
include 'vendor/autoload.php';

// Load helpers
include 'helpers/db.php';
include 'helpers/mailer.php';

// Gather FuelPHP
use Fuel\Validation\Validator;

// Settings
$showName = $params->get('name', false);
$showNumber = $params->get('number', false);
$showEmail = $params->get('email', false);

if($showHeading = $params->get('heading')){
  $heading = $params->get('heading', false);
  $subHeading = $params->get('subheading', false);
}

// Check for post data
if($post = JRequest::getVar('helpdesk', false, 'post')){

  // Check honeypot
  if( ! empty($_POST['birthday']) ){
    return true;
  }

  // Validate
  $val = new Validator;
  $showName ? $val->addField('name')->required() : null;
  $showNumber ? $val->addField('number')->required()->number() : null;
  $showEmail ? $val->addField('email')->required()->email() : null;
  $result = $val->run($post);
  if($result->isValid()){

    // Save data
    //QueryHelperDB::save($post);

    // Email data
    QueryHelperMailer::send(
      array(
        $params->get('to_a'), 
        $params->get('to_b'), 
        $params->get('to_c')
      ),
      array(
        $params->get('cc_a'), 
        $params->get('cc_b'), 
        $params->get('cc_c')
      ),
      array(
        $params->get('bcc_a'), 
        $params->get('bcc_b'), 
        $params->get('bcc_c')
      ),
      $params->get('subject'),
      $post
    );

    // We done
    print '<div class="ui message"><i class="ui circular checkmark icon"></i>Sent successfully, we will be in touch.</div>';

    // Message
    //JFactory::getApplication()->enqueueMessage('Please check that all required fields have been completed.', 'success');
  }
}

// Display data
ob_start();
  require JModuleHelper::getLayoutPath('mod_helpdesk', 'default');
  $get = ob_get_contents();
ob_end_clean();

// Check for header
if($showHeading){
  require 'modules/mod_helpdesk/helpers/heading.php';
}

print preg_replace("({{ ?form ?}})", $get, $params->get('template'));
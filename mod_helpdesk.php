<?php

/**
 * Helpdesk Module Entry Point
 * 
 * @package    Joomla
 * @subpackage Modules
 * @license    MIT
 * @link
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

// Load helpers
include 'helpers/database.php';
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

    // Save data and check token
    if(QueryHelperDatabase::save($post)){

      // Email data
      QueryHelperMailer::send($params, $post);

      // We done
      print '<div class="ui message"><i class="ui circular checkmark icon"></i>Sent successfully, we look forward to answering your question.</div>';

      // Dont show the formn
      return null;
    }
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
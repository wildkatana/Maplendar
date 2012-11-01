<?php

/**
 * Implements hook_user_insert
 */
function maplendar_user_insert(&$edit, $account, $category) {
  // BEN's Code to create an XML file for this user will go here
  $user_id = $account->uid;
  // Create XML for user
  
}

/**
 * Page handler for the calendar url
 */
function maplendar_calendar_page($account) {
  drupal_add_library('system', 'ui.dialog');
  drupal_add_library('system', 'ui.listview');
  drupal_add_js(array('maplendar' => array(
    'xml_contents' => file_get_contents(drupal_get_path('module', 'maplendar') . '/calendarXML.xml'), // FIXME - This will need to be changed to the path for the user's xml file
  )), 'setting');
  drupal_add_js(drupal_get_path('module', 'maplendar') . '/calendarXMLScript.js', array('scope' => 'footer'));
  return theme('maplendar_calendar_page', array('account' => $account));
}

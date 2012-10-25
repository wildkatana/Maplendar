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
  return theme('maplendar_calendar_page', array('account' => $account));
}

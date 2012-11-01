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
    'events' => $account->events,
  )), 'setting');
  drupal_add_js(drupal_get_path('module', 'maplendar') . '/calendarXMLScript.js', array('scope' => 'footer'));
  return theme('maplendar_calendar_page', array('account' => $account));
}

/**
 * Build a form to let users add new events
 */
function maplendar_add_event_form($form, &$form_state, $account) {
  $form['account'] = array(
    '#type' => 'value',
    '#value' => $account,
  );
  $form['date'] = array(
    '#type' => 'date_popup',
    '#title' => t("Date"),
    '#description' => t("Enter the date and time for this event."),
    '#required' => TRUE,
  );
  $form['title'] = array(
    '#title' => t("Event Description"),
    '#description' => t("Describe your event"),
    '#type' => 'textfield',
    '#required' => TRUE,
  );
  $form['place'] = array(
    '#title' => t("Place"),
    '#description' => t("Where the event takes place"),
    '#type' => 'textfield',
  );
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t("Add"),
  );
  return $form;
}

/**
 * Submit handler for the add event form
 */
function maplendar_add_event_form_submit($form, &$form_state) {
  $account = $form_state['values']['account'];
  $record = new stdClass();
  $record->uid = $account->uid;
  $record->title = $form_state['values']['title'];
  $record->place = $form_state['values']['place'];
  $timestamp = strtotime($form_state['values']['date']);
  $record->start_time = $timestamp;
  $record->end_time = strtotime("+1 hour", $timestamp);
  drupal_write_record('maplendar_events', $record);
  drupal_set_message(t('Successfully added new event'));
  drupal_goto('maplendar/' . $account->uid . '/calendar');
}

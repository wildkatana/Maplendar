<?php

/**
 * Page handler for the calendar url
 * 
 * Leighton Notes:
 * This is the calendar page callback we defined in maplendar_menu() function.
 */
function maplendar_calendar_page($account) {
  // Add the dialog jquery UI library (instead of the whole jquery UI libraries for performance)
  drupal_add_library('system', 'ui.dialog');
  // Add a javascript setting that will be in the Drupal.settings.maplendar.events variable
  // This will be an array of all of the events for the user that we are viewing the calendar for
  // The events property was populated in the maplendar_user_load() function
  drupal_add_js(array('maplendar' => array(
    'events' => $account->events,
  )), 'setting');
  // Add the calendarXMLScript.js file to the footer of the page so that it will run after
  // jQuery Mobile and other dependencies have loaded all the way
  drupal_add_js(drupal_get_path('module', 'maplendar') . '/calendarXMLScript.js', array('scope' => 'footer'));
  // Call the maplendar_calendar_page.tpl.php file and pass in the $account variable
  return theme('maplendar_calendar_page', array('account' => $account));
}

/**
 * Build a form to let users add new events
 * 
 * Leighton Notes:
 * This is the callback function for the add event page defined in maplendar_menu().
 * This function builds a $form array of the form elements that we want to show to
 * the user, and then returns that array. Drupal will build the form for us from
 * the specifications in this array.
 */
function maplendar_add_event_form($form, &$form_state, $account) {
  $form = array();
  
  $form['account'] = array(
    '#type' => 'value',
    '#value' => $account,
  );
  $form['date'] = array(
    '#type' => 'date_popup', // This will add the date and time elements including the date popup calendar as provided by the Date drupal module
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
 * 
 * Leighton Notes:
 * This function is called when the form above is submitted, and it is passed the
 * values of the form in the $form_state['values'] array. We create a new record
 * to save in the maplendar_events database table.
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
  drupal_goto('user/' . $account->uid . '/calendar');
}

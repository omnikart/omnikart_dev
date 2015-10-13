<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

// Heading
$_['heading_title'] = 'Newsletter Enhancements';

// Text
$_['text_edit'] = 'Settings';
$_['text_success'] = 'Success: You have modified module Newsletter Enhancements!';
$_['text_module'] = 'Modules';
$_['text_module_localization'] = 'Localization';
$_['text_ne'] = 'Newsletter';
$_['text_ne_email'] = 'Create and Send';
$_['text_ne_draft'] = 'Draft Newsletter';
$_['text_ne_marketing'] = 'Marketing Subscribers';
$_['text_ne_subscribers'] = 'List Subscribers';
$_['text_ne_stats'] = 'Newsletter Stats';
$_['text_ne_robot'] = 'Scheduled Newsletter';
$_['text_ne_template'] = 'Newsletter Templates';
$_['text_ne_subscribe_box'] = 'Subscribe Box';
$_['text_ne_blacklist'] = 'Blacklisted Emails';
$_['text_ne_support'] = 'Support';
$_['text_ne_support_register'] = 'Register to get Support';
$_['text_ne_support_login'] = 'Login to get Support';
$_['text_ne_support_dashboard'] = 'Support Dashboard';
$_['text_ne_update_check'] = 'Check For Updates';
$_['text_default'] = 'Default';
$_['text_general_settings'] = 'General';
$_['text_throttle_settings'] = 'Throttle';
$_['text_mailgun'] = 'Mailgun';
$_['text_mailgun_info'] = 'For more information please visit Mailgun\'s <a href="http://www.mailgun.com">website</a> and <a href="http://documentation.mailgun.com">documentation</a>.';

$_['text_personalisation_tags'] = 'Subject and message personalization tags: <code>{name}</code>, <code>{lastname}</code>, <code>{email}</code> will be replaced with corresponding values. <code>{link}</code> tag will be replaced with url to confirm subscription.';

$_['text_cron_command'] = '/usr/bin/wget -O /dev/null -q "%s" >/dev/null 2>&1';
$_['text_help'] = '*/5 * * * *<br/><br/> *  * * * * command to be executed<br/> -  - - - -<br/> |  | | | |<br/> |  | | | +- - - - day of week (0 - 6) (Sunday=0)<br/> |  | | +- - - - - month (1 - 12)<br/> |  | +- - - - - - day of month (1 - 31)<br/> |  +- - - - - - - hour (0 - 23)<br/> +- - - - - - - -  minute (0 - 59) */5 means "each 5"<br/><br/>I.e. configure cron job to run command each 5 minutes.';
$_['text_licence_info'] = 'To activate module please provide your email which was used for module purchasing and order ID.';
$_['text_licence_text'] = '<p>The ID of order may be found in the <a href="https://www.opencart.com/index.php?route=account/order" target="_blank">Order History</a> of your OpenCart account and the e-mail is used in your account.</p><br/><ul><li>I agree to Use purchased license for ONE website ONLY</li><li>Reselling or giving away for free the downloaded work is NOT ALLOWED</li><li>Copyrights removal is NOT ALLOWED</li></ul><br/><p>Please, read the installation Guide before opening a ticket</p>';

// Entry
$_['entry_use_throttle'] = 'Use throttle when sending emails';
$_['help_use_throttle'] = 'This feature requires configure cron job on server.';
$_['entry_use_embedded_images'] = 'Use embedded images in newsletter';
$_['help_use_embedded_images'] = 'Not supported by Gmail web app.';
$_['entry_throttle_emails'] = 'Emails to send in one iteration';
$_['help_throttle_emails'] = 'Number of emails to send.';
$_['entry_throttle_time'] = 'Throttle interval';
$_['help_throttle_time'] = 'Time in minutes.';
$_['entry_sent_retries'] = 'Retries count';
$_['help_sent_retries'] = 'Amount of retries to send newsletter to specified email address.';
$_['entry_name'] = 'Name';
$_['text_yes'] = 'Yes';
$_['text_no'] = 'No';
$_['entry_cron_code'] = 'Cron command';
$_['entry_cron_help'] = 'Cron command help';
$_['entry_list'] = 'Marketing lists';
$_['entry_weekdays'] = 'Weekdays';
$_['entry_months'] = 'Months';
$_['entry_january'] = 'January';
$_['entry_february'] = 'February';
$_['entry_march'] = 'March';
$_['entry_april'] = 'April';
$_['entry_may'] = 'May';
$_['entry_june'] = 'June';
$_['entry_july'] = 'July';
$_['entry_august'] = 'August';
$_['entry_september'] = 'September';
$_['entry_october'] = 'October';
$_['entry_november'] = 'November';
$_['entry_december'] = 'December';
$_['entry_sunday'] = 'Sunday';
$_['entry_monday'] = 'Monday';
$_['entry_tuesday'] = 'Tuesday';
$_['entry_wednesday'] = 'Wednesday';
$_['entry_thursday'] = 'Thursday';
$_['entry_friday'] = 'Friday';
$_['entry_saturday'] = 'Saturday';
$_['entry_transaction_id'] = 'Order ID';
$_['entry_transaction_email'] = 'E-mail';
$_['entry_hide_marketing'] = 'Hide marketing lists in frontend';
$_['entry_subscribe_confirmation'] = 'Subscription confirmation';
$_['entry_subject'] = 'Subject';
$_['entry_message'] = 'Message';
$_['entry_website'] = 'Website';

// Button
$_['button_add_list'] = 'Add';
$_['button_activate'] = 'Activate';
$_['button_save'] = 'Save';
$_['button_cancel'] = 'Cancel';

// Error
$_['error_permission'] = 'Warning: You do not have permission to modify module Newsletter Enhancements!';

$_['entry_use_smtp'] = 'Use custom mail configuration';
$_['entry_mail_protocol'] = 'Mail Protocol';
$_['help_mail_protocol'] = 'Only choose \'Mail\' unless your host has disabled the php mail function.';
$_['text_mail'] = 'Mail';
$_['text_mail_phpmailer'] = 'Mail (PHPMailer)';
$_['text_smtp'] = 'SMTP';
$_['text_smtp_phpmailer'] = 'SMTP (PHPMailer)';
$_['entry_email'] = 'Email address';
$_['entry_mail_parameter'] = 'Mail Parameters';
$_['help_mail_parameter'] = 'When using \'Mail\', additional mail parameters can be added here (e.g. "-femail@storeaddress.com".';
$_['entry_smtp_host'] = 'SMTP Host';
$_['entry_smtp_username'] = 'SMTP Username';
$_['entry_smtp_password'] = 'SMTP Password';
$_['entry_smtp_port'] = 'SMTP Port';
$_['entry_smtp_timeout'] = 'SMTP Timeout';
$_['entry_stores'] = 'Stores';
$_['text_smtp_settings'] = 'Mail';
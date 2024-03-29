<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Listens for Instant Payment Notification from PayPal
 *
 * This script waits for Payment notification from PayPal,
 * then double checks that data by sending it back to PayPal.
 * If PayPal verifies this then it sets up the enrolment for that
 * user.
 *
 * @package    enrol
 * @subpackage paypalrequest
 * @copyright  2010 Eugene Venter - 2012 Inter-American Development Bank (http://www.iadb.org) (bid-indes@iadb.org)
 * @author     Eugene Venter - Maiquel Sampaio de Melo - Melvyn Gomez (melvyng@openranger.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require("../../config.php");
require_once("lib.php");
require_once($CFG->libdir.'/eventslib.php');
require_once($CFG->libdir.'/enrollib.php');


/// Keep out casual intruders
if (empty($_POST) or !empty($_GET)) {
    print_error("Sorry, you can not use the script that way.");
}

/// Read all the data from PayPal and get it ready for later;
/// we expect only valid UTF-8 encoding, it is the responsibility
/// of user to set it up properly in PayPal business account,
/// it is documented in docs wiki.

$req = 'cmd=_notify-validate';

$data = new stdClass();

foreach ($_POST as $key => $value) {
    $req .= "&$key=".urlencode($value);
    $data->$key = $value;
}

$custom = explode('-', $data->custom);
$data->userid           = (int)$custom[0];
$data->courseid         = (int)$custom[1];
$data->instanceid       = (int)$custom[2];
$data->payment_gross    = $data->mc_gross;
$data->payment_currency = $data->mc_currency;
$data->timeupdated      = time();

//TODO
$file = "/home/indesvir/logs/stage-test-paypalrequest-2012-01-14.log";
$message = "DATA:\r\n\r\n".print_r($data, true)."\r\nPOST:\r\n\r\n".print_r($_POST, true);
mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[00] - DEV - TEST - enrol/paypalrequest - ipn.php - data", $message);
file_put_contents($file, $message);

/// get the user and course records

if (! $user = $DB->get_record("user", array("id"=>$data->userid))) {
    message_paypalrequest_error_to_admin("Not a valid user id", $data);
    die;
}

if (! $course = $DB->get_record("course", array("id"=>$data->courseid))) {
    message_paypalrequest_error_to_admin("Not a valid course id", $data);
    die;
}

if (! $context = context_course::instance($course->id, IGNORE_MISSING)) {	
    message_paypalrequest_error_to_admin("Not a valid context id", $data);
    die;
}

if (! $plugin_instance = $DB->get_record("enrol", array("id"=>$data->instanceid, "status"=>0))) {
    message_paypalrequest_error_to_admin("Not a valid instance id", $data);
    die;
}

$plugin = enrol_get_plugin('paypalrequest');

mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[01] - DEV - TEST - enrol/paypalrequest - ipn.php - data", "DATA:\r\n\r\n".print_r($data, true)."\r\nPOST:\r\n\r\n".print_r($_POST, true)."\r\nConfig paypalbusiness:\r\n\r\n".$plugin->get_config('paypalbusiness'));

/// Open a connection back to PayPal to validate the data
$header = '';
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
$paypaladdr = empty($CFG->usepaypalsandbox) ? 'www.paypal.com' : 'www.sandbox.paypal.com';
$header .="Host: ".$paypaladdr."\r\n'";

try{
	$fp = fsockopen ($paypaladdr, 80, $errno, $errstr, 30);
} catch (Exception $e){
	mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[EXCEPTION-1] - DEV - TEST - enrol/paypalrequest - ipn.php", "Error connecting to Paypal: ". $paypaladdr);
}

mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[02.a] - DEV - TEST - enrol/paypalrequest - ipn.php", "(!fp)???:\r\n\r\n".var_dump(!$fp)."\r\n !feof(fp)???:\r\n\r\n".var_dump(!feof($fp)));

if (!$fp) {  /// Could not open a socket to PayPal - FAIL
    echo "<p>Error: could not access paypal.com</p>";
	mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "DEV - TEST - enrol/paypalrequest - ipn.php", "Could not access paypal.com to verify payment");
    message_paypalrequest_error_to_admin("Could not access paypal.com to verify payment", $data);
    die;
}

/// Connection is OK, so now we post the data to validate it
try{
	fputs ($fp, $header.$req);
} catch (Exception $e){
	mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[EXCEPTION-2] - DEV - TEST - enrol/paypalrequest - ipn.php", "Error connecting to Paypal (header.req): ". $header.$req);
}

/// Now read the response and check if everything is OK.

while (!feof($fp)) {
    $result = fgets($fp, 1024);
	//TODO
	mail("maiquel.sampaio+paypalrequest2013011402@gmail.com", "[02.b] - DEV - TEST - enrol/paypalrequest - ipn.php", "result:\r\n".var_dump($result)."\r\n\r\nprint_r:\r\n".print_r($result, true));
	
    if (strcmp($result, "VERIFIED") == 0) {          // VALID PAYMENT!


        // check the payment_status and payment_reason

        // If status is not completed or pending then unenrol the student if already enrolled
        // and notify admin

        if ($data->payment_status != "Completed" and $data->payment_status != "Pending") {
            $plugin->unenrol_user($plugin_instance, $data->userid);
            message_paypalrequest_error_to_admin("Status not completed or pending. User unenrolled from course", $data);
            die;
        }

        // If currency is incorrectly set then someone maybe trying to cheat the system

        if ($data->mc_currency != $plugin_instance->currency) {
            message_paypalrequest_error_to_admin("Currency does not match course settings, received: ".$data->mc_currency, $data);
            die;
        }

        // If status is pending and reason is other than echeck then we are on hold until further notice
        // Email user to let them know. Email admin.

        if ($data->payment_status == "Pending" and $data->pending_reason != "echeck") {
            $eventdata = new stdClass();
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_paypalrequest';
            $eventdata->name              = 'paypalrequest_enrolment';
            $eventdata->userfrom          = get_admin();
            $eventdata->userto            = $user;
            $eventdata->subject           = "Moodle: PayPal payment";
            $eventdata->fullmessage       = "Your PayPal payment is pending.";
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);

            message_paypalrequest_error_to_admin("Payment pending", $data);
            die;
        }

        // If our status is not completed or not pending on an echeck clearance then ignore and die
        // This check is redundant at present but may be useful if paypal extend the return codes in the future

        if (! ( $data->payment_status == "Completed" or
               ($data->payment_status == "Pending" and $data->pending_reason == "echeck") ) ) {
            die;
        }

        // At this point we only proceed with a status of completed or pending with a reason of echeck



        if ($existing = $DB->get_record("enrol_paypalrequest", array("txn_id"=>$data->txn_id))) {   // Make sure this transaction doesn't exist already
            message_paypalrequest_error_to_admin("Transaction $data->txn_id is being repeated!", $data);
            die;

        }

        if ($data->business != $plugin->get_config('paypalbusiness')) {   // Check that the email is the one we want it to be
            message_paypalrequest_error_to_admin("Business email is {$data->business} (not ".
                    $plugin->get_config('paypalbusiness').")", $data);
            die;

        }

        if (!$user = $DB->get_record('user', array('id'=>$data->userid))) {   // Check that user exists
            message_paypalrequest_error_to_admin("User $data->userid doesn't exist", $data);
            die;
        }

        if (!$course = $DB->get_record('course', array('id'=>$data->courseid))) { // Check that course exists
            message_paypalrequest_error_to_admin("Course $data->courseid doesn't exist", $data);;
            die;
        }

        $coursecontext = context_course::instance($course->id, IGNORE_MISSING);		

        // Check that amount paid is the correct amount
        if ( (float) $plugin_instance->cost <= 0 ) {
            $cost = (float) $plugin->get_config('cost');
        } else {
            $cost = (float) $plugin_instance->cost;
        }

        if ($data->payment_gross < $cost) {
            $cost = format_float($cost, 2);
            message_paypalrequest_error_to_admin("Amount paid is not enough ($data->payment_gross < $cost))", $data);
            die;

        }

        // ALL CLEAR !

        $DB->insert_record("enrol_paypalrequest", $data);

        if ($plugin_instance->enrolperiod) {
            $timestart = time();
            $timeend   = $timestart + $plugin_instance->enrolperiod;
        } else {
            $timestart = 0;
            $timeend   = 0;
        }

        // Enrol user
        $plugin->enrol_user($plugin_instance, $user->id, $plugin_instance->roleid, $timestart, $timeend);

        // Pass $view=true to filter hidden caps if the user cannot see them
        if ($users = get_users_by_capability($context, 'moodle/course:update', 'u.*', 'u.id ASC',
                                             '', '', '', '', false, true)) {
            $users = sort_by_roleassignment_authority($users, $context);
            $teacher = array_shift($users);
        } else {
            $teacher = false;
        }

        $mailstudents = $plugin->get_config('mailstudents');
        $mailteachers = $plugin->get_config('mailteachers');
        $mailadmins   = $plugin->get_config('mailadmins');
        $shortname = format_string($course->shortname, true, array('context' => $context));


        if (!empty($mailstudents)) {
            $a->coursename = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->profileurl = "$CFG->wwwroot/user/view.php?id=$user->id";

            $eventdata = new stdClass();
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_paypalrequest';
            $eventdata->name              = 'paypalrequest_enrolment';
            $eventdata->userfrom          = $teacher;
            $eventdata->userto            = $user;
            $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
            $eventdata->fullmessage       = get_string('welcometocoursetext', '', $a);
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);

        }

        if (!empty($mailteachers)) {
            $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->user = fullname($user);

            $eventdata = new stdClass();
            $eventdata->modulename        = 'moodle';
            $eventdata->component         = 'enrol_paypalrequest';
            $eventdata->name              = 'paypalrequest_enrolment';
            $eventdata->userfrom          = $user;
            $eventdata->userto            = $teacher;
            $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
            $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
            $eventdata->fullmessageformat = FORMAT_PLAIN;
            $eventdata->fullmessagehtml   = '';
            $eventdata->smallmessage      = '';
            message_send($eventdata);
        }

        if (!empty($mailadmins)) {
            $a->course = format_string($course->fullname, true, array('context' => $coursecontext));
            $a->user = fullname($user);
            $admins = get_admins();
            foreach ($admins as $admin) {
                $eventdata = new stdClass();
                $eventdata->modulename        = 'moodle';
                $eventdata->component         = 'enrol_paypalrequest';
                $eventdata->name              = 'paypalrequest_enrolment';
                $eventdata->userfrom          = $user;
                $eventdata->userto            = $admin;
                $eventdata->subject           = get_string("enrolmentnew", 'enrol', $shortname);
                $eventdata->fullmessage       = get_string('enrolmentnewuser', 'enrol', $a);
                $eventdata->fullmessageformat = FORMAT_PLAIN;
                $eventdata->fullmessagehtml   = '';
                $eventdata->smallmessage      = '';
                message_send($eventdata);
            }
        }

    } else if (strcmp ($result, "INVALID") == 0) { // ERROR
        $DB->insert_record("enrol_paypalrequest", $data, false);
        message_paypalrequest_error_to_admin("Received an invalid payment notification!! (Fake payment?)", $data);
    }
}

fclose($fp);
exit;


//--- HELPER FUNCTIONS --------------------------------------------------------------------------------------


function message_paypalrequest_error_to_admin($subject, $data) {
    echo $subject;
    $admin = get_admin();
    $site = get_site();

    $message = "$site->fullname:  Transaction failed.\n\n$subject\n\n";

    foreach ($data as $key => $value) {
        $message .= "$key => $value\n";
    }

    $eventdata = new stdClass();
    $eventdata->modulename        = 'moodle';
    $eventdata->component         = 'enrol_paypalrequest';
    $eventdata->name              = 'paypalrequest_enrolment';
    $eventdata->userfrom          = $admin;
    $eventdata->userto            = $admin;
    $eventdata->subject           = "PAYPAL ERROR: ".$subject;
    $eventdata->fullmessage       = $message;
    $eventdata->fullmessageformat = FORMAT_PLAIN;
    $eventdata->fullmessagehtml   = '';
    $eventdata->smallmessage      = '';
	
	//TODO
	mail("maiquel.sampaio+paypalrequest2013011401@gmail.com", "[ERROR] - DEV - TEST - enrol/paypalrequest - ipn.php - error", "eventdata:\r\n\r\n".print_r($eventdata, true));
	
    message_send($eventdata);
}



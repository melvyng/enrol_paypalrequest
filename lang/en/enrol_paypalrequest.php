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
 * Strings for component 'enrol_paypal', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package    enrol
 * @subpackage paypalrequest
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com} - 2012 Inter-American Development Bank (http://www.iadb.org) (bid-indes@iadb.org)
 * @author     Maiquel Sampaio de Melo - Melvyn Gomez (melvyng@openranger.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later 
 */

$string['assignrole'] = 'Assign role';
$string['businessemail'] = 'PayPal business email';
$string['businessemail_desc'] = 'The email address of your business PayPal account';
$string['cost'] = 'Enrol cost';
$string['transfer_cost'] = 'Bank transfer cost';
$string['costerror'] = 'The enrolment cost is not numeric';
$string['costorkey'] = 'Please choose one of the following methods of enrolment.';
$string['currency'] = 'Currency';
$string['defaultrole'] = 'Default role assignment';
$string['defaultrole_desc'] = 'Select role which should be assigned to users during PayPal enrolments';
$string['enrolenddate'] = 'End date';
$string['enrolenddate_help'] = 'If enabled, users can be enrolled until this date only.';
$string['enrolenddaterror'] = 'Enrolment end date cannot be earlier than start date';
$string['enrolperiod'] = 'Enrolment duration';
$string['enrolperiod_desc'] = 'Default length of time that the enrolment is valid (in seconds). If set to zero, the enrolment duration will be unlimited by default.';
$string['enrolperiod_help'] = 'Length of time that the enrolment is valid, starting with the moment the user is enrolled. If disabled, the enrolment duration will be unlimited.';
$string['enrolstartdate'] = 'Start date';
$string['enrolstartdate_help'] = 'If enabled, users can be enrolled from this date onward only.';
$string['mailadmins'] = 'Notify admin';
$string['mailstudents'] = 'Notify students';
$string['mailteachers'] = 'Notify teachers';
$string['messageprovider:paypalrequest_enrolment'] = 'PayPal enrolment messages';
$string['nocost'] = 'There is no cost associated with enrolling in this course!';
$string['paypalrequest:config'] = 'Configure PayPal enrol instances';
$string['paypalrequest:manage'] = 'Manage enrolled users';
$string['paypalrequest:unenrol'] = 'Unenrol users from course';
$string['paypalrequest:unenrolself'] = 'Unenrol self from the course';
$string['paypalrequest:enabledisable'] = 'Allows course administrators to enable/disable Paypal registrations';
$string['paypalaccepted'] = 'PayPal payments accepted';
$string['pluginname'] = 'INDES PayPal Request';
$string['pluginname_desc'] = 'The PayPal Request module is a branch version of the original Paypal module of Module. It must be used only with Enrollment Request instances. It allows you to set up paid courses.  If the cost for any course is zero, then students are not asked to pay for entry.  There is a site-wide cost that you set here as a default for the whole site and then a course setting that you can set for each course individually. The course cost overrides the site cost.';
$string['sendpaymentbutton'] = 'Send payment via PayPal';
$string['status'] = 'Allow PayPal enrolments';
$string['status_desc'] = 'Allow users to use PayPal to enrol into a course by default.';
$string['unenrolselfconfirm'] = 'Do you really want to unenrol yourself from course "{$a}"?';

// INDES
$string['paymentmethods'] = 'Payment Methods';
$string['paymentcreditcard'] = 'International credit card';
$string['paymentcreditcardintro'] = 'Click in the following button to pay with credit card.';
$string['paymentcreditcardbutton'] = 'Pay for Enrollment';
$string['paymentbankdeposit'] = 'Bank deposit';
$string['paymentbankdepositintro'] = 'Sí no cuenta con una tarjeta de crédito internacional o su país no se encuentra en el listado de Paypal, realice el pago siguiendo las instrucciones de pago en el siguiente enlace';
$string['paymentbankdepositdetails'] = 'Instrucciones de pago a través de cuenta bancaria en su país.';
$string['disableenrollmentconfirm'] = 'Do you really want to disable the enrollment of participant "{$a->user}" in the course "{$a->course}"?';
$string['enableenrollmentconfirm'] = 'Do you really want to enable the enrollment of participant "{$a->user}" in the course "{$a->course}"?';
$string['disableenrollment'] = 'Disable enrollment of participant';
$string['enableenrollment'] = 'Enable enrollment of participant';
$string['paypalrequestrefundsubject'] = 'Usuario dado de baja PayPal';
$string['paypalrequestrefundbody'] = '
<p>Estimado BID-INDES,</p>
<p>Le informamos que ha ocurrido una baja en el metodo de matriculacion de PayPal. Al participante {$a->participantname} se le ha devuelto el dinero a través de PayPal y se le ha desmatriculado del curso.</p> 
<p>Esta es la información relacionada a baja:</p>
<p>Baja curso por PP: {$a->coursefullname}</p>
<p>Enlace al curso: <a href=\"{$a->courseurl}\">{$a->coursefullname}</a></p>
<p>Nombre del participante: {$a->participantname}</p>
<p>Email: {$a->participantemail}</p>
<p>Le rogamos tenga esta información en cuenta y verifique que este usuario no tiene acceso al curso y se le ha comunicado la baja.</p>
<p>Atentamente,</p>
<p>BID-INDES</p>
';
$string['paypalerrormessage'] = '';
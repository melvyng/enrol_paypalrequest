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
 * Adds new instance of enrol_paypalrequest to specified course
 * or edits current instance.
 *
 * @package    enrol
 * @subpackage paypalrequest
 * @copyright  2010 Petr Skoda  {@link http://skodak.org} - 2012 Inter-American Development Bank (http://www.iadb.org) (bid-indes@iadb.org)
 * @author     Eugene Venter - Maiquel Sampaio de Melo - Melvyn Gomez (melvyng@openranger.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class enrol_paypalrequest_edit_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        list($instance, $plugin, $context) = $this->_customdata;

        $mform->addElement('header', 'header', get_string('pluginname', 'enrol_paypalrequest'));

        $mform->addElement('text', 'name', get_string('custominstancename', 'enrol'));
		$mform->setType('name', PARAM_TEXT);

        $options = array(ENROL_INSTANCE_ENABLED  => get_string('yes'),
                         ENROL_INSTANCE_DISABLED => get_string('no'));
        $mform->addElement('select', 'status', get_string('status', 'enrol_paypalrequest'), $options);
        $mform->setDefault('status', $plugin->get_config('status'));

        $mform->addElement('text', 'cost', get_string('cost', 'enrol_paypalrequest'), array('size'=>4));
		$mform->setType('cost', PARAM_INT);
        $mform->setDefault('cost', $plugin->get_config('cost'));
		
        $mform->addElement('text', 'customint1', get_string('transfer_cost', 'enrol_paypalrequest'), array('size'=>4));
		$mform->setType('customint1', PARAM_INT);
        $mform->setDefault('customint1', $plugin->get_config('customint1'));		
		
		$paypalcurrencies = $plugin->get_currencies();
		$mform->addElement('select', 'currency', get_string('currency', 'enrol_paypalrequest'), $paypalcurrencies);
        $mform->setDefault('currency', $plugin->get_config('currency'));

        if ($instance->id) {
            $roles = get_default_enrol_roles($context, $instance->roleid);
        } else {
            $roles = get_default_enrol_roles($context, $plugin->get_config('roleid'));
        }
        $mform->addElement('select', 'roleid', get_string('assignrole', 'enrol_paypalrequest'), $roles);
        $mform->setDefault('roleid', $plugin->get_config('roleid'));


        $mform->addElement('duration', 'enrolperiod', get_string('enrolperiod', 'enrol_paypalrequest'), array('optional' => true, 'defaultunit' => 86400));
        $mform->setDefault('enrolperiod', $plugin->get_config('enrolperiod'));
        $mform->addHelpButton('enrolperiod', 'enrolperiod', 'enrol_paypalrequest');

        $mform->addElement('date_selector', 'enrolstartdate', get_string('enrolstartdate', 'enrol_paypalrequest'), array('optional' => true));
        $mform->setDefault('enrolstartdate', 0);
        $mform->addHelpButton('enrolstartdate', 'enrolstartdate', 'enrol_paypalrequest');

        $mform->addElement('date_selector', 'enrolenddate', get_string('enrolenddate', 'enrol_paypalrequest'), array('optional' => true));
        $mform->setDefault('enrolenddate', 0);
        $mform->addHelpButton('enrolenddate', 'enrolenddate', 'enrol_paypalrequest');

        $mform->addElement('hidden', 'id');
		$mform->setType('id', PARAM_INT);
		
        $mform->addElement('hidden', 'courseid');
		$mform->setType('courseid', PARAM_INT);

        $this->add_action_buttons(true, ($instance->id ? null : get_string('addinstance', 'enrol')));

        $this->set_data($instance);
    }

    function validation($data, $files) {
        global $DB, $CFG;
        $errors = parent::validation($data, $files);

        list($instance, $plugin, $context) = $this->_customdata;

        if (!empty($data['enrolenddate']) and $data['enrolenddate'] < $data['enrolstartdate']) {
            $errors['enrolenddate'] = get_string('enrolenddaterror', 'enrol_paypalrequest');
        }

        $cost = str_replace(get_string('decsep', 'langconfig'), '.', $data['cost']);
        if (!is_numeric($cost)) {
            $errors['cost'] = get_string('costerror', 'enrol_paypalrequest');
        }
		
        $customint1 = str_replace(get_string('decsep', 'langconfig'), '.', $data['customint1']);
        if (!is_numeric($customint1)) {
            $errors['customint1'] = get_string('costerror', 'enrol_paypalrequest');
        }		

        return $errors;
    }
}
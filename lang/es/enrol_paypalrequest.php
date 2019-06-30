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
 * Strings for component 'enrol_paypal', language 'es', branch 'MOODLE_22_STABLE'
 *
 * @package    enrol
 * @subpackage paypalrequest
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com} - 2012 Inter-American Development Bank (http://www.iadb.org) (bid-indes@iadb.org)
 * @author     Maiquel Sampaio de Melo - Melvyn Gomez (melvyng@openranger.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later 
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'PayPal para Postulaciones del INDES';
$string['pluginname_desc'] = 'El módulo de PayPal le permite crear cursos de pago. Si el coste de cualquier curso es cero, entonces los estudiantes no tienen que pagar para entrar. Hay un coste para todo el sitio que se establece como un valor predeterminado para todo el sitio y también valor de configuración que se puede establecer para cada curso por separado. El costo del curso anula el costo de sitio.';

$string['assignrole'] = 'Asignar rol';
$string['businessemail'] = 'Correo electrónico de negocios de PayPal';
$string['businessemail_desc'] = 'La dirección de correo electrónico de su cuenta PayPal Business';
$string['cost'] = 'Costo de inscripción';
$string['transfer_cost'] = 'Costo de transferencia bancaria';
$string['costerror'] = 'El costo de inscripción no es numérico';
$string['costorkey'] = 'Por favor, seleccione uno de los siguientes métodos de matriculación.';
$string['currency'] = 'Moneda';
$string['defaultrole'] = 'Asignación de roles por defecto';
$string['defaultrole_desc'] = 'Seleccione el rol que debería asignarse a los usuarios durante las matriculaciones con PayPal';
$string['enrolenddate'] = 'Fecha de finalización';
$string['enrolenddaterror'] = 'La fecha final de matrícula no puede ser anterior a la fecha inicial.';
$string['enrolperiod'] = 'Período de inscripción';
$string['enrolperiod_desc'] = 'Duración predeterminada del período de validez de la matrícula (en segundos). Si se establece en cero, la matricula, por defecto, será ilimitada en el tiempo.';
$string['enrolstartdate'] = 'Fecha de inicio';
$string['mailadmins'] = 'Notificar a admin';
$string['mailstudents'] = 'Notificar a los estudiantes';
$string['mailteachers'] = 'Notificar a los profesores';
$string['nocost'] = 'No hay ningún costo asociados a la inscripción en este curso';
$string['paypalaccepted'] = 'Pagos PayPal aceptados';
$string['paypalrequest:config'] = 'Configuración de inscripciones PayPal';
$string['paypalrequest:manage'] = 'Administrar los usuarios registrados';
$string['paypalrequest:unenrol'] = 'Dar de baja usuarios del curso';
$string['paypalrequest:unenrolself'] = 'Darse de baja a sí mismo del curso';
$string['paypalrequest:enabledisable'] = 'Permite al usuario habilitar/deshabilitar matrículas por Paypal';
$string['sendpaymentbutton'] = 'Enviar pago por Paypal';
$string['status'] = 'Permitir la matrícula con PayPal';
$string['status_desc'] = 'Permitir a los usuarios utilizar PayPal para inscribirse en un curso de forma predeterminada.';
$string['unenrolselfconfirm'] = '¿Está seguro que desea darse de baja del curso "{$a}"?';

// INDES
$string['paymentmethods'] = 'Formas de pago de matrícula';
$string['paymentcreditcard'] = 'Tarjeta de crédito internacional';
$string['paymentcreditcardintro'] = 'Utilice el botón de abajo para pagar con tarjeta de credito.';
$string['paymentcreditcardbutton'] = 'Realizar Pago de Matrícula';
$string['paymentbankdeposit'] = 'Depósito Bancario';
$string['paymentbankdepositintro'] = 'Sí no cuenta con una tarjeta de crédito internacional o su país no se encuentra en el listado de Paypal, realice el pago siguiendo las instrucciones de pago en el siguiente enlace';
$string['paymentbankdepositdetails'] = 'Instrucciones de pago a través de cuenta bancaria en su país.';
$string['disableenrollmentconfirm'] = '¿Está seguro que quiere deshabilitar la postulación del participante "{$a->user}" en el curso "{$a->course}"?';
$string['enableenrollmentconfirm'] = '¿Está seguro que quiere habilitar la postulación del participante "{$a->user}" en el curso "{$a->course}"?';
$string['disableenrollment'] = 'Deshabilitar postulación de participante';
$string['enableenrollment'] = 'Habilitar postulación de participante';
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
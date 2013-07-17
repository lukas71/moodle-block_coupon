<?php

/*
 * File: block_voucher.php
 * Encoding: UTF-8
 * @package voucher
 * 
 * @Version 1.0.0
 * @Since 11-jul-2013
 * @copyright Sebsoft.nl
 * @author Menno de Ridder <menno@sebsoft.nl>
 */

//DEFAULT
$string['blockname'] = 'Voucher';
$string['pluginname'] = 'Voucher';

$string['form-desc:voucher_enablecron'] = 'Block CRON inschakelen';
$string['form-desc:voucher_enabledebug'] = 'Block debugging inschakelen';
$string['form-desc:voucher_debugemail'] = 'Block debugging email adres';

$string['redirect_in'] = 'Automatisch verwijzen in ';
$string['seconds'] = 'seconden';

// Headers
$string['heading:administration'] = 'Beheer';
$string['heading:generatevouchers'] = 'Voucher genereren';
$string['heading:inputvouchers'] = 'Voucher invoeren';

// Errors
$string['error:nopermission'] = 'U heeft geen toestemming om dit te doen';
$string['error:required'] = 'Dit is een verplicht veld.';
$string['error:numeric_only'] = 'Dit veld is een verplicht numeriek veld.';
$string['error:invalid_email'] = 'Dit e-mail adres is ongeldig.';
$string['error:invalid_voucher_code'] = 'U heeft een ongeldig voucher code ingevuld.';
$string['error:voucher_already_used'] = 'Deze voucher is al gebruikt.';
$string['error:unable_to_enrol'] = 'Een error is opgetreden tijdens het inschrijven in een nieuwe cursus. Neem contact op met support.';
$string['error:missing_course'] = 'De cursus die aan de Voucher is gelinkt bestaat niet meer. Neem contact op met support.';
$string['error:cohort_sync'] = 'Een error is opgetreden tijdens het synchroniseren van de cohortes. Neem contact op met support.';
$string['error:plugin_disabled'] = 'De cohort_sync plugin staat uit. Neem contact op met support.';
$string['error:missing_cohort'] = 'De cohort(en) die aan deze Voucher gelinkt is bestaat niet meer. Neem contact op met support.';
$string['error:missing_group'] = 'De groep(en) die aan deze Voucher gelinkt is bestaat niet meer. Neem contact op met support.';

// Success strings
$string['success:voucher_used'] = 'Voucher gebruikt - U kunt nu uw nieuwe cursus(en) in';

// URL texts
$string['url:generate_vouchers'] = 'Genereer Voucher';
$string['url:input_voucher'] = 'Voucher invoeren';

// Form Labels
$string['label:voucher_type'] = 'Genereer gebaseerd op';
$string['label:voucher_email'] = 'E-mail adres';
$string['label:voucher_amount'] = 'Aantal vouchers';
$string['label:type_course'] = 'Cursus';
$string['label:type_cohorts'] = 'Cohort(s)';

$string['label:voucher_connect_course'] = 'Cursus(sen) toevoegen';
$string['label:connected_courses'] = 'Toegevoegde cursus(sen)';
$string['label:no_courses_connected'] = 'Er zijn nog geen cursussen toegevoegd aan deze cohort.';

$string['label:add_groups'] = 'Groep(en) toevoegen';
$string['label:no_groups_selected'] = 'Er zijn nog geen groepen aan deze cursus toegevoegd.';

$string['label:generate_pdfs'] = 'Genereer losse PDF\'s';

$string['label:cohort'] = 'Cohort';
$string['label:voucher_code'] = 'Voucher Code';

// Labels for already selected stuffz
$string['label:selected_groups'] = 'Geselecteerde groep(en)';
$string['label:selected_course'] = 'Geselecteerde cursus';
$string['label:selected_cohort'] = 'Geselecteerde cohort(en)';

// help texts
$string['label:voucher_type_help'] = 'De vouchers worden gebaseerd op een cursus of een of meer cohorts.';
$string['label:voucher_email_help'] = 'Dit is het e-mail adres waar de gegenereerde vouchers naar toe gestuurd worden.<br />
    Standaard wordt hier het e-mail adres uit de plugin config ingevuld.';
$string['label:voucher_amount_help'] = 'Het aantal vouchers dat gegenereerd zal worden.';

// buttons
$string['button:next'] = 'Volgende';
$string['button:save'] = 'Genereer Vouchers';
$string['button:submit_voucher_code'] = 'Invoeren';

// view strings
$string['view:generate_voucher:title'] = 'Genereer Voucher';
$string['view:generate_voucher:heading'] = 'Genereer Voucher';

$string['view:input_voucher:title'] = 'Voucher invoeren';
$string['view:input_voucher:heading'] = 'Voucher invoeren';

$string['course'] = 'cursus';
$string['cohort'] = 'cohort';

$string['pdf_generated'] = 'The vouchers have been attached to this email in PDF files.<br /><br />';

//$string['mail:body:vouchers_generated'] = '
//        Hello {$a->user_fullname},<br /><br />
//        A new Moodle Voucher has been generated. {$a->generate_pdf}The specifications are listed below.<br /><br />
//        <hr />
//        Voucher type: ' . $a->voucher_type . '<br />
//        Created by: ' . $a->voucher_owner . '<br />
//        Amount: ' . $a->voucher_amount . '<br />
//        Subscriptions:<br />
//        ' . $a->voucher_submission . '<br />
//        <hr /><br /><br />
//        With kind regards,<br /><br />
//        ' . $a->salutation;
//$string['mail:subject:vouchers_generated'] = 'Moodle Vouchers generated';
<?php

/*
 * File: settings.php
 * Encoding: UTF-8
 * @package voucher
 * 
 * @Version 1.0.0
 * @Since 11-jul-2013
 * @copyright Sebsoft.nl
 * @author Menno de Ridder <menno@sebsoft.nl>
 */
if (!defined('BLOCK_VOUCHER_CLASSROOT')) require($CFG->dirroot . '/blocks/voucher/class/settings.php');
require_once(BLOCK_VOUCHER_CLASSROOT . 'admin_setting_customConfigTextInt.php');

defined('MOODLE_INTERNAL') || die('moodle_internal not defined');
if ($ADMIN->fulltree)
{

    // General settings
    $settings->add(new admin_setting_heading(
            'voucher/heading_general',
            get_string('form:heading:general', BLOCK_VOUCHER),
            ''
        ));
    
    $settings->add(new admin_setting_configcheckbox(
            'voucher/use_alternative_email',
            get_string('label:use_alternative_email', BLOCK_VOUCHER),
            get_string('label:use_alternative_email_desc', BLOCK_VOUCHER),
            0
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/alternative_email',
            get_string('label:alternative_email', BLOCK_VOUCHER),
            get_string('label:alternative_email_desc', BLOCK_VOUCHER),
            ''
        ));
    
    $max_code_length_choices = array(6=>6, 8=>8, 16=>16, 32=>32);
    $settings->add(new admin_setting_configselect(
            'voucher/voucher_code_length',
            get_string('label:voucher_code_length', BLOCK_VOUCHER),
            get_string('label:voucher_code_length_desc', BLOCK_VOUCHER),
            16,
            $max_code_length_choices
        ));
    
    $max_voucher_choices = array(5=>5, 10=>10, 25=>25, 50=>50, 100=>100);
    $settings->add(new admin_setting_configselect(
            'voucher/max_vouchers',
            get_string('label:max_vouchers', BLOCK_VOUCHER),
            get_string('label:max_vouchers_desc', BLOCK_VOUCHER),
            50,
            $max_voucher_choices
        ));

    
    // API settings
    $settings->add(new admin_setting_heading(
            'voucher/heading_api',
            get_string('form:heading:api', BLOCK_VOUCHER),
            ''
        ));
    
    $settings->add(new admin_setting_configcheckbox(
            'voucher/api_enabled',
            get_string('label:api_enabled', BLOCK_VOUCHER),
            get_string('label:api_enabled_desc', BLOCK_VOUCHER),
            0
        ));

    $settings->add(new admin_setting_configtext(
            'voucher/api_user',
            get_string('label:api_user', BLOCK_VOUCHER),
            get_string('label:api_user_desc', BLOCK_VOUCHER),
            ''
        ));
    
    $settings->add(new admin_setting_configtext(
            'voucher/api_password',
            get_string('label:api_password', BLOCK_VOUCHER),
            get_string('label:api_password_desc', BLOCK_VOUCHER),
            ''
        ));
    
    $settings->add(new admin_setting_configcheckbox(
            'voucher/exclude_course_summary',
            get_string('label:exclude_course_summary', BLOCK_VOUCHER),
            get_string('label:exclude_course_summary_desc', BLOCK_VOUCHER),
            0
        ));

    
    
    // Instructions settings
    $settings->add(new admin_setting_heading(
            'voucher/heading_instructions',
            get_string('form:heading:instructions', BLOCK_VOUCHER),
            ''
        ));
    
    // Information fields, to be displayed above each form
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_type',
            get_string('label:info_voucher_type', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_course',
            get_string('label:info_voucher_course', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_course_groups',
            get_string('label:info_voucher_course_groups', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_cohorts',
            get_string('label:info_voucher_cohorts', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_cohort_courses',
            get_string('label:info_voucher_cohort_courses', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_voucher_confirm',
            get_string('label:info_voucher_confirm', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));
    $settings->add(new admin_setting_configtext(
            'voucher/info_imageupload',
            get_string('label:info_imageupload', BLOCK_VOUCHER),
            get_string('label:info_desc', BLOCK_VOUCHER),
            ''
        ));

}

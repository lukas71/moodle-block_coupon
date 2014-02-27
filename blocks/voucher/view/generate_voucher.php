<?php

/*
 * File: generate_voucher.php
 * Encoding: UTF-8
 * @package voucher
 * 
 * @Version 1.0.0
 * @Since 11-jul-2013
 * @copyright Sebsoft.nl
 * @author Menno de Ridder <menno@sebsoft.nl>
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once $CFG->dirroot . '/blocks/voucher/class/settings.php';

$id = required_param('id', PARAM_INT);

if ($id)    //DEFAULT CHECKS
{
    if (!$instance = $DB->get_record('block_instances', array('id' => $id)))
    {
        print_error("Instance id incorrect");
    }
//    $context = context_block::instance($instance->id);
//    $course_context = $context->get_course_context(false);
//
//    if (!$course = $DB->get_record("course", array("id" => $course_context->_instanceid)))
//    {
//        //print_error("Course is misconfigured");
//        $course = get_site();
//    }
    
    $course = get_site();
    $context = context_course::instance($course->id);

    require_login($course, true);
    //ADD course LINK
    $PAGE->navbar->add(ucfirst($course->fullname), new moodle_url('/course/view.php', array('id' => $course->id)));
}

$url = new moodle_url('/blocks/voucher/view/generate_voucher.php', array('id' => $id));
$PAGE->set_url($url);

$PAGE->set_title(get_string('view:generate_voucher:title', BLOCK_VOUCHER));
$PAGE->set_heading(get_string('view:generate_voucher:heading', BLOCK_VOUCHER));
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');

//make sure the moodle editmode is off
voucher_Helper::forceNoEditingMode();

if (voucher_Helper::getPermission('generatevouchers'))
{
    //
    require_once BLOCK_VOUCHER_CLASSROOT.'forms/generate_voucher_form.php';
    $mform = new generate_voucher_form($url);
    
    if ($mform->is_cancelled())
    {
        unset($SESSION->voucher);
        redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
    }
    elseif ($data = $mform->get_data())
    {
        // Cache form input
        $SESSION->voucher = new stdClass();
        $SESSION->voucher->type = ($data->voucher_type['type'] == 0) ? 'course' : 'cohorts';
        
        // And redirect user to next page
        redirect(voucher_Helper::createBlockUrl('view/generate_voucher_step_two.php', array('id' => $id)));
    }
    else
    {
        if (isset($SESSION->voucher)) unset($SESSION->voucher);
        
        echo $OUTPUT->header();
        $mform->display();
        echo $OUTPUT->footer();
    }
}
else
{
    print_error(get_string('error:nopermission', BLOCK_VOUCHER));
}
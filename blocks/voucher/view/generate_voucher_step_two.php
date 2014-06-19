<?php

/*
 * File: generate_voucher_step_two.php
 * Encoding: UTF-8
 * @package voucher
 * 
 * @Version 1.0.0
 * @Since 12-jul-2013
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

    $course = get_site();
    $context = context_course::instance($course->id);

    require_login($course, true);
    //ADD course LINK
    $PAGE->navbar->add(ucfirst($course->fullname), new moodle_url('/course/view.php', array('id' => $course->id)));
}

// Make sure the voucher object is set in cache
if (!isset($SESSION->voucher)) print_error(get_string('error:nopermission', BLOCK_VOUCHER));

$url = new moodle_url('/blocks/voucher/view/generate_voucher_step_two.php', array('id' => $id));
$PAGE->set_url($url);

$PAGE->set_title(get_string('view:generate_voucher:title', BLOCK_VOUCHER));
$PAGE->set_heading(get_string('view:generate_voucher:heading', BLOCK_VOUCHER));
$PAGE->set_context($context);
$PAGE->set_pagelayout('standard');

//make sure the moodle editmode is off
voucher_Helper::forceNoEditingMode();

if (voucher_Helper::getPermission('generatevouchers'))
{
    // Make sure sessions are still alive
    if (!isset($SESSION->voucher)) {
        print_error("error:sessions-expired", BLOCK_VOUCHER);
    }
    
    // Depending on our data we'll get the right form
    if ($SESSION->voucher->type == 'course') {
        
        require_once BLOCK_VOUCHER_CLASSROOT.'forms/generate_voucher_course_form.php';
        $mform = new generate_voucher_course_form($url);

    } else {

        require_once BLOCK_VOUCHER_CLASSROOT.'forms/generate_voucher_cohorts_form.php';
        $mform = new generate_voucher_cohorts_form($url);

    }
    
    if ($mform->is_cancelled())
    {
        unset($SESSION->voucher);
        redirect(new moodle_url('/course/view.php', array('id' => $course->id)));
    }
    elseif ($data = $mform->get_data())
    {
        
        if ($SESSION->voucher->type == 'course') {
            $SESSION->voucher->courses = $data->voucher_courses;
            
            $hasGroups = false;
            foreach($data->voucher_courses as $courseid) {
                $groups = $DB->get_records("groups", array('courseid'=>$courseid));
                if (count($groups) > 0) {
                    $hasGroups = true;
                    break;
                }
                
            }
            
            $next_page = ($hasGroups) ? 'generate_voucher_step_three' : $next_page = 'generate_voucher_step_four';
            
        } else {
            $SESSION->voucher->cohorts = $data->voucher_cohorts;
            $next_page = 'generate_voucher_step_three';
        }

        redirect(voucher_Helper::createBlockUrl('view/' . $next_page . '.php', array('id'=>$id)));
    }
    else
    {
//        if (isset($SESSION->voucher_type)) unset($SESSION->voucher_type);
        
        echo $OUTPUT->header();
        $mform->display();
        echo $OUTPUT->footer();
    }
}
else
{
    print_error(get_string('error:nopermission', BLOCK_VOUCHER));
}

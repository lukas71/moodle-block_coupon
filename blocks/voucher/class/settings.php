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

defined('MOODLE_INTERNAL') || die();

define('BLOCK_VOUCHER', 'block_voucher');

define('BLOCK_VOUCHER_WWWROOT', $CFG->wwwroot . '/blocks/voucher/');
define('BLOCK_VOUCHER_DIRROOT', $CFG->dirroot . '/blocks/voucher/');
define('BLOCK_VOUCHER_CLASSROOT', BLOCK_VOUCHER_DIRROOT.'class/');

define('BLOCK_VOUCHER_LOGOFILE', $CFG->dataroot.'/voucher_logos/logo.png'); // logo file

// Label for the ivm feedback to report
define('BLOCK_VOUCHER_IVMFEEDBACK', 'Rapportcijfer');

//include the core DEFAULTS
require_once BLOCK_VOUCHER_CLASSROOT.'voucher_exception.php';
require_once BLOCK_VOUCHER_CLASSROOT.'db.php';
require_once BLOCK_VOUCHER_CLASSROOT.'Helper.php';

<?php
// This file is part of Moodle - http://moodle.org/

defined('MOODLE_INTERNAL') || die();

/**
 * Post installation procedure.
 */
function xmldb_lesson_install() {
    global $CFG;

    require_once($CFG->dirroot . '/mod/lesson/locallib.php');
    lesson_seed_default_skins();
}

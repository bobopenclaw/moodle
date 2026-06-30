<?php
// This file is part of Moodle - http://moodle.org/

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$id = required_param('id', PARAM_INT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

admin_externalpage_setup('modlessonskins');

$skin = $DB->get_record('lesson_skins', ['id' => $id], '*', MUST_EXIST);
$url = new moodle_url('/mod/lesson/skin_delete.php', ['id' => $id]);
$returnurl = new moodle_url('/mod/lesson/skin.php');
$PAGE->set_url($url);

if ($skin->name === 'standard') {
    redirect($returnurl, get_string('cannotdeletestandardskin', 'lesson'), null, \core\output\notification::NOTIFY_ERROR);
}

if ($DB->record_exists('lesson', ['skin' => $skin->name])) {
    redirect($returnurl, get_string('cannotdeleteskininuse', 'lesson'), null, \core\output\notification::NOTIFY_ERROR);
}

if ($confirm && confirm_sesskey()) {
    $DB->delete_records('lesson_skins', ['id' => $skin->id]);
    redirect($returnurl);
}

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('deletelessonskin', 'lesson', format_string($skin->title)));
echo $OUTPUT->confirm(
    get_string('deletelessonskinconfirm', 'lesson', format_string($skin->title)),
    new moodle_url($url, ['confirm' => 1, 'sesskey' => sesskey()]),
    $returnurl
);
echo $OUTPUT->footer();

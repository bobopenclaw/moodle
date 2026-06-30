<?php
// This file is part of Moodle - http://moodle.org/

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/mod/lesson/locallib.php');

$id = optional_param('id', 0, PARAM_INT);

admin_externalpage_setup('modlessonskins');

$url = new moodle_url('/mod/lesson/skin_edit.php', ['id' => $id]);
$PAGE->set_url($url);

if ($id) {
    $skin = $DB->get_record('lesson_skins', ['id' => $id], '*', MUST_EXIST);
    $title = get_string('editlessonskin', 'lesson', format_string($skin->title));
} else {
    $defaults = lesson_get_default_skin_definitions();
    $standard = $defaults['standard'];
    $skin = (object) [
        'id' => 0,
        'name' => '',
        'title' => '',
        'description' => '',
        'template' => $standard['template'],
        'customcss' => '',
        'fontfamily' => $standard['fontfamily'],
        'backgroundcolor' => $standard['backgroundcolor'],
        'contentbackgroundcolor' => $standard['contentbackgroundcolor'],
        'accentcolor' => $standard['accentcolor'],
        'answerbackgroundcolor' => $standard['answerbackgroundcolor'],
        'enabled' => 1,
        'sortorder' => 50,
    ];
    $title = get_string('addlessonskin', 'lesson');
}

$mform = new \mod_lesson\form\skin_form($url, ['skin' => $skin]);
$mform->set_data($skin);

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/mod/lesson/skin.php'));
} else if ($data = $mform->get_data()) {
    $record = clone $data;
    $record->timemodified = time();
    if ($record->id) {
        $DB->update_record('lesson_skins', $record);
    } else {
        $record->timecreated = time();
        $DB->insert_record('lesson_skins', $record);
    }
    redirect(new moodle_url('/mod/lesson/skin.php'));
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
$mform->display();
echo $OUTPUT->footer();

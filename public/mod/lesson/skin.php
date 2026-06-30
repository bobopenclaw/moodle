<?php
// This file is part of Moodle - http://moodle.org/

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/mod/lesson/locallib.php');

admin_externalpage_setup('modlessonskins');

$PAGE->set_url(new moodle_url('/mod/lesson/skin.php'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('managelessonskins', 'lesson'));

echo html_writer::div(
    $OUTPUT->single_button(new moodle_url('/mod/lesson/skin_edit.php'), get_string('addlessonskin', 'lesson'), 'get'),
    'mb-3'
);

$skins = $DB->get_records('lesson_skins', null, 'sortorder ASC, title ASC');

$table = new html_table();
$table->head = [
    get_string('skinname', 'lesson'),
    get_string('skintitle', 'lesson'),
    get_string('enabled', 'admin'),
    get_string('sortorder', 'lesson'),
    get_string('actions'),
];
$table->attributes['class'] = 'generaltable';

foreach ($skins as $skin) {
    $actions = [
        html_writer::link(
            new moodle_url('/mod/lesson/skin_edit.php', ['id' => $skin->id]),
            get_string('edit')
        ),
    ];
    if ($skin->name !== 'standard') {
        $actions[] = html_writer::link(
            new moodle_url('/mod/lesson/skin_delete.php', ['id' => $skin->id]),
            get_string('delete')
        );
    }

    $table->data[] = [
        s($skin->name),
        format_string($skin->title),
        $skin->enabled ? get_string('yes') : get_string('no'),
        $skin->sortorder,
        implode(' | ', $actions),
    ];
}

if ($table->data) {
    echo html_writer::table($table);
} else {
    echo $OUTPUT->notification(get_string('nolessonskins', 'lesson'), 'info');
}

echo $OUTPUT->footer();

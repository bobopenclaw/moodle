<?php
// This file is part of Moodle - http://moodle.org/

namespace mod_lesson\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form for editing Lesson skins.
 *
 * @package   mod_lesson
 * @copyright 2026 onwards Moodle Pty Ltd <support@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class skin_form extends \moodleform {

    /**
     * Form definition.
     */
    protected function definition() {
        $mform = $this->_form;
        $skin = $this->_customdata['skin'] ?? null;
        $isedit = !empty($skin->id);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name', get_string('skinname', 'lesson'), ['size' => 40]);
        $mform->setType('name', PARAM_ALPHANUMEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addHelpButton('name', 'skinname', 'lesson');
        if ($isedit) {
            $mform->freeze('name');
        }

        $mform->addElement('text', 'title', get_string('skintitle', 'lesson'), ['size' => 60]);
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        $mform->addElement('textarea', 'description', get_string('description'), ['rows' => 3, 'cols' => 80]);
        $mform->setType('description', PARAM_TEXT);

        $mform->addElement('advcheckbox', 'enabled', get_string('enable'));
        $mform->setDefault('enabled', 1);

        $mform->addElement('text', 'sortorder', get_string('sortorder', 'lesson'), ['size' => 8]);
        $mform->setType('sortorder', PARAM_INT);
        $mform->setDefault('sortorder', 50);

        $mform->addElement('header', 'skinappearencehdr', get_string('appearance'));

        $mform->addElement('text', 'fontfamily', get_string('skinfontfamily', 'lesson'), ['size' => 80]);
        $mform->setType('fontfamily', PARAM_TEXT);
        $mform->addHelpButton('fontfamily', 'skinfontfamily', 'lesson');

        $colourfields = [
            'backgroundcolor' => get_string('skinbackgroundcolor', 'lesson'),
            'contentbackgroundcolor' => get_string('skincontentbackgroundcolor', 'lesson'),
            'accentcolor' => get_string('skinaccentcolor', 'lesson'),
            'answerbackgroundcolor' => get_string('skinanswerbackgroundcolor', 'lesson'),
        ];
        foreach ($colourfields as $field => $label) {
            $mform->addElement('text', $field, $label, ['size' => 8]);
            $mform->setType($field, PARAM_TEXT);
        }

        $mform->addElement('header', 'skinlayouthdr', get_string('skinlayout', 'lesson'));

        $mform->addElement('textarea', 'template', get_string('skintemplate', 'lesson'), [
            'rows' => 18,
            'cols' => 100,
            'class' => 'monospace',
        ]);
        $mform->setType('template', PARAM_RAW);
        $mform->addRule('template', null, 'required', null, 'client');
        $mform->addHelpButton('template', 'skintemplate', 'lesson');

        $mform->addElement('textarea', 'customcss', get_string('skincustomcss', 'lesson'), [
            'rows' => 18,
            'cols' => 100,
            'class' => 'monospace',
        ]);
        $mform->setType('customcss', PARAM_RAW);
        $mform->addHelpButton('customcss', 'skincustomcss', 'lesson');

        $this->add_action_buttons(true, get_string('savechanges'));
    }

    /**
     * Validate form data.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        if (empty($data['id']) && $DB->record_exists('lesson_skins', ['name' => $data['name']])) {
            $errors['name'] = get_string('skinnametaken', 'lesson');
        }

        foreach (['backgroundcolor', 'contentbackgroundcolor', 'accentcolor', 'answerbackgroundcolor'] as $field) {
            if ($data[$field] !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $data[$field])) {
                $errors[$field] = get_string('skininvalidcolour', 'lesson');
            }
        }

        return $errors;
    }
}

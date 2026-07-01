<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file keeps track of upgrades to
 * the lesson module
 *
 * Sometimes, changes between versions involve
 * alterations to database structures and other
 * major things that may break installations.
 *
 * The upgrade function in this file will attempt
 * to perform all the necessary actions to upgrade
 * your older installation to the current version.
 *
 * If there's something it cannot do itself, it
 * will tell you what you need to do.
 *
 * The commands in here will all be database-neutral,
 * using the methods of database_manager class
 *
 * Please do not forget to use upgrade_set_timeout()
 * before any action that may take longer time to finish.
 *
 * @package mod_lesson
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 o
 */

/**
 *
 * @global stdClass $CFG
 * @global moodle_database $DB
 * @param int $oldversion
 * @return bool
 */
function xmldb_lesson_upgrade($oldversion) {
    global $DB, $CFG;
    $dbman = $DB->get_manager();

    // Automatically generated Moodle v4.4.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v4.5.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v5.0.0 release upgrade line.
    // Put any upgrade step following this.

    // Automatically generated Moodle v5.1.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2026022300) {
        // Changing precision of field name on table lesson to (1333).
        $table = new xmldb_table('lesson');
        $field = new xmldb_field('name', XMLDB_TYPE_CHAR, '1333', null, XMLDB_NOTNULL, null, null, 'course');

        // Launch change of precision for field name.
        $dbman->change_field_precision($table, $field);

        // Lesson savepoint reached.
        upgrade_mod_savepoint(true, 2026022300, 'lesson');
    }

    if ($oldversion < 2026030600) {
        // Define field reason to be added to lesson_overrides.
        $table = new xmldb_table('lesson_overrides');
        $field = new xmldb_field('reason', XMLDB_TYPE_TEXT, null, null, null, null, null, 'password');

        // Conditionally launch add field reason.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field reasonformat to be added to lesson_overrides.
        $formatfield = new xmldb_field('reasonformat', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0', 'reason');

        // Conditionally launch add field reasonformat.
        if (!$dbman->field_exists($table, $formatfield)) {
            $dbman->add_field($table, $formatfield);
        }

        // Lesson savepoint reached.
        upgrade_mod_savepoint(true, 2026030600, 'lesson');
    }

    // Automatically generated Moodle v5.2.0 release upgrade line.
    // Put any upgrade step following this.

    if ($oldversion < 2026042001) {
        // Define field skin to be added to lesson.
        $table = new xmldb_table('lesson');
        $field = new xmldb_field('skin', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, 'standard', 'bgcolor');

        // Conditionally launch add field skin.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Lesson savepoint reached.
        upgrade_mod_savepoint(true, 2026042001, 'lesson');
    }

    if ($oldversion < 2026042002) {
        // Lesson savepoint reached for the previous file-backed skin prototype.
        upgrade_mod_savepoint(true, 2026042002, 'lesson');
    }

    if ($oldversion < 2026042003) {
        // Define table lesson_skins to be created.
        $table = new xmldb_table('lesson_skins');

        // Adding fields to table lesson_skins.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('title', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('template', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('customcss', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('fontfamily', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('backgroundcolor', XMLDB_TYPE_CHAR, '7', null, null, null, null);
        $table->add_field('contentbackgroundcolor', XMLDB_TYPE_CHAR, '7', null, null, null, null);
        $table->add_field('accentcolor', XMLDB_TYPE_CHAR, '7', null, null, null, null);
        $table->add_field('answerbackgroundcolor', XMLDB_TYPE_CHAR, '7', null, null, null, null);
        $table->add_field('enabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table lesson_skins.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Adding indexes to table lesson_skins.
        $table->add_index('name', XMLDB_INDEX_UNIQUE, ['name']);
        $table->add_index('enabled', XMLDB_INDEX_NOTUNIQUE, ['enabled']);

        // Conditionally launch create table for lesson_skins.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        require_once($CFG->dirroot . '/mod/lesson/locallib.php');
        lesson_seed_default_skins();

        // Lesson savepoint reached.
        upgrade_mod_savepoint(true, 2026042003, 'lesson');
    }

    if ($oldversion < 2026042004) {
        require_once($CFG->dirroot . '/mod/lesson/locallib.php');
        $defaults = lesson_get_default_skin_definitions();
        foreach (['ocean', 'roman'] as $skinname) {
            if (!isset($defaults[$skinname])) {
                continue;
            }
            if ($record = $DB->get_record('lesson_skins', ['name' => $skinname])) {
                $record->template = $defaults[$skinname]['template'];
                $record->customcss = $defaults[$skinname]['customcss'];
                $record->timemodified = time();
                $DB->update_record('lesson_skins', $record);
            }
        }

        // Lesson savepoint reached.
        upgrade_mod_savepoint(true, 2026042004, 'lesson');
    }

    return true;
}

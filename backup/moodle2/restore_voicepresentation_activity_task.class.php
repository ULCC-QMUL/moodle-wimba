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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/voicepresentation/backup/moodle2/restore_voicepresentation_stepslib.php'); // Because it exists (must)

/**
 * voicepresentation restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_voicepresentation_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // voicepresentation only has one structure step
        $this->add_step(new restore_voicepresentation_activity_structure_step('voicepresentation_structure', 'voicepresentation.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        // List of voicepresentations in course
        $rules[] = new restore_decode_rule('VOICEPRESENTATIONINDEX', '/mod/voicepresentation/index.php?id=$1', 'course');
        // Groupselect by cm->id and voicepresentation->id
        $rules[] = new restore_decode_rule('VOICEPRESENTATIONVIEWBYID', '/mod/voicepresentation/view.php?id=$1', 'course_module');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * voicepresentation logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('voicepresentation', 'add', 'view.php?id={course_module}', '{voicepresentation}');
        $rules[] = new restore_log_rule('voicepresentation', 'update', 'view.php?id={course_module}', '{voicepresentation}');
        $rules[] = new restore_log_rule('voicepresentation', 'view', 'view.php?id={course_module}', '{voicepresentation}');

        return $rules;
    }   

    /** 
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('voicepresentation', 'view all', 'index.php?id={course}', null);

        return $rules;
    }

    /**
     * http://devtools.bbbb.net:8080/browse/CVMI-114
     * Here we ensure that the grades are correct.
     * http://docs.moodle.org/dev/Restore_2.0_for_developers
     */
    public function after_restore() {
        global $DB;

        $userinfo = $this->get_setting_value('userinfo');
        $curVBRec = $DB->get_record("voicepresentation", array("id" => $this->get_activityid()));
        $newrid = $curVBRec->rid;
        $curVBResRec = $DB->get_record("voicepresentation_resources", array("rid" => $curVBRec->rid));
        $oldVBResRec = $DB->get_record("voicepresentation_resources", array("rid" => $curVBResRec->fromrid));

        // Old VB Resource wasn't gradeable, return
        if ($oldVBResRec->gradeid == -1) {
            return true;
        }

        $newResource = voicetools_api_get_resource($curVBRec->rid);
        $students = WIMBA_getStudentsEnrolled($oldVBResRec->course);
        $users_key = array_keys($students);

        $gradesfromInitialResource = grade_get_grades($oldVBResRec->course, "mod", "voicepresentation", $oldVBResRec->gradeid,$users_key);
        $grades = null;
        if(isset($gradesfromInitialResource->items[0])) {
            $grades = voicepresentation_build_gradeObject_From_ArrayOfGradeInfoObjects($gradesfromInitialResource->items[0]->grades);
        }

        $newResourceOptions = $newResource->WIMBA_getOptions();

        // Original VB Resource had gradeds but we aren't copying userinfo.  In this case we delete the grades created by Moodle
        if ($userinfo == 0 && $grades != null) {
            voicepresentation_delete_grade_column($curVBRec->rid, $curVBRec->course, $curVBRec->id);//delete the one automatically created by moodle
            return true;
        }

        voicepresentation_delete_grade_column($curVBRec->rid, $curVBRec->course, $curVBRec->id);//delete the one automatically created by moodle
        voicepresentation_add_grade_column($curVBRec->rid, $curVBRec->course, $newResource->WIMBA_getTitle(), $newResourceOptions->WIMBA_getPointsPossible(),$grades);
        $curVBResRec->gradeid = $curVBRec->id;
        $DB->update_record("voicepresentation_resources", $curVBResRec);
    }

}

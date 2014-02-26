<?php
// This file is part of blocks_semester_sortierung for Moodle - http://moodle.org/
//
// It is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// If not, see <http://www.gnu.org/licenses/>.

/**
 * db/upgrade.php
 * upgrade script
 *
 * @package       blocks_semester_sortierung
 * @author        Andreas Hruska (andreas.hruska@tuwien.ac.at)
 * @author        Katarzyna Potocka (katarzyna.potocka@tuwien.ac.at)
 * @author        Simeon Naydenov
 * @copyright     2014 Academic Moodle Cooperation {@link http://www.academic-moodle-cooperation.org}
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


function xmldb_block_semester_sortierung_upgrade($oldversion, $block) {
    global $DB;

    if ($oldversion < 2013010904) {
        $settings = $DB->get_records_list('config', 'name', array('semester_sortierung_wintermonths',
            'semester_sortierung_sortcourses'));

        $dataobject = new stdClass;
        $dataobject->plugin = 'blocks/semester_sortierung';
        foreach ($settings as $id => $settingobj) {
            $dataobject->name = substr($settingobj->name, 20);
            $dataobject->value = $settingobj->value;
            if (!$DB->record_exists('config_plugins', array('plugin'=>'blocks/semester_sortierung', 'name' => $dataobject->name))) {
                $DB->insert_record('config_plugins', $dataobject);
                $DB->delete_records('config', array('name' => $settingobj->name));
            }
        }
        upgrade_block_savepoint(true, 2013010904, 'semester_sortierung');
    }


    return true;
}
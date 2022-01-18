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
 * Change visibility of video activities.
 *
 * @package    block_opencast
 * @copyright  2022 Tamara Gunkel, WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_opencast\local\hidden_videos_api;

require_once('../../config.php');
require_once('./renderer.php');

global $PAGE, $OUTPUT, $CFG;

$episodeid = required_param('episodeid', PARAM_ALPHANUMEXT);
$seriesid = required_param('seriesid', PARAM_ALPHANUMEXT);
$courseid = required_param('courseid', PARAM_INT);
$ocinstanceid = optional_param('ocinstanceid', \tool_opencast\local\settings_api::get_default_ocinstance()->id, PARAM_INT);

$baseurl = new moodle_url('/blocks/opencast/changeactivityvisibility.php',
    array('episodeid' => $episodeid, 'seriesid' => $seriesid, 'ocinstanceid' => $ocinstanceid, 'courseid' => $courseid));
$PAGE->set_url($baseurl);

// TODO check that teacher has write capabilities for video.
require_login();

$redirecturl = new moodle_url('/blocks/opencast/index.php', array('courseid' => $courseid, 'ocinstanceid' => $ocinstanceid));

// TODO Capability check.
// $coursecontext = context_course::instance($courseid);
// require_capability('block/opencast:addvideo', $coursecontext);

// TODO Check if video exists. Use opencast api for that.


// TODO process mixed visibility.
if (hidden_videos_api::is_video_hidden($ocinstanceid, $seriesid, $episodeid)) {
    hidden_videos_api::unhide_video($ocinstanceid, $seriesid, $episodeid);
    \block_opencast\local\activitymodulemanager::unhide_activities_of_video($ocinstanceid, $episodeid);
} else {
    hidden_videos_api::hide_video($ocinstanceid, $seriesid, $episodeid);
    \block_opencast\local\activitymodulemanager::hide_activities_of_video($ocinstanceid, $episodeid);
    // TODO maybe create dialog where teachers can select which activities to (un)hide.
}

// TODO add notification that visibility was changed.
redirect($redirecturl);

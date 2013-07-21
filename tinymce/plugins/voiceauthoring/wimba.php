<?php

/******************************************************************************
 *                                                                            *
 * Copyright (c) 1999-2013 Blackboard Collaborate, All Rights Reserved.       *
 *                                                                            *
 * COPYRIGHT:                                                                 *
 *      This software is the property of Horizon Wimba.                       *
 *      You can redistribute it and/or modify it under the terms of           *
 *      the GNU General Public License as published by the                    *
 *      Free Software Foundation.                                             *
 *                                                                            *
 * WARRANTIES:                                                                *
 *      This software is distributed in the hope that it will be useful,      *
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *      GNU General Public License for more details.                          *
 *                                                                            *
 *      You should have received a copy of the GNU General Public License     *
 *      along with the Horizon Wimba Moodle Integration;                      *
 *      if not, write to the Free Software Foundation, Inc.,                  *
 *      51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA                *
 *                                                                            *
 * Author: Brian Drust                                                        *
 *                                                                            *
 * Date: 13th December 2012                                                   *
 *                                                                            *
 ******************************************************************************/

/* $Id: wimba.php 64342 2008-06-12 18:12:25Z thomasr $ */

global $COURSE;

//require_once(dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/config.php');
require('../../../../../config.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/lib/editor/tinymce/plugins/dragmath/dragmath.php');
require_once('../../../../../mod/voiceauthoring/lib.php');

$id = optional_param('id', SITEID, PARAM_INT);
require_course_login($id);
@header('Content-Type: text/html; charset=utf-8');
$vtAction=new WIMBA_vtAction($USER->email);
$vtUser = new WIMBA_VtUser(NULL);
$vtUserRigths = new WIMBA_VtRights(NULL);

$vtUserRigths->WIMBA_setProfile ('moodle.recorder.instructor');
$type="record";

if (!voicetools_api_isConfigured()) {
  print_error(get_string('error_unconfigured_vt','voiceauthoring'));
}

$dbResource = $DB->get_record("voiceauthoring_resources", array("course" => 0));

if($dbResource === false)// the resource is not yet created
{
  $result = $vtAction->WIMBA_createRecorder("Voice Authoring associated to the course 0");//create the resource on the vt
  if( $result != null && $result->error != "error")
  {
    if( WIMBA_storeResource($result->WIMBA_getRid(),0,"recorder", "voiceauthoring") )
    {
         $rid = $result->WIMBA_getRid();
    }
  }
  $mid=0;
}
else
{
    $rid = $dbResource->rid;
    $mid = $dbResource->mid+1;
    $dbResource->mid = $mid;
    $DB->update_record("voiceauthoring_resources",$dbResource);
}

$cid = $COURSE->id;
$resource = voicetools_api_get_resource($rid);

if( $resource )
{
    $message=new WIMBA_vtMessage(null);
    $message->WIMBA_setMid($mid);

    $result = $vtAction->WIMBA_getVtSession($resource,$vtUser,$vtUserRigths,$message);

    if($result === false)
    {
        $error = "There is a problem to display the voice authoring";
    }
    $version = '2011031102';
}

$editor = get_texteditor('tinymce');
$plugin = $editor->get_plugin('voiceauthoring');

header('Content-Type: text/html; charset=utf-8');
header('X-UA-Compatible: IE=EmulateIE8');

?>

<!DOCTYPE html>
<html>
<head>
<!-- <meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/> -->
<title><?php print_string("modulename","voiceauthoring");?></title>
<script type="text/javascript">var wwwroot="<?php echo $CFG->wwwroot; ?>";</script>
<script type="text/javascript" src="<?php echo $editor->get_tinymce_base_url(); ?>tiny_mce_popup.js"></script>
<script type="text/javascript" src="<?php echo $editor->get_tinymce_base_url(); ?>utils/form_utils.js"></script>
<script type="text/javascript" src="<?php echo $editor->get_tinymce_base_url(); ?>utils/validate.js"></script>
<script type="text/javascript" src="js/wimba.js?v=<?php echo $version; ?>"></script>
<link href="css/wimba.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form onsubmit="insertWimba();return false;" action="#">
<input type="hidden" id="f_mid" name="f_mid" value="<?php echo $mid?>">
<input type="hidden" id="f_rid" name="f_rid" value="<?php echo $rid?>">
<input type="hidden" id="f_cid" name="f_cid" value="<?php echo $cid?>">
<div class="clearfix">
    <span class="wimba_boxTopTitle"> Please record a message:</span>
    <p style="padding-left:15px;" id="applet_container"></p>
    <script type="text/javascript">
      this.focus();
    </script>
    <script type="text/javascript" src="<?php echo $CFG->voicetools_servername; ?>/ve/record.js"></script>
    <script type="text/javascript">
        var w_p = new Object();
        w_p.nid="<?php echo $result->WIMBA_getNid();?>";
        w_p.language = "en";
        w_p.autostart = "true";
        tinyMCEPopup._onDOMLoaded = function() {};

        if (window.w_ve_record_tag) w_ve_record_tag(w_p, document.getElementById("applet_container"));
        else document.write("Applet should be there, but the Blackboard Collaborate Voice server is down");
    </script>
</div>

<div class="mceActionPanel">
    <input type="submit" id="insert" name="insert" value="Insert" />
    <input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
</div>
</form>

</body>
</html>

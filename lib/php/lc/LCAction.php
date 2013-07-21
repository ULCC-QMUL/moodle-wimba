<?php
/******************************************************************************
 *                                                                            *
 * Copyright (c) 1999-2008  Wimba, All Rights Reserved.                       *
 *                                                                            *
 * COPYRIGHT:                                                                 *
 *      This software is the property of Wimba.                               *
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
 *      along with the Wimba Moodle Integration;                              *
 *      if not, write to the Free Software Foundation, Inc.,                  *
 *      51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA                *
 *                                                                            *
 * Author: Thomas Rollinger                                                   *
 *                                                                            *
 * Date: January 2007                                                         *
 *                                                                            *
 ******************************************************************************/

require_once ("LCUser.php");
require_once("PrefixUtil.php");

class WIMBA_LCAction {
    var $session = null;
    var $api = null;
    var $prefixUtil  =null;
    var $server = "";
    var $adminName = "";
    var $adminPass = "";
    var $courseId = "";
    var $login = "";
    var $prefix = "";
    var $errormsg = "";
    
    function WIMBA_LCAction($session, $server, $login, $password, $path, $courseId = "")
    {
        global $PAGE;
        $this->session = $session;
       
        $this->prefixUtil = new WIMBA_PrefixUtil();
        $this->prefix = $this->prefixUtil->WIMBA_getPrefix($login);
        
        if (isset ($this->session))
        {
            $this->courseId = $this->session->WIMBA_getCourseId();
        } else if (isset ($courseId) && $courseId != "")
        {
            $this->courseId = $courseId;
        }
        else
        {
            $this->courseId = $PAGE->course->id;
        }

        if ($this->courseId == "" || $this->courseId === null) {
            wimba_add_log(WIMBA_WARN,WC,__FUNCTION__ . ": Empty or Null courseId found in LCAction constructor.");
        }

        $this->api = WIMBA_LCApi::WIMBA_getInstance($server, $login, $password,$this->prefix, $this->courseId, $path);
        $this->server = $server;

        //create the user of the course
        if ($this->WIMBA_createFirstTime() === false) {
            $this->errormsg = $this->api->WIMBA_lcapi_get_errormsg();
        }
    } 

    function WIMBA_getServer()
    {
        return $this->server;
    } 

    function WIMBA_getPrefix()
    {
        return $this->prefix;
    } 

    /*
     * Create 4 room for the first use
     */
    function WIMBA_createFirstTime()
    {
        if ($this->api->WIMBA_lcapi_get_users($this->WIMBA_getStudentUserid()) === false) 
        {
            // if the above failed we should not continue
            if ($this->api->WIMBA_lcapi_get_error() != null) {
                return false;
            }
            // create the two users
            if ($this->api->WIMBA_lcapi_create_user($this->WIMBA_getStudentUserid()) === false) {
                return false;
            }
        } 
        
        if ($this->api->WIMBA_lcapi_get_users($this->WIMBA_getTeacherUserid()) === false) 
        {
            // if the above failed we should not continue
            if ($this->api->WIMBA_lcapi_get_error() != null) {
                return false;
            }
            // create the two users
            if ($this->api->WIMBA_lcapi_create_user($this->WIMBA_getTeacherUserid()) === false) {
                return false;
            } 
        }
        
        return true; 
    } 

    function WIMBA_createRoom($roomId, $update)
    {
        $user_Student = new WIMBA_LCUser($this->api->WIMBA_lcapi_get_users($this->WIMBA_getStudentUserid()), $this->prefix);
        $user_Instructor = new WIMBA_LCUser($this->api->WIMBA_lcapi_get_users($this->WIMBA_getTeacherUserid()), $this->prefix);
        $room = new WIMBA_LCRoom();

        if ($update == "false") 
        {
            $room->WIMBA_setArguments($roomId, $this->session->request["description"], $this->session->request["longname"], null, false, false);
        }
        else 
        {
            $room = $this->api->WIMBA_lcapi_get_room_info($roomId);

            $room->WIMBA_setDescription($this->session->request["description"]);
            $room->WIMBA_setLongname($this->session->request["longname"]);
        } 
        // Access settings
        if ( $this->session->request["accessAvailable"] == "1") 
        {
            $room->WIMBA_setPreview("0");
        }
        else 
        {
            $room->WIMBA_setPreview("1");
        } 

        if ($this->session->request["action"] == "create" || $room->WIMBA_isArchive() == false) 
        {
            if ($this->session->request["led"] == "student") 
            { // discussion room
                // Archives
                $room->WIMBA_setArchiveEnabled($this->session->request["archiveEnabled"]);

                // Default media
                $room->WIMBA_setHmsTwoWayEnabled("1");
                $room->WIMBA_setStudentVideoOnStartupEnabled("1");
                $room->WIMBA_setHmsSimulcastRestricted("0");
                $room->WIMBA_setVideoBandwidth($this->session->request["video_bandwidth"]); 
                // Default chat
                $room->WIMBA_setChatEnabled("1");
                $room->WIMBA_setPrivateChatEnabled("1"); 
                // Default features
                // eBoard
                $room->WIMBA_setStudentWhiteboardEnabled("0");
                $room->WIMBA_setBOREnabled("0");
                $room->WIMBA_setBORCarouselsPublic("0");
                $room->WIMBA_setBORShowRoomCarousels("0");

                $room->WIMBA_setArchiveEnabled($this->session->request["archiveEnabled"]);
                $room->WIMBA_setLiveShareEnabled($this->session->request["appshareEnabled"]);
                $room->WIMBA_setPptImportEnabled($this->session->request["pptEnabled"]);
                $room->WIMBA_setGuestAccess($this->session->request["guests"]);
            } 
            else 
            {
                // archives
                $room->WIMBA_setArchiveEnabled("1");

                // media
                $room->WIMBA_setHmsTwoWayEnabled($this->session->request["hms_two_way_enabled"]);
                $room->WIMBA_setStudentVideoOnStartupEnabled($this->session->request["enable_student_video_on_startup"]);
                if ($this->session->request["hms_simulcast_restricted"] == "0") 
                {
                    $room->WIMBA_setHmsSimulcastRestricted("1");
                } 
                else 
                {
                    $room->WIMBA_setHmsSimulcastRestricted("0");
                } 
                $room->WIMBA_setVideoBandwidth($this->session->request["video_bandwidth"]);
                if ($this->session->request["video_bandwidth"] == "custom")
                {
                    $room->WIMBA_setVideoWindowSizeOnStartup($this->session->request["video_window_size_on_startup"]);
                    $room->WIMBA_setVideoWindowEncodingSize($this->session->request["video_window_encoding_size"]);
                    $room->WIMBA_setVideoDefaultBitRate($this->session->request["video_default_bit_rate"]);
                }
                // Chat
                $room->WIMBA_setChatEnabled($this->session->request["chatEnabled"]);
                $room->WIMBA_setPrivateChatEnabled($this->session->request["privateChatEnabled"]);

                $room->WIMBA_setStudentWhiteboardEnabled($this->session->request["enabled_student_eboard"]);

                $room->WIMBA_setBOREnabled($this->session->request["enabled_breakoutrooms"]);
                $room->WIMBA_setBORCarouselsPublic($this->session->request["enabled_students_breakoutrooms"]);
                $room->WIMBA_setBORShowRoomCarousels($this->session->request["enabled_students_mainrooms"]);

                $room->WIMBA_setLiveShareEnabled("1");
                $room->WIMBA_setPptImportEnabled("1");
            } 
            // common features
            $room->WIMBA_setUserstatusEnabled($this->session->request["enabled_status"]);
            $room->WIMBA_setSendUserstatusUpdates($this->session->request["status_appear"]); 
            // Maximum Users
            if ($this->session->request["userlimit"] == true) 
            {
                $room->WIMBA_setUserLimit((string) $this->session->request["userlimitValue"]);
            } 
            else 
            {
                $room->WIMBA_setUserLimit("-1");
            } 
            // no limit
        } 
        
        if(!$room->WIMBA_isArchive() || $room->WIMBA_getArchiveVersion() == VALUE_50_ARCHIVE){
          //mp3/mp4 room settings
           $allowMp3Download = $this->session->request["can_download_mp3"];
           $allowMp4Download = $this->session->request["can_download_mp4"];
           $mp4EncodingType = $this->session->request["mp4_encoding_type"];
           $mp4MediaPriority = $this->session->request["mp4_media_priority"];
           $mp4NotIncludeVideo = $this->session->request["mp4_media_priority_content_include_video"];
    
           $room->WIMBA_setDownloadMP3Enabled($allowMp3Download);
           $room->WIMBA_setDownloadMP4Enabled($allowMp4Download);
           $room->WIMBA_setMp4EncodingType($mp4EncodingType);
    
           if ($mp4MediaPriority == VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS_WITH_VIDEO && $mp4NotIncludeVideo) {
             $room->WIMBA_setMp4MediaPriority(VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS);
           }
           else {
             $room->WIMBA_setMp4MediaPriority($mp4MediaPriority);
           }
        }

        if(!$room->WIMBA_isArchive())
        {
          $room->WIMBA_setAutoOpenArchive($this->session->request["auto_open_archive"]);
          $room->WIMBA_setArchiveReminderEnabled($this->session->request["display_archive_reminder"]);
          $room->WIMBA_setArchiveEnabled($this->session->request["enable_archives"]);
        }
        
        
        if ($update == "true") 
        { // modify the room
            $this->api->WIMBA_lcapi_modify_room($roomId, $room->WIMBA_getAttributes()); 
            // before : Students and Instructors have the same rights
            // now : Instructors lead the presentation
            if ($room->WIMBA_isArchive() == false) 
            {
                if ($this->session->request["led"] == "instructor" && $this->WIMBA_isStudentAdmin($roomId, $this->session->WIMBA_getCourseId() . "_S") == "true") 
                {
                    $this->api->WIMBA_lcapi_remove_user_role($roomId, $user_Student->WIMBA_getUserId(), "Instructor");
                    $this->api->WIMBA_lcapi_add_user_role($roomId, $user_Student->WIMBA_getUserId(), "Student");
                } else 
                {
                    if ($this->session->request["led"] == "student" 
                        && $this->WIMBA_isStudentAdmin($roomId, $this->session->WIMBA_getCourseId() . "_S") == "false") 
                    { // student need instructor rigths
                        $this->api->WIMBA_lcapi_add_user_role($roomId, $user_Student->WIMBA_getUserId(), "Instructor");
                        $this->api->WIMBA_lcapi_remove_user_role($roomId, $user_Student->WIMBA_getUserId(), "Student");
                    } 
                } 
            } 
        } 
        else 
        {
            $this->api->WIMBA_lcapi_create_class($roomId, $room->WIMBA_getLongname(), $room->WIMBA_getAttributes());

            if ($this->session->request["led"] == "student") 
            {// student have same rights than teacher
                    $this->api->WIMBA_lcapi_add_user_role($roomId, $user_Student->WIMBA_getUserId(), "Instructor");
            } 
            else 
            {
                $this->api->WIMBA_lcapi_add_user_role($roomId, $user_Student->WIMBA_getUserId(), "Student");
            } 
            $this->api->WIMBA_lcapi_add_user_role($roomId, $user_Instructor->WIMBA_getUserId(), "ClassAdmin");
        } 
        // guest access
        if ($this->session->request["guests"] == "1") 
        {
            $this->api->WIMBA_lcapi_add_user_role($roomId, "Guest", "Student");
        }
        else
        {
            $this->api->WIMBA_lcapi_remove_user_role($roomId, "Guest", "Student");
        }

        $error = $this->api->WIMBA_lcapi_get_error();
        if( ! empty($error) )
        {
            return "error";
        }
        return $roomId;
    } 

    /*
     * Create a default room
     */
    function WIMBA_createSimpleRoom($longname, $lecture, $courseId)
    { 
        // room
        $id = $courseId . rand();

        $room = new WIMBA_LCRoom();
        $room->WIMBA_setArguments($id, null, $longname, null, "0", "0");
        $room->WIMBA_setBORCarouselsPublic("0");
        $room->WIMBA_setAutoOpenArchive("1");

        $this->api->WIMBA_lcapi_create_class($id, $longname, $room->WIMBA_getAttributes());

        if ($lecture == "true")
        { // instructor lead the presentation
            $this->api->WIMBA_lcapi_add_user_role($id, $this->WIMBA_getStudentUserid(), "Student");
            $this->api->WIMBA_lcapi_add_user_role($id, $this->WIMBA_getTeacherUserid(), "ClassAdmin");
        } 
        else 
        {
            $this->api->WIMBA_lcapi_add_user_role($id, $this->WIMBA_getStudentUserid(), "Instructor");
            $this->api->WIMBA_lcapi_add_user_role($id, $this->WIMBA_getTeacherUserid(), "ClassAdmin");
        } 
        return $id;
    } 

    function WIMBA_getRooms($userid, $roomid = '', $archive = '0')
    {
        $rooms = $this->api->WIMBA_lcapi_get_rooms($userid, $roomid, $archive);

        if(!is_array($rooms)) {
            $this->errormsg = $this->api->WIMBA_lcapi_get_errormsg();
            return false;
        }
        return $rooms;
    } 
    function WIMBA_getRoom($roomid)
    {
        $this->prefixUtil->WIMBA_trimPrefix($roomid,$this->prefix);
        if(!$room = $this->api->WIMBA_lcapi_get_room_info($roomid)) {
            $this->errormsg = $this->api->WIMBA_lcapi_get_errormsg();
            return false;
        }
        return $room;
    } 

    function WIMBA_deleteRoom($roomid)
    {
        return $this->api->WIMBA_lcapi_delete_room($roomid);
    } 
    function WIMBA_getAuthoken()
    {
        $screenName = $this->session->WIMBA_getFirstname() . "_" . $this->session->WIMBA_getLastname();
        return $this->api->WIMBA_lcapi_get_session($this->session->WIMBA_getLcCurrentUser(), $screenName);
    } 
    function WIMBA_getAuthokenNormal($userID, $firstName, $lastName)
    {
        return $this->api->WIMBA_lcapi_get_session($userID, $firstName . "_" . $lastName);
    } 
    function WIMBA_isStudentAdmin($room_id, $user_id)
    {
        $room_id = $this->prefixUtil->WIMBA_trimPrefix($room_id,$this->prefix);
        $role = $this->api->WIMBA_lcapi_get_user_role($room_id, $user_id);

        if ($role == "Instructor")
        {
            return "true";
        }
        return "false";
    } 
    function WIMBA_isGuestAuthorized($room_id)
    {
        $role = $this->api->WIMBA_lcapi_get_user_role($room_id, "Guest");
        if ($role == "Student")
        {
            return true;
        }
        return false;
    } 
    function WIMBA_getPhoneNumbers()
    {
        return $this->api->WIMBA_lcapi_get_simulcast();
    } 

    /**
     * Returns the LC userID of the teacher profile in the for this course
     * 
     * @return string - the LC user id of the student profile
     */
    function WIMBA_getStudentUserid()
    {
        return $this->courseId . "_S";
    } 

    /**
     * Returns the LC userID of the teacher profile in the for this course
     * 
     * @return string - the LC user id of the teacher profile
     */
    function WIMBA_getTeacherUserid()
    {
        return $this->courseId . "_T";
    } 
    
    function WIMBA_getRoomPreview($roomId)
    {
        $roomId = $this->prefixUtil->WIMBA_trimPrefix($roomId,$this->prefix);
        $preview=$this->api->WIMBA_lcapi_get_room_preview($roomId);
        if(empty($preview))//the lc return an empty value when the room is open
        {
                return "0";
        }
        return $preview;
    }
    
    function WIMBA_setRoomPreview($roomId,$preview){
        $room=new WIMBA_LCRoom();
        
        $room->WIMBA_setPreview($preview);
        
        $this->api->WIMBA_lcapi_modify_room($roomId, $room->WIMBA_getAttributes());
    }
    
    function WIMBA_removeRole($roomId,$userId,$typeRole)
    {
         $roomId = $this->prefixUtil->WIMBA_trimPrefix($roomId,$this->prefix);
         $this->api->WIMBA_lcapi_remove_user_role($roomId,$userId,$typeRole);      
    }
    function WIMBA_cloneRoom($course_id,$roomId, $userData = "0", $isStudentAdmin, $preview )
    {
        $newId = "c".rand();
        $oldIdWithoutPrefix = $this->prefixUtil->WIMBA_trimPrefix($roomId,$this->prefix);
        
        if( $userData=="1" )
        {   
            $this->api->WIMBA_lcapi_clone_class($oldIdWithoutPrefix, $newId);
        }
        else
        {
            $this->api->WIMBA_lcapi_clone_class($oldIdWithoutPrefix, $newId,"1");
        }
        
        if(empty($preview))//preview is empty for the available room
        {
            $preview = "0";
        }
        
        $this->WIMBA_setRoomPreview($newId,$preview);

        if ($isStudentAdmin == "true")
        { // instructor lead the presentation
            $this->api->WIMBA_lcapi_add_user_role($newId, $course_id."_S", "Instructor");
            $this->api->WIMBA_lcapi_add_user_role($newId, $course_id."_T", "ClassAdmin");
        } 
        else 
        {
            $this->api->WIMBA_lcapi_add_user_role($newId, $course_id."_S", "Student");
            $this->api->WIMBA_lcapi_add_user_role($newId, $course_id."_T", "ClassAdmin");
        } 
        return $this->prefix.$newId;
       
    }
    function WIMBA_getVersion()
    {
        $config=$this->api->WIMBA_lcapi_get_status();
        $version=$config["horizon_version"];
        if(empty($version))
        {
            $version="Unknown";
        }
        return $version;
    }
    
    function WIMBA_getRoomName($roomId)
    {
        return $this->api->WIMBA_lcapi_get_room_name($roomId);
    }
    
    function WIMBA_getMp3Status($roomId)
    {
        return $this->api->WIMBA_lcapi_getMP3Status($roomId,1,$this->session->WIMBA_getLcCurrentUser());
    }
    function WIMBA_getMp4Status($roomId)
    {
        return $this->api->WIMBA_lcapi_getMP4Status($roomId,1,$this->session->WIMBA_getLcCurrentUser());
    }
    function WIMBA_getSystemConfig() {
        return $this->api->WIMBA_lcapi_get_system_config();
    }
     
} 



?>

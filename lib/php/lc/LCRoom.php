<?php
/*
 * Created on Jun 5, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

require_once("PrefixUtil.php"); 
// Constants
define("ATTRIB_ROOM_DESCRIPTION", "description");
define("ATTRIB_ROOM_LONG_NAME", "longname");
define("ATTRIB_ROOM_IS_ARCHIVE", "archive");
define("ATTRIB_ROOM_ID", "class_id");
define("ATTRIB_ROOM_CONTACT_EMAIL", "contact_email");
define("ATTRIB_ROOM_ARCHIVE", "archive");
define("ATTRIB_ROOM_PREVIEW", "preview");
define("ATTRIB_ROOM_ADD_CAROUSEL", "add_carousel");
define("ATTRIB_ROOM_DELETE_CAROUSEL", "delete_carousel");
define("ATTRIB_ROOM_PARTICIPANT_PIN", "participant_pin");
define("ATTRIB_ROOM_PRESENTER_PIN", "presenter_pin"); 
// Media
define("ATTRIB_ROOM_HMS_SIMULCAST", "hms_simulcast");
define("ATTRIB_ROOM_HMS_SIMULCAST_RESTRICTED", "hms_simulcast_restricted");
define("ATTRIB_ROOM_HMS_TWO_WAY_ENABLED", "hms_two_way_enabled");
define("ATTRIB_ROOM_MEDIA_FORMAT", "media_format");
define("ATTRIB_ROOM_MEDIA_TYPE", "media_type"); 
// Room
define("ATTRIB_ROOM_ARCHIVE_REMINDER_ENABLED", "display_archive_reminder");
define("ATTRIB_ROOM_USERSTATUS_ENABLED", "userstatus_enabled");
define("ATTRIB_ROOM_SEND_USERSTATUS_UPDATES", "send_userstatus_updates"); 
// Lecture room
define("ATTRIB_ROOM_CAN_EBOARD", "can_eboard");
define("ATTRIB_BOR_ENABLED", "bor_enabled");
define("ATTRIB_BOR_CAROUSELS_PUBLIC", "bor_carousels_public");
define("ATTRIB_BOR_SHOW_ROOM_CAROUSELS", "bor_show_room_carousels"); 
// Discussion room
define("ATTRIB_ROOM_CAN_ARCHIVE", "can_archive");
define("ATTRIB_ROOM_CAN_LIVESHARE", "can_liveshare");
define("ATTRIB_ROOM_CAN_PPT_IMPORT", "can_ppt_import"); 
// Chat
define("ATTRIB_ROOM_CHATENABLE", "chatenable");
define("ATTRIB_ROOM_PRIVATECHATENABLE", "privatechatenable"); 
// Access
define("ATTRIB_ROOM_USERLIMIT", "userlimit");
define("ATTRIB_ROOM_ENABLE_GUEST_ACCESS", "enable_guest_access");

define("ATTRIB_ROOM_CAN_LOGCHAT", "can_logchat");
define("ATTRIB_ROOM_CAN_MOVE_STUDENT", "can_move_student");
define("ATTRIB_ROOM_CAN_SHOW_WEB", "can_show_web");

define("ATTRIB_ROOM_VF_WIDTH", "vf_width");
define("ATTRIB_ROOM_VF_HEIGHT", "vf_height");
define("ATTRIB_ROOM_VF_LOCATION", "vf_location");
define("ATTRIB_ROOM_VIDEOFRAMESET", "videoframeset");

define("ATTRIB_ROOM_STUDENT_WB_ENABLED", "student_wb_enabled");
define("ATTRIB_ROOM_STUDENT_WB_LIVEAPP", "student_wb_liveapp");

define("ATTRIB_ROOM_ENABLE_STUDENT_VIDEO_ON_STARTUP", "enable_student_video_on_startup");
define("ATTRIB_ROOM_GUEST_URL", "guest_url");
define("ATTRIB_ROOM_HMS_VIDEO_WINDOW_SIZE_ON_STARTUP", "video_window_size_on_startup");
define("ATTRIB_ROOM_HMS_VIDEO_WINDOW_ENCODING_SIZE", "video_window_encoding_size");
define("ATTRIB_ROOM_HMS_VIDEO_DEFAULT_BIT_RATE", "video_default_bit_rate");
define("ATTRIB_ROOM_HMS_VIDEO_BIT_RATE_CEILING", "video_bit_rate_ceiling");
define("ATTRIB_ROOM_HMS_VIDEO_BANDWIDTH", "video_bandwidth");

define("VALUE_HMS_SIMULCAST_NONE", "none");
define("VALUE_HMS_SIMULCAST_BRIDGE", "bridge");
define("VALUE_HMS_SIMULCAST_DOTELL", "dotell");
define("VALUE_HMS_SIMULCAST_PUBLIC", "public");

define("VALUE_MEDIA_FORMAT_NONE", "none");
define("VALUE_MEDIA_FORMAT_QUICKTIME", "quicktime");
define("VALUE_MEDIA_FORMAT_REALMEDIA", "realmedia");
define("VALUE_MEDIA_FORMAT_HMS", "hms");

define("VALUE_MEDIA_TYPE_NONE", "none");
define("VALUE_MEDIA_TYPE_ONE_WAY_AUDIO", "one-way-audio");

define("VALUE_MEDIA_TYPE_TWO_WAY_VIDEO", "two-way-video");
define("VALUE_MEDIA_TYPE_ONE_WAY_VIDEO", "one-way-video");
define("VALUE_MEDIA_TYPE_SIMULCAST_ONLY", "simulcast-only");

define("VALUE_VF_LOCATION_TOP_LEFT", "Top Left");
define("VALUE_VF_LOCATION_TOP_RIGHT", "Top Right");
define("VALUE_VF_LOCATION_BOTTOM_LEFT", "Bottom Left");
define("VALUE_VF_LOCATION_BOTTOM_RIGHT", "Bottom Right");

define("ATTRIB_BOR_AUTO_MOVE_INSTRUCTORS", "bor_auto_move_instructors");
define("ATTRIB_BOR_AUTO_MOVE_SELF", "bor_auto_move_self");
define("ATTRIB_BOR_INITIAL_NUMBER", "bor_initial_number");

define(" VALUE_VF_WIDTH_160", 160);
define(" VALUE_VF_WIDTH_270", 270);
define(" VALUE_VF_WIDTH_320", 320);
define(" VALUE_VF_HEIGHT_120", 120);
define(" VALUE_VF_HEIGHT_210", 210);
define(" VALUE_VF_HEIGHT_240", 240);

define("VALUE_HMS_VIDEO_WINDOW_SIZE_SLOW", "80x60");
define("VALUE_HMS_VIDEO_WINDOW_SIZE_MEDIUM", "160x120");
define("VALUE_HMS_VIDEO_WINDOW_SIZE_FAST", "320x240");

define("VALUE_HMS_VIDEO_ENCODING_SIZE_SLOW", "80x60");
define("VALUE_HMS_VIDEO_ENCODING_SIZE_MEDIUM", "160x120");
define("VALUE_HMS_VIDEO_ENCODING_SIZE_FAST", "320x240");

define("VALUE_HMS_VIDEO_DEFAULT_BIT_RATE_SLOW", "32kb");
define("VALUE_HMS_VIDEO_DEFAULT_BIT_RATE_MEDIUM", "128kb");
define("VALUE_HMS_VIDEO_DEFAULT_BIT_RATE_FAST", "256kb");

define("VALUE_HMS_VIDEO_BIT_RATE_CEILING_SLOW", "128kb");
define("VALUE_HMS_VIDEO_BIT_RATE_CEILING_MEDIUM", "256kb");
define("VALUE_HMS_VIDEO_BIT_RATE_CEILING_FAST", "256kb");

define("VALUE_HMS_VIDEO_VIDEO_BANDWIDTH_SMALL", "small");
define("VALUE_HMS_VIDEO_VIDEO_BANDWIDTH_MEDIUM", "medium");
define("VALUE_HMS_VIDEO_VIDEO_BANDWIDTH_LARGE", "large");
define("VALUE_HMS_VIDEO_VIDEO_BANDWIDTH_CUSTOM", "custom");

define("ROOM_SELECTION_LOBBY_LINK", "--LOBBY--");
define("ROOM_SELECTION_SECTION_DEFAULT", "--SECTION--");
define("ROOM_SELECTION_LIST", "--LIST--");

define("ACCEPTABLE_ROOM_ID_LENGTH", "54");
define("ACCEPTABLE_ROOM_ID_REGEX", "/[a-zA-Z0-9_]{1,54}|\\-\\-LOBBY\\-\\-|\\-\\-SECTION\\-\\-/");

define("VALUE_OLD_ARCHIVE","pre5");//a version prior to WC 5.0.0
define("VALUE_50_ARCHIVE","5+");//5.0.0 and above
//$test=array("pre5","5+");
//define("VALUE_ARCHIVE_LIST",$test);
define("ATTRIB_ARCHIVE_VERSION","archive_version");

// MP4/MP3 settings
define("ATTRIB_ROOM_CAN_DOWNLOAD_MP4", "can_download_mp4");
define("ATTRIB_ROOM_CAN_DOWNLOAD_MP3", "can_download_mp3");
define("ATTRIB_ROOM_AUTO_OPEN_NEW_ARCHIVES", "auto_open_new_archives");
define("ATTRIB_ROOM_MP4_ENCODING_TYPE", "mp4_encoding_type");
define("ATTRIB_ROOM_MP4_MEDIA_PRIORITY", "mp4_media_priority");


/**
   * Mp4 Settings
   */
define("VALUE_MP4_ENCODING_TYPE_STANDARD", "standard");
define("VALUE_MP4_ENCODING_TYPE_STREAMING", "streaming");
define("VALUE_MP4_ENCODING_TYPE_HIGH_QUALITY", "high_quality");
//define("mp4EncodingTypeList", array(
  //      VALUE_MP4_ENCODING_TYPE_STANDARD,
    ///    VALUE_MP4_ENCODING_TYPE_STREAMING,
      //  VALUE_MP4_ENCODING_TYPE_HIGH_QUALITY));

define("VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS", "content_focus_no_video");
define("VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS_WITH_VIDEO", "content_focus_with_video");
define("VALUE_MP4_MEDIA_PRIORITY_VIDEO_FOCUS", "video_focus");
define("VALUE_MP4_MEDIA_PRIORITY_EBOARD_ONLY", "eboard_only");
define("VALUE_MP4_MEDIA_PRIORITY_APPSHARE_ONLY", "appshare_only");
define("VALUE_MP4_MEDIA_PRIORITY_VIDEO_ONLY", "video_only");
//define("mp4MediaPriorityList", array(
 //       VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS,
 //       VALUE_MP4_MEDIA_PRIORITY_CONTENT_FOCUS_WITH_VIDEO,
   //     VALUE_MP4_MEDIA_PRIORITY_VIDEO_FOCUS,
     //   VALUE_MP4_MEDIA_PRIORITY_EBOARD_ONLY,
       // VALUE_MP4_MEDIA_PRIORITY_APPSHARE_ONLY,
       // VALUE_MP4_MEDIA_PRIORITY_VIDEO_ONLY));



class WIMBA_LCRoom {

    var $attributes = array();
    /**
     * Constructor
     * 
     * @param currentRecord $ 
     * @param prefix $ 
     */
    function WIMBA_LCRoom()
    {
    } 

    function WIMBA_setByRecord($currentRecord, $prefix)
    { 
       
        // room attributes
        $prefixUtil = new WIMBA_PrefixUtil();
        $rid = $prefixUtil->WIMBA_trimPrefix($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_ID), $prefix);

        $this->WIMBA_setRoomId($rid);
        $this->WIMBA_setDescription($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_DESCRIPTION));
        $this->WIMBA_setLongname($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_LONG_NAME));
        $this->WIMBA_setContactEmail($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CONTACT_EMAIL));
        $this->WIMBA_setArchive($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_ARCHIVE));
        $this->WIMBA_setPreview($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_PREVIEW));
        $this->WIMBA_setBORAutoMoveInstructors($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_AUTO_MOVE_INSTRUCTORS));
        $this->WIMBA_setBORAutoMoveSelf($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_AUTO_MOVE_SELF));
        $this->WIMBA_setBORCarouselsPublic($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_CAROUSELS_PUBLIC));
        $this->WIMBA_setBOREnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_ENABLED));
        $this->WIMBA_setBORInitialNumber($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_INITIAL_NUMBER));
        $this->WIMBA_setBORShowRoomCarousels($this->WIMBA_getKeyValue($currentRecord,ATTRIB_BOR_SHOW_ROOM_CAROUSELS));
        $this->WIMBA_setUserstatusEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_USERSTATUS_ENABLED));
        $this->WIMBA_setSendUserstatusUpdates($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_SEND_USERSTATUS_UPDATES)); 
        $this->WIMBA_setArchiveReminderEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_ARCHIVE_REMINDER_ENABLED));
        // Media Settings
        // Compatible with LC 5.X
        $this->WIMBA_setHmsSimulcast($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_SIMULCAST));
        $this->WIMBA_setHmsSimulcastRestricted($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_SIMULCAST_RESTRICTED));
        $this->WIMBA_setHmsTwoWayEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_TWO_WAY_ENABLED));
        $this->WIMBA_setMediaFormat($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_MEDIA_FORMAT));
        $this->WIMBA_setMediaType($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_MEDIA_TYPE));
        $this->WIMBA_setVideoFrameWidth($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_VF_WIDTH));
        $this->WIMBA_setVideoFrameHeight($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_VF_HEIGHT));
        $this->WIMBA_setVideoFrameLocation($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_VF_LOCATION));
        $this->WIMBA_setVideoFrameSet($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_VIDEOFRAMESET));
        $this->WIMBA_setStudentVideoOnStartupEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_ENABLE_STUDENT_VIDEO_ON_STARTUP)); 
        $this->WIMBA_setVideoWindowSizeOnStartup($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_VIDEO_WINDOW_SIZE_ON_STARTUP));
        $this->WIMBA_setVideoWindowEncodingSize($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_VIDEO_WINDOW_ENCODING_SIZE));
        $this->WIMBA_setVideoDefaultBitRate($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_VIDEO_DEFAULT_BIT_RATE));
        // WIMBA_setVideoBitRateCeiling((String)currentRecord[ATTRIB_ROOM_HMS_VIDEO_BIT_RATE_CEILING));
        $this->WIMBA_setVideoBandwidth($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_HMS_VIDEO_BANDWIDTH)); 
        // Advanced settings
        $this->WIMBA_setArchiveEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_ARCHIVE));
        $this->WIMBA_setEboardEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_EBOARD));
        $this->WIMBA_setLiveShareEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_LIVESHARE));
        $this->WIMBA_setLogChatEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_LOGCHAT));
        $this->WIMBA_setMoveStudentEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_MOVE_STUDENT));
        $this->WIMBA_setPptImportEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_PPT_IMPORT));
        $this->WIMBA_setShowWebEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CAN_SHOW_WEB));
        $this->WIMBA_setChatEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_CHATENABLE));
        $this->WIMBA_setPrivateChatEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_PRIVATECHATENABLE));
        $this->WIMBA_setStudentWhiteboardEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_STUDENT_WB_ENABLED));
        $this->WIMBA_setStudentLiveAppEnabled($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_STUDENT_WB_LIVEAPP)); 
        // Access
        $this->WIMBA_setUserLimit($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_USERLIMIT));
        $this->WIMBA_setGuestURL($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_GUEST_URL));
        $this->WIMBA_setGuestAccess($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_ENABLE_GUEST_ACCESS));
        // PINs are compatible with LC 4.3.0+
        $this->WIMBA_setParticipantPin($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_PARTICIPANT_PIN));
        $this->WIMBA_setPresenterPin($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_PRESENTER_PIN));
        $this->WIMBA_setArchiveVersion($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ARCHIVE_VERSION));
        
        //Mp4
        $this->WIMBA_setMp4EncodingType($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_MP4_ENCODING_TYPE));
        $this->WIMBA_setMp4MediaPriority($this->WIMBA_getKeyValue($currentRecord,ATTRIB_ROOM_MP4_MEDIA_PRIORITY));
        $this->WIMBA_setDownloadMP3Enabled($this->WIMBA_getKeyValue($currentRecord, ATTRIB_ROOM_CAN_DOWNLOAD_MP3));
        $this->WIMBA_setDownloadMP4Enabled($this->WIMBA_getKeyValue($currentRecord, ATTRIB_ROOM_CAN_DOWNLOAD_MP4));
        $this->WIMBA_setAutoOpenArchive($this->WIMBA_getKeyValue($currentRecord, ATTRIB_ROOM_AUTO_OPEN_NEW_ARCHIVES));
        
    } 

    /**
     * Constructor
     * 
     * @param classId $ 
     * @param description $ 
     * @param longname $ 
     * @param contactEmail $ 
     * @param isArchive $ 
     */
    function WIMBA_setArguments($classId, $description,
        $longname, $contactEmail,
        $isArchive, $isPreview)
    {
        $this->WIMBA_setRoomId($classId);
        $this->WIMBA_setDescription($description);
        $this->WIMBA_setLongname($longname);
        $this->WIMBA_setArchive($isArchive);
        $this->WIMBA_setContactEmail($contactEmail);
        $this->WIMBA_setPreview($isPreview);
    } 

    /**
     * 
     * @return Returns the roomId.
     */
    function WIMBA_getRoomId()
    {
        return $this->roomId;
    } 

    /**
     * 
     * @param classId $ The roomId to set.
     */
    function WIMBA_setRoomId($classId)
    {
        if ($this->WIMBA_isValidRoomId($classId)) 
        {
            $this->roomId = $classId;
        } 
        else 
        {
            LOG . Debug("isValidRoomId: setRoomID: " + $classId); 
            // throw new ArgumentException("Room IDs must match " + ACCEPTABLE_ROOM_ID_REGEX + ".  Given room ID: " + classId);
        } 
    } 

    /**
     * 
     * @return Returns the description.
     */
    function WIMBA_getDescription()
    {
        return $this->attributes[ATTRIB_ROOM_DESCRIPTION];
    } 

    /**
     * 
     * @param description $ The description to set.
     */
    function WIMBA_setDescription($description)
    {
        $this->attributes[ATTRIB_ROOM_DESCRIPTION] = $description;
    } 

    /**
     * 
     * @return Returns the longname.
     */
    function WIMBA_getLongname()
    {
        return $this->attributes[ATTRIB_ROOM_LONG_NAME];
    } 

    /**
     * 
     * @param longname $ The longname to set.
     */
    function WIMBA_setLongname($longname)
    {
        $this->attributes[ATTRIB_ROOM_LONG_NAME] = $longname;
    } 

    /**
     * 
     * @return Returns the archive.
     */
    function WIMBA_isArchive()
    {
        return $this->attributes[ATTRIB_ROOM_ARCHIVE];
    } 

    /**
     * 
     * @param isArchive $ The archive to set.
     */
    function WIMBA_setArchive($isArchive)
    {
        $this->attributes[ATTRIB_ROOM_ARCHIVE] = $isArchive;
    } 

    /**
     * 
     * @return Returns the preview.
     */
    function WIMBA_isPreview()
    {
        return $this->attributes[ATTRIB_ROOM_PREVIEW];
    } 

    /**
     * 
     * @param isPreview $ The archive to set.
     */
    function WIMBA_setPreview($isPreview)
    {
        $this->attributes[ATTRIB_ROOM_PREVIEW] = $isPreview;
    } 

    /**
     * 
     * @return Returns the contactEmail.
     */
    function WIMBA_getContactEmail()
    {
        return $this->attributes[ATTRIB_ROOM_CONTACT_EMAIL];
    } 

    /**
     * 
     * @param contactEmail $ The contactEmail to set.
     */
    function WIMBA_setContactEmail($contactEmail)
    {
        $this->attributes[ATTRIB_ROOM_CONTACT_EMAIL] = $contactEmail;
    } 

    /**
     * Compares the given $to the regular expression ACCEPTABLE_ROOM_ID_REGEX
     * to determine whether it fits the LC requirements for that field.
     * 
     * @param id $ 
     * @return 
     */
    function WIMBA_isValidRoomId($id)
    {
        if ($id != null && preg_match(ACCEPTABLE_ROOM_ID_REGEX, $id) > 0) {
            return true;
        } else {
            return true;
        } 
    } 

    function WIMBA_intValue($Hashtable, $key)
    {
        $s = $Hashtable[key];
        return Int32 . Parse(s);
    } 

    function WIMBA_setHmsSimulcast($hmsSimulcast)
    {
        $this->attributes[ATTRIB_ROOM_HMS_SIMULCAST] = $hmsSimulcast;
    } 

    function WIMBA_setHmsSimulcastRestricted($hmsSimulcastRestricted)
    {
        $this->attributes[ATTRIB_ROOM_HMS_SIMULCAST_RESTRICTED] = $hmsSimulcastRestricted;
    } 

    function WIMBA_setHmsTwoWayEnabled($hmsTwoWayEnabled)
    {
        $this->attributes[ATTRIB_ROOM_HMS_TWO_WAY_ENABLED] = $hmsTwoWayEnabled;
    } 

    function WIMBA_setMediaFormat($mediaFormat)
    {
        $this->attributes[ATTRIB_ROOM_MEDIA_FORMAT] = $mediaFormat;
    } 

    function WIMBA_setMediaType($mediaType)
    {
        $this->attributes[ATTRIB_ROOM_MEDIA_TYPE] = $mediaType;
    } 

    function WIMBA_setArchiveEnabled($archiveEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_ARCHIVE] = $archiveEnabled;
    } 

    function WIMBA_setEboardEnabled($eboardEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_EBOARD] = $eboardEnabled;
    } 

    function WIMBA_setLiveShareEnabled($liveShareEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_LIVESHARE] = $liveShareEnabled;
    } 

    function WIMBA_setLogChatEnabled($logChatEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_LOGCHAT] = $logChatEnabled;
    } 

    function WIMBA_setMoveStudentEnabled($moveStudentEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_MOVE_STUDENT] = $moveStudentEnabled;
    } 

    function WIMBA_setPptImportEnabled($pptImportEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_PPT_IMPORT] = $pptImportEnabled;
    } 

    function WIMBA_setShowWebEnabled($showWebEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CAN_SHOW_WEB] = $showWebEnabled;
    } 

    function WIMBA_setChatEnabled($chatEnabled)
    {
        $this->attributes[ATTRIB_ROOM_CHATENABLE] = $chatEnabled;
    } 

    function WIMBA_setPrivateChatEnabled($privateChatEnabled)
    {
        $this->attributes[ATTRIB_ROOM_PRIVATECHATENABLE] = $privateChatEnabled;
    } 

    function WIMBA_setStudentWhiteboardEnabled($studentWhiteboardEnabled)
    {
        $this->attributes[ATTRIB_ROOM_STUDENT_WB_ENABLED] = $studentWhiteboardEnabled;
    } 

    function WIMBA_setStudentLiveAppEnabled($studentLiveAppEnabled)
    {
        $this->attributes[ATTRIB_ROOM_STUDENT_WB_LIVEAPP] = $studentLiveAppEnabled;
    } 

    function WIMBA_setUserLimit($userLimit)
    {
        $this->attributes[ATTRIB_ROOM_USERLIMIT] = $userLimit;
    } 
    
    function WIMBA_setGuestURL($guestURL)
    {
        $this->attributes[ATTRIB_ROOM_GUEST_URL] = $guestURL;
    }
    
    function WIMBA_setGuestAccess($guestAccess)
    {
        $this->attributes[ATTRIB_ROOM_ENABLE_GUEST_ACCESS] = $guestAccess;
    }

    function WIMBA_setArchiveReminderEnabled($enabled)
    {
        $this->attributes[ATTRIB_ROOM_ARCHIVE_REMINDER_ENABLED] = $enabled;
    }

    function WIMBA_getHmsSimulcast()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_SIMULCAST];
    } 

    function WIMBA_isHmsSimulcastRestricted()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_SIMULCAST_RESTRICTED];
    } 

    function WIMBA_isHmsTwoWayEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_TWO_WAY_ENABLED];
    } 

    function WIMBA_getMediaFormat()
    {
        return $this->attributes[ATTRIB_ROOM_MEDIA_FORMAT];
    } 

    function WIMBA_getMediaType()
    {
        return $this->attributes[ATTRIB_ROOM_MEDIA_TYPE];
    } 

    function WIMBA_isArchiveEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_ARCHIVE];
    }

    function WIMBA_isArchiveReminderEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_ARCHIVE_REMINDER_ENABLED];
    } 

    function WIMBA_isEboardEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_EBOARD];
    } 

    function WIMBA_isLiveShareEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_LIVESHARE];
    } 

    function WIMBA_isLogChatEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_LOGCHAT ];
    } 

    function WIMBA_isMoveStudentEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_MOVE_STUDENT];
    } 

    function WIMBA_isPptImportEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_PPT_IMPORT];
    } 

    function WIMBA_isShowWebEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CAN_SHOW_WEB];
    } 

    function WIMBA_isChatEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_CHATENABLE];
    } 

    function WIMBA_isPrivateChatEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_PRIVATECHATENABLE];
    } 

    function WIMBA_isStudentWhiteboardEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_STUDENT_WB_ENABLED] ;
    } 

    function WIMBA_isStudentLiveAppEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_STUDENT_WB_LIVEAPP];
    } 

    function WIMBA_getUserLimit()
    {
        return $this->attributes[ATTRIB_ROOM_USERLIMIT];
    } 
    
    function WIMBA_getGuestURL()
    {
        return $this->attributes[ATTRIB_ROOM_GUEST_URL];
    }

    function WIMBA_setVideoFrameWidth($videoFrameWidth)
    {
        $this->attributes[ATTRIB_ROOM_VF_HEIGHT] = $videoFrameWidth;
    } 

    function WIMBA_getVideoFrameWidth()
    {
        return $this->attributes[ATTRIB_ROOM_VF_HEIGHT];
    } 

    function WIMBA_setVideoFrameHeight($videoFrameHeight)
    {
        $this->attributes[ATTRIB_ROOM_VF_HEIGHT] = $videoFrameHeight;
    } 

    function WIMBA_getVideoFrameHeight()
    {
        return $this->attributes[ATTRIB_ROOM_VF_HEIGHT];
    } 

    function WIMBA_setVideoFrameLocation($videoFrameLocation)
    {
        $this->attributes[ATTRIB_ROOM_VF_LOCATION] = $videoFrameLocation;
    } 

    function WIMBA_getVideoFrameLocation()
    {
        return $this->attributes[ATTRIB_ROOM_VF_LOCATION];
    } 

    function WIMBA_setVideoFrameSet($videoFrameSet)
    {
        $this->attributes[ATTRIB_ROOM_VIDEOFRAMESET] = $videoFrameSet;
    } 

    function WIMBA_isVideoFrameSet()
    {
        return $this->attributes[ATTRIB_ROOM_VIDEOFRAMESET];
    } 

    function WIMBA_getParticipantPin()
    {
        return $this->attributes[ATTRIB_ROOM_PARTICIPANT_PIN];
    } 

    function WIMBA_setParticipantPin($participantPin)
    {
        $this->attributes[ATTRIB_ROOM_PARTICIPANT_PIN] = $participantPin;
    } 

    function WIMBA_getPresenterPin()
    {
        return $this->attributes[ATTRIB_ROOM_PRESENTER_PIN];
    } 

    function WIMBA_setPresenterPin($presenterPin)
    {
        $this->attributes[ATTRIB_ROOM_PRESENTER_PIN] = $presenterPin;
    } 

    function WIMBA_isBORAutoMoveInstructors()
    {
        return $this->attributes[ATTRIB_BOR_AUTO_MOVE_INSTRUCTORS];
    } 

    function WIMBA_setBORAutoMoveInstructors($isAutoMoveInstructors)
    {
        $this->attributes[ATTRIB_BOR_AUTO_MOVE_INSTRUCTORS] = $isAutoMoveInstructors;
    } 

    function WIMBA_isBORAutoMoveSelf()
    {
        return $this->attributes[ATTRIB_BOR_AUTO_MOVE_SELF];
    } 

    function WIMBA_setBORAutoMoveSelf($isAutoMoveSelf)
    {
        $this->attributes[ATTRIB_BOR_AUTO_MOVE_SELF] = $isAutoMoveSelf;
    } 

    function WIMBA_isBORCarouselsPublic()
    {
        return $this->attributes[ATTRIB_BOR_CAROUSELS_PUBLIC];
    } 

    function WIMBA_setBORCarouselsPublic($isCarouselsPublic)
    {
        $this->attributes[ATTRIB_BOR_CAROUSELS_PUBLIC] = $isCarouselsPublic;
    } 

    function WIMBA_isBOREnabled()
    {
        return $this->attributes[ATTRIB_BOR_ENABLED];
    } 

    function WIMBA_setBOREnabled($isEnabled)
    {
        $this->attributes[ATTRIB_BOR_ENABLED] = $isEnabled;
    } 

    function WIMBA_isBORShowRoomCarousels()
    {
        return $this->attributes[ATTRIB_BOR_SHOW_ROOM_CAROUSELS];
    } 

    function WIMBA_setBORShowRoomCarousels($isShowRoomCarousels)
    {
        $this->attributes[ATTRIB_BOR_SHOW_ROOM_CAROUSELS] = $isShowRoomCarousels;
    } 

    function WIMBA_getBORInitialNumber()
    {
        return $this->attributes[ATTRIB_BOR_INITIAL_NUMBER];
    } 

    function WIMBA_setBORInitialNumber($initialNumber)
    {
        $this->attributes[ATTRIB_BOR_INITIAL_NUMBER] = $initialNumber;
    } 

    function WIMBA_isStudentVideoOnStartupEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_ENABLE_STUDENT_VIDEO_ON_STARTUP];
    } 

    function WIMBA_setStudentVideoOnStartupEnabled($isStudentVideoOnStartupEnabled)
    {
        $this->attributes[ATTRIB_ROOM_ENABLE_STUDENT_VIDEO_ON_STARTUP] = $isStudentVideoOnStartupEnabled;
    } 

    function WIMBA_getVideoWindowSizeOnStartup()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_VIDEO_WINDOW_SIZE_ON_STARTUP];
    } 

    function WIMBA_setVideoWindowSizeOnStartup($videoWindowSizeOnStartup)
    {
        $this->attributes[ATTRIB_ROOM_HMS_VIDEO_WINDOW_SIZE_ON_STARTUP] = $videoWindowSizeOnStartup;
    } 

    function WIMBA_getVideoWindowEncodingSize()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_VIDEO_WINDOW_ENCODING_SIZE];
    } 

    function WIMBA_setVideoWindowEncodingSize($videoWindowEncodingSize)
    {
        $this->attributes[ATTRIB_ROOM_HMS_VIDEO_WINDOW_ENCODING_SIZE] = $videoWindowEncodingSize;
    } 

    function WIMBA_getVideoDefaultBitRate()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_VIDEO_DEFAULT_BIT_RATE];
    } 

    function WIMBA_setVideoDefaultBitRate($videoDefaultBitRate)
    {
        $this->attributes[ATTRIB_ROOM_HMS_VIDEO_DEFAULT_BIT_RATE] = $videoDefaultBitRate;
    } 

    function WIMBA_getVideoBitRateCeiling()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_VIDEO_BIT_RATE_CEILING];
    } 

    function WIMBA_setVideoBitRateCeiling($videoBitRateCeiling)
    {
        $this->attributes[ATTRIB_ROOM_HMS_VIDEO_BIT_RATE_CEILING] = $videoBitRateCeiling;
    } 

    function WIMBA_isUserstatusEnabled()
    {
        return $this->attributes[ATTRIB_ROOM_USERSTATUS_ENABLED];
    } 

    function WIMBA_setUserstatusEnabled($isEnabled)
    {
        $this->attributes[ATTRIB_ROOM_USERSTATUS_ENABLED] = $isEnabled;
    } 

    function WIMBA_isSendUserstatusUpdates()
    {
        return $this->attributes[ATTRIB_ROOM_SEND_USERSTATUS_UPDATES];
    } 

    function WIMBA_setSendUserstatusUpdates($sendUserstatusUpdates)
    {
        $this->attributes[ATTRIB_ROOM_SEND_USERSTATUS_UPDATES] = $sendUserstatusUpdates;
    } 

    function WIMBA_getVideoBandwidth()
    {
        return $this->attributes[ATTRIB_ROOM_HMS_VIDEO_BANDWIDTH];
    } 

    function WIMBA_setVideoBandwidth($bandwidth)
    {
        $this->attributes[ATTRIB_ROOM_HMS_VIDEO_BANDWIDTH] = $bandwidth;
    } 

    function WIMBA_getAttributes()
    {
        return $this->attributes;
    } 
    
    function WIMBA_getKeyValue($tab,$key){
        if(array_key_exists($key,$tab)){
            return $tab[$key];
        }
        return "";
    }
    function WIMBA_getArchiveVersion() 
    { 
       return $this->attributes[ATTRIB_ARCHIVE_VERSION];
    }
	  	 
    function WIMBA_setArchiveVersion($archiveVersion)
    {
     // if(!in_array(VALUE_ARCHIVE_LIST,$archiveVersion))
     // {
     //   return false;
     // }
      $this->attributes[ATTRIB_ARCHIVE_VERSION] = $archiveVersion;
    }
    
    function WIMBA_isAutoOpenArchive()
    {
      return $this->attributes[ATTRIB_ROOM_AUTO_OPEN_NEW_ARCHIVES];
    }
  
    function WIMBA_setAutoOpenArchive($autoOpenArchive)
    {
      $this->attributes[ATTRIB_ROOM_AUTO_OPEN_NEW_ARCHIVES] = $autoOpenArchive;
    }
  
    function WIMBA_getMp4EncodingType()
    {
      return $this->attributes[ATTRIB_ROOM_MP4_ENCODING_TYPE];
    }
  
    function WIMBA_setMp4EncodingType($mp4EncodingType)
    {
      //if (!in_array(mp4EncodingTypeList,$mp4EncodingType)) {
     //   return false;
      //}
      $this->attributes[ATTRIB_ROOM_MP4_ENCODING_TYPE] = $mp4EncodingType;
    }
  
    function WIMBA_getMp4MediaPriority()
    {
      return $this->attributes[ATTRIB_ROOM_MP4_MEDIA_PRIORITY];
    }
  
    function WIMBA_setMp4MediaPriority($mp4MediaPriority)
    {
     // if (!in_array(mp4MediaPriorityList,$mp4MediaPriority)) {
       // return false;
    //  }
      $this->attributes[ATTRIB_ROOM_MP4_MEDIA_PRIORITY] = $mp4MediaPriority;
    }
  
    function WIMBA_isDownloadMP3Enabled()
    {
      return $this->attributes[ATTRIB_ROOM_CAN_DOWNLOAD_MP3];
    }
  
    function WIMBA_setDownloadMP3Enabled($downloadMP3Enabled)
    {
      $this->attributes[ATTRIB_ROOM_CAN_DOWNLOAD_MP3] = $downloadMP3Enabled;
    }
  
    function WIMBA_isDownloadMP4Enabled()
    {
      return $this->attributes[ATTRIB_ROOM_CAN_DOWNLOAD_MP4];
    }
  
    function WIMBA_setDownloadMP4Enabled($downloadMP4Enabled)
    {
      $this->attributes[ATTRIB_ROOM_CAN_DOWNLOAD_MP4] = $downloadMP4Enabled;
    }
} 

?>

<?php
  class WIMBA_WimbaUI {
    
    var $session;
    var $api;
    var $xml;
    var $currentObject;
    var $isArchive = "false";
    var $resourceType = "room";
    var $lectureRoom;
    var $discussionRoom;
    var $isLectureRoom;
    var $disabledSetting;
    var $currentTab;
    //for vt
    var $currentObjectInformations = NULL;
    var $currentObjectOptions = NULL;
    var $currentObjectAudioFormat = NULL;
    var $startSelect = false;
    var $endSelect = false;
    var $prefix;
    var $id;
    
    function WIMBA_WimbaUI($session_params, $api = NULL, $currentIdtab = "") {
      $this->session = new WIMBA_WimbaMoodleSession($session_params);
      $this->api = $api;
      $this->xml = new WIMBA_WimbaXml();
      $this->currentTab = $currentIdtab;
        
      if($api!=null) {
        $this->prefix=$api->WIMBA_getPrefix();
      }
    }
    
    /**
    * Configure different parmaters used to generate the xml according to the current product 
    * @param product : the product which will be display (liveclassroom or voicetools)
    * @param $serverInformations : contains the object informations get from the server
    * @param $databaseInformations : contains the informations get from the database
    */
    function WIMBA_setCurrentProduct($product, $objectInformation = NULL,  $databaseInformations= NULL)
    {
        $this->product = $product;
        $this->currentObject = $objectInformation;
       
        if($this->currentObject == null) 
        {
            $this->id = $this->session->WIMBA_getCourseId().rand();//generate the id for the resource
        }
        
        if ($product == "liveclassroom") 
        {
            if( isset($this->currentObject) )
            {
                $roomId=$this->currentObject->WIMBA_getRoomId();
                $isStudentAdmin= $this->api->WIMBA_isStudentAdmin($roomId, $this->session->WIMBA_getCourseId()."_S");
            }
            
            if ($this->currentObject != null && $this->currentObject->WIMBA_isArchive()) 
            {
                $this->isArchive = "true";
				$this->resourceType =  "archive";
            }  
  
            if ($this->currentObject == null 
                || $this->currentObject != null && isset($isStudentAdmin) && $isStudentAdmin == "false")
            {     
                $this->lectureRoom = "activeSetting";
                $this->discussionRoom = "hiddenSetting";
                $this->disabledSetting = "activeSetting";
                $this->isLectureRoom = true;
            }
            else 
            {
                $this->lectureRoom = "hiddenSetting";
                $this->discussionRoom = "activeSetting";
                $this->disabledSetting = "disabledSetting";  
                $this->isLectureRoom = false;
            }
        }
        else 
        {
            //data of the database
            $this->currentObjectInformations = $databaseInformations;
            if ($this->currentObjectInformations != null 
                && $this->currentObjectInformations->start_date != -1) 
            {
                $this->startSelect = true;
            }
            
            if ( $this->currentObjectInformations != null 
                && $this->currentObjectInformations->end_date != -1 ) 
            {
                $this->endSelect = true;
            }
            
            if (isset($this->currentObject) 
                && isset($this->currentObjectInformations)) {
                //for php 4-> object->object doesn't work
                $this->currentObjectOptions = $this->currentObject->WIMBA_getOptions();
                $this->currentObjectAudioFormat = $this->currentObjectOptions->WIMBA_getAudioFormat();
            }
        }
    }
    
     /**
    * Add the necessary elements to the current xml object to render the principal view of the lc component
    * @param message : eventual information message displayed at the bottom of the component
    */
    function WIMBA_getLCPrincipalView($message) {
        /********
         SESSION
         *********/
        $this->xml->WIMBA_CreateInformationElement(
                        $this->session->timeOfLoad,
                        $this->session->hparams["firstname"],
                        $this->session->hparams["lastname"],
                        $this->session->hparams["email"],
                        $this->session->hparams["role"],
                        $this->session->hparams["course_id"],
                        $this->session->signature,
                        "");
        
        /********
         HEADER
         *********/
        if($this->session->WIMBA_isInstructor())
        {
            $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "false", "true");
        }
        else
        {
            $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "false", "false");
        }
        /********
         MENU
         *********/
        $this->xml->WIMBA_addButtonElement(
                        "all",
                        "all",
                        "disabled",
                        "launch",
                        get_string('toolbar_launch', 'liveclassroom'),
                        "javascript:WIMBA_LaunchElement('manageAction.php','liveclassroom');");

        if($this->session->WIMBA_isInstructor())
        {
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            'activities',
                            get_string('toolbar_activity', 'liveclassroom'),
                            "WIMBA_doOpenAddActivity('../../course/mod.php','section=0&sesskey=" . sesskey() . "&add=liveclassroom')");     
            
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all", 
                            "enabled",
                            "new",
                            get_string('toolbar_new', 'liveclassroom'),
                            "javascript:loadNewSettings('generateXmlSettingsPanel.php','create','liveclassroom' ,'liveclassroom','all')");
            
            $this->xml->WIMBA_addSpaceElement("20px", "instructor");
            
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            "content",
                            get_string('toolbar_content', 'liveclassroom'),
                            "javascript:WIMBA_openContent('manageAction.php');");
            
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            "report",
                            get_string('toolbar_reports', 'liveclassroom'),
                            "javascript:WIMBA_openReport('manageAction.php');");
             
            $this->xml->WIMBA_addSpaceElement("10px", "instructor");
            
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            "settings",
                            get_string('toolbar_settings', 'liveclassroom'),
                            "javascript:WIMBA_editSettings('generateXmlSettingsPanel.php','all');");
             
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            "delete",
                            get_string('toolbar_delete', 'liveclassroom'),
                            "javascript:WIMBA_deleteResource('manageAction.php');");
            
            $this->xml->WIMBA_addSpaceElement("50px", "instructor");
        }
        else
        {
            $this->xml->WIMBA_addSpaceElement("300px", "instructor");
        }
        
        
        //MESSAGE BAR
        if (isset ($message) && $message!="") 
        {
            $this->xml->WIMBA_addMessage($message);
        }
        
        $rooms=$this->WIMBA_getListLiveClassroom();
       
        // https://wimba.agilebuddy.com/bugs/3558
        // When $rooms is set to the boolean "false", $rooms == null will evaluate to true.
        // We need need to use the === operator as this checks type as well.
        if ($rooms != false || $rooms === null || empty($rooms))
        {
            $this->xml->WIMBA_addProduct(
                            "liveclassroom",
                            "productType",
                            "Live classroom", 
                            "liveclassroom",
                            $rooms ,
                            get_string('list_no_liveclassrooms', 'liveclassroom'),
                            array("&#160;","Title","Access","Download","Room Details"));
        }
        else 
        {
            //problem to get the vt resource
            if (isset($this->api->errormsg)) {
                $this->xml->WIMBA_setError($this->api->errormsg.' '.get_string('contactadmin','liveclassroom'));
            } else {
                $this->xml->WIMBA_setError(get_string('error_connection_lc','liveclassroom'));
            }
        }
    }
    
    
     /**
    * Add the necessary elements to the current xml object to render the principal view of the vt component
    * @param message : eventual information message displayed at the bottom of the component
    */    
    function WIMBA_getVTPrincipalView($message,$type=null) {
        /********
         SESSION
         *********/
        $this->xml->WIMBA_CreateInformationElement(
                        $this->session->timeOfLoad,
                        $this->session->hparams["firstname"],
                        $this->session->hparams["lastname"],
                        $this->session->hparams["email"],
                        $this->session->hparams["role"],
                        $this->session->hparams["course_id"],
                        $this->session->signature,
                        "");
        /********
         HEADER
         *********/
        ( $this->session->WIMBA_isInstructor() )
            ? $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "false", "true")
            : $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "false", "false")
            ;
        
        /********
         MENU
         *********/
        $this->xml->WIMBA_addButtonElement(
                        "all",
                        "all",
                        "disabled",
                        "launch",
                        get_string('toolbar_launch', 'voiceemail'),
                        "javascript:WIMBA_LaunchElement('manageAction.php','voiceemail');");
                                     
        if($this->session->WIMBA_isInstructor())
        {
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            'activities',
                            get_string('toolbar_activity', 'voiceemail'),
                            "WIMBA_doOpenAddActivity('../../course/mod.php','section=0&sesskey=" . sesskey() . "&add=voiceemail')");
                                         
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "enabled",
                            "new",
                            get_string('toolbar_new', 'voiceemail'),
                            "javascript:loadNewSettings('generateXmlSettingsPanel.php','create','voicetools' ,'vmail','all','false')");
                                         
            $this->xml->WIMBA_addSpaceElement("150px", "instructor");
            if(WIMBA_isGradebookAvailable() && isset($type) && $type == "board"){
              $this->xml->WIMBA_addButtonElement(
                              "instructor", 
                              "all",
                              "disabled",
                              "grade",
                              get_string('toolbar_grade', 'voiceemail'),
                              "javascript:WIMBA_showGrades('grades.php');");
            }                                 
            $this->xml->WIMBA_addButtonElement(
                            "instructor", 
                            "all",
                            "disabled",
                            "settings",
                            get_string('toolbar_settings', 'voiceemail'),
                            "javascript:WIMBA_editSettings('generateXmlSettingsPanel.php','all');");
                                         
            $this->xml->WIMBA_addButtonElement(
                            "instructor",
                            "all",
                            "disabled",
                            "delete",
                            get_string('toolbar_delete', 'voiceemail'),
                            "javascript:WIMBA_deleteResource('manageAction.php');");
                                         
            $this->xml->WIMBA_addSpaceElement("50px", "instructor");
        }
        else
        {
            $this->xml->WIMBA_addSpaceElement("300px", "instructor");
        }

        /********
         MESSAGE BAR
         *********/
        $resources = $this->WIMBA_getListVoiceTools();
     
        if (is_array($resources)) 
        {
            $this->xml->WIMBA_addProduct(
                            "voicetools",
                            "productType",
                            "Voice Board",
                            "board",
                            $resources,
                            get_string('list_no_voiceemails', 'voiceemail'));
        }
        else 
        {//problem to get the vt resource
            wimba_add_log(WIMBA_INFO,voiceemail_LOGS,"No resource have been created yet");  
            $this->xml->WIMBA_setError(get_string("error_connection_vt", "voiceemail"));
        }
        
        if (isset ($message) && $message!="") 
        {
            $this->xml->WIMBA_addMessage($message);
        }
    
    }
    
    function WIMBA_getLCSettingsView($update) {
        /********
         SESSION
         *********/

        $this->xml->WIMBA_CreateInformationElement(
                        $this->session->timeOfLoad,
                        $this->session->hparams["firstname"],
                        $this->session->hparams["lastname"],
                        $this->session->hparams["email"],
                        $this->session->hparams["role"],
                        $this->session->hparams["course_id"],
                        $this->session->signature,
                        "");
        /********
         HEADER
         *********/
        $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "true", "true");
    
        if($update=="update")
        {
            $this->xml->WIMBA_addContextBarElement(
                            get_string("contextbar_settings", "liveclassroom"),
                            get_string("general_" . $this->product, "liveclassroom"),
                            $this->currentObject->WIMBA_getLongname(), "");
        }
        else
        {   
            $this->xml->WIMBA_addContextBarElement(
                            get_string("contextbar_settings", "liveclassroom"),
                            get_string("general_" . $this->product, "liveclassroom"),
                            get_string("contextbar_new_" . $this->product, "liveclassroom"), "");
        }
        /********
         * Settings tabs
         */
        $this->config = $this->api->WIMBA_getSystemConfig();
        $this->WIMBA_createLcInfoPanel();
        $this->WIMBA_createLcArchivesPanel();
        $this->WIMBA_createLcMediaPanel();
        $this->WIMBA_createLcFeaturesPanel();
        $this->WIMBA_createLcChatPanel();
        $this->WIMBA_createLcAccessPanel();
        if( $this->currentObject == null || !$this->currentObject->WIMBA_isArchive() || $this->currentObject->WIMBA_getArchiveVersion() == VALUE_50_ARCHIVE){
          $this->WIMBA_createLcAudioSettingsPanel();
        }
        
        if ($update == "update" && $this->currentObject != null && !$this->currentObject->WIMBA_isArchive()) 
        {
            $this->WIMBA_createLcAdvancedPanel();
            $this->xml->WIMBA_createValidationButtonElement(
                        get_string("validationElement_ok", "liveclassroom"),
                        "submit",
                        "javascript:launchAjaxRequest('generateXmlMainPanel.php','',true,'all')", 
                        "advanced_Ok",
                        "hideElement");
        }
        
  
        
       
        $this->xml->WIMBA_createValidationButtonElement(
                        get_string("validationElement_cancel", "liveclassroom"),
                        "link",
                        "javascript:launchAjaxRequest('generateXmlMainPanel.php','',true,'all')",
                        "setting_Cancel");
   
        if ($update != "update") 
        {
            $this->xml->WIMBA_createValidationButtonElement(
                            get_string("validationElement_create", "liveclassroom"),
                            "submit",
                            "javascript:WIMBA_submitForm('manageAction.php','create','".$this->id."')",
                            "setting_Create");
        }
        else 
        {
            $this->xml->WIMBA_createValidationButtonElement(
                            get_string("validationElement_saveAll", "liveclassroom"),
                            "submit",
                            "javascript:WIMBA_submitForm('manageAction.php','update','".$this->currentObject->WIMBA_getRoomId()."')",
                            "setting_Save");
        }
    }
    
    function WIMBA_getVTSettingsView($update) {
   
        /********
         SESSION
         *********/
         $this->xml->WIMBA_createInformationElement(
                        $this->session->timeOfLoad,
                        $this->session->hparams["firstname"],
                        $this->session->hparams["lastname"],
                        $this->session->hparams["email"],
                        $this->session->hparams["role"],
                        $this->session->hparams["course_id"],
                        $this->session->signature,
                        "");
         /********
          HEADER
          *********/
         $this->xml->WIMBA_addHeaderElement("lib/web/pictures/items/headerbar-logo.png", "true", "true");
         if($update=="update")
         {
            $this->xml->WIMBA_addContextBarElement(
                            get_string("contextbar_settings", 'voiceemail'),
                            get_string("general_" . $this->product, 'voiceemail'),
                            $this->currentObject->WIMBA_getTitle(),
                            "");
         }
         else
         {
            $this->xml->WIMBA_addContextBarElement(
                            get_string("contextbar_settings", 'voiceemail'),
                            get_string("general_" . $this->product, 'voiceemail'),
                            get_string("contextbar_new_" . $this->product, 'voiceemail'),
                            "");
         }
        
         /********
          * Settings tabs
          */
         if ($this->product == "board") 
         {
             $this->WIMBA_createGeneralInfoPanelStart($update);
             $this->WIMBA_createVBInfoPanel();
             //Media settings
             $this->WIMBA_createVBVPMediaPanel();
             $this->WIMBA_createVBFeaturesPanel();
             $this->WIMBA_createVTAccessPanel();
         }
         elseif ($this->product == "presentation") 
         {
             $this->WIMBA_createGeneralInfoPanelStart($update);
             $this->WIMBA_createVPInfoPanel();
             //Media settings
             $this->WIMBA_createVBVPMediaPanel();
             $this->WIMBA_createVTAccessPanel();
         }
         elseif ($this->product == "pc") 
         {
             $this->WIMBA_createGeneralInfoPanelStart($update);
             
             $this->WIMBA_createPCInfoPanel();
             //Media settings
             $this->WIMBA_createPCMediaPanel();
             $this->WIMBA_createPCFeaturesPanel();
             $this->WIMBA_createVTAccessPanel();
         }
        
        $this->xml->WIMBA_createValidationButtonElement(
                        get_string("validationElement_cancel", 'voiceemail'),
                        "link",
                        "javascript:launchAjaxRequest('generateXmlMainPanel.php','',true,'all')",
                        "setting_Cancel");

        if ($update != "update") 
        {
            $this->xml->WIMBA_createValidationButtonElement(
                            get_string("validationElement_create", 'voiceemail'),
                            "submit",
                            "javascript:WIMBA_submitForm('manageAction.php','create','')",
                            "setting_Create");
        }
        else 
        {
            $this->xml->WIMBA_createValidationButtonElement(
                            get_string("validationElement_saveAll", 'voiceemail'),
                            "submit",
                            "javascript:WIMBA_submitForm('manageAction.php','update','" . $this->currentObject->WIMBA_getRid() . "')",
                            "setting_Save");
        }
    }
    
     /**
    * Add the necessary elements to the current xml object to render the panel information of the lc settings
    */   
    function WIMBA_createLcInfoPanel() {
        
        $this->xml->WIMBA_addCustomLineElement( "*",
                                          "required",  
                                          get_string("settings_title", 'liveclassroom'),
                                          array("class"=>"LargeLabel_Width TextRegular_Right"));
        
        $parameters = array (
            "type" => "input",
            "name" => "longname",
            "id" => "longname",
            "style" => "input",
            "maxlength" => "50"
        );
        if ($this->currentObject != null) 
        { 
            $parameters["value"] = $this->currentObject->WIMBA_getLongName();
        }
        $this->xml->WIMBA_addInputElement($parameters);
  
        $this->xml->WIMBA_addCustomLineElement( "*",
                                          "required",  
                                          get_string("settings_required", 'liveclassroom')
                                          );

        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement( "label", 
                                            get_string("settings_description", 'liveclassroom'),
                                            array("class"=>"LargeLabel_Width TextRegular_Right"));
        
        $parameters = array (
            "name" => "description",
            "id" => "description",
            "rows" => "4",
            "cols" => "30"
        ); 
        $display = "";
        if (isset ($this->currentObject)) 
        {
            $display = $this->currentObject->WIMBA_getDescription();
        }
        $this->xml->WIMBA_addTextAreaElement($parameters,$display);
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement( "label", 
                                            get_string("settings_type", 'liveclassroom'),
                                            array("class"=>"LargeLabel_Width TextRegular_Right"));
        
        $parameters = array (
            "type" => "radio",
            "name" => "led",
            "value" => "instructor",
            "id" => "led_instructor",
            "onclick" => "WIMBA_toggleTypeOfRoom(\"lectureRoom\")"
        );
        if($this->isArchive == "true") 
        {
            $parameters["disabled"] = "true";
        }

        $roomId="";
        if(isset($this->currentObject)){
            $roomId=$this->currentObject->WIMBA_getRoomId();
            $courseId=$this->session->WIMBA_getCourseId() . "_S";
            $isStudentAdmin= $this->api->WIMBA_isStudentAdmin($roomId, $courseId);
        }
        if ($this->currentObject == null || $isStudentAdmin == "false") {
            $parameters["checked"] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        
        $parameters = array (
            "class" => "top",
            "for" => "led_instructor",   
        );
        if($this->isArchive=="true") 
        {
            $parameters["disabled"] ="true";
        }
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_mainLecture_comment", 'liveclassroom'),
                         $parameters);
        
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "radio",
            "name" => "led",
            "value" => "student",
            "id" => "led_student",    
            "onclick" => "WIMBA_toggleTypeOfRoom(\"discussionRoom\")",
            "class" => "AlignRight"
        );
        if($this->isArchive == "true") 
        {
            $parameters["disabled"] = "true";
        }
        if ($this->currentObject != null && $isStudentAdmin == "true") 
        {
            $parameters["checked"] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        
        $parameters = array (
            "class" => "top",
            "for" => "led_student",   
        );
        if($this->isArchive=="true") 
        {
            $parameters["disabled"] = "true";
        }
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label",
                        get_string("settings_discussion_comment", 'liveclassroom'),
                         $parameters);
        
        $this->xml->WIMBA_createLine();
        if ($this->currentTab == "Info" || $this->currentTab == "") {
            $this->xml->WIMBA_createPanelSettings(
                            get_string("tab_title_roomInfo", 'liveclassroom'),
                            "block",
                            "Info",
                            "current",
                            "all",
                            "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
        else
        {
            $this->xml->WIMBA_createPanelSettings(
                            get_string("tab_title_roomInfo", 'liveclassroom'),
                            "none",
                            "Info",
                            "",
                            "all",
                            "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel archives of the lc settings
    */
    function WIMBA_createLcArchivesPanel() {

        if ($this->currentObject == null || !$this->currentObject->WIMBA_isArchive()) {

            $this->xml->WIMBA_addSimpleLineElement("label",
                                             get_string("settings_enable_archives", 'liveclassroom'),
                                             array("class"=>"LargeLabel_Width TextRegular_Right"));
                           
            $parameters = array('type' => 'checkbox',
                                'name' => 'enable_archives',
                                'value' => 'true',
                                'id' => 'enable_archives',
				"onclick" => "WIMBA_doToggleArchivesStatus(archiveEnabled, this)");
            if ($this->currentObject == null || $this->currentObject->WIMBA_isArchiveEnabled()) {
              $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_createLine();

            $this->xml->WIMBA_addSimpleLineElement("label",
                                             get_string("settings_archive_access", 'liveclassroom'),
                                             array("class"=>"LargeLabel_Width TextRegular_Right"));
                                             
            $parameters = array('type' => 'checkbox',
                                'name' => 'auto_open_archive',
                                'value' => 'true',
                                'id' => 'auto_open_archive');
            if ($this->currentObject == null || $this->currentObject->WIMBA_isAutoOpenArchive()) {
              $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            
            $this->xml->WIMBA_addSimpleLineElement("label",
                                             get_string("settings_auto_open_archive", 'liveclassroom'));
            $this->xml->WIMBA_createLine();


            $this->xml->WIMBA_addSimpleLineElement("label",
                                             get_string("settings_display_archive_reminder", 'liveclassroom'),
                                             array("class"=>"LargeLabel_Width TextRegular_Right"));
                                
            $parameters = array('type' => 'checkbox',
                                              'name' => 'display_archive_reminder',
                                              'value' => 'true',
                                              'id' => 'display_archive_reminder');
            if ($this->currentObject == null || $this->currentObject->WIMBA_isArchiveReminderEnabled()) {
              $parameters["checked"] = "true";
            }                 
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_createLine();

            if ($this->currentTab == "Archives") {
                $this->xml->WIMBA_createPanelSettings(get_string("tab_title_archives", 'liveclassroom'),
                                                "block",
                                                "Archives",
                                                "current",
                                                "mainLecture",
                                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            } else {
                $this->xml->WIMBA_createPanelSettings(get_string("tab_title_archives", 'liveclassroom'),
                                                "none",
                                                "Archives",
                                                "",
                                                "mainLecture",
                                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");

            }

        }

    }

    /**
    * Add the necessary elements to the current xml object to render the panel medua of the lc settings
    */  
    function WIMBA_createLcMediaPanel() {
        $options = array ();
        //this tab is not available for archives
        if ($this->currentObject == null || !$this->currentObject->WIMBA_isArchive()) 
        {
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_student_privileges", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));
                               
            $parameters = array (
                "type" => "checkbox",
                "name" => "hms_two_way_enabled",
                "value" => "true",
                "id" => "hms_two_way_enabled",
                "class" => "discussionRoomDisabled"
            );
            if ($this->lectureRoom == "hiddenSetting") 
            {
              $parameters["disabled"]= "disabled";
            }
            if ($this->currentObject == null || $this->currentObject->WIMBA_isHmsTwoWayEnabled()) 
            {
              $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);   
      
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_hms_two_way_enabled", 'liveclassroom'));
      
            $this->xml->WIMBA_createLine();

            
            $parameters = array (
                "type" => "checkbox",
                "name" => "enable_student_video_on_startup",
                "value" => "true",
                "id" => "enable_student_video_on_startup",
                "class" => "AlignRight discussionRoomDisabled"
            );
            if ($this->lectureRoom == "hiddenSetting") 
            {
                $parameters["disabled"]= "disabled";
            }
            if ($this->currentObject == null || $this->currentObject->WIMBA_isStudentVideoOnStartupEnabled()) 
            {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);   
        
    
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_enable_student_video_on_startup", 'liveclassroom'));
        
            $this->xml->WIMBA_createLine();
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "hms_simulcast_restricted",
                "value" => "25",
                "id" => "hms_simulcast_restricted",
                "class" => "AlignRight discussionRoomDisabled"
            );
            if ($this->lectureRoom == "hiddenSetting") 
            {
                $parameters["disabled"]= "disabled";
            }
    
            if ($this->currentObject == null || !$this->currentObject->WIMBA_isHmsSimulcastRestricted()) 
            {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
                   
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_hms_simulcast_restricted", 'liveclassroom'));
         
            $this->xml->WIMBA_createLine();
              
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_video_bandwidth", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));
                            
  
            //select parameter
            $dfltBandwidth = $this->config['dflt-bandwidth'];
            $bandwidthCap = $this->config['bandwidth_cap'];

            // Default when creating a room to fastest alowed
            $currentBandwidth = array("fastest" => 512, "fast" => 256, "medium" => 128, "slow" => 32);
            $currentPreset = isset($this->currentObject) ? $currentBandwidth[$this->currentObject->WIMBA_getVideoBandwidth()] : $dfltBandwidth;
            $optionsParamaters = array ("value" => "fastest",
                                        "display" => get_string("settings_video_bandwidth_fastest",'liveclassroom'),
                                        "selected" => ($currentPreset == 512) ? 'true' : null);

            if ($optionsParamaters['selected'] || $bandwidthCap >= $currentBandwidth[$optionsParamaters['value']]) {
                $options[] = $optionsParamaters;
            }

            $optionsParamaters = array ("value" => "fast",
                                        "display" => get_string("settings_video_bandwidth_fast",'liveclassroom'),
                                        "selected" => ($currentPreset == 256) ? 'true' : null);

            if ($optionsParamaters['selected'] || $bandwidthCap >= $currentBandwidth[$optionsParamaters['value']]) {
                $options[] = $optionsParamaters;
            }

            $optionsParamaters = array ("value" => "medium",
                                        "display" => get_string("settings_video_bandwidth_medium",'liveclassroom'),
                                        "selected" => ($currentPreset == 128) ? 'true' : null);

            if ($optionsParamaters['selected'] || $bandwidthCap >= $currentBandwidth[$optionsParamaters['value']]) {
                $options[] = $optionsParamaters;
            }

            $optionsParamaters = array ("value" => "slow",
                                        "display" => get_string("settings_video_bandwidth_slow",'liveclassroom'),
                                        "selected" => ($currentPreset == 32) ? 'true' : null);

            $options[] = $optionsParamaters;
                
            $optionsParamaters = array (
                "value" => "custom",
                "display" => get_string("settings_video_bandwidth_custom",'liveclassroom')
            );
    
            if ($this->currentObject != null && $this->currentObject->WIMBA_getVideoBandwidth() == "custom") 
            {
                $optionsParamaters["selected"] = "true";
                $options[] = $optionsParamaters;
            }
            $this->xml->WIMBA_createOptionElement("video_bandwidth", "video_bandwidth", $options);
            $this->xml->WIMBA_createLine("subPart");
            unset($options);

            $this->xml->WIMBA_createLine();
            if(isset($bandwidthCap) && $currentPreset > $bandwidthCap) {
                $this->xml->WIMBA_addSimpleLineElement('span',get_string('settings_video_bandwidth_cap_set','liveclassroom',$bandwidthCap),array('style' => 'color:#f00;'));
            }
            $this->xml->WIMBA_createLine();

            if ($this->currentObject != null && $this->currentObject->WIMBA_getVideoBandwidth() == "custom") {

                $this->xml->WIMBA_addSimpleLineElement(
                                "label",
                                get_string("settings_video_popup_size", 'liveclassroom'),
                                array("class"=>"LargeLabel_Width TextRegular_Right"));
                $options[] = array ("value" => "640x480",
                                    "display" => "640x480",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowSizeOnStartup() == "640x480" ? "true" : null));
                $options[] = array ("value" => "320x240",
                                    "display" => "320x240",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowSizeOnStartup() == "320x240" ? "true" : null));
                $options[] = array ("value" => "160x120",
                                    "display" => "160x120",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowSizeOnStartup() == "160x120" ? "true" : null));
                $options[] = array ("value" => "80x60",
                                    "display" => "80x60",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowSizeOnStartup() == "80x60" ? "true" : null));
                $this->xml->WIMBA_createOptionElement("video_window_size_on_startup", "video_window_size_on_startup", $options);
                $this->xml->WIMBA_createLine();
                unset($options);

                $this->xml->WIMBA_addSimpleLineElement(
                                "label",
                                get_string("settings_video_resolution", 'liveclassroom'),
                                array("class"=>"LargeLabel_Width TextRegular_Right"));
                $options[] = array ("value" => "640x480",
                                    "display" => "640x480",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowEncodingSize() == "640x480" ? "true" : null));
                $options[] = array ("value" => "320x240",
                                    "display" => "320x240",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowEncodingSize() == "320x240" ? "true" : null));
                $options[] = array ("value" => "160x120",
                                    "display" => "160x120",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowEncodingSize() == "160x120" ? "true" : null));
                $options[] = array ("value" => "80x60",
                                    "display" => "80x60",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoWindowEncodingSize() == "80x60" ? "true" : null));
                $this->xml->WIMBA_createOptionElement("video_window_encoding_size", "video_window_encoding_size", $options);
                $this->xml->WIMBA_createLine();
                unset($options);

                $this->xml->WIMBA_addSimpleLineElement(
                                "label",
                                get_string("settings_video_bitrate", 'liveclassroom'),
                                array("class"=>"LargeLabel_Width TextRegular_Right"));
                $options[] = array ("value" => "512kb",
                                    "display" => "512kb",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoDefaultBitRate() == "512kb" ? "true" : null));
                $options[] = array ("value" => "256kb",
                                    "display" => "256kb",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoDefaultBitRate() == "256kb" ? "true" : null));
                $options[] = array ("value" => "128kb",
                                    "display" => "128kb",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoDefaultBitRate() == "128kb" ? "true" : null));
                $options[] = array ("value" => "32kb",
                                    "display" => "32kb",
                                    "selected" => ($this->currentObject != null && $this->currentObject->WIMBA_getVideoDefaultBitRate() == "32kb" ? "true" : null));
                $this->xml->WIMBA_createOptionElement("video_default_bit_rate", "video_default_bit_rate", $options);
                $this->xml->WIMBA_createLine();
                unset($options);
            }
        }
        
        if ($this->currentTab == "Media")
        {
            $this->xml->WIMBA_createPanelSettings(
                            get_string("tab_title_media", 'liveclassroom'),
                            "block",
                            "Media", 
                            "current", 
                            "mainLecture", 
                            "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
        else
        {
            if ($this->currentObject != null && $this->currentObject->WIMBA_isArchive()) 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_media", 'liveclassroom'),
                                "none",
                                "Media",
                                "disabled",
                                "mainLecture",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
            else 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_media", 'liveclassroom'),
                                "none",
                                "Media",
                                "",
                                "mainLecture",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
        }
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel features of the lc settings
    */  
    function WIMBA_createLcFeaturesPanel() {
    
        if ($this->currentObject == null || !$this->currentObject->WIMBA_isArchive()) {
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_status_indicators", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));
                            
            $parameters = array (
                "type" => "checkbox",
                "name" => "enabled_status",
                "value" => "true",
                "id" => "enabled_status",
                "onclick" => "WIMBA_doStatusEnabled()"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isUserstatusEnabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_enabled_status", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine();
              
            $parameters = array (
                "type" => "checkbox",
                "name" => "status_appear",
                "value" => "true",
                "id" => "status_appear",
                "class" => "SubOption"
            );
            
            if ($this->currentObject != null && !$this->currentObject->WIMBA_isUserstatusEnabled()) 
            {
                $parameters["disabled"] = "true";
            }
            else{
                if ($this->currentObject == null || $this->currentObject->WIMBA_isSendUserstatusUpdates()) 
                {
                    $parameters["checked"] = "true";
                }
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_status_appear", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine();   
            
            $this->xml->WIMBA_addSimpleLineElement("label", 
                                             get_string("settings_eboard", 'liveclassroom'),
                                              array("class"=>"LargeLabel_Width TextRegular_Right"));
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "enabled_student_eboard",
                "value" => "true",
                "id" => "enabled_student_eboard",
            );
            if ($this->currentObject != null && $this->currentObject->WIMBA_isStudentWhiteboardEnabled()) 
            {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement("label",
                                             get_string("settings_enabled_student_eboard", 'liveclassroom'));

            if($this->isLectureRoom)
            {                                 
                $this->xml->WIMBA_createLine("subPart","lectureRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("subPart hideElement","lectureRoom");
            }
            
            $this->xml->WIMBA_addSimpleLineElement("label", 
                                             get_string("settings_breakout", 'liveclassroom'), 
                                             array("class"=>"LargeLabel_Width TextRegular_Right"));
            if ($this->currentObject != null) {
                $isStudentAdmin = $this->api->WIMBA_isStudentAdmin($this->currentObject->WIMBA_getRoomId(), $this->session->WIMBA_getCourseId()."_S");
            }

            $parameters = array (
                "type" => "checkbox",
                "name" => "enabled_breakoutrooms",
                "value" => "true",
                "id" => "enabled_breakoutrooms",
                "onclick" => "WIMBA_doBreakoutEnabled()"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isBOREnabled() || $isStudentAdmin == "true") 
            {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_enabled_breakoutrooms", 'liveclassroom'));
                            
            if($this->isLectureRoom)
            {                                 
                $this->xml->WIMBA_createLine("subPart","lectureRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("subPart hideElement","lectureRoom");
            }
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "enabled_students_breakoutrooms",
                "value" => "true",
                "id" => "enabled_students_breakoutrooms",
                "class"=>"SubOption"
            );
            if ($this->currentObject != null && !$this->currentObject->WIMBA_isBOREnabled() && $isStudentAdmin == "false") 
            {
                $parameters["disabled"] = "true";
            }
            else
            {
                if ($this->currentObject != null && 
                        $this->currentObject->WIMBA_isBOREnabled() && 
                        $this->currentObject->WIMBA_isBORCarouselsPublic()) 
                {
                    $parameters["checked"] = "true";
                }
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_enabled_students_breakoutrooms", 'liveclassroom'));

            if($this->isLectureRoom)
            {                                 
                $this->xml->WIMBA_createLine("","lectureRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("hideElement","lectureRoom");
            }
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "enabled_students_mainrooms",
                "value" => "true",
                "id" => "enabled_students_mainrooms",
                "class"=>"SubOption"
            );
            if ($this->currentObject != null && !$this->currentObject->WIMBA_isBOREnabled() && $isStudentAdmin == "false") 
            {
                $parameters["disabled"] = "true";
            }
            else 
            {
                if ($this->currentObject != null && 
                        $this->currentObject->WIMBA_isBOREnabled() && 
                        $this->currentObject->WIMBA_isBORShowRoomCarousels())
                {
                    $parameters["checked"] = "true";
                }
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_enabled_students_mainrooms", 'liveclassroom'));
       
            
            if($this->isLectureRoom)
            {                                 
                $this->xml->WIMBA_createLine("","lectureRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("hideElement","lectureRoom");
            }
                            
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_presenter_console", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));

            
            $parameters = array (
                "type" => "checkbox",
                "name" => "archiveEnabled",
                "value" => "true",
                "id" => "archiveEnabled",
		"onclick" => "WIMBA_doToggleFeaturesArchiving(this, enable_archives)"
            );

            if ($this->currentObject == null || $this->currentObject->WIMBA_isArchiveEnabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_enabled_archiving", 'liveclassroom'));
            
            
            if($this->isLectureRoom)
            {                                 
               $this->xml->WIMBA_createLine("hideElement subPart","discussionRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("subPart","discussionRoom");
            }
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "appshareEnabled",
                "value" => "true",
                "id" => "enable_appshare",
                "class" => "AlignRight"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isLiveShareEnabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_enabled_appshare", 'liveclassroom'));
            
            if($this->isLectureRoom)
            {                                 
               $this->xml->WIMBA_createLine("hideElement","discussionRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("","discussionRoom");
            }
            
            $parameters = array (
                "type" => "checkbox",
                "name" => "pptEnabled",
                "value" => "true",
                "id" => "enable_ppt",
                "class" => "AlignRight"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isPptImportEnabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                                "label", 
                                get_string("settings_enabled_onfly_ppt", 'liveclassroom'));
            
            if($this->isLectureRoom)
            {                                 
               $this->xml->WIMBA_createLine("hideElement","discussionRoom");
            }
            else   
            {
                 $this->xml->WIMBA_createLine("","discussionRoom");
            }
        }
        
        if ($this->currentObject != null && $this->currentObject->WIMBA_isArchive()) 
        {
            $this->xml->WIMBA_createPanelSettings(
                            get_string("tab_title_features", 'liveclassroom'),
                            "none",
                            "Features",
                            "disabled",
                            "mainLecture-discussion",
                            "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
        else
        {
            if ($this->currentTab == "Features") 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_features", 'liveclassroom'),
                                "block",
                                "Features",
                                "current",
                                "mainLecture-discussion",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
            else 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_features", 'liveclassroom'),
                                "none",
                                "Features",
                                "",
                                "mainLecture-discussion",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
        }
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel chat of the lc settings
    */  
    function WIMBA_createLcChatPanel() {
        if ($this->currentObject == null || !$this->currentObject->WIMBA_isArchive()) 
        {
            //Panel Chat
            $parameters = array (
                "type" => "checkbox",
                "name" => "chatEnabled",
                "value" => "true",
                "id" => "chatEnabled",
                "onclick" => "WIMBA_doChangeChat()",
                "class" => "Padding_50px"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isChatEnabled()) 
            {
               $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_chat_enabled", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine();
            $parameters = array (
                "type" => "checkbox",
                "name" => "privateChatEnabled",
                "value" => "true",
                "id" => "privateChatEnabled",
                "class" => "smallSubOption"
            );
            if ($this->currentObject != null && !$this->currentObject->WIMBA_isChatEnabled()) 
            {
                $parameters["disabled"] = "true";
                $parameters["checked"] = "true";
            }
            else {
               if ($this->currentObject == null || 
                       (($this->currentObject->WIMBA_isChatEnabled() && $this->currentObject->WIMBA_isPrivateChatEnabled()))) 
               {
                   $parameters["checked"] = "true";
               }
            }
            $this->xml->WIMBA_addInputElement($parameters);
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_private_chat_enabled", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine();
            
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_private_chat_enabled_comment", 'liveclassroom'),
                            array("class" => "TextComment Padding_50px"));

            $this->xml->WIMBA_createLine();
        }
        
        if ($this->currentTab == "Chat")
        {
            $this->xml->WIMBA_createPanelSettings(
                            get_string("tab_title_chat", 'liveclassroom'),
                            "block",
                            "Chat",
                            "current",
                            "mainLecture",
                            "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
        else
        {
            if ($this->isLectureRoom === false || isset( $this->currentObject ) && $this->currentObject->WIMBA_isArchive())
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_chat", 'liveclassroom'),
                                "none",
                                "Chat",
                                "disabled",
                                "mainLecture",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
            else
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_chat", 'liveclassroom'),
                                "none",
                                "Chat",
                                "",
                                "mainLecture",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
        }
    } 
    
    /**
    * Add the necessary elements to the current xml object to render the panel acess of the lc settings
    */  
    function WIMBA_createLcAccessPanel() {
        $parameters = array (
            "type" => "checkbox",
            "name" => "accessAvailable",
            "value" => "true",
            "id" => "accessAvailable_true",
            "class" => "Padding_50px"
        );
        if ($this->currentObject == null || !$this->currentObject->WIMBA_isPreview()) 
        {
           $parameters["checked"] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_available", 'liveclassroom'), 
                        array ("for" => "accessAvailable_true"));
        
        $this->xml->WIMBA_createLine();
         
        $parameters = array (
            "type" => "button",
            "value" => get_string("settings_dial_in_informations",'liveclassroom'),
            "class" => "Padding_50px"
        );
        
        if ($this->currentObject==null || $this->currentObject->WIMBA_isArchive()) 
        {
           $parameters["disabled"] = "true";
        }
        else
        {
            $parameters["onclick"] = "showInformation('manageAction.php','".$this->prefix.$this->currentObject->WIMBA_getRoomId()."','liveclassroom');";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        
        $this->xml->WIMBA_createLine();     
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_max_user", 'liveclassroom'),
                        array("class" => "Padding_50px"));
 
        $this->xml->WIMBA_createLine("subPart");
        
        $parameters = array (
            "type" => "radio",
            "name" => "userlimit",
            "value" => "false",
            "class" => "Padding_MaxUser",
            "onclick" => "WIMBA_toggleUserlimit(false)",
            "id" => "userlimit_false",
        );

        if ($this->isArchive == "true") {
           $parameters["disabled"] = "true";
        }
        
        if (($this->currentObject == null || $this->currentObject->WIMBA_getUserLimit() == -1) && $this->isArchive != "true") {
           $parameters["checked"] = "true";
        }
        
        $this->xml->WIMBA_addInputElement($parameters);
        
        $parameters = array ("for" => "userlimit_false");
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_max_user_unlimited", 'liveclassroom'), 
                        $parameters);
        
        $this->xml->WIMBA_createLine();
      
        $parameters = array (
            "type" => "radio",
            "name" => "userlimit",
            "value" => "true",
            "class" => "Padding_MaxUser",
            "onclick" => "WIMBA_toggleUserlimit(true)",
            "id" => "userlimit_true",   
        );
        if ($this->isArchive == "true") {
            $parameters["disabled"] = "true";
        }
        
        if (($this->currentObject != null && $this->currentObject->WIMBA_getUserLimit() != -1) && $this->isArchive != "true") {
            $parameters["checked"] = "true";
        }
        
        $this->xml->WIMBA_addInputElement($parameters);
        $parameters = array ("for" => "userlimit_true");
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_max_user_limited", 'liveclassroom'), 
                        $parameters);
        
        $parameters = array (
            "type" => "text",
            "name" => "userlimitValue",
            "id" => "userlimittext",
            "style" => "style"
        );
        
        if ($this->currentObject == null || $this->currentObject->WIMBA_getUserLimit() == -1 || $this->isArchive == "true") {
            $parameters["disabled"] = "true";
        }
        
        if (($this->currentObject != null && $this->currentObject->WIMBA_getUserLimit() != -1) && $this->isArchive != "true") {
            $parameters["value"] = $this->currentObject->WIMBA_getUserLimit();
        }
        
        $this->xml->WIMBA_addInputElement($parameters);
        
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "checkbox",
            "name" => "guests",
            "id" => "guestAcess_value",
            "value" => "true",
            "class" => "Padding_50px",
            "onclick" => "$(\"launcher_link_row\").removeClassName(\"hideElement\")"
        );
        
        // If this is a new room the default value comes from the server setting
        if ($this->currentObject == null) {
            if ($this->config['guest_access']) {
                $parameters["checked"] = "true";
            }
        } else { //existing room, get value from room attribute
            if ($this->api->WIMBA_isGuestAuthorized($this->currentObject->WIMBA_getRoomId())) {
                $parameters["checked"] = "true";
            }
        }

        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_enabled_guest", 'liveclassroom'));

        $this->xml->WIMBA_createLine();
         
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_roomId_guest", 'liveclassroom'),
                        array( "class" => "Padding_50px") );
                        
        $parameters = array (
            "type" => "text",
            "name" => "launcher_link",
            "id" => "launcher_link",
            "style" => "width:400px"
        );
        if ($this->currentObject != null) {
            $parameters["value"] = $this->currentObject->WIMBA_getGuestURL();
        } else {
            $parameters["value"] = '';
        }
        $this->xml->WIMBA_addInputElement($parameters);
         
        $parameters = array (
            "colspan" => 3,
            "id" => "launcher_link_row"
        );
        if ($this->currentObject == null || 
               ($this->currentObject != null && !$this->api->WIMBA_isGuestAuthorized($this->currentObject->WIMBA_getRoomId()))) {
            $style="hideElement";
        }
        
        $this->xml->WIMBA_createLine($style,"","launcher_link_row");
        
        $this->xml->WIMBA_addSimpleLineElement(
                       "span", 
                       get_string("settings_guest_access_comment1", 'liveclassroom'),
                       array("class"=>"TextComment"));

                       
        $this->xml->WIMBA_createLine("Padding_50px");
        
        $this->xml->WIMBA_addSimpleLineElement(
                       "span", 
                       get_string("settings_guest_access_comment2", 'liveclassroom'),
                       array("class"=>"TextComment"));

                       
        $this->xml->WIMBA_createLine("Padding_50px");
        
        if ($this->currentTab == "Access") {
           $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_access", 'liveclassroom'), 
                        "block",
                        "Access",
                        "current",
                        "mainLecture",
                        "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        } else {
           $this->xml->WIMBA_createPanelSettings(
                           get_string("tab_title_access", 'liveclassroom'), 
                           "none", 
                           "Access", 
                           "", 
                           "mainLecture", 
                           "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
        }
    }
    

 	/**
    * Add the necessary elements to the current xml object to render the panel features of the lc settings
    */  
    function WIMBA_createLcAudioSettingsPanel() {
    
            if ($this->currentObject != null && $this->currentObject->WIMBA_isArchive()) {
           
                $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_download_media", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));
                $this->xml->WIMBA_addSimpleLineElement(
                            "img", 
                            "",
                            array("src"=>"lib/web/pictures/items/listitem-mp3.png",
                                  "border"=>"0",
                                  "width"=>"27px", 
                                  "height"=>"13px",
                                  "alt"=>"Download MP3",
                                  "title"=>"Download MP3",
                               	  "style"=>"vertical-align:middle"
                            ));
                $this->xml->WIMBA_addSimpleLineElement(
                            "link", 
                            "Download Archive in MP3 Format",
                            array("href"=>"javascript:downloadAudioFile('manageAction.php','getMp3Status','".$this->currentObject->WIMBA_getRoomId()."')",
                               	  "style"=>"padding-left:5px"
                            ));                            
                $this->xml->WIMBA_createLine();             
              
                $this->xml->WIMBA_addSimpleLineElement(
                            "img", 
                            "",
                            array("src"=>"lib/web/pictures/items/listitem-mp4.png",
                                  "border"=>"0",
                                  "width"=>"27px", 
                                  "height"=>"13px",
                                  "alt"=>"Download MP4",
                                  "title"=>"Download MP4",
                               	  "style"=>"vertical-align:middle",
                               	  "class"=>"AlignRight"
                            ));
               $this->xml->WIMBA_addSimpleLineElement(
                            "link", 
                            "Download Archive in MP4 Format",
                            array("href"=>"javascript:downloadAudioFile('manageAction.php','getMp4Status','".$this->currentObject->WIMBA_getRoomId()."')",
                               	  "style"=>"padding-left:5px"
                            ));                            
               $this->xml->WIMBA_createLine();   
  
            }
           
            
            $this->xml->WIMBA_addSimpleLineElement(
                            "label", 
                            get_string("settings_archive_availaibility", 'liveclassroom'),
                            array("class"=>"LargeLabel_Width TextRegular_Right"));
                            
            $parameters = array (
                "type" => "checkbox",
                "name" => "can_download_mp3",
                "value" => "true",
                "id" => "can_download_mp3"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isDownloadMP3Enabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);
			  
			$parameters = array ("for"=>"can_download_mp3");
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_allow_download_mp3_".$this->resourceType, 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine();
 			
			$parameters = array (
                "type" => "checkbox",
                "name" => "can_download_mp4",
                "value" => "true",
                "id" => "can_download_mp4",
				"class" => "AlignRight"
            );
            if ($this->currentObject == null || $this->currentObject->WIMBA_isDownloadMP4Enabled()) {
                $parameters["checked"] = "true";
            }
            $this->xml->WIMBA_addInputElement($parameters);

			$parameters = array ("for"=>"can_download_mp4");
            $this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_allow_download_mp4_".$this->resourceType, 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine();            
			
			$parameters = array ("class" => "LargeLabel_Width TextRegular_Right Bold");
 			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4Settings", 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine(); 
   			
			$parameters = array ("style" => "width:450px;display:block");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4SettingsSetence_".$this->resourceType, 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine("CommentLabel"); 

			$parameters = array ("class" => "Bold");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4SettingsComment", 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine("Padding_200px"); 
  
			$parameters = array (
            	"type" => "radio",
           		"value" => "content_focus_with_video",
            	"id" => "mp4_media_priority_content_focus_with_video",
           		"name" => "mp4_media_priority",
           		"onclick" => "WIMBA_doChangeMediaPriority()",
            	"class" => "AlignRight"
        	);
        	if ($this->currentObject == null || ($this->currentObject->WIMBA_getMp4MediaPriority() == "content_focus_no_video" 
											|| $this->currentObject->WIMBA_getMp4MediaPriority() == "content_focus_with_video")) 
        	{
           		$parameters["checked"] = "true";
        	}
        	$this->xml->WIMBA_addInputElement($parameters);
        	$parameters = array ("for"=>"mp4_media_priority_content_focus_with_video", "class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4Settings_content_focus_no_video", 'liveclassroom'),$parameters);
            
            $this->xml->WIMBA_createLine(); 

		 	$parameters = array (
                "type" => "checkbox",
                "name" => "mp4_media_priority_content_include_video",
                "value" => "true",
                "id" => "mp4_media_priority_content_include_video",
                "class" => "SubOption grey"
            );
            
            if ($this->currentObject != null && $this->currentObject->WIMBA_getMp4MediaPriority() == "content_focus_no_video" ) 
            {
                $parameters["checked"] = "true";
                $parameters["class"] = "SubOption black";
            }
            if ($this->currentObject != null && $this->currentObject->WIMBA_getMp4MediaPriority() != "content_focus_no_video"  && $this->currentObject->WIMBA_getMp4MediaPriority() != "content_focus_with_video" ) 
            {
                $parameters["disabled"] = "true";
            }
			
			$this->xml->WIMBA_addInputElement($parameters);
			
			$parameters = array ("id"=>"mp4_media_priority_content_include_video_label", "for"=>"mp4_media_priority_video_focus", "class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4Settings_doNotIncludeVideo", 'liveclassroom'),$parameters);
            
            $this->xml->WIMBA_createLine("Reduce_space");
			
			
			
			$parameters = array (
            	"type" => "radio",
           		"value" => "video_focus",
            	"id" => "mp4_media_priority_video_focus",
           		"name" => "mp4_media_priority",
           		"onclick" => "WIMBA_doChangeMediaPriority()",
            	"class" => "AlignRight"
        	);
        	if ($this->currentObject != null && $this->currentObject->WIMBA_getMp4MediaPriority() == "video_focus" ) 
        	{
           		$parameters["checked"] = "true";
        	}
        	$this->xml->WIMBA_addInputElement($parameters);
        	$parameters = array ("for"=>"mp4_media_priority_video_focus", "class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4Settings_video_focus", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine();

			$parameters = array("class"=>"Bold");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4encodingOptions", 'liveclassroom'),
                            $parameters);
            
            $this->xml->WIMBA_createLine("Padding_200px"); 

			$parameters = array (
                "type" => "radio",
                "name" => "mp4_encoding_type",
                "value" => "streaming",
                "id" => "mp4_encoding_type_streaming",
			    "class"=>"AlignRight"
            );
          
            if ($this->currentObject != null && $this->currentObject->WIMBA_getMp4EncodingType() == "streaming" ) 
            {
                $parameters["checked"] = "true";
            }
			$this->xml->WIMBA_addInputElement($parameters);

 			$parameters = array ("class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4encodingOptions_streaming", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine(""); 

			$parameters = array (
                "type" => "radio",
                "name" => "mp4_encoding_type",
                "value" => "standard",
                "id" => "mp4_encoding_type_standard",
			    "class"=>"AlignRight"
            );
            
            if ($this->currentObject == null || $this->currentObject->WIMBA_getMp4EncodingType() == "standard" ) 
            {
                $parameters["checked"] = "true";
            }
			$this->xml->WIMBA_addInputElement($parameters);

 			$parameters = array ("class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4encodingOptions_standard", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine("ReduceSpace") 	;			
			
			$parameters = array (
                "type" => "radio",
                "name" => "mp4_encoding_type",
                "value" => "high_quality",
                "id" => "mp4_encoding_type_high",
			    "class"=>"AlignRight"
            );
            
            if ($this->currentObject != null && $this->currentObject->WIMBA_getMp4EncodingType() == "high_quality" ) 
            {
                $parameters["checked"] = "true";
            }
			$this->xml->WIMBA_addInputElement($parameters);

 			$parameters = array ("class" => "userlimit_true");
			$this->xml->WIMBA_addSimpleLineElement(
                            "label",
                            get_string("settings_mp4encodingOptions_highQuality", 'liveclassroom'));
            
            $this->xml->WIMBA_createLine("ReduceSpace");



		
        
 
            if ($this->currentTab == "MP3/MP4") 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_archive_settings", 'liveclassroom'),
                                "block",
                                "MP3/MP4",
                                "current",
                                "mainLecture-discussion",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
            else 
            {
                $this->xml->WIMBA_createPanelSettings(
                                get_string("tab_title_archive_settings", 'liveclassroom'),
                                "none",
                                "MP3/MP4",
                                "",
                                "mainLecture-discussion",
                                "WIMBA_editSettings(\"generateXmlSettingsPanel.php\",\"all\")");
            }
        
    }
    

    /**
    * Add the necessary elements to the current xml object to render the panel Advanced of the lc settings
    */  
    function WIMBA_createLcAdvancedPanel() {
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_advanced_comment_1", 'liveclassroom'));
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_advanced_comment_2", 'liveclassroom'));
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement("space");
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "button",
            "value" => get_string("settings_advanced_room_settings_button",'liveclassroom'),
            "onclick" => "WIMBA_openRoomSettings('manageAction.php')"
        );
        $this->xml->WIMBA_addInputElement($parameters);
    
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "button",
            "value" => get_string("settings_advanced_media_settings_button",'liveclassroom'),
            "onclick" => "WIMBA_openMediaSettings('manageAction.php')"
        );
        $this->xml->WIMBA_addInputElement($parameters);
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_advanced", 'liveclassroom'),
                        "none", 
                        "Advanced", 
                        "", 
                        "all", 
                        "saveSettings(\"manageAction.php\",\"".$this->currentObject->WIMBA_getRoomId()."\")");
                        
        $this->xml->WIMBA_createAdvancedPopup(
                        get_string("advancedPopup_title", 'liveclassroom'), 
                        get_string("advancedPopup_sentence", 'liveclassroom'));
    }
    
    
    
/***********************************
*
*           Voice Tools
* 
* ***********************************/
    
    /**
    * Add the necessary elements to the current xml object to render the first part of the panel Info
    */ 
    function WIMBA_createGeneralInfoPanelStart($update) {
    
        $this->xml->WIMBA_addCustomLineElement(
                        "*",
                        "required", 
                        get_string('title', 'voiceemail'),
                        array ("class" => "LargeLabel_Width TextRegular_Right"));

        $url = "manageAction.php";
        $action = "update";
        $rid = "";

        if ($update != "update") {
            $action = "create";
            $rid = $this->id;
        } else {
            $rid = $this->currentObject->WIMBA_getRid();
        }

        $parameters = array (
            "id" => "longname",
            "maxlength" => "50",
            "name" => "longname",
            "type" => "text",
            "onkeypress" => "javascript:submitenter(this,event,'$url','$action','$rid')",
        );
        if (isset ($this->currentObject)) 
        {
            $parameters["value"] = $this->currentObject->WIMBA_getTitle();
        }
        else 
        {
            $parameters["value"] = "";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        "*", 
                        array ("class" => "required"));
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_required", 'voiceemail'));

        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('description', 'voiceemail'),
                         array ("class" => "LargeLabel_Width TextRegular_Right"));
        
        $parameters = array (
            "name" => "description",
            "id" => "description",
            "rows" => "4",
            "cols" => "30"
        );
        
        $display = "";
        if (isset ($this->currentObject)) 
        {
            $display = $this->currentObject->WIMBA_getDescription();
        }
        $this->xml->WIMBA_addTextAreaElement($parameters,$display);
        
        $this->xml->WIMBA_createLine();
    }
     

    /**
    * Add the necessary elements to the current xml object to render the second part of the panel Info (voice board)
    */ 
    function WIMBA_createVBInfoPanel() {
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('type', 'voiceemail'),
                        array("class"=>"LargeLabel_Width TextRegular_Right"));
        
        $parameters = array (
            "type" => "radio",
            "value" => "student",
            "id" => "led_student",
            "name" => "led",
            "onclick" => "WIMBA_managePublicState(\"show_compose\",\"public\")"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getFilter() == "false") 
        {
           $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('public', 'voiceemail'), 
                        array ("for" => "led_student","class" => "top"));
        $this->xml->WIMBA_addSimpleLineElement("br", NULL);
        $this->xml->WIMBA_addSimpleLineElement(
                        "span", 
                        get_string('public_comment', 'voiceemail'), 
                        array ("class" => "TextComment AlignRight"));
        
        $this->xml->WIMBA_createLine();
             
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "show_compose",
            "name" => "show_compose",
            "class" =>"AlignRight"
        );
        if (!isset ($this->currentObject)) 
        {
            $parameters['checked'] = "true";
        }
        else 
        {
           if ($this->currentObjectOptions->WIMBA_getFilter() == "false") 
           {
               if ($this->currentObjectOptions->WIMBA_getShowCompose() == "true") 
               {
                   $parameters['checked'] = "true";
               }
            }
            else 
            {   
               $parameters['disabled'] = "true";
            }
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('start_thread', 'voiceemail'));
        
        $this->xml->WIMBA_createLine("subOption");
       
        $parameters = array (
            "type" => "radio",
            "value" => "instructor",
            "id" => "led_instructor",
            "name" => "led",
            "onclick" => "WIMBA_managePublicState(\"show_compose\",\"private\")",
            "class" => "AlignRight"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getFilter() == "true") 
        {
           $parameters["checked"] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('private', 'voiceemail'), 
                        array ("for" => "led_instructor","valign" => "top"));
        $this->xml->WIMBA_addSimpleLineElement("br", NULL);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('private_comment', 'voiceemail'), 
                        array ("class" => "TextComment AlignRight"));
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string('tab_title_Info', 'voiceemail'), 
                        "block", 
                        "Info", 
                        "current", 
                        "all");
    }
    
    /**
    * Add the necessary elements to the current xml object to render the second part of the panel Info (voice presentation)
    */ 
    function WIMBA_createVPInfoPanel() {
        $this->xml->WIMBA_addSimpleLineElement("space");
        
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "show_reply",
            "name" => "show_reply",
            "class" => "AlignRight"
        );
        if (!isset ($this->currentObjectOptions) || $this->currentObjectOptions->WIMBA_getShowReply() == "true") 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('comment_slide', 'voiceemail'));
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement("space");
       
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "filter",
            "name" => "filter",
            "class" => "AlignRight"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getFilter() == "true") 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('private_slide', 'voiceemail'));
        $this->xml->WIMBA_createLine();

        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('private_slide_comment', 'voiceemail'), 
                        array ("class" => "AlignRight TextComment"));
        $this->xml->WIMBA_createLine();
  
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_Info", 'voiceemail'), 
                        "block", 
                        "Info", 
                        "current", 
                        "all");
    }

     /**
    * Add the necessary elements to the current xml object to render the second part of the panel Info (podcaster)
    */    
    function WIMBA_createPCInfoPanel() {
        $this->xml->WIMBA_addSimpleLineElement("space");
       
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "show_compose",
            "name" => "show_compose",
            "class" => "AlignRight"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getShowCompose() == "true") 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", 
                                         get_string('post_podcast', 'voiceemail'));
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_Info", 'voiceemail'), 
                        "block", 
                        "Info", 
                        "current", 
                        "all");
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel Media (voice presentation and voice board)
    */ 
    function WIMBA_createVBVPMediaPanel() {
        $this->xml->WIMBA_addSimpleLineElement("label",
                                         get_string('audioquality', 'voiceemail'),
                                         array("class"=>"LargeLabel_Width TextRegular_Right"));
        $this->xml->WIMBA_createOptionElement(
                        "audio_format", 
                        "audio_format", 
                        $this->WIMBA_createOptionAudioSettings());
     
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_max_message", 'voiceemail'),
                        array("class"=>"LargeLabel_Width TextRegular_Right"));

        $this->xml->WIMBA_createOptionElement(
                        "max_length", 
                        "max_length", 
                        $this->WIMBA_createOptionMaxLength());
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string('tab_title_media', 'voiceemail'), 
                        "none", 
                        "Media", 
                        "", 
                        "all");
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel Media (podcaster)
    */ 
    function WIMBA_createPCMediaPanel() {
        $this->xml->WIMBA_addSimpleLineElement(
                        "label",
                        get_string("settings_audio", 'voiceemail'),
                        array("class"=>"LargeLabel_Width TextRegular_Right"));

        $this->xml->WIMBA_createOptionElement(
                        "audio_format", 
                        "audio_format", 
                        $this->WIMBA_createOptionAudioSettings());
       
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string("settings_auto_publish_podcast", 'voiceemail'),
                        array("class"=>"LargeLabel_Width TextRegular_Right"));
      
        $this->xml->WIMBA_createOptionElement(
                        "delay", 
                        "delay", 
                        $this->WIMBA_createOptionDelay());
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_media", 'voiceemail'), 
                        "none", 
                        "Media", 
                        "", 
                        "all");
    }    
      /**
    * Add the necessary elements to the current xml object to render the panel Features (voice board)
    */     
    function WIMBA_createVBFeaturesPanel() {
        global $DB;
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "short_title",
            "name" => "short_title",
            "class" => "Padding_50px"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getShortTitle() == 'true') 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('short_message', 'voiceemail'));
        
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "chrono_order",
            "name" => "chrono_order",
            "class" => "Padding_50px"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getChronoOrder() == 'true') 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('chrono_order', 'voiceemail'));
        
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "show_forward",
            "name" => "show_forward",
            "class" => "Padding_50px"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getShowForward() == 'true') 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('show_forward', 'voiceemail'));
        
        $this->xml->WIMBA_createLine("Padding_top_25px");
        
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "show_reply",
            "name" => "show_reply",
            "class" => "Padding_50px"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getShowReply() == 'true') 
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('settings_show_reply', 'voiceemail'));
        
        $this->xml->WIMBA_createLine();
        if(WIMBA_isGradebookAvailable()){
          $disabledPointsPossible = false;
          $parameters = array (
              "type" => "checkbox",
              "value" => "true",
              "id" => "grade",
              "name" => "grade",
              "onclick" => "WIMBA_managePointsPossible()",
              "class" => "Padding_50px"
          );
          if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getGrade() == 'true') 
          {
              $parameters['checked'] = "true";
          }else
          {
              
              $disabledPointsPossible=true;
          }
          if(isset ($this->currentObject)){
            $activities = $DB->get_records("voiceemail", array("rid" => $this->currentObject->WIMBA_getRid()));
            if(empty($activities)){
             $parameters['disabled'] = "true";
            }
          }else{
             $parameters['disabled'] = "true";
          }
          $this->xml->WIMBA_addInputElement($parameters);
          $this->xml->WIMBA_addSimpleLineElement("label", get_string('grade_settings', 'voiceemail'));
          
          $this->xml->WIMBA_createLine("Padding_top_25px");
          
          $this->xml->WIMBA_addSimpleLineElement(
                          "label", 
                          get_string('points_possible', 'voiceemail'), 
                          array ("for" => "points_possible","class"=>"Padding_50px"));
                          
           $parameters = array (
              "type" => "text",
              "id" => "points_possible",
              "name" => "points_possible",
          );
          
          if (isset ($this->currentObject)) 
          {
              $parameters['value'] = $this->currentObjectOptions->WIMBA_getPointsPossible();
          }
          if($disabledPointsPossible === true){
              $parameters['disabled'] = true;
          }
          
          $this->xml->WIMBA_addInputElement($parameters);
          
          $this->xml->WIMBA_createLine();
        }
        $this->xml->WIMBA_createPanelSettings(
                        get_string('tab_title_features', 'voiceemail'),
                        "none", 
                        "Features", 
                        "",       
                        "all");
    }    

      /**
    * Add the necessary elements to the current xml object to render the panel Features (podcaster)
    */     
    function WIMBA_createPCFeaturesPanel() {
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "short_title",
            "name" => "short_title",
            "class" => "Padding_50px"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getShortTitle() == 'true') 
        {
           $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement("label", get_string('short_message', 'voiceemail'));
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string("tab_title_features", 'voiceemail'),
                        "none",
                        "Features",
                        "",
                        "all");
    }
    
    /**
    * Add the necessary elements to the current xml object to render the panel Access
    */
    function WIMBA_createVTAccessPanel() {
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "accessAvailable",
            "name" => "accessAvailable",
            "onclick" => "javascript:WIMBA_manageAvailibility()",
            "class" => "Padding_50px"
        );
        
        if (!isset ($this->currentObjectInformations) || $this->currentObjectInformations->availability == "1")
        {
            $parameters['checked'] = "true";
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('available', 'voiceemail'), 
                        array ("for" => "acessAvailable"));
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_addSimpleLineElement("space");
        $this->xml->WIMBA_createLine();
        
        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "start_date",
            "name" => "start_date",
            "onclick" => "javascript:WIMBA_manageAvailibilityDate(\"start\")",
            "class" => "Padding_50px"
        );
        if (isset ($this->currentObjectInformations) && $this->currentObjectInformations->availability == "0") 
        {
            $parameters['disabled'] = "true";
        }
        else
        {
            if (isset ($this->currentObjectInformations) && $this->currentObjectInformations->start_date != "-1") 
            {
                $parameters['checked'] = "true";
            }
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('start_date', 'voiceemail'), 
                        array ("for" => "start_date"));
        
        $this->xml->WIMBA_createLine();
       
        if ($this->currentObjectInformations != null) 
        {
            $optionsMonth = $this->WIMBA_createSelectMonth(
                                        $this->startSelect, 
                                        date('m', $this->currentObjectInformations->start_date));
        }
        else
        {
            $optionsMonth = $this->WIMBA_createSelectMonth();
        }
        if ($this->startSelect === false || $this->currentObject == null) 
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_month", 
                            "start_month_field", 
                            $optionsMonth, 
                            "true",
                            "Padding_50px");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_month", 
                            "start_month_field", 
                            $optionsMonth);
        }
       
        if ($this->currentObjectInformations != null)
        {
            $optionsDay = $this->WIMBA_createSelectDay(
                                    $this->startSelect, 
                                    date('d', $this->currentObjectInformations->start_date));
        }
        else
        {
            $optionsDay = $this->WIMBA_createSelectDay();
        }
        if ($this->startSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_day", 
                            "start_day_field", 
                            $optionsDay, 
                            "true");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_day", 
                            "start_day_field",  
                            $optionsDay);
        }
    
        if ($this->currentObjectInformations != null)
        {
            $optionsYear = $this->WIMBA_createSelectYear(
                                    $this->startSelect, 
                                    date('Y', $this->currentObjectInformations->start_date));
        }
        else
        {
            $optionsYear = $this->WIMBA_createSelectYear();
        }
        if ($this->startSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_year", 
                            "start_year_field", 
                            $optionsYear, 
                            "true");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_year", 
                            "start_year_field", 
                            $optionsYear);
        }

        if ($this->currentObjectInformations != null)
        {
            $optionsHour = $this->WIMBA_createSelectHour(
                                    $this->startSelect, 
                                    date('G', $this->currentObjectInformations->start_date));
        }
        else
        {
            $optionsHour = $this->WIMBA_createSelectHour();
        }
        if ($this->startSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_hr", 
                            "start_hr_field", 
                            $optionsHour, 
                            "true",
                            "Padding_50px");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_hr", 
                            "start_hr_field", 
                            $optionsHour);
        }
      
        if ($this->currentObjectInformations != null)
        {
            $optionsMinute = $this->WIMBA_createSelectMin(
                                        $this->startSelect, 
                                        date('i', $this->currentObjectInformations->start_date));
        }
        else
        {
            $optionsMinute = $this->WIMBA_createSelectMin();
        }
        if ($this->startSelect === false || $this->currentObject == null)
        {   
            $this->xml->WIMBA_createOptionElement(
                            "start_min", 
                            "start_min_field", 
                            $optionsMinute, 
                            "true");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "start_min", 
                            "start_min_field", 
                            $optionsMinute);
        }
        
        $this->xml->WIMBA_createLine();

        $parameters = array (
            "type" => "checkbox",
            "value" => "true",
            "id" => "end_date",
            "name" => "end_date",
            "onclick" => "javascript:WIMBA_manageAvailibilityDate(\"end\")",
            "class" => "Padding_50px"
        );
        
        if (isset ($this->currentObjectInformations) && $this->currentObjectInformations->availability == "0") 
        {
            $parameters['disabled'] = "true";
        }
        else 
        {
            if (isset ($this->currentObjectInformations) && $this->currentObjectInformations->end_date != "-1") {
                $parameters['checked'] = true;
            }
        }
        $this->xml->WIMBA_addInputElement($parameters);
        $this->xml->WIMBA_addSimpleLineElement(
                        "label", 
                        get_string('end_date', 'voiceemail'), 
                        array ("for" => "end_date"));     
     
        $this->xml->WIMBA_createLine();
        
        if ($this->currentObjectInformations != null) 
        {
            $optionsMonth = $this->WIMBA_createSelectMonth(
                                        $this->endSelect, 
                                        date('m', $this->currentObjectInformations->end_date));
        }
        else
        {
            $optionsMonth = $this->WIMBA_createSelectMonth();
        }
        if ($this->endSelect === false || $this->currentObject == null) 
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_month", 
                            "end_month_field", 
                            $optionsMonth, 
                            "true",
                            "Padding_50px");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_month", 
                            "end_month_field", 
                            $optionsMonth,"false",$style);
        }
       
        if ($this->currentObjectInformations != null)
        {
            $optionsDay = $this->WIMBA_createSelectDay(
                                    $this->endSelect, 
                                    date('d', $this->currentObjectInformations->end_date));
        }
        else
        {
            $optionsDay = $this->WIMBA_createSelectDay();
        }
        if ($this->endSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_day", 
                            "end_day_field", 
                            $optionsDay, 
                            "true");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_day", 
                            "end_day_field", 
                            $optionsDay);
                                        
        }
        
         if ($this->currentObjectInformations != null)
        {
            $optionsYear = $this->WIMBA_createSelectYear(
                                    $this->endSelect, 
                                    date('Y', $this->currentObjectInformations->end_date));
        }
        else
        {
            $optionsYear = $this->WIMBA_createSelectYear();
        }
        if ($this->endSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_year", 
                            "end_year_field", 
                            $optionsYear, 
                            "true");
        }
        else
        {   
            $this->xml->WIMBA_createOptionElement(
                            "end_year", 
                            "end_year_field", 
                            $optionsYear);
                                
        }
      
         if ($this->currentObjectInformations != null)
        {
            $optionsHour = $this->WIMBA_createSelectHour(
                                    $this->endSelect, 
                                    date('G', $this->currentObjectInformations->end_date));
        }
        else
        {
            $optionsHour = $this->WIMBA_createSelectHour();
        }
        if ($this->endSelect === false || $this->currentObject == null) 
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_hr", 
                            "end_hr_field", 
                            $optionsHour, 
                            "true",
                            "Padding_50px");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_hr", 
                            "end_hr_field", 
                             $optionsHour);
        }
        
        if ($this->currentObjectInformations != null)
        {
            $optionsMinute = $this->WIMBA_createSelectMin(
                                        $this->endSelect, 
                                        date('i', $this->currentObjectInformations->end_date));
        }
        else
        {
            $optionsMinute = $this->WIMBA_createSelectMin();
        }
        
        if ($this->endSelect === false || $this->currentObject == null)
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_min", 
                            "end_min_field", 
                            $optionsMinute, 
                            "true");
        }
        else
        {
            $this->xml->WIMBA_createOptionElement(
                            "end_min", 
                            "end_min_field", 
                           $optionsMinute);
        }
        
        $this->xml->WIMBA_createLine();
        
        $this->xml->WIMBA_createPanelSettings(
                        get_string('access', 'voiceemail'), 
                        "none", 
                        "Access", 
                        "", 
                        "all");
    }

    /*
    * Fill an array with the different audio options
    */    
    function WIMBA_createOptionAudioSettings() {
        $option = array (
            "value" => "spx_8_q3",
            "display" => get_string('basicquality','voiceemail')
        );
        if (isset ($this->currentObject) && $this->currentObjectAudioFormat->WIMBA_getName() == "spx_8_q3") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "spx_16_q4",
            "display" => get_string('standardquality','voiceemail')
        );
        if (!isset ($this->currentObject) || $this->currentObjectAudioFormat->WIMBA_getName() == "spx_16_q4") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "spx_16_q6",
            "display" => get_string('goodquality','voiceemail')
        );
        if (isset ($this->currentObject) && $this->currentObjectAudioFormat->WIMBA_getName() == "spx_16_q6") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "spx_32_q8",
            "display" => get_string('superiorquality','voiceemail')
        );
        if (isset ($this->currentObject) && $this->currentObjectAudioFormat->WIMBA_getName() == "spx_32_q8") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        return $options;
    }
    
    /*
     * Fill an array with the different delay options
     */
    function WIMBA_createOptionDelay() {
        $options = array ();
        $option = array (
            "value" => "-1",
            "display" => "0 s"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "-1") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "60000",
            "display" => "1 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "60000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "120000",
            "display" => "2 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "120000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "180000",
            "display" => " 3 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "180000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "300000",
            "display" => "5 min"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getDelay() == "300000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "600000",
            "display" => "10 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "600000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "1200000",
            "display" => "20 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "1200000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "1800000",
            "display" => "30 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "1800000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
            "value" => "3600000",
            "display" => "60 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getDelay() == "3600000") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        return $options;
    }

    /*
     * Return an array with the different max length options
     * We use this array to generate the xml of the corresponding drop-down list
     */    
    function WIMBA_createOptionMaxLength() {
        $options = array ();
        $option = array (
        "value" => "15",
        "display" => "15 s"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "15") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "30",
        "display" => "30 s"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "30") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "60",
        "display" => "1 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "60") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "180",
        "display" => "3 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "180") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "300",
        "display" => "5 min"
        );
        if (!isset ($this->currentObject) || $this->currentObjectOptions->WIMBA_getMaxLength() == "300") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "600",
        "display" => "10 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "600") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        $option = array (
        "value" => "1200",
        "display" => "20 min"
        );
        if (isset ($this->currentObject) && $this->currentObjectOptions->WIMBA_getMaxLength() == "1200") {
            $option["selected"] = "true";
        }
        $options[] = $option;
        
        return $options;
    }
    
    /* Return an array with 
     * We use this array to generate the xml of the corresponding drop-down list
     * @param selected : 
     * @param year : 
     */    
    function WIMBA_createSelectYear($selected = false, $year = NULL) {
        $options = array ();
        $option = array (
            "value" => "0",
            "selected" => "true",
            "display" => "--"
        );
        $options[] = $option;
        for ($i = date("Y"); $i <= date("Y") + 10; $i++) {
            $option = array (
                "value" => $i,
                "display" => $i
            );
            
            if (isset ($this->currentObject) && $selected == true && $year == $i) {
                $option['selected'] = "true";
            }
            $options[] = $option;
        }
        return $options;
    }
    
    function WIMBA_createSelectMonth($selected = false, $month = NULL) {
        $options = array ();
        $option = array (
            "value" => "0",
            "selected" => "true",
            "display" => "--"
        );
        $options[] = $option;
        for ($i = 1; $i <= 12; $i++) {
            $option = array (
                "value" => $i,
                "display" => get_string("month" . $i,
                "voiceemail"
            ));
            if (isset ($this->currentObject) && $selected == true && $month == $i) {
              $option['selected'] = "true";
            }
            $options[] = $option;
        }
        return $options;
    }
    
    function WIMBA_createSelectDay($selected = false, $day = NULL) {
        $options = array ();
        $option = array (
            "value" => "0",
            "selected" => "true",
            "display" => "--"
        );
        $options[] = $option;
        for ($i = 1; $i <= 31; $i++) {
            $option = array (
                "value" => $i,
                "display" => $i
            );
            if (isset ($this->currentObject) && $selected == true && $day == $i) {
               $option['selected'] = "true";
            }
            $options[] = $option;
        }
        return $options;
    }
    
    function WIMBA_createSelectHour($selected = false, $hr = NULL) {
        $options = array ();
        $option = array (
                    "value" => "0",
                    "selected" => "true",
                    "display" => "--"
        );
        $options[] = $option;
        for ($i = 1; $i < 25; $i++) {
            $option = array (
                "value" => $i,
                "display" => date("h A",mktime($i-1,1,1,1,1,2007))
            );
            if (isset ($this->currentObject) && $selected == true && $hr == $i ) {
              $option['selected'] = "true";
            }
            $options[] = $option;
        }
        return $options;
    }
    
 
    function WIMBA_createSelectMin($selected = false, $min = NULL) {
        $options = array ();
        $option = array (
            "value" => "0",
            "selected" => "true",
            "display" => "--"
        );
        $options[] = $option;
        for ($i = 1; $i < 61; $i = $i +5) {
            $option = array (
                "value" => $i,
                "display" => $i-1
            );
            if (isset ($this->currentObject) && $selected == true && $min == $i) {
                $option['selected'] = "true";
            }
            $options[] = $option;
        }
        return $options;
    }
    
    /*
     *  List all the rooms and their archives associed + the orphaned archives list
     *
     * 
     **/
    function WIMBA_getListLiveClassroom() {
        global $DB; 
        $liveclassroom = array ();
        $archives = array ();
        $rooms = $this->api->WIMBA_getRooms($this->session->WIMBA_getCourseId() . "_T");
        if ($rooms === false) {
            return false;
        }

        foreach ($rooms as $room) {
            $id = $room->WIMBA_getRoomId();
            $name = $room->WIMBA_getLongname();
            $preview = $room->WIMBA_isPreview();
            if ($room->WIMBA_isArchive()) {
                $canDownloadMp3 = $room->WIMBA_isDownloadMP3Enabled();
                $canDownloadMp4 = $room->WIMBA_isDownloadMP4Enabled();
                $archive = new WIMBA_XmlArchive(
                                    $id, 
                                    $name, 
                                    $preview, 
                                    $canDownloadMp3,
                                    $canDownloadMp4,
                                    "manageAction.php", 
                                    $this->session->url_params . "&time=" . time() . "&action=launch");     
                                            
                $archive->WIMBA_setTooltipAvailability(get_string("tooltipLC_".$preview."_student","liveclassroom"));
                $archive->WIMBA_setTooltipDial(get_string("tooltip_dial","liveclassroom"));
                list ($roomId, $other) = preg_split('/_/', $id);
                $archive->WIMBA_setParent($roomId);
                $archives[$roomId][] = $archive;
            } else {
                if ($this->session->WIMBA_isInstructor() || !$this->session->WIMBA_isInstructor()  && $preview == 0) {
                    $xmlRoom = new WIMBA_XmlRoom(
                                        $id, 
                                        $name, 
                                        true, 
                                        $preview, 
                                        null, 
                                        "manageAction.php", 
                                        $this->session->url_params . "&time=" . time() . "&action=launch");

                    if ( $lcs = $DB->get_records("liveclassroom", array("type" => $this->prefix.$id))) {
                      $activities = array();
                      foreach($lcs as $lc) {
                        array_push($activities, $lc->name);
                      }
                      $xmlRoom->WIMBA_setLinkedActivities(implode("|",$activities));
                    } else {
                }
                                        
                    $xmlRoom->WIMBA_setTooltipAvailability(get_string("tooltipLC_".$preview."_student","liveclassroom"));
                    $xmlRoom->WIMBA_setTooltipDial(get_string("tooltip_dial","liveclassroom"));
                    $liveclassroom[$id] = $xmlRoom;
                }
            }
        }
        foreach ($archives as $key => $value) {
            if (array_key_exists($key, $liveclassroom)) {
                if ($this->session->WIMBA_isInstructor()) {
                    $liveclassroom[$key]->WIMBA_setArchive($archives[$key]);
                    $listArchives = $archives[$key];
                    for ($i = 0; $i < count($listArchives); $i++) {
                        if ($listArchives[$i]->WIMBA_getAvailability() == "available" 
                            && $liveclassroom[$key]->WIMBA_getAvailability() == "unavailable") {
                            $orphaned = new WIMBA_XmlOrphanedArchive($listArchives[$i],
                                                                     "student",
                                                                     "manageAction.php",
                                                                     $this->session->url_params . "&time=" . time() . "&action=launch");
                            $liveclassroom[$orphaned->WIMBA_getId()] = $orphaned;
                        }
                    }
                } else {
                    $listArchives = $archives[$key];
                    for ($i = 0; $i < count($listArchives); $i++) {
                        if ($listArchives[$i]->WIMBA_getAvailability() == "available") {
                            $liveclassroom[$key]->WIMBA_AddOneArchive($listArchives[$i]);
                        }
                    }
                }
            } else {
                $listArchives = $archives[$key];             
                for ($i = 0; $i < count($listArchives); $i++) {
                    if ($this->session->WIMBA_isInstructor() || 
                        (!$this->session->WIMBA_isInstructor() && $listArchives[$i]->WIMBA_getAvailability() == "available")) {
                        $orphaned = new WIMBA_XmlOrphanedArchive($listArchives[$i],
                                                                 "",
                                                                 "manageAction.php", 
                                                                 $this->session->url_params . "&time=" . time() . "&action=launch");
                        $liveclassroom[$orphaned->WIMBA_getId()] = $orphaned;
                    }
                }
            }
        }
        return $liveclassroom;
    }
    
     /**
    * 
    * 
    */   
    function WIMBA_getListVoiceTools() {

	global $DB;
        $resources = array ();
        $list = voiceemail_get_voicetools_list($this->session->hparams["course_id"]);
    
        if( $list != false)
        {
            $vtResources = voicetools_api_get_resources($list["rid"]);
            if ( $vtResources === false ) // problem
            {
                wimba_add_log(WIMBA_ERROR,voiceemail_LOGS,"Problem to get the list of resources from the voiceemail_LOGS server"); 
                return false;
            }
            else
            {   $ressources = $vtResources->WIMBA_getResources();
        
            }
        }
        else//error to get the database content
        {
            wimba_add_log(WIMBA_ERROR,voiceemail_LOGS,"Problem to get the list of resources from the databse");   
            return false;    
        }
         
        for ($i = 0; $i < count($ressources); $i++) 
        {
            $resource = $vtResources->WIMBA_getResource($i);
            $grade = -1;
            
            $rid = $resource->WIMBA_getRid();
            if($list["info"][$rid]->gradeid != -1)
            {
                $grade = $list["info"][$rid]->gradeid;
            }
            if ($list["info"][$rid]->availability == "0") 
            {
                $preview = false;
            }
            elseif ($list["info"][$rid]->start_date == -1 && $list["info"][$rid]->end_date == -1)
            {
                $preview = true;
            }
            elseif ($list["info"][$rid]->start_date == -1 && time() <= $list["info"][$rid]->end_date) 
            {
                $preview = true;
            }
            elseif ($list["info"][$rid]->start_date < time() && $list["info"][$rid]->end_date == -1) 
            {
                $preview = true;
            }
            elseif ($list["info"][$rid]->start_date < time() && time() < $list["info"][$rid]->end_date) 
            {
                $preview = true;
            }
            else 
            {
                $preview = false;
            }
         
            $xmlResource = new WIMBA_XmlResource(
                                    $rid, 
                                    $resource->WIMBA_getTitle(), 
                                    $preview, 
                                    "manageAction.php", 
                                    $this->session->url_params . "&time=" . time() . "&action=launch",
                                    $grade
                                    );

            if ( $voiceemail = $DB->get_records("voiceemail", array("rid" => $rid))) {
                 $activities = array();
                 foreach($voiceemail as $voicetool) {
                     array_push($activities, $voicetool->name);
                 }
                 $xmlResource->WIMBA_setLinkedActivities(implode("|",$activities));
            }
                                    
            $xmlResource->WIMBA_setTooltipAvailability(get_string("tooltipVT_" . $preview . "_student", 'voiceemail'));
            if ($this->session->WIMBA_isInstructor() || !$this->session->WIMBA_isInstructor() && $preview) 
            {
                $xmlResource->WIMBA_setType($resource->WIMBA_getType());
                $resources[$resource->WIMBA_getTitle() . $resource->WIMBA_getRid()] = $xmlResource;              
            }
        }

        return $resources;
    }    
    
    function WIMBA_getXmlString()
    {
        return $this->xml->WIMBA_getXml();
    }
    
    function WIMBA_getSession()
    {
        return $this->session;
    }
    
    function WIMBA_getSessionError()
    {
        return $this->session->error;
    }

    function WIMBA_setError($error) {
        $this->xml->WIMBA_setError($error);
    }

}
?>

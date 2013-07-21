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

class WIMBA_vtAction{

  var $params;//stack of parameters
  var $creator;
  
    function WIMBA_vtAction($emailCreator,$params=null)
    {
        $this->params=$params;
        $this->creator=$emailCreator;
    }
    
    /**************
    VOICE BOARD
    *****************/
    /*
    * This function creates a voice board on the vt server 
    * params : elements of the form
    */
    function WIMBA_createBoard(){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("board");     //Voice Baord
        $resource->WIMBA_setMail($this->creator);
        if (isset($this->params["longname"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
       
        if($this->params['default']=="true")
        {
            $options->WIMBA_setFilter("false");
            $options->WIMBA_setShowCompose("true");
               //Media
            $audio->WIMBA_setName("spx_16_q4");
            //message length     
            $options->WIMBA_setMaxLength("300");
            //Features 
            $options->WIMBA_setShowReply("true");
            $options->WIMBA_setShortTitle("true");
            $options->WIMBA_setGrade("false");
            $options->WIMBA_setPointsPossible("");
        }    
        else
        {
            if (isset($this->params["description"])) 
            {
                $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
            }
            if ($this->params["led"] == "student")
            {
                $options->WIMBA_setFilter("false");
                $options->WIMBA_setShowCompose($this->params['show_compose']);
            }
            else
            {
                $options->WIMBA_setFilter("true");
                $options->WIMBA_setShowCompose("false");
            }
            
            //Media
            $audio->WIMBA_setName($this->params["audio_format"]);
            //message length     
            $options->WIMBA_setMaxLength($this->params["max_length"]);
            //Features 
            //short message titles
            $options->WIMBA_setShortTitle($this->params["short_title"]);
            //chronological order
            $options->WIMBA_setChronoOrder($this->params["chrono_order"]);        
            //forward message
            $options->WIMBA_setShowForward($this->params["show_forward"]);
            
            $options->WIMBA_setShowReply($this->params["show_reply"]);
            if(isset($this->params["grade"])) 
            {
              $options->WIMBA_setGrade($this->params["grade"]);
            }
            if(isset($this->params["points_possible"])) 
            {
                 $options->WIMBA_setPointsPossible($this->params["points_possible"]);
            }
        }
        
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        //create the resource on the vt server
        
        $result = voicetools_api_create_resource($resource->WIMBA_getResource()); 
   
        return $result;
    }
         
    /*
    * This function modifies a voice board on the vt server 
    */
    function WIMBA_modifyBoard($id){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("board");     //Voice Baord
        $resource->WIMBA_setMail($this->creator);
        if(isset($this->params["description"])) 
        {
            $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
        }else{
            $resource->WIMBA_setDescription("");
        }
        
        if (isset($this->params["longname"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
        
        if ($this->params["led"] == "student")
        {
            $options->WIMBA_setFilter("false");
            $options->WIMBA_setShowCompose($this->params["show_compose"]);
        }
        else
        {
            $options->WIMBA_setFilter("true");
            $options->WIMBA_setShowCompose("false");
        }
        //Media
        $audio->WIMBA_setName($this->params["audio_format"]);
        //message length
        $options->WIMBA_setMaxLength($this->params["max_length"]);
        //Features 
        //short message titles
        $options->WIMBA_setShortTitle($this->params["short_title"]);
        //chronological order
        $options->WIMBA_setChronoOrder($this->params["chrono_order"]);        
        //forward message
        $options->WIMBA_setShowForward($this->params["show_forward"]);
        
        $options->WIMBA_setShowReply($this->params["show_reply"]);
        if(isset($this->params["grade"])) 
        {
          $options->WIMBA_setGrade($this->params["grade"]);
        }
        if(isset($this->params["points_possible"])) 
        {
             $options->WIMBA_setPointsPossible($this->params["points_possible"]);
        }else{
             $options->WIMBA_setPointsPossible("");   
        }
       
        
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        //update
        $resource->WIMBA_setRid($id);      
        
        //create the resource on the vt server 
        $result = voicetools_api_modify_resource($resource->WIMBA_getResource());
        return $result;
    } 

    /****************
    VOICE PRESENTATION
    ****************/  
    /*
    * This function creates a voice presentation the vt server 
    */
    function WIMBA_createPresentation(){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("presentation");     //Voice Presentation
        $resource->WIMBA_setMail($this->creator);
        if (isset($this->params["longname"])) 
        {
                $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
        
        if($this->params['default']=="true")
        {
            $options->WIMBA_setFilter("true");
            //slides comments private  
            $options->WIMBA_setShowReply("true");
               //Media
            $audio->WIMBA_setName("spx_16_q4");
            //message length     
            $options->WIMBA_setMaxLength("300");
        }    
        else
        {        
            if (isset($this->params["description"])) 
            {
                $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
            } 
            
            $options->WIMBA_setFilter($this->params["filter"]);
            //slides comments private  
            $options->WIMBA_setShowReply($this->params["show_reply"]);        
            $audio->WIMBA_setName($this->params["audio_format"]);
            $options->WIMBA_setMaxLength($this->params["max_length"]);
        }
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        //create the resource on the vt server 
        $result = voicetools_api_create_resource($resource->WIMBA_getResource());
        return $result;
    } 

  /*
  * This function modifies a voice presentation the vt server 
  * @param id : id of the resource
  */
    function WIMBA_modifyPresentation($id)
    {
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("presentation");     //Voice Presentation
        $resource->WIMBA_setMail($this->creator);
        if (isset($this->params["description"])) 
        {
            $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
        }
        if (isset($this->params["longname"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
    
        $options->WIMBA_setFilter($this->params["filter"]);
        //slides comments private         
        $options->WIMBA_setShowReply($this->params["show_reply"]); 
        $audio->WIMBA_setName($this->params["audio_format"]);
        //message length
        $options->WIMBA_setMaxLength($this->params["max_length"]);
    
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        $resource->WIMBA_setRid($id);       
        //create the resource on the vt server 
        $result = voicetools_api_modify_resource($resource->WIMBA_getResource());
        return $result;
    }      

    /********
    Podcaster
    **********/ 
    /*
    * This function creates a podcaster the vt server 
    */
    function WIMBA_createPodcaster(){
    
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);
        
        // Info                
        $resource->WIMBA_setType("pc");//Podcaster
        $resource->WIMBA_setMail($this->creator);
        if (isset($this->params["longname"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
        
        if($this->params['default']=="true")
        {
            $options->WIMBA_setShowCompose("true");
               //Media
            $audio->WIMBA_setName("spx_16_q4");
            //message length     
            $options->WIMBA_setDelay("300");
            //Features 
            //short message titles
            $options->WIMBA_setShortTitle("true");
        }    
        else
        {   
            if (isset($this->params["description"])) 
            {
                $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
            }
            $options->WIMBA_setShowCompose($this->params["show_compose"]);        
            //Media      
            $audio->WIMBA_setName($this->params["audio_format"]);
            $options->WIMBA_setDelay($this->params["delay"]);    //no delay   
            //Features    
            $options->WIMBA_setShortTitle($this->params["short_title"]);
        }
        $options->WIMBA_setMaxLength(1200);     //set to 20 min  
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);       
       
        //create the resource on the vt server 
        $result = voicetools_api_create_resource($resource->WIMBA_getResource());                    
        return $result;
    }  
           
     /*
      * This function modifies a podcaster the vt server 
      * @param id : id of the resource
      */
    function WIMBA_modifyPodcaster($id){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);
        
        // Info                
        $resource->WIMBA_setType("pc");//Podcaster
        $resource->WIMBA_setMail($this->creator);
        if (isset($this->params["description"])) 
        {
            $resource->WIMBA_setDescription(stripslashes($this->params["description"]));
        }
        if (isset($this->params["longname"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["longname"]));
        }
         
        $options->WIMBA_setShowCompose($this->params["show_compose"]);        
        //Media      
        $audio->WIMBA_setName($this->params["audio_format"]);
        $options->WIMBA_setDelay($this->params["delay"]);    //no delay   
         //Features    
        $options->WIMBA_setShortTitle($this->params["short_title"]);
        
        $options->WIMBA_setMaxLength(1200);     //set to 20 min  
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options); 
        
        $resource->WIMBA_setRid($id);     
        //create the resource on the vt server 
        $result = voicetools_api_modify_resource($resource->WIMBA_getResource());
        return $result;
    }
    
    /********
    Recorder
    **********/ 
    /*
    * This function creates a recorder the vt server 
    * params : blocks' id
    */
    function WIMBA_createRecorder($title){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);
        
        // Info                
        $resource->WIMBA_setType("recorder");
        $resource->WIMBA_setTitle($title);
        $resource->WIMBA_setMail($this->creator);
        
        $options->WIMBA_setMaxLength(1200);     //set to 20 min  
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);       
           
        //create the resource on the vt server 
        $result = voicetools_api_create_resource($resource->WIMBA_getResource());             
        
        return $result;
    }

   
    function WIMBA_deleteResource($rid)  
    {  
        $result = voicetools_api_delete_resource($rid);     
        return $result;
    }

    /*******************
     VOICE EMAIL
    ****************/  
    /*
    * This function creates a voice presentation the vt server 
    */
    function WIMBA_createVMmail($title){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("vmail");     //Voice Presentation
        $resource->WIMBA_setMail($this->creator);
       
        if (isset($this->params["name"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["name"]));
        }
        else if(!empty($title))
        {
            
            $resource->WIMBA_setTitle($title);
        }
        
        //Media
        $audio->WIMBA_setName($this->params["audio_format"]);
        $options->WIMBA_setMaxLength($this->params["max_length"]);
        $options->WIMBA_setReplyLink($this->params["reply_link"]);
        
        if (isset($this->params["subject"])) 
        {
            $options->WIMBA_setSubject($this->params["subject"]);
        }
        
        $options->WIMBA_setTo($this->params["recipients"]);
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        
        //create the resource on the vt server 
        $result = voicetools_api_create_resource($resource->WIMBA_getResource());
        return $result;
    } 
     /*******************
     VOICE EMAIL
    ****************/  
    /*
    * This function creates a voice presentation the vt server 
    */
    function WIMBA_updateVMmail($id,$title){
        $resource = new WIMBA_vtResource(NULL);
        $audio = new WIMBA_vtAudioFormat(NULL); 
        $options = new WIMBA_vtOptions(NULL);   
        // Info                
        $resource->WIMBA_setType("vmail");     
        $resource->WIMBA_setMail($this->creator);
        
        if (isset($this->params["name"])) 
        {
            $resource->WIMBA_setTitle(stripslashes($this->params["name"]));
        }
        
        //Media
        $audio->WIMBA_setName($this->params["audio_format"]);
        $options->WIMBA_setMaxLength($this->params["max_length"]);
        $options->WIMBA_setReplyLink($this->params["reply_link"]);
        
        if (isset($this->params["subject"])) 
        {
            $options->WIMBA_setSubject($this->params["subject"]);
        }
        else
        {
             $options->WIMBA_setSubject("");
        }
        
        $options->WIMBA_setTo($this->params["recipients"]);
        $options->WIMBA_setAudioFormat($audio);  
        $resource->WIMBA_setOptions($options);  
        
        //create the resource on the vt server 
        $resource->WIMBA_setRid($id);     
        //create the resource on the vt server 
        $result = voicetools_api_modify_resource($resource->WIMBA_getResource());
        return $result;
    } 
    
    
    function WIMBA_getResource($rid)  
    { 
        $result=voicetools_api_get_resource ($rid) ;  
        return $result;
    }
    
    function WIMBA_createUser($screenName,$email)
    {
        $vtUser = new WIMBA_VtUser(NULL);  
        $vtUser->WIMBA_setScreenName($screenName); 
        $vtUser->WIMBA_setEmail ($email);
        return $vtUser; 
    }

    function WIMBA_createUserRights($product,$role)
    {
        $vtUserRigths = new WIMBA_vtRights(NULL);  
        $vtUserRigths->WIMBA_setProfile ( 'moodle.'.$product.'.'.strtolower($role));  
        if($product=="presentation")
        {   
            $vtUserRigths->WIMBA_add("reply_message");
        }
        return $vtUserRigths;
    }  
    
    function WIMBA_isAudioExist($rid,$mid){

        return voicetools_api_audio_exists($rid,$mid);
    }
    
    function WIMBA_getVtSession($resource,$user,$rights,$message=null)
    {
        if($message!=null)
        {
            return voicetools_api_create_session ($user->WIMBA_getUser(),$resource->WIMBA_getResource(),$rights->WIMBA_getRights(),$message->WIMBA_getMessage()) ;
        }
        else
        {
            return voicetools_api_create_session ($user->WIMBA_getUser(),$resource->WIMBA_getResource(),$rights->WIMBA_getRights()) ;
        }
    }
    
    function WIMBA_getAverageMessageLengthPerUser($rid)
    {
        $result = voicetools_get_average_length_messages_per_user($rid);
        if($result == "not_implemented")
          return $result;
        return WIMBA_convertResultFromGetAverageLengthPerUser($result);
    }
    
    function WIMBA_getNbMessagePerUser($rid)
    {
        $array = voicetools_get_nb_messages_per_user($rid);
        return WIMBA_convertResultFromGetNbMessagePerUser($array);
    }
}


?>

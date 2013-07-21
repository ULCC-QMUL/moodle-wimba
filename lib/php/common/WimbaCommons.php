<?php    
/******************************************************************************
 *                                                                            *
 * Copyright (c) 1999-2012  Blackboard Collaborate, All Rights Reserved.      *
 *                                                                            *
 * COPYRIGHT:                                                                 *
 *      This software is the property of Blackboard Collaborate.              *
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
 *      along with the Blackboard Collaborate Moodle Integration;             *
 *      if not, write to the Free Software Foundation, Inc.,                  *
 *      51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA                *
 *                                                                            *
 * Author: Thomas Rollinger                                                   *
 *                                                                            *
 * Date: January 2007                                                         *
 *                                                                            *
 ******************************************************************************/

/* $Id: WimbaVoicetoolsAPI.php 45764 2007-02-28 22:04:25Z thomasr $ */
class WIMBA_WimbaMoodleSession{

    var $hparams=array();
    var $url_params;
    var $signature="";       
    var $currentVtUser ;
    var $currentVtUserRigths;  
    var $timeOfLoad;    
    var $error=false;
    var $request;  

  
    function WIMBA_WimbaMoodleSession($parameters=NULL){
        $this->timeOfLoad = time(); 
        $signature="";   
        $this->request=$parameters;
        if (($this->timeOfLoad - $parameters["time"] <= 1800) && ($this->timeOfLoad - $parameters["time"] >= 0))
        {//30 min
            ksort($parameters);
            foreach ($parameters as $key => $value)
            {   
                if (strstr($key, 'enc_'))//param use to signature
                {        
                    if( $value=="")
                    {
                        $this->hparams[substr($key,4)] ="";
                    }
                    else 
                    {
                        $log = defined('WC') ? WC : 'liveclassroom';
                        $this->hparams[substr($key,4)] = WIMBA_wimbaDecode($value);
                        wimba_add_log(WIMBA_DEBUG,$log,__FUNCTION__ . ": KEY=$key - VALUE=$value - DECODED VALUE=".WIMBA_wimbaDecode($value));
                    }
                    $signature .= WIMBA_wimbaDecode($value);
                    // Decode and reencode the parm we put back on the URL
                    $p = "enc_".substr($key,4)."=".WIMBA_wimbaEncode(WIMBA_wimbaDecode($value))."&";
                    $this->url_params .= $p;   
                }
            } 
            if ($parameters["signature"] != md5($signature))//good signatureature
            {
                $this->error="signature";
            }
            $this->signature=md5($signature);      
            $this->url_params .="signature=".WIMBA_wimbaEncode($this->signature);
        }
        else//session time out
        {
           $this->error="session";        
        }
    }

    function WIMBA_getCourseId(){   
    
        if(isset($this->hparams["course_id"]))
        {
            return $this->hparams["course_id"];
        }
    	return null;
    }

    function WIMBA_getLcCurrentUser(){
        if($this->WIMBA_isInstructor())
        {
            return  $this->WIMBA_getCourseId()."_T";
        }
        return  $this->WIMBA_getCourseId()."_S";
    } 

    function WIMBA_setCurrentVtUSer($product,$screenName="",$email="",$role=""){
        $this->currentVtUser = new WIMBA_VtUser(NULL);
        $this->currentVtUserRigths = new WIMBA_vtRights(NULL);  
        
        if($screenName=="")
        {
            $this->currentVtUser->WIMBA_setScreenName($this->hparams["firstname"]."_".$this->hparams["lastname"]);
        }
        else
        {     
            $this->currentVtUser->WIMBA_setScreenName($screenName);
        }
        
        if($email=="")   
        {
            $this->currentVtUser->WIMBA_setEmail ($this->hparams["email"]);
        }
        else
        {
            $this->currentVtUser->WIMBA_setEmail ( $email);
        }    
    
    
        if (($role!="" && $role=="Instructor") || ($role=="" && $this->hparams["role"]=="Instructor")) 
        {
            $this->WIMBA_setVtUserRigths($product,"instructor");     
        }
        else 
        {       
            $this->WIMBA_setVtUserRigths($product,"student");
        }
            
    } 
  
  
    function WIMBA_getVtUser(){
        return  $this->currentVtUser;
    } 
    
    function WIMBA_getVtUserRigths(){
        return  $this->currentVtUserRigths;
    }
    
    function WIMBA_setVtUserRigths($product,$role){
        $this->currentVtUserRigths->WIMBA_setProfile ( 'moodle.'.$product.'.'.$role);  
        if($product=="presentation")
        {
            $this->currentVtUserRigths->WIMBA_add("reply_message");
        }
    }
  
    function WIMBA_isInstructor(){
        if($this->hparams["role"]!=null && $this->hparams["role"]=="Instructor")
        {
        	return true;
        }
        return false;
    }

    function WIMBA_getFirstname(){
    
        if(isset($this->hparams["firstname"]))
        {
        	return $this->hparams["firstname"];
        }
        return "";
    } 
 
    function WIMBA_getEmail(){
    
        if(isset($this->hparams["email"]))
        {
            return $this->hparams["email"];
        }
        return "";
    } 
    function WIMBA_getLastname(){
    
        if(isset($this->hparams["lastname"]))
        {
        	return $this->hparams["lastname"];
        }
        return "";
    } 
}





?>

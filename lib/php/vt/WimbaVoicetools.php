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

/* $Id: $ */

/**
 * This class represent a resource info Object
 */
class  WIMBA_VtResource{
    var $pairset;
    var $nameSetPair;
    var $nameValuePair;
    var $options;
    var $hparams;
    var $error;
    var $error_message;
    
    /**
     * This function retrieves the different informations of the resource 
     * and fill a associative array (hparams) to manipulate the informations easily
     * 
     * @param  $pairset : array which represent the resource
     */
    function WIMBA_vtResource($pairset=null, $isList = false)
    {
        if ($isList == true) 
        {
            $this->pairset = $pairset["pairSet"];
        } 
        else 
        {
            $this->pairset = $pairset;
        } 

        if (isset($this->pairset["groups"])) 
        {
            $this->nameSetPair = $this->pairset["groups"];
        } 
        else 
        {
            $this->nameSetPair = null;
        } 

        if (isset($this->pairset["values"])) 
        {
            $this->nameValuePair = $this->pairset["values"];
        } 
        else 
        {
            $this->nameValuePair = null;
        } 

        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if ($status_code != "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < count($this->nameValuePair); $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "error_message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["value"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 

        if ($this->nameSetPair != null) 
        {
            for ($i = 0; $i < count($this->nameSetPair); $i++) 
            {
                if ($this->nameSetPair[$i]["name"] == "options") 
                {
                    $this->options = new WIMBA_vtOptions($this->nameSetPair[$i]["pairSet"]);
                } 
            } 
        } 
    } 

    /*
     * Return the type of the resource
     * Different type of resource
     *  -   Voice Board (value: board). 
     *       o   A resource of this type will be used to store messages posted in a given Voice Board. 
     *   -   Podcaster (value: pc). 
     *       o   A resource of this type will be used to publish audio message in an RSS Podcasting feed, available to download by most popular podcasting applications (aka podcatchers).. 
     *   -   Voice Presentation (value: presentation). 
     *       o   A resource of this type will be used to design online presentations, where each slide receives an audio comment. Users can then eventually comment on these slides.
     *   -   Voice Direct (value: voicedirect). 
     *       o   The archives of a Voice Direct conference will be stored in a Voice Direct resource. 
     *   -   Voice Email (value: vmail).
     *       o    The voice emails posted for a given Voice Email application will be archived in a Voice Email resource. 
     *   -   Voice Authoring (value: recorder). 
     *       o   A Voice Authoring resource is used to store messages recorded using the Voice Authoring recorders and players. 
     * 
     ***/
    function WIMBA_getType()
    {
        if (isset($this->hparams["type"])) 
        {
            return $this->hparams["type"];
        } 
        return "";
    } 

    /*
     * Return the resource identifier 
     */ 
    function WIMBA_getRid()
    {
        if (isset($this->hparams["rid"])) 
        {
            return $this->hparams["rid"];
        } 
        return "";
    } 
    
    /*
     * Return the resource title 
     */ 
    function WIMBA_getTitle()
    {
        if (isset($this->hparams["title"])) 
        {
            return $this->hparams["title"];
        } 
        return "";
    } 
    
    /*
     * Return the resource description 
     */
    function WIMBA_getDescription()
    {
        if (isset($this->hparams["description"])) 
        {
            return $this->hparams["description"];
        } 
        return "";
    } 
    
    /*
     * Return the email address of the resource creator. 
     */
    function WIMBA_getMail()
    {
        if (isset($this->hparams["email"]))
        {
            return $this->hparams["email"];
        }
        return "";
    } 
    
    /*
     * Return the options of the resource
     */
    function WIMBA_getOptions()
    {
        return $this->options;
    } 

    /*
     * Set the resource description 
     */    
    function WIMBA_setType($type)
    {
        $this->hparams["type"] = $type;
    }
    
    /*
     * Set the resource identifier 
     */    
    function WIMBA_setRid($rid)
    {
        $this->hparams["rid"] = $rid;
    }
    
    /*
     * Set the resource title 
     */
    function WIMBA_setTitle($title)
    {
        $this->hparams["title"] = $title;
    }

    /*
     * Set the resource description 
     */
    function WIMBA_setDescription($description)
    {
        $this->hparams["description"] = $description;
    }
    
    /*
     * Set the email of the resource creator 
     */
    function WIMBA_setMail($email)
    {
        $this->hparams["email"] = $email;
    }
    
    /*
     * Set the resource options 
     */
    function WIMBA_setOptions($options)
    {
        $this->options = $options;
    } 
    
     /*
     * Set the resource options 
     */
    function WIMBA_setEmailFrom($options)
    {
        $this->options->WIMBA_setFrom($options);
    } 

    /**
     * This function build a "Pairset" th thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getResource()
    {
        $this->nameSetPair[0]["name"] = "options";
        if ($this->options != null) 
        {
            $this->nameSetPair[0]["pairSet"] = $this->options->WIMBA_getOptions();
        } 
        else 
        {
            $this->nameSetPair[0]["pairSet"] = null;
        } 

        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * This class represent a resources Object
 */
class WIMBA_vtResources {
    var $pairset;
    var $nameSetPair;
    var $nameValuePair;
    var $options;
    var $hparams;
    var $error;
    var $error_message;
    var $resources;

    /**
     * This function retrieves the different informations of the resource 
     * and fill a associative array (hparams) to manipulate the informations easily
     * 
     * @param  $pairset : array which represent the resource
     */
    function WIMBA_vtResources($pairset=null)
    {
        $this->pairset = $pairset;
        if (isset($this->pairset["groups"])) 
        {
            $this->nameSetPair = $this->pairset["groups"];
        } 
        else 
        {
            $this->nameSetPair = null;
        } 

        if (isset($this->pairset["values"])) 
        {
            $this->nameValuePair = $this->pairset["values"];
        } 
        else 
        {
            $this->nameValuePair = null;
        } 

        if ($this->nameSetPair != null) 
        {
            for ($i = 0; $i < count($this->nameSetPair); $i++) 
            {
                $resource = new WIMBA_vtResource($this->nameSetPair[$i]["pairSet"]);
                $this->resources[] = $resource;
            } 
        } 
    } 

    /**
     * This function build a "Pairset" th thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getResources()
    {
        return $this->resources;
    } 
    function WIMBA_getResource($i)
    {
        return $this->resources[$i];
    } 
} 

class WIMBA_vtOptions {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $audio;
    var $hparams;
    var $error;
    var $error_message;
    
    /**
     * This function retrieves the different informations of the pairset 
     * and fill a associative array (hparams) to manipulate the informations easily
     * 
     * @param  $pairset : array which represent the options
     */
    function WIMBA_vtOptions($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];

        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < count($this->nameValuePair); $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                            $this->error = true;
                        } 
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
        if ($this->nameSetPair != null) {
            for ($i = 0; $i < count($this->nameSetPair); $i++) 
            {
                if ($this->nameSetPair[$i]["name"] == "audio_format") 
                {
                    $this->audio = new WIMBA_vtAudioFormat($this->nameSetPair[$i]["pairSet"]);
                } 
            } 
        } 
    } 
    
    /*
     * Return the maximum recording time allowed for a message in seconds.
     */
    function WIMBA_getMaxLength()
    {
        if (isset($this->hparams["max_length"])) 
        {
            return $this->hparams["max_length"];
        } 
        return '';
    } 
    
    /*
     * Return the the short titles. 
     */
    function WIMBA_getShortTitle()
    {
        if (isset($this->hparams["short_title"])) 
        {
            return $this->hparams["short_title"];
        } 
        return false;
    } 
    
    /*
     * Returns whether or not the messages in the associated resource should
     * appear in chronological order.
     */
    function WIMBA_getChronoOrder()
    {
        if (isset($this->hparams["chrono_order"])) 
        {
            return $this->hparams["chrono_order"];
        } 
        return false;
    } 
    
    /*
     * Return whether or not to display the "message compose" button. 
     */
    function WIMBA_getShowCompose()
    {
        if (isset($this->hparams["show_compose"])) 
        {
            return $this->hparams["show_compose"];
        } 
        return false;
    } 
    
    /*
     * Return whether or not to display the "message forward" button.
     */
    function WIMBA_getShowForward()
    {
        if (isset($this->hparams["show_forward"])) 
        {
            return $this->hparams["show_forward"];
        } 
        return false;
    } 
    
    /*
     * Return whether or not to display the "reply" buttond 
     */
    function WIMBA_getShowReply()
    {
        if (isset($this->hparams["show_reply"])) 
        {
            return $this->hparams["show_reply"];
        } 
        return false;
    } 
    
    /*
     * Return the time before a message is made available 
     * for download after it has been posted to the server
     */
    function WIMBA_getDelay()
    {
        if (isset($this->hparams["delay"])) 
        {
            return $this->hparams["delay"];
        } 
        return 0;
    } 
    
    /*
     * Return the filter
     */
    function WIMBA_getFilter()
    {
        if (isset($this->hparams["filter"])) 
        {
            return $this->hparams["filter"];
        } 
        return false;
    } 
    
    /*
     * Return the audio quality 
     */
    function WIMBA_getAudioFormat()
    {
        return $this->audio;
    } 
    
    
    /*
     * Returns whether or not the reply links will be included 
     * in Voice Email receveid
     * Use only for vmail
     */
    function WIMBA_getReplyLink()
    {
        if (isset($this->hparams["reply_links"])) 
        {
            return $this->hparams["reply_links"];
        } 
        return false;
    } 
    
    /*
     * Returns the form Field
     * Use only for vmail
     */
    function WIMBA_getFrom()
    {
        if (isset($this->hparams["from"])) 
        {
            return $this->hparams["from"];
        } 
        return false;
    } 

    /*
     * Returns the To Field
     * Use only for vmail
     */
    function WIMBA_getTo()
    {
        if (isset($this->hparams["to"])) 
        {
            return $this->hparams["to"];
        } 
        return false;
    } 
    
    /*
     * Returns the CC Field
     * Use only for vmail
     */
    function WIMBA_getCc()
    {
        if (isset($this->hparams["cc"])) 
        {
            return $this->hparams["cc"];
        } 
        return "";
    } 

    /*
     * Returns the BCC Field
     * Use only for vmail
     */
    function WIMBA_getBcc()
    {
        if (isset($this->hparams["bcc"])) 
        {
            return $this->hparams["bcc"];
        } 
        return "";
    } 
 
    /*
     * Returns the Subject Field
     * Use only for vmail
     */
    function WIMBA_getSubject()
    {
        if (isset($this->hparams["subject"])) 
        {
            return $this->hparams["subject"];
        } 
        return "";
    } 
   
    /*
     * Returns the body of the mail
     * Use only for vmail
     */
    function WIMBA_getText()
    {
        if (isset($this->hparams["text"])) 
        {
            return $this->hparams["text"];
        } 
        return "";
    } 
     
    /*
     * Returns whether or not the fields disabled
     * Use only for vmail
     */
    function WIMBA_getDisable()
    {
        if (isset($this->hparams["disable"])) 
        {
            return $this->hparams["disable"];
        } 
        return false;
    } 
    
     /*
     * Returns whether or not the fields hidden
     * Use only for vmail
     */
    function WIMBA_getHide()
    {
        if (isset($this->hparams["hide"])) 
        {
            return $this->hparams["hide"];
        } 
        return false;
    }
    
     /*
     * Returns whether or not the resource is gradable
     */
    function WIMBA_getGrade()
    {
        if (isset($this->hparams["grade"])) 
        {
            return $this->hparams["grade"];
        } 
        return false;
    }
    
    /*
     * Returns the points possible for the grade
     */
    function WIMBA_getPointsPossible()
    {
        if (isset($this->hparams["points_possible"])) 
        {
            return $this->hparams["points_possible"];
        } 
        return "";
    }
    
    /*
     * Set the maximum recording time allowed for a message in seconds.
     */
    function WIMBA_setMaxLength($maxLength)
    {
        $this->hparams["max_length"] = $maxLength;
    } 
    
    /*
     * Set the filter
     * @param filter
     */
    function WIMBA_setFilter($filter)
    {
        $this->hparams["filter"] = $filter;
    } 

    function WIMBA_setShortTitle($shortTitle)
    {
        $this->hparams["short_title"] = $shortTitle;
    } 
    
   /**
   * Sets whether or not the messages in the associated resource should appear
   * in chronological order.
   *
   * @param chrono_order
   */
    function WIMBA_setChronoOrder($chronoOrder)
    {
        $this->hparams["chrono_order"] = $chronoOrder;
    } 
    
   /**
   * Sets whether or not to display the "composed" button..
   *
   * @param chrono_order
   */
    function WIMBA_setShowCompose($showCompose)
    {
        $this->hparams["show_compose"] = $showCompose;
    } 
    
   /**
   * Sets whether or not to display the "forward" button..
   *
   * @param chrono_order
   */
    function WIMBA_setShowForward($showForward)
    {
        $this->hparams["show_forward"] = $showForward;
    }
     
   /**
   * Sets the time before a message is made available 
     * for download after it has been posted to the server
   *
   * @param delay
   */
    function WIMBA_setDelay($delay)
    {
        $this->hparams["delay"] = $delay;
    }
     
   /**
   * Sets whether or not to display the "reply" button.
   *
   * @param showReply 
   */    
    function WIMBA_setShowReply($showReply)
    {
        $this->hparams["show_reply"] = $showReply;
    } 
    
   /**
   * Sets the audio format
   *
   * @param audio
   */
    function WIMBA_setAudioFormat($audio)
    {
        $this->audio = $audio;
    } 

    /*
     * Sets whether or not the reply links will be included 
     * in Voice Email receveid
     * Use only for vmail
     */
    function WIMBA_setReplyLink($replyLink)
    {
        $this->hparams["reply_links"] = $replyLink;
    } 
    
    /*
     * Sets the form Field
     * Use only for vmail
     */
    function WIMBA_setFrom($from)
    {
        $this->hparams["from"] = $from;          
    } 

    /*
     * Sets the To Field
     * Use only for vmail
     */
    function WIMBA_setTo($to)
    {
        $this->hparams["to"] = $to; 
    } 
    
    /*
     * Sets the CC Field
     * Use only for vmail
     */
    function WIMBA_setCc($cc)
    {
        $this->hparams["cc"] = $cc;
    } 

    /*
     * Sets the BCC Field
     * Use only for vmail
     */
    function WIMBA_setBcc($bcc)
    {
        $this->hparams["bcc"] = $bcc; 
    } 
 
    /*
     * Sets the Subject Field
     * Use only for vmail
     */
    function WIMBA_setSubject($subject)
    {
        $this->hparams["subject"] = $subject; 
    } 
   
    /*
     * Sets the body of the mail
     * Use only for vmail
     */
    function WIMBA_setText($text)
    {
        $this->hparams["text"] = $text;    
    } 
     
    /*
     * Sets the fields disabled
     * Use only for vmail
     */
    function WIMBA_setDisable($disable)
    {
        $this->hparams["disable"] = $disable;
    } 
    
     /*
     * Sets the fields hidden
     * Use only for vmail
     */
    function WIMBA_setHide()
    {
        $this->hparams["hide"] = $hide;
    }
    
 	/*
     * Set whether or not the resource is gradable
     */
    function WIMBA_setGrade($grade)
    {
        $this->hparams["grade"] = $grade;     
    }
    
    /*
     * Set the points possible for the grade
     */
    function WIMBA_setPointsPossible($pointsPossible)
    {
        $this->hparams["points_possible"] = $pointsPossible;   
    }
    
    
    /**
     * This function build a "Pairset" th thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getOptions()
    {
        $this->nameSetPair[0]["name"] = "audio_format";

        if ($this->audio != null) 
        {
            $this->nameSetPair[0]["pairSet"] = $this->audio->WIMBA_getAudioFormat();
        } 
        else
        {
            $this->nameSetPair[0]["pairSet"] = null;
        } 

        $i = 0;
        if ($this->hparams != null)
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 


/**
 * The AudioFormat describes various characteristics of a sound recording.
 * This includes the following attributes :
 * <ul>
 * <li> its file format (.wav, .au, .gsm, .ogg, ...),
 * <li> its encoding (PCM, ADPCM, GSM, VORBIS, SPEEX, ...),
 * <li> the number of channels (1=mono, 2=stereo, ...),
 * <li> the sample rate (8000Hz, 11025Hz, 16000Hz, 22050Hz, 32000Hz, 44100Hz, ...),
 * <li> the audio datas size.
 * <li> ...
 * </ul>
 *
 */
class WIMBA_vtAudioFormat {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $hparams;
    var $error;
    var $error_message;

    function WIMBA_vtAudioFormat($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];
        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < count($this->nameValuePair); $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
    } 
    
   /**
   * Returns the Name.
   */
    function WIMBA_getName()
    {
        return $this->hparams["name"];
    } 
    
   /**
   * Returns the audio format of the associated audio data.
   * Returns -1 if the format is unknown or not yet known.
   */   
    function WIMBA_getFileFormat()
    {
        return $this->hparams["file_fornat"];
    } 
    
   /**
   * Returns the audio format of the associated audio data.
   * Returns -1 if the format is unknown or not yet known.
   */
    function WIMBA_getEncoding()
    {
        return $this->hparams["encoding"];
    } 
    
   /**
   * Returns the number of audio channels in the associated audio data
   * (1=mono, 2=stereo, ...).
   * Returns -1 if the number of channels is not yet known.
   */ 
    function WIMBA_getChannels()
    {
        return $this->hparams["channels"];
    } 
    
   /**
   * Returns the sample rate (also known as the sampling frequency) of the
   * associated audio data (in samples per second).
   * The most common sampling rates are:
   * <ul>
   * <li>  8000 kHz - Telephone Quality (Speech Narrowband)
   * <li> 11025 kHz - AM Radio Quality
   * <li> 16000 kHz - Speech Wideband
   * <li> 22050 kHz - FM radio Quality
   * <li> 32000 kHz - Speech Ultra-wideband
   * <li> 44100 kHz - CD Quality
   * <li> 48000 kHz - Digital Audio Tape (DAT) Quality
   * </ul>
   * Returns -1 if samplerate is not yet known.
   */
    function WIMBA_getSampleRate()
    {
        return $this->hparams["sample_rate"];
    } 
    
  /**
   * Returns the size (in bytes) of the associated audio data (without header).
   * Returns -1 if datasize is not yet known.
   */    
    function WIMBA_getDataSize()
    {
        return $this->hparams["data_size"];
    } 
    
   /**
   * Returns the size of each sample in bits (Bits Per Sample).
   * This information is optional because:
   * <ul>
   * <li> it may be redundant information for some audio formats:
   *      A-Law and Mu-Law are always 8 bits per sample
   * <li> it is not an integer value:
   *      GSM averages out at 1.65 bits per sample
   * <li> it may have no sense:
   *      For a VBR (varible bit rate) signal it is variable
   * </ul>
   * But for some formats it is essential:
   * <ul>
   * <li> PCM can be 8, 12, 16, 24 or 32 bits per sample
   * </ul>
   * Therefore the function should return:
   * <ul>
   * <li> -3 if the value is not integer
   * <li> -2 if the information makes no sense
   * <li> -1 if the value is not yet known
   * <li> +x where x is the real value, even if it is redundant
   * </ul>
   */ 
    function WIMBA_getSampleSize()
    {
        return $this->hparams["sample_size"];
    } 
    
   /**
   * Returns the size (in bytes) of an audio block of data.
   * If data is packed into blocks, it returns the blocks size:
   * ex:
   * <ul>
   * <li> 33 for pure GSM frames
   * <li> 65 for GSM wav blocks
   * </ul>
   * For PCM type signal (PCM, A-Law, Mu-Law), which don't come in blocks,
   * but whose channels are interlaced, one should return
   * <pre>
   *   BlockSize = Channels * BitsPerSample / 8
   * </pre>
   * Return -1 if the value is not yet known.
   */
    function WIMBA_getBlockSize()
    {
        return $this->hparams["block_size"];
    }
     
   /**
   * Returns the average bit rate (Bits Per Second) of the signal.
   * This information is optional, because this value can be often be
   * calculated with the following formula:
   * <pre>
   *   AvgBitRate = Channels * SampleRate * SampleSize
   * </pre>
   * But this formula is not valid for VBR (variable bitrate audio),
   * where a more acurate formula would be:
   * <pre>
   *   AvgBitRate = FileSize / AudioDuration
   * </pre>
   * Return -1 if the value is not yet known
   */
    function WIMBA_getAvgBitRate()
    {
        return $this->hparams["avg_bit_rate"];
    } 
    
   /**
   * Returns whether or not the associated audio is VBR (Varible Bit Rate).
   * @return whether or not the associated audio is VBR (Varible Bit Rate).
   */
    function WIMBA_getIsVbr()
    {
        return $this->hparams["is_vbr"];
    } 
    
    function WIMBA_getNbSample()
    {
        return $this->hparams["nb_samples"];
    } 
   
  /**
   * Returns the additional codec parameters used to encode/decode the
   * associated audio data (such as: quality settings, filters, ...).
   * These additional parameters are optional and, depending on the codec,
   * might not be necessary, or might have to be parsed by the codec.
   * As such it should be standardized. The suggested format is
   * therefore a that of a java properties file. At it's simplest if there is
   * just one parameter it can be one line (ex for speex: "quality=3").
   */
    function WIMBA_getCodecParams()
    {
        return $this->hparams["codec_params"];
    } 
    
  
    function WIMBA_setName($name)
    {
        $this->hparams["name"] = $name;
    } 
    
    function WIMBA_setFileFormat($fileFormat)
    {
        $this->hparams["file_fornat"] = $fileFormat;
    } 
    
    function WIMBA_setEncoding($encoding)
    {
        $this->hparams["encoding"] = $encoding;
    } 
    
    function WIMBA_setChannels($channels)
    {
        $this->hparams["channels"] = $channels;
    } 
    
    function WIMBA_setSampleRate($sampleRate)
    {
        $this->hparams["sample_rate"] = $sampleRate;
    } 
    
    function WIMBA_setDataSize($dataSize)
    {
        $this->hparams["data_size"] = $dataSize;
    } 
    function WIMBA_setSampleSize($sampleSize)
    {
        $this->hparams["sample_size"] = $sampleSize;
    } 
    
    function WIMBA_setBlockSize($blockSize)
    {
        $this->hparams["block_size"] = $blockSize;
    } 
    
    function WIMBA_setAvgBitRate($avgBitRate)
    {
        $this->hparams["avg_bit_rate"] = $avgBitRate;
    } 
    
    function WIMBA_setIsVbr($isVbr)
    {
        $this->hparams["is_vbr"] = $isVbr;
    } 
    
    function WIMBA_setNbSample($nbSamples)
    {
        $this->hparams["nb_samples"] = $nbSamples;
    } 
    
    function WIMBA_setCodecParams($codecParams)
    {
        $this->hparams["codec_params"] = $codecParams;
    } 
    
    /**
     * This function build a "Pairset"  thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getAudioFormat()
    {
        $this->nameSetPair[0] = null;
        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * Data structure representing a set of Rights associated with a profile.
 * <p>
 * The <code>Rights</code> object is used to define the rights that
 * a user should be granted for a given application. There are two ways
 * to define rights.
 * Each Wimba application supports a list of individual rights: the right
 * to compose a message in a Voice Board or the right to raise the hand
 * in a Voice Direct conference for example. For ease of use, Wimba has
 * introduced the concept of profiles. A profile is a simple way to group
 * individual rights. A typical profile would be a teacher profile or a
 * student profile for a given application. The Wimba Server has
 * predefined profiles and new profiles can be added in the
 * <code>setup.conf</code> file located in the
 * <code>.../wimba/WEB-INF/etc/</code> directory of your Wimba Server.
 */
class WIMBA_vtRights {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $parameters;
    var $hparams;
    var $error;
    var $error_message;

    function WIMBA_vtRights($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];
        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < $this->nameValuePair . count; $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
        if ($this->nameSetPair != null) 
        {
            for ($i = 0; $i < count($this->nameSetPair); $i++) 
            {
                if ($this->nameSetPair[$i]["name"] == "parameters") {
                } 
                $this->parameters = new VtParameters($this->nameSetPair[$i]["pairSet"]);
            } 
        } 
    }
     
  /**
   * Gets the rights profile.
   */
    function WIMBA_getProfile()
    {
        return $this->hparams["profile"];
    } 
  
  /**
   * Sets the rights profile.
   */ 
    function WIMBA_setProfile($profile)
    {
        $this->hparams["profile"] = $profile;
    } 
   
   /**
   * Sets the rights that must be added to the profile rights.
   */
    function WIMBA_add($param)
    {
        $this->hparams["add"] = $param;
    } 
    
    /*
     *  Gets the parameters.
     */
    function WIMBA_getParameters()
    {
        return $this->parameters;
    } 
    
    function WIMBA_setParameters($param)
    {
        $this->parameters = $param;
    } 
    
    /**
     * This function build a "Pairset"  thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getRights()
    {
        if ($this->parameters != null) 
        {
            $this->nameSetPair[0]["name"] = "parameters";
            $this->nameSetPair[0]["pairSet"] = $this->parameters->WIMBA_getParameters();
        }
        else 
        {
            $this->nameSetPair = null;
        } 

        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * Data structure representing Parameters of an applet.
 * <p>
 * The <code>Parameters</code> object is embedded inside the
 * <code>Rights</code> object and enables to define additional
 * settings for a given session. This object may seem to be
 * redundant with the <code>Options</code> field, but it is not.
 * The <code>Options</code> is used to store the configuration of a
 * <code>Resource</code> in the database. The
 * <code>Parameters</code> object is not persisted with the
 * <code>Resource</code>. The <code>Parameters</code> object
 * is used to set parameters to a session.
 * 
 */
class WIMBA_vtParameters {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $hparams;
    var $error;
    var $error_message;

    function WIMBA_vtParameters($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];
        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < $this->nameValuePair . count; $j++)
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
    } 
    
    /*
     * Gets the filtered names.
     */
    function WIMBA_getFilter()
    {
        return $this->hparams["filtered_names"];
    } 

    function WIMBA_setFilter($name)
    {
        $this->hparams["filtered_names"] = $name;
    } 
    
    /**
     * This function build a "Pairset"  thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getParameters()
    {
        $this->nameSetPair[0] = null; 
        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * Data structure representing a User.
 * <p>
 * The <code>User</code> object is used to manipulate user data such as the
 * user first and last names, the email address. The Wimba SDK enables single
 * sign on between Wimba and a third party application without populating the
 * Wimba Server database with all users.
 * 
 */
class WIMBA_vtUser {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $hparams;
    var $error;
    var $error_message;

    function WIMBA_VtUser($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];
        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < $this->nameValuePair . count; $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
    } 

    /*
     * Returns the Users Screen name (login).
     */    
    function WIMBA_getScreenName()
    {
        return $this->hparams["screen_name"];
    } 
    /*
     * Returns a Users e-mail address.
     */   
    function WIMBA_getEmail()
    {
        return $this->hparams["email"];
    } 

    function WIMBA_setScreenName($screenName)
    {
        $this->hparams["screen_name"] = $screenName;
    } 

    function WIMBA_setEmail($email)
    {
        $this->hparams["email"] = $email;
    } 
    
    /**
     * This function build a "Pairset"  thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getUser()
    {
        $this->nameSetPair[0] = null; 
        // $this->nameValuePair = new Hvoiceemail_LOGSoiceBoardApi.vbWebService.NameValuePair[hparams.Count];
        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * Data structure representing a Message.
 * <p>
 * The Message object is used only for creating sessions on Voice Authoring
 * resources (<code>type=recorder</code>).
 */
class WIMBA_vtMessage {
    var $pairset;
    var $nameSetPair = array();
    var $nameValuePair = array();
    var $hparams;
    var $error;
    var $error_message;

    function WIMBA_vtMessage($pairset=null)
    {
        $status_code = "";
        $this->pairset = $pairset;
        $this->nameSetPair = $pairset["groups"];
        $this->nameValuePair = $pairset["values"];
        if ($this->nameValuePair != null) 
        {
            for ($i = 0; $i < count($this->nameValuePair); $i++) 
            {
                if ($this->nameValuePair[$i]["name"] == "status_code") 
                {
                    $status_code = $this->nameValuePair[$i]["value"];
                    if (!$status_code == "ok") 
                    {
                        $this->error_message = null;
                        for ($j = 0; $j < $this->nameValuePair . count; $j++) 
                        {
                            if ($this->nameValuePair[$j]["name"] == "message") 
                            {
                                $this->error_message = $this->nameValuePair[$j]["name"];
                            } 
                        } 
                        $this->error = true;
                    } 
                } 
                $this->hparams[$this->nameValuePair[$i]["name"]] = $this->nameValuePair[$i]["value"];
            } 
        } 
    } 
    /*
     * Gets the Messages ID.
     */
    function WIMBA_getMid()
    {
        return $this->hparams["mid"];
    } 

    function WIMBA_setMid($mid)
    {
        $this->hparams["mid"] = $mid;
    } 
    
    /**
     * This function build a "Pairset"  thanks to the informations 
     * acontained on the associative array (hparams)
     */
    function WIMBA_getMessage()
    {
        $this->nameSetPair[0] = null; 
        // $this->nameValuePair = new Hvoiceemail_LOGSoiceBoardApi.vbWebService.NameValuePair[hparams.Count];
        $i = 0;
        if ($this->hparams != null) 
        {
            foreach ($this->hparams as $name => $value) 
            {
                $this->nameValuePair[$i]["name"] = $name;
                $this->nameValuePair[$i]["value"] = $value;
                $i++;
            } 
        } 
        $this->pairset["groups"] = $this->nameSetPair;
        $this->pairset["values"] = $this->nameValuePair;

        return $this->pairset;
    } 
} 

/**
 * Associates session information with a timestamp.
 * <p>
 * The <code>SessionInfo</code> object is returned by the
 * <code>createSession</code> method. This object encapsulates a
 * <code>Resource</code> object, an optional
 * <code>Message</code> object, an optional <code>User</code>
 * object and a <code>Rights</code> object. Moreover, the
 * <code>SessionInfo</code> object contains a nid, which is the
 * unique session identifier. The <code>createSession</code> method
 * will create a session for a given user on a given application and will
 * grant this user given rights.
 */
class WIMBA_vtSessionInfo {
    var $nid;
    var $user;
    var $rigths;
    var $resource;
    var $isOpen;
    var $error;
    function WIMBA_vtSessionInfo($pairset)
    {
        $groups = $pairset["groups"];
        for ($i = 0; $i < count($groups);$i++) 
        {
            if ($groups[$i]["name"] == "error") 
            {
                $this->error = "error";
            } 
            else if ($groups[$i]["name"] == "user") 
            {
                $this->user = new WIMBA_vtUser($groups[$i]["pairSet"]);
            }
            else if ($groups[$i]["name"] == "rights") 
            {
                $this->rigths = new WIMBA_vtRights($groups[$i]["pairSet"]);
            } 
            else if ($groups[$i]["name"] == "resource") 
            {
                $this->resource = new WIMBA_vtResource($groups[$i]["pairSet"]);
            } 
        } 

        $value = $pairset["values"];
        for ($i = 0; $i < count($value); $i++) {
            if ($value[$i]["name"] == "nid") {
                $this->nid = $value[$i]["value"];
            } 
        } 
    } 

    function WIMBA_getNid()
    {
        return $this->nid;
    } 
    
    function WIMBA_getUser()
    {
        return $this->user;
    }
     
    function WIMBA_getRigths()
    {
        return $this->rigths;
    } 

    function WIMBA_getresource()
    {
        return $this->resource;
    } 

    function WIMBA_getIsOpen()
    {
        return $this->isOpen;
    } 
    
    function WIMBA_setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;
    } 
} 

?>

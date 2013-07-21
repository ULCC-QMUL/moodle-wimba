<?php
/*
 * Created on Jun 12, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
require_once("PrefixUtil.php");
define("MAX_NICKNAME" , "64");
define("NICKNAME_CHARS", "a-zA-Z0-9_");
define("NON_NICKNAME_CHARS_REGEXP", "[^a-zA-Z0-9_]");
define("NICKNAME_REGEXP", "[^a-zA-Z0-9_]{1,64}");

define("MAX_FIRSTNAME", "64");
define("FIRSTNAME_CHARS", "a-zA-Z0-9_");
define("NON_FIRSTNAME_CHARS_REGEXP", "[^a-zA-Z0-9_]");
define("FIRSTNAME_REGEXP", "[^a-zA-Z0-9_]{1,64}");

define("MAX_LASTNAME", "64");
define("LASTNAME_CHARS", "a-zA-Z0-9_");
define("NON_LASTNAME_CHARS_REGEXP", "[^a-zA-Z0-9_]");
define("LASTNAME_REGEXP", "[^a-zA-Z0-9_]{1,64}");

define("MAX_USERID", "54");
define("USERID_CHARS", "a-zA-Z0-9_");
define("NON_USERID_CHARS_REGEXP", "[^a-zA-Z0-9_]");
define("USERID_REGEXP", "[^a-zA-Z0-9_]{1,64}");

define("ATTRIB_USER_ID", "user_id");
define("ATTRIB_FIRST_NAME", "first_name");
define("ATTRIB_LAST_NAME", "last_name"); 
// Predefined user in Life Classroom server used for Guest access
define("USER_ID_GUEST", "Guest");

class WIMBA_LCUser {

    var $firstName = null;
    var $lastName = null;
    var $userId = null;
    
    function WIMBA_LCUser($props, $prefix)
    {
        $prefixUtil = new WIMBA_PrefixUtil();
        $userID = $prefixUtil->WIMBA_trimPrefix($props[ATTRIB_USER_ID], $prefix);
        $this->userId = $userID;
        $this->WIMBA_setFirstName($props[ATTRIB_FIRST_NAME]);
        $this->WIMBA_setLastName($props[ATTRIB_LAST_NAME]);
    } 
    
    /**
     * 
     * @return 
     */
    function WIMBA_getFirstName()
    {
        return $this->firstName;
    } 
    /**
     * 
     * @return 
     */
    function WIMBA_getLastName()
    {
        return $this->lastName;
    } 
    /**
     * 
     * @return 
     */
    function WIMBA_getUserId()
    {
        return $this->userId;
    } 
    /**
     * 
     * @param string $ 
     */
    function WIMBA_setFirstName($firstName)
    {
        if ($this->firstName != null) 
        {
            $this->firstName = substr(preg_replace(trim($firstName), NON_FIRSTNAME_CHARS_REGEXP, "_"), MAX_FIRSTNAME);
        } 
        else 
        {
            $this->firstName = "";
        } 
    } 
    /**
     * 
     * @param string $ 
     */
    function WIMBA_setLastName($lastName)
    {
        if ($this->lastName != null) 
        {
            $this->lastName = substr(preg_replace(trim($lastName), NON_LASTNAME_CHARS_REGEXP, "_"), MAX_LASTNAME);
        } 
        else 
        {
            $this->lastName = "";
        } 
    } 
    /**
     * 
     * @param string $ 
     */
    function WIMBA_setUserId($userId)
    {
        if ($this->userId != null) 
        {
            $this->userId = substr(preg_replace(trim($userId), NON_USERID_CHARS_REGEXP, "_"), MAX_USERID);
        } 

    } 
    /**
     * getNickname
     * 
     * @return 
     */
    function WIMBA_getNickname()
    {
        $ret = $this->firstName + "_" + $this->lastName;
        if (strlen($ret) > MAX_USERID)
        {
            return substr($ret, 0, MAX_USERID);
        }
        return $ret;
    } 
} 

?>

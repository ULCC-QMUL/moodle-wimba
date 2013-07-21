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

     
/* $Id: $ */  
class WIMBA_WimbaXml
{
    var $xmldoc;
    var $part=array(); //contains the different part of the UI
    var $linePart=array();
    var $lineElement=array();  
    var $panelLines=array();  
    var $validationElements;
    var $Informations;
    var $error=NULL;
    var $finalstring;
      
    function WIMBA_WimbaXml()
    {           
        $this->xmldoc = WIMBA_domxml_new_doc("1.0");
    }
    
    /*
     * This function generate the global xml which will render the html
     * Each element of $part is grouped in only one xml
     */
    function WIMBA_getXml() 
    {
        if( empty($this->finalstring) )
        {
            $root = $this->xmldoc->WIMBA_create_element("root");
            $windows = $this->xmldoc->WIMBA_create_element("windows");
       
            if( $this->error == NULL )
            {
                if(isset($this->Informations))
                    $root->WIMBA_append_child($this->Informations);         
                      
                foreach ($this->part  as $key => $value)
                {
                    $windows->WIMBA_append_child($this->WIMBA_addWindowsElement($key, $value));
                }                
            }
            else
            {
                $windows->WIMBA_append_child($this->WIMBA_addWindowsElement("error",$this->error));    
            }
        
            $root->WIMBA_append_child($windows);
            $this->xmldoc->WIMBA_append_child($root);
            $xmlstring = $this->xmldoc->WIMBA_dump_mem(true);  // Xml datas into a string
            $this->finalstring = str_replace("\n", '', $xmlstring);
        }
        return $this->finalstring;    
    }
    
     /*
     * Add the headerBar element. This element is the bar at the top of the component. 
     * It composed by a logo and a drop down to switch the display of the component
     * @param pictureUrl : path of the logo
     * @param disabled : manage the disabled parameter for the drop down 
     * @param isInstructor : role of the current user( the drop down is never displayed for student)
     */   
    function WIMBA_addHeaderElement($pictureUrl,$disabled,$isInstructor)
    {
        if (!isset($this->part["headerBar"]))
        {
            $this->part["headerBar"]=$this->xmldoc->WIMBA_create_element("headerBarInformations");
        }
        
        $picture = $this->xmldoc->WIMBA_create_element("pictureUrl");
        $picture->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($pictureUrl));   
        $disable = $this->xmldoc->WIMBA_create_element("disabled");
        $disable->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($disabled));  
        $hbinstructorview = $this->xmldoc->WIMBA_create_element("instructorView");
        $hbinstructorview->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(get_string('instructorview', 'voiceboard')));
        $hbstudentview = $this->xmldoc->WIMBA_create_element("studentView");
        $hbstudentview->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(get_string('studentview', 'voiceboard')));
        $isInstructorElement = $this->xmldoc->WIMBA_create_element("isInstructor");
        $isInstructorElement->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($isInstructor));   
        $this->part["headerBar"]->WIMBA_append_child($picture);  
        $this->part["headerBar"]->WIMBA_append_child($hbinstructorview);  
        $this->part["headerBar"]->WIMBA_append_child($hbstudentview);  
        $this->part["headerBar"]->WIMBA_append_child($disable); 
        $this->part["headerBar"]->WIMBA_append_child($isInstructorElement);   
    }
    
    /*
     * Add a Filter to the filterBar element.
     * @param value: 
     * @param name:
     * @param action:
     * @param availibility:
     */
    function WIMBA_addFilter($value,$name,$action,$availibility)
    {
        if (!isset($this->part["filterBar"]))//first elemet of the filterBar
        {
            //creation of the filterbar element
            $this->part["filterBar"]= $this->xmldoc->WIMBA_create_element("filters");
        }
        
        $filter = $this->xmldoc->WIMBA_create_element('filter');
        $filterValue = $this->xmldoc->WIMBA_create_element("value");
        $filterValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
        $filter->WIMBA_append_child($filterValue);
        $filterName = $this->xmldoc->WIMBA_create_element("name");
        $filterName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));
        $filter->WIMBA_append_child($filterName);
        $filterValue = $this->xmldoc->WIMBA_create_element("value");
        $filterValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
        $filter->WIMBA_append_child($filterValue);
        $filterAvailibility = $this->xmldoc->WIMBA_create_element("availibility");
        $filterAvailibility->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($availibility));
        $filter->WIMBA_append_child($filterAvailibility);
        $filterAction = $this->xmldoc->WIMBA_create_element("action");
        $filterAction->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($action));
        $filter->WIMBA_append_child($filterAction);   
        $this->part["filterBar"]->WIMBA_append_child($filter);
    }

    
    /*
     * Add the contextBar  element. This element is the bar after the header bar(Choice product panel and settings panel). 
     * @param context : context of the WIMBA_display ( settings or other)
     * @param product : current product selected
     * @param name : name of the resource
     * @param style : style that you want to apply to the bar
     */   
    function WIMBA_addContextBarElement($context, $product="",$name="",$style="")
    {
        if (!isset($this->part["contextBar"]))  
        {
            $this->part["contextBar"] = $this->xmldoc->WIMBA_create_element("contextBarInformations");
        }
        
        $contextBarType = $this->xmldoc->WIMBA_create_element("context");
        $contextBarType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($context));
        $contextBarStyle = $this->xmldoc->WIMBA_create_element("style");
        $contextBarStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($style));
        $contextBarProduct = $this->xmldoc->WIMBA_create_element("product");
        $contextBarProduct->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($product));
        $contextBarName = $this->xmldoc->WIMBA_create_element("name");
        $contextBarName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));   
        $this->part["contextBar"]->WIMBA_append_child($contextBarType);
        $this->part["contextBar"]->WIMBA_append_child($contextBarProduct);
        $this->part["contextBar"]->WIMBA_append_child($contextBarName);
        $this->part["contextBar"]->WIMBA_append_child($contextBarStyle);
    }

    /*
     * Add a new button to the toolBar
     * @param typeOfUser : type of user which can see the button
     * @param typeOfProduct : type of product for which the button is available
     * @param availibility : availability by default(no room/resource selected)
     * @param category : the type of button( use for css)
     * param  value : text under the button
     * param  action : javascript function called by clicking on the button
     */   
    function WIMBA_addButtonElement($typeOfUser, $typeOfProduct, $availibility="", $category="", $value="", $action="")
    {
        if (!isset($this->part["toolBar"]))
        {
            $this->part["toolBar"]=$this->xmldoc->WIMBA_create_element("menuElements");
        }
        
        $element = $this->xmldoc->WIMBA_create_element("menuElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("button"));
        $elementTypeOfUser = $this->xmldoc->WIMBA_create_element("typeOfUser");//for student and isntructor or just for instructor
        $elementTypeOfUser->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($typeOfUser));
        $elementTypeOfProduct = $this->xmldoc->WIMBA_create_element("typeOfProduct");//for student and isntructor or just for instructor
        $elementTypeOfProduct->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($typeOfProduct));
        $elementCategory = $this->xmldoc->WIMBA_create_element("category");
        $elementCategory->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($category));
        $elementValue = $this->xmldoc->WIMBA_create_element("value");
        $elementValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
        $elementAvailibility = $this->xmldoc->WIMBA_create_element("availibility");
        $elementAvailibility->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($availibility));
        $elementAction = $this->xmldoc->WIMBA_create_element("action");
        $elementAction->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($action));
        $element->WIMBA_append_child($elementAvailibility);
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementTypeOfUser);
        $element->WIMBA_append_child($elementTypeOfProduct);
        $element->WIMBA_append_child($elementCategory);
        $element->WIMBA_append_child($elementValue);
        $element->WIMBA_append_child($elementAction);
        $this->part["toolBar"]->WIMBA_append_child($element);
    }

    
    /*
     * Add a space element to the toolbar
     * @param width : width of the space
     * @param typeOfUSer : type of user for which the space is available
     */ 
    function WIMBA_addSpaceElement($width, $typeOfUser)
    {
        if (!isset($this->part["toolBar"]))
        {
            $this->part["toolBar"]=$this->xmldoc->WIMBA_create_element("menuElements");
        }
        $element = $this->xmldoc->WIMBA_create_element("menuElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("space"));
        $elementTypeOfUser = $this->xmldoc->WIMBA_create_element("typeOfUser");//for student and isntructor or just for instructor
        $elementTypeOfUser->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($typeOfUser));
        $elementWidth = $this->xmldoc->WIMBA_create_element("width");//for student and isntructor or just for instructor
        $elementWidth->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($width));    
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementTypeOfUser);
        $element->WIMBA_append_child($elementWidth);
        $this->part["toolBar"]->WIMBA_append_child($element);
    }

    /*
     * Add a separator to the toolbar
     * @param typeOfUSer : type of user for which the separator is available
     */ 
    function WIMBA_addSeparatorElement($typeOfUser)
    {
        if (!isset($this->part["toolBar"]))
        {
            $this->part["toolBar"]=$this->xmldoc->WIMBA_create_element("menuElements");
        }
        $element = $this->xmldoc->WIMBA_create_element("menuElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("separator"));
        $elementTypeOfUser = $this->xmldoc->WIMBA_create_element("typeOfUser");//for student and isntructor or just for instructor
        $elementTypeOfUser->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($typeOfUser));
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementTypeOfUser);
        $this->part["toolBar"]->WIMBA_append_child($element);
    }
    
    /*
     * Add a search element to the toolbar
     * @param typeOfUSer : type of user for which the separator is available
     * @param browser : type of borwser(safari has a speial html element for the search)
     */ 
    function WIMBA_addSearchElement($isInstructor,$browser,$disableSelectView='false')
    {
        if (!isset($this->part["toolBar"]))
        {
            $this->part["toolBar"]=$this->xmldoc->WIMBA_create_element("menuElements");
        }
        $element = $this->xmldoc->WIMBA_create_element("menuElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("search"));

        $elementBrowser = $this->xmldoc->WIMBA_create_element("browser");//for student and isntructor or just for instructor
        $elementBrowser->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($browser)); 
         
        $disable = $this->xmldoc->WIMBA_create_element("disabled");
        $disable->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($disable));  
        $hbinstructorview = $this->xmldoc->WIMBA_create_element("instructorView");
        $hbinstructorview->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(get_string('instructorview', 'voiceboard')));
        $hbstudentview = $this->xmldoc->WIMBA_create_element("studentView");
        $hbstudentview->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(get_string('studentview', 'voiceboard')));
        $isInstructorElement = $this->xmldoc->WIMBA_create_element("isInstructor");
        $isInstructorElement->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($isInstructor));   
         
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementBrowser);
        $element->WIMBA_append_child($hbinstructorview);  
         $element->WIMBA_append_child($hbstudentview);  
         $element->WIMBA_append_child($disable); 
         $element->WIMBA_append_child($isInstructorElement); 
        
        $this->part["toolBar"]->WIMBA_append_child($element);
        
    }

     /*
     * Add a product and his element to the list
     * @param name : name of the product ( LC or VT)
     * @param cssStyle : css which will be apply to the title bar
     * @param value : name of the tools( vb,vp,podcaster, lc..)
     * @param type : type of the tools(use for the search)
     * @param elements :  elements of the list( already xml elements) 
     * @param sentence : sentence when no elements are available
     */ 
    function WIMBA_addProduct($name, $cssStyle, $value,$type,$elements,$sentence,$arrayTitle)
    {
        if (!isset($this->part["list"]))
        {
            $this->part["list"]=$this->xmldoc->WIMBA_create_element("products");
        }
        
        $product= $this->xmldoc->WIMBA_create_element("product");
        $productName= $this->xmldoc->WIMBA_create_element("productName");
        $productName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));
        $product->WIMBA_append_child($productName);
        $productCssStyle = $this->xmldoc->WIMBA_create_element("style");//for student and isntructor or just for instructor
        $productCssStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($cssStyle));
        $product->WIMBA_append_child($productCssStyle);
        $productValue = $this->xmldoc->WIMBA_create_element("value");//for student and isntructor or just for instructor
        $productValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
        $product->WIMBA_append_child($productValue);
        $productType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $productType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($type));
        $product->WIMBA_append_child($productType);
        
        if(count($arrayTitle)>0)
        {
            $titles = $this->xmldoc->WIMBA_create_element("titles");
            if($arrayTitle!=null){
                foreach ($arrayTitle as $key => $string)
                {
                      $title = $this->xmldoc->WIMBA_create_element("title");//for student and isntructor or just for instructor
                      $value = $this->xmldoc->WIMBA_create_element("value");
                      $value->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($string));
	    
                      $title->WIMBA_append_child($value);
                      $titles->WIMBA_append_child($title);
                }
            }
            $product->WIMBA_append_child($titles);
        }
        
        if(count($elements)>0)
        {
            $listElements = $this->xmldoc->WIMBA_create_element("listElements");
            if($elements!=null){
                foreach ($elements as $key => $value)
                {
                    $listElements->WIMBA_append_child($value->WIMBA_getXml($this->xmldoc)); 
                }
                }
            $product->WIMBA_append_child($listElements);
        }
        $productSentence = $this->xmldoc->WIMBA_create_element("NoElementSentence");//for student and isntructor or just for instructor
        $productSentence->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($sentence));
        $product->WIMBA_append_child($productSentence);
        $this->part["list"]->WIMBA_append_child($product);
    }
    
    /*
     * Return a message Elment
     * @param type : type of message ( error or something else)
     * @param sentemce : sentence of the message
     */ 
    function WIMBA_createMessageElement($type, $sentemce)
    {
        $message = $this->xmldoc->WIMBA_create_element("message");
        $messageType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
        $messageType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($type));
        $messageValue = $this->xmldoc->WIMBA_create_element("value");//for student and isntructor or just for instructor
        $messageValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($sentemce));
        $message->WIMBA_append_child($messageType);
        $message->WIMBA_append_child($messageValue);
        return $message;
    }
    
    /*
     * Create the information element which contains different parameters
     * @param timeOfLoad : time when the component was loading
     * @param firstName : first name of the current user
     * @param lastName : llast name of the current user
     * @param email : email of the current user
     * @param role : role of the current user
     * @param courseId : course id of the current course
     * @param signature : signature to avoid bad connexion
     * @picturesToLoad : list of pictures used on the integration to manage the preload of them
     *      
     */ 
    function WIMBA_CreateInformationElement($timeOfLoad, $firstName, $lastName, $email, $role, $courseId, $signature,$picturesToLoad=null)
    {
        $this->Informations = $this->xmldoc->WIMBA_create_element("information");
        $etimeOfLoad = $this->xmldoc->WIMBA_create_element("timeOfLoad");
        $etimeOfLoad->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($timeOfLoad));
        $efirstName = $this->xmldoc->WIMBA_create_element("firstName");
        $efirstName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($firstName)));
        $elastName = $this->xmldoc->WIMBA_create_element("lastName");
        $elastName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($lastName)));
        $eemail = $this->xmldoc->WIMBA_create_element("email");
        $eemail->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($email)));
        $erole = $this->xmldoc->WIMBA_create_element("role");
        $erole->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($role)));
        $ecourseId = $this->xmldoc->WIMBA_create_element("courseId");
        $ecourseId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($courseId)));
        $esignature = $this->xmldoc->WIMBA_create_element("signature");
        $esignature->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node(WIMBA_wimbaEncode($signature)));
        if($picturesToLoad!=null)
        {
            $epicturesToLoad = $this->xmldoc->WIMBA_create_element("picturesToLoad");
            $epicturesToLoad->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($picturesToLoad));
            $this->Informations->WIMBA_append_child($epicturesToLoad);
        }
        
        $this->Informations->WIMBA_append_child($etimeOfLoad);
        $this->Informations->WIMBA_append_child($efirstName);
        $this->Informations->WIMBA_append_child($elastName);
        $this->Informations->WIMBA_append_child($erole);
        $this->Informations->WIMBA_append_child($ecourseId);
        $this->Informations->WIMBA_append_child($esignature);
        $this->Informations->WIMBA_append_child($eemail);
 
    }
    
    /*
     * Create a windows element, This element 
     * @param type : type of the part
     * @param elementPart : xml of the part    
     */ 
    function WIMBA_addWindowsElement($type, $xmlElementPart)
    {
        $windowsElement = $this->xmldoc->WIMBA_create_element("windowsElement");
        $windowsElementType = $this->xmldoc->WIMBA_create_element("type");
        $windowsElementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($type));
        $windowsElement->WIMBA_append_child($windowsElementType);
        $windowsElement->WIMBA_append_child($xmlElementPart);
        return $windowsElement;
    }
    
    /*
     * Add a new tool in the choice panel
     * param pictureUrl : path of the tool picture
     * param name : name of tools
     * param description : description of the choice
     * param action :  javascript action behind the button 
     */ 
    function WIMBA_addProductChoice($pictureUrl,$name,$description,$action){
    
        if (!isset($this->part["productChoice"]))
        {
            $this->part["productChoice"]=$this->xmldoc->WIMBA_create_element("products");
        }
        
        $product = $this->xmldoc->WIMBA_create_element("product");
        $productPictureUrl =  $this->xmldoc->WIMBA_create_element('pictureUrl');
        $productPictureUrl->WIMBA_append_child( $this->xmldoc->WIMBA_create_text_node($pictureUrl));
        $product->WIMBA_append_child($productPictureUrl);
        $productValue =  $this->xmldoc->WIMBA_create_element('value');
        $productValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));
        $product->WIMBA_append_child($productValue);
        $productDescription = $this->xmldoc->WIMBA_create_element('description');
        $productDescription->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($description));
        $product->WIMBA_append_child($productDescription);
        $productAction = $this->xmldoc->WIMBA_create_element("action");
        $productAction->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($action));  
        $product->WIMBA_append_child($productAction);
        $this->part["productChoice"]->WIMBA_append_child($product);
    }
       
    /*
     * Add a new element to the elements stack. An element can be all the basic html element(input, label....)
     * @param type: type of the html element
     * @parm display : string that have to be displayed
     * @param parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */
    function WIMBA_addSimpleLineElement($type,$display="",$parameters=NULL)
    {
        $element = $this->xmldoc->WIMBA_create_element("lineElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($type));
        $element->WIMBA_append_child($elementType);
        if($display!="")
            {
            $elementDisplay = $this->xmldoc->WIMBA_create_element("display");
            $elementDisplay->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($display));
            $element->WIMBA_append_child( $elementDisplay);
        }       
        if($parameters !=NULL)
        { 
            $elementParameters = $this->xmldoc->WIMBA_create_element("parameters");
            foreach ($parameters as $key => $value)
            {
                $parameter = $this->xmldoc->WIMBA_create_element("parameter");
                $parameterName = $this->xmldoc->WIMBA_create_element("name");
                $parameterName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($key));
                $parameterValue = $this->xmldoc->WIMBA_create_element("value");
                if (isset($value))
                {
                    $parameterValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value)); 
                }
                $parameter->WIMBA_append_child($parameterName);
                $parameter->WIMBA_append_child($parameterValue);
                $elementParameters->WIMBA_append_child($parameter);
            }
            $element->WIMBA_append_child($elementParameters);
        }
        $this->lineElement[]=$element;
    }

    /*
     * Add a custom element which is a concatenation of two element like * + something
     * @parm first_part : first string
     * @parm display : string that have to be displayed
     * @param parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */
    function WIMBA_addCustomLineElement($firstPart,$firstStyle,$secondPart,$parameters=NULL)
    {
        $element = $this->xmldoc->WIMBA_create_element("lineElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("custom"));
        $element->WIMBA_append_child($elementType);

        $elementFirstPart = $this->xmldoc->WIMBA_create_element("firstPart");
        $elementFirstPart->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($firstPart));
        $element->WIMBA_append_child( $elementFirstPart);

        $elementSecondPart = $this->xmldoc->WIMBA_create_element("secondPart");
        $elementSecondPart->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($secondPart));
        $element->WIMBA_append_child( $elementSecondPart);
        
        $elementfirstStyle = $this->xmldoc->WIMBA_create_element("firstStyle");
        $elementfirstStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($firstStyle));
        $element->WIMBA_append_child( $elementfirstStyle);
               
        if($parameters !=NULL)
        { 
            $elementParameters = $this->xmldoc->WIMBA_create_element("parameters");
            foreach ($parameters as $key => $value)
            {
                $parameter = $this->xmldoc->WIMBA_create_element("parameter");
                $parameterName = $this->xmldoc->WIMBA_create_element("name");
                $parameterName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($key));
                $parameterValue = $this->xmldoc->WIMBA_create_element("value");
                if (isset($value))
                {
                    $parameterValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value)); 
                }
                $parameter->WIMBA_append_child($parameterName);
                $parameter->WIMBA_append_child($parameterValue);
                $elementParameters->WIMBA_append_child($parameter);
            }
            $element->WIMBA_append_child($elementParameters);
        }
        $this->lineElement[]=$element;
    }
        
    /*
     * Add a new input element 
     * @param parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */
    function WIMBA_addInputElement($parameters=NULL)
    {
        $this->WIMBA_addSimpleLineElement("input","",$parameters);
    }
    
    /*
     * Add a new textarea element 
     * @param parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */
    function WIMBA_addTextAreaElement($parameters=NULL,$display)
    {
        $this->WIMBA_addSimpleLineElement("textarea",$display,$parameters);
    }
    
    function WIMBA_addDivLineElement($type, $displayContext,$parameters)
    {
        $element = $this->xmldoc->WIMBA_create_element("lineElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("div"));
        $elementDisplayContext = $this->xmldoc->WIMBA_create_element("displayContext");
        $elementDisplayContext->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($displayContext));
        $elementParameters = $this->xmldoc->WIMBA_create_element("parameters");
        for ($i = 0; $i < count($parameters);$i++)
        {
            $parameter = $this->xmldoc->WIMBA_create_element("parameter");
            $parameterName = $this->xmldoc->WIMBA_create_element("name");
            $parameterName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters[$i]));
            $parameterValue = $this->xmldoc->WIMBA_create_element("value");
            if ($parameters.GetByIndex(i) != NULL)
            {
                $parameterValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters[$i]));
            }
            $parameter->WIMBA_append_child($parameterName);
            $parameter->WIMBA_append_child($parameterValue);
            $elementParameters->WIMBA_append_child($parameter);
        }
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementDisplayContext);
        $element->WIMBA_append_child($elementParameters);
        $this->lineElement[]=$element;
    }
    
    /*
     * Add a option element 
     * @param name : name of the elemtn
     * @param id : id of the element
     * @param listOptions : array of options ( each option contains an array of attributes)
     * @param disabled : manage the disable attribute of the html element
     */
    function WIMBA_createOptionElement($name,$id, $listOptions,$disabled="",$style="")
    {
        $element = $this->xmldoc->WIMBA_create_element("lineElement");
        $elementType = $this->xmldoc->WIMBA_create_element("type");
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("select"));
        $elementDisplayContext = $this->xmldoc->WIMBA_create_element("displayContext");
        $elementDisplayContext->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("all"));
        $elementStyle = $this->xmldoc->WIMBA_create_element("style");
        $elementStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($style));
        $elementName = $this->xmldoc->WIMBA_create_element("name");
        $elementName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));

        if($disabled!="")
        {
            $elementDisabled = $this->xmldoc->WIMBA_create_element("disabled");
            $elementDisabled->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($disabled));
            $element->WIMBA_append_child($elementDisabled);   
        }
        
        $elementId = $this->xmldoc->WIMBA_create_element("id");
        $elementId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($id));
        $options=$this->xmldoc->WIMBA_create_element("options");
        
        for ($j = 0; $j < count($listOptions); $j++)
        {
            $parameters=$listOptions[$j];
            $option=$this->xmldoc->WIMBA_create_element("option");
            foreach ($parameters as $key=>$value)
            {
                $optionParameter = $this->xmldoc->WIMBA_create_element($key);
                $optionParameter->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
                $option->WIMBA_append_child($optionParameter);
            }
            $options->WIMBA_append_child($option);
        }
        
        $element->WIMBA_append_child($elementType);
        $element->WIMBA_append_child($elementDisplayContext);
        $element->WIMBA_append_child($elementName);
        $element->WIMBA_append_child($elementId);
        $element->WIMBA_append_child($elementStyle);
        $element->WIMBA_append_child($options);
        $this->lineElement[]=$element;
    }
    
    /*
     * Create a linePart element which correspond to a <td> element.
     * This function add the elements of the stack LineElement to a new LinePartElement 
     * and add it to the LinePart stack
     * @param $parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */
    function WIMBA_createLinePart($parameters=NULL)
    {
        $panelLinePart = $this->xmldoc->WIMBA_create_element("panelLinePart");
        if(isset($parameters["style"]))
        {
            $elementStyle = $this->xmldoc->WIMBA_create_element("style");
            $elementStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters["style"]));
            $panelLinePart->WIMBA_append_child($elementStyle);
        }
        if(isset($parameters["align"]))
        {
            $elementAlign = $this->xmldoc->WIMBA_create_element("align");
            $elementAlign->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters["align"]));
            $panelLinePart->WIMBA_append_child($elementAlign);
        }
        if(isset($parameters["colspan"]))
        {
            $elementColpsan = $this->xmldoc->WIMBA_create_element("colspan");
            $elementColpsan->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters["colspan"]));
            $panelLinePart->WIMBA_append_child($elementColpsan);
        }
        if(isset($parameters["id"]))
        {
            $elementId = $this->xmldoc->WIMBA_create_element("id");
            $elementId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters["id"]));
            $panelLinePart->WIMBA_append_child($elementId);
        }
        if(isset($parameters["context"]))
        {
            $elementContext = $this->xmldoc->WIMBA_create_element("context");
            $elementContext->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($parameters["context"]));
            $panelLinePart->WIMBA_append_child($elementContext);
        }
        for ($j = 0; $j < count($this->lineElement); $j++)
        {
            $panelLinePart->WIMBA_append_child($this->lineElement[$j]);
        }
        $this->linePart[]=$panelLinePart;
        $this->lineElement=array();//clear the tab lineElement
    }
    
    /*
     * Create a line element which correspond to a <tr> element.
     * This function add the elements of the stack LinePart to a new panelLine 
     * and add it to the panelLines stack
     * @param style : style of the <tr>
     * @param id: id of the <tr>
     */
    function WIMBA_createLine( $style="", $context="",  $id="")
    {
        $line = $this->xmldoc->WIMBA_create_element("panelLine");
        $lineStyle = $this->xmldoc->WIMBA_create_element("style");
        $lineStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($style));
        $line->WIMBA_append_child($lineStyle);
        //necessary for hidden div
        $lineId = $this->xmldoc->WIMBA_create_element("id");
        $lineId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($id));
        $line->WIMBA_append_child($lineId);
        
        $lineContext = $this->xmldoc->WIMBA_create_element("context");
        $lineContext->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($context));
        $line->WIMBA_append_child($lineContext);
        
        for ($i = 0; $i < count($this->lineElement);$i++)
        {
            $line->WIMBA_append_child($this->lineElement[$i]);
        }
        $this->panelLines[]=$line;
        $this->lineElement=array();
    }
    
    
    /*
     * Add a button to the validation bar(at the bottom of the settings)
     * and add it to the panelLines stack
     * @param value : text under the button
     * @param style : style apply to the button
     * param  action : javascript function called by clicking on the button 
     * @param id: id of the button
     */    
    function WIMBA_createValidationButtonElement($value,$type,$action,$id,$style="")
    {
        if (!isset($this->part["validationBar"]))
        {
            $this->part["validationBar"]=$this->xmldoc->WIMBA_create_element("validationElements");
        }
        $element=$this->xmldoc->WIMBA_create_element("validationButton");
        $elementaction = $this->xmldoc->WIMBA_create_element("action");
        $elementaction->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($action));
        $elementValue = $this->xmldoc->WIMBA_create_element("value");
        $elementValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
        $elementType = $this->xmldoc->WIMBA_create_element("type");
        $elementType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($type));
        $elementStyle = $this->xmldoc->WIMBA_create_element("style");
        $elementStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($style));
        $elementId = $this->xmldoc->WIMBA_create_element("id");
        $elementId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($id));
        $element->WIMBA_append_child($elementaction);   
        $element->WIMBA_append_child($elementType);   
        $element->WIMBA_append_child($elementValue);   
        $element->WIMBA_append_child($elementId);   
        $element->WIMBA_append_child($elementStyle);   
        $this->part["validationBar"]->WIMBA_append_child($element);
    }
    
    /*
     * Add a comment(string) to the validation bar(at the bottom of the settings)
     * and add it to the panelLines stack
     * @param $parameters : array of WIMBA_attributes ( Key = name of the attribute, Value = value of the attribute) 
     */    
    function WIMBA_createValidationCommentElement($parameters)
    {
        if (!isset($this->part["validationBar"]))
        {
            $this->part["validationBar"]=$this->xmldoc->WIMBA_create_element("validationElements");
        }
        $element = $this->xmldoc->WIMBA_create_element("validationElement");
        $name = $this->xmldoc->WIMBA_create_element("type");
        $name->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("validationComment"));
        foreach ($parameters  as $key => $value)
        {
            $parameterName = $this->xmldoc->WIMBA_create_element($key);
            $parameterName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));
            $element->WIMBA_append_child($parameterName);  
        }
        $element->WIMBA_append_child($name);
        $this->part["validationBar"]->WIMBA_append_child($element);
    }
   
     /*
     * Create a panel settings element which represent a part of the settings
     * This element is composed by two part
     *  -The first one manage the top of the tab ( use to navigate between the tab)
     *  -The second manage the content of the tab (form). This part is created with the elements
     * contained in the panelLines stack
     * @param name : name of the navigation tab
     * @param display : string which is displayed in the navigation tab
     * @param id : id of the navigation tab
     * @param style : style applied to the tab
     * @param contextDisplay : manage the default avaibility of the tab
     * @param additionalFunction : function called when you go out the advanced tab
     */   
    function WIMBA_createPanelSettings($name, $display, $id,$style, $contextDisplay,$additionalFunction="")
    {
        if (!isset($this->part["tabs"]))
        {
            $this->part["tabs"]=$this->xmldoc->WIMBA_create_element("tabsInformations");
        }
        if (!isset($this->part["tabsContent"]))
        {
            $this->part["tabsContent"]=$this->xmldoc->WIMBA_create_element("tabsContent");
        }
        $panelSettings = $this->xmldoc->WIMBA_create_element("tabInformation");
        $divName = $this->xmldoc->WIMBA_create_element("name");
        $divName->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($name));
        $divDisplay = $this->xmldoc->WIMBA_create_element("display");
        $divDisplay->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($display));
        $divId = $this->xmldoc->WIMBA_create_element("id");
        $divId->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($id));
        $divStyle = $this->xmldoc->WIMBA_create_element("style");
        $divStyle->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($style));
        $divContextDisplay = $this->xmldoc->WIMBA_create_element("contextDisplay");
        $divContextDisplay->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($contextDisplay));
        if($additionalFunction!="")
        {
            $divContextAdditionalFunction = $this->xmldoc->WIMBA_create_element("additionalFunction");
            $divContextAdditionalFunction->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($additionalFunction));
            $panelSettings->WIMBA_append_child($divContextAdditionalFunction);
        }
        $panelSettings->WIMBA_append_child($divName);
        $panelSettings->WIMBA_append_child($divDisplay);
        $panelSettings->WIMBA_append_child($divStyle);
        $panelSettings->WIMBA_append_child($divId);
        $panelSettings->WIMBA_append_child($divContextDisplay);
        $panelContent = $this->xmldoc->WIMBA_create_element("tabContent");
        $panelContent->WIMBA_append_child($divDisplay->WIMBA_clone_node(true));
        $panelContent->WIMBA_append_child($divId->WIMBA_clone_node(true));
            
        for ($i = 0; $i < count($this->panelLines);$i++)
        {
            $panelContent->WIMBA_append_child($this->panelLines[$i]);
        }  
        $this->panelLines=array();
        $this->part["tabs"]->WIMBA_append_child($panelSettings);
        $this->part["tabsContent"]->WIMBA_append_child($panelContent);
    }
    
    /*
     * Set the error. 
     * if this element is set, it will be the only one displayed on the component
     * @param $errorString : sentence of the erro
     */  
    function WIMBA_setError($errorString)
    {
        if( !isset($this->error) ) //display jsut the first error
        {
            $this->error = $this->xmldoc->WIMBA_create_element("message");
            $messageType = $this->xmldoc->WIMBA_create_element("type");//for student and isntructor or just for instructor
            $messageType->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node("error"));
            $messageValue = $this->xmldoc->WIMBA_create_element("value");//for student and isntructor or just for instructor
            $messageValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($errorString));
            $this->error->WIMBA_append_child($messageType);
            $this->error->WIMBA_append_child($messageValue);
        }
    }
    
    /*
     * Add a message Element
     * @param messageString : sentence of the message
     */   
    function WIMBA_addMessage($messageString)
    {
        if (!isset($this->part["message"]))
        {
            $this->part["message"]=$this->xmldoc->WIMBA_create_element("message");
        }
        
        $message = $this->xmldoc->WIMBA_create_element("message");//for student and isntructor or just for instructor
        $messageValue = $this->xmldoc->WIMBA_create_element("value");//for student and isntructor or just for instructor
        $messageValue->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($messageString));
        $this->part["message"]->WIMBA_append_child($messageValue);
    }
    
    /*
     * Create the Dial popup which represent the popup displayed for the dial information
     * @param titleLabel : title of the popup
     * @param phoneLabel : label
     * @param pinLabel : label
     * @param pinI : pin code of the instructor
     * @param pinS : pin code of the student
     * @param toll : array of phone number
     */       
    function WIMBA_createPopupDialElement($titleLabel,$phoneLabel,$pinLabel,$pinI, $pinS, $toll)
    {
        if (!isset($this->part["popupDial"]))
        {
            $this->part["popupDial"]=$this->xmldoc->WIMBA_create_element("popupDial"); 
        }
        $pinNumbers = $this->xmldoc->WIMBA_create_element("pin");
        $title = $this->xmldoc->WIMBA_create_element("popupTitle");
        $title->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($titleLabel));
        $epinL = $this->xmldoc->WIMBA_create_element("pinLabel");
        $epinL->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($pinLabel));
        $epinI = $this->xmldoc->WIMBA_create_element("instructor");
        $epinI->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($pinI));
        $epinS = $this->xmldoc->WIMBA_create_element("student");
        $epinS->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($pinS));
        $pinNumbers->WIMBA_append_child($epinL);
        $pinNumbers->WIMBA_append_child($epinI);
        $pinNumbers->WIMBA_append_child($epinS);
        $ephones = $this->xmldoc->WIMBA_create_element("phones");
        $ephoneL = $this->xmldoc->WIMBA_create_element("phoneLabel");
        $ephoneL->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($phoneLabel));
        $ephones->WIMBA_append_child($ephoneL);
        foreach ($toll as $key => $value) 
        {
            if($key!="" && $value!="")
            {
                $ephone = $this->xmldoc->WIMBA_create_element("phone");
                $ephoneDesc = $this->xmldoc->WIMBA_create_element("phoneDesc");
                $ephoneDesc->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($key));
                $ephone->WIMBA_append_child($ephoneDesc);
                $ephoneNumber = $this->xmldoc->WIMBA_create_element("number");
                $ephoneNumber->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($value));;
                $ephone->WIMBA_append_child($ephoneNumber);
                $ephones->WIMBA_append_child($ephone);
            }
        }
        $this->part["popupDial"]->WIMBA_append_child($title);
        $this->part["popupDial"]->WIMBA_append_child($pinNumbers);
        $this->part["popupDial"]->WIMBA_append_child($ephones);
    
    }
    
    
    
     /*
     * Create the advanced popup which represent the popup displayed when you click on advanced settings
     * @param $popupTitle : title of the popup
     * @param $popupSentence : sentence displayed in the popup
     */
    function  WIMBA_createAdvancedPopup($popupTitle, $popupSentence)
    {
        if (!isset($this->part["advancedPopup"]))
        {
            $this->part["advancedPopup"]=$this->xmldoc->WIMBA_create_element("advancedPopup");   
        }
        $title = $this->xmldoc->WIMBA_create_element("popupTitle");
        $title->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($popupTitle));
        $sentence = $this->xmldoc->WIMBA_create_element("popupSentence");
        $sentence->WIMBA_append_child($this->xmldoc->WIMBA_create_text_node($popupSentence));
        $this->part["advancedPopup"]->WIMBA_append_child($title);
        $this->part["advancedPopup"]->WIMBA_append_child($sentence);
    }
}
?>

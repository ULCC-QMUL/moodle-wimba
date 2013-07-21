<?php
/*
 * Created on May 30, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class WIMBA_XmlArchive{
 	var $id;
 	var $nameDisplay;
 	var $preview;
 	var $url;
 	var $param;
 	var $url_params;
 	var $tooltipAvailability;
	var $tooltipDial;
	var $parent;
	var $canDownloadMp3;
	var $canDownloadMp4;
	 /*
     * Constructor
     * @param id : id of the archive
     * @param nameDisplay : name of the archive
     * @param preview : avaibility of the room
     * @param url : path of the file which manage the launch of the archive
     * @param url_params : parameters needed to be able to call the specific file
     */
	function WIMBA_XmlArchive( $id, $nameDisplay, $preview,$canDownloadMp3, $canDownloadMp4, $url, $url_params) {
        $this->id = $id;
        $this->nameDisplay = $nameDisplay;
        if ($preview == false)
        {
            $this->preview = "available";
        }
        else
        {
            $this->preview = "unavailable";
        }
        $this->url = $url;
        $this->url_params = $url_params;
        $this->canDownloadMp3=$canDownloadMp3;
        $this->canDownloadMp4=$canDownloadMp4;
    }
    
    /*
     * Return the xml element of the object 
     */
    function WIMBA_getXml($xml){
    
    	$element = $xml->WIMBA_create_element('archive');
    	
	    $product = $xml->WIMBA_create_element("product");
	    $product->WIMBA_append_child($xml->WIMBA_create_text_node("liveclassroom"));
	    
	    $type = $xml->WIMBA_create_element("type");
	    $type->WIMBA_append_child($xml->WIMBA_create_text_node("archive"));	
	    
	    $id = $xml->WIMBA_create_element("id");
	    $id->WIMBA_append_child($xml->WIMBA_create_text_node($this->id));	
    	
	    $nameDisplay = $xml->WIMBA_create_element("nameDisplay");
	    $nameDisplay->WIMBA_append_child($xml->WIMBA_create_text_node($this->nameDisplay));	
    	
	    $tooltipAvailability = $xml->WIMBA_create_element("tooltipAvailability");
	    $tooltipAvailability->WIMBA_append_child($xml->WIMBA_create_text_node($this->tooltipAvailability));	
	   	
	    $tooltipDial = $xml->WIMBA_create_element("tooltipDial");
	    $tooltipDial->WIMBA_append_child($xml->WIMBA_create_text_node($this->tooltipDial));	
	   	
	    $preview = $xml->WIMBA_create_element("preview");
	    $preview->WIMBA_append_child($xml->WIMBA_create_text_node($this->preview));	
	    
	    $url = $xml->WIMBA_create_element("url");
	    $url->WIMBA_append_child($xml->WIMBA_create_text_node($this->url));
	    
	    $parent = $xml->WIMBA_create_element("parent");
        $parent->WIMBA_append_child($xml->WIMBA_create_text_node($this->parent));
        
	    $canDownloadMp3 = $xml->WIMBA_create_element("canDownloadMp3");
        $canDownloadMp3->WIMBA_append_child($xml->WIMBA_create_text_node($this->canDownloadMp3));

        $canDownloadMp4 = $xml->WIMBA_create_element("canDownloadMp4");
        $canDownloadMp4->WIMBA_append_child($xml->WIMBA_create_text_node($this->canDownloadMp4));
        
        
	    $param = $xml->WIMBA_create_element("param");
	    $param->WIMBA_append_child($xml->WIMBA_create_text_node($this->param));
	    
	    $element->WIMBA_append_child($product);
	    $element->WIMBA_append_child($id);
        $element->WIMBA_append_child($nameDisplay);
        $element->WIMBA_append_child($preview);
        $element->WIMBA_append_child($tooltipAvailability);
        $element->WIMBA_append_child($tooltipDial);
        $element->WIMBA_append_child($url);
        $element->WIMBA_append_child($canDownloadMp3);
        $element->WIMBA_append_child($canDownloadMp4);
        $element->WIMBA_append_child($param);
        $element->WIMBA_append_child($type);
        $element->WIMBA_append_child($parent);
        
        return $element;
    }
    
    function WIMBA_getId() {
        return $this->id;
    }
    
    function WIMBA_getAvailability() {
        return $this->preview;
    }
    
    function WIMBA_getName() {
        return $this->nameDisplay;
    }
    
    function WIMBA_setTooltipDial($tooltipDial)
    {
        $this->tooltipDial = $tooltipDial;
    }
     function WIMBA_setParent($parent)
    {
        $this->parent = $parent;
    }
    
    function WIMBA_setTooltipAvailability($tooltipAvailability) {
        $this->tooltipAvailability = $tooltipAvailability;
    }
    
    function WIMBA_getTooltipDial() {
        return $this->tooltipDial;
    }
    
    function WIMBA_getTooltipAvailability() {
        return $this->tooltipAvailability;
    }
    
    function WIMBA_canDownloadMp3() {
        return $this->canDownloadMp3;
    }
    
    function WIMBA_canDownloadMp4() {
        return $this->canDownloadMp4;
    }
}
?>

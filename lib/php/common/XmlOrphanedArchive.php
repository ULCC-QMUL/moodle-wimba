<?php
/*
 * Created on May 30, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class WIMBA_XmlOrphanedArchive{
 	var $id;
 	var $nameDisplay;
 	var $preview;
 	var $url;
 	var $param;
 	var $url_params;
 	var $tooltipAvailability;
	var $tooltipDial;
	var $canDownloadMp3;
	var $canDownloadMp4;
	
	/*
     * Constructor
     * An orphaned archive is a arhive without link to a room
     * @param archive : initial archive
     * @param contextDisplay : context of display (all users, just for student)
     */
    function WIMBA_XmlOrphanedArchive($archive, $contextDisplay, $url, $url_params) {
         $this->id = $archive->WIMBA_getId();
         $this->nameDisplay = $archive->WIMBA_getName();
         $this->preview = $archive->WIMBA_getAvailability();
         $this->contextDisplay = $contextDisplay;
         $this->tooltipAvailability = $archive->WIMBA_getTooltipAvailability();
         $this->tooltipDial = $archive->WIMBA_getTooltipDial();
         $this->WIMBA_canDownloadMp3 = $archive->WIMBA_canDownloadMp3();
         $this->WIMBA_canDownloadMp4 = $archive->WIMBA_canDownloadMp4();
         $this->url = $url;
         $this->url_params = $url_params;
    }

    /*
     * Return the xml element of the object 
     */
    function WIMBA_getXml($xml){
    	$element = $xml->WIMBA_create_element('Element');
    	
	    $product = $xml->WIMBA_create_element("product");
	    $product->WIMBA_append_child($xml->WIMBA_create_text_node("liveclassroom"));
	    
	    $type = $xml->WIMBA_create_element("type");
	    $type->WIMBA_append_child($xml->WIMBA_create_text_node("orphanedarchive".$this->contextDisplay));	
	    
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
	    
	    $param = $xml->WIMBA_create_element("param");
	    $param->WIMBA_append_child($xml->WIMBA_create_text_node($this->param));

	    $canDownloadMp3 = $xml->WIMBA_create_element("canDownloadMp3");
        $canDownloadMp3->WIMBA_append_child($xml->WIMBA_create_text_node($this->WIMBA_canDownloadMp3));

        $canDownloadMp4 = $xml->WIMBA_create_element("canDownloadMp4");
        $canDownloadMp4->WIMBA_append_child($xml->WIMBA_create_text_node($this->WIMBA_canDownloadMp4));
        
	    
	    $element->WIMBA_append_child($product);
	    $element->WIMBA_append_child($id);
        $element->WIMBA_append_child($nameDisplay);
        $element->WIMBA_append_child($preview);
        $element->WIMBA_append_child($tooltipAvailability);
        $element->WIMBA_append_child($tooltipDial);
        $element->WIMBA_append_child($url);   
        $element->WIMBA_append_child($canDownloadMp3);
        $element->WIMBA_append_child($canDownloadMp4);    
        $element->WIMBA_append_child($type);
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
    
    function WIMBA_setTooltipDial($tooltipDial) {
        $this->tooltipDial = $tooltipDial;
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
	
}
?>

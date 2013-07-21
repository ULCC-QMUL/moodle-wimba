<?php
/*
 * Created on May 30, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */   
 
class WIMBA_XmlRoom{
 	
  var $id;
  var $nameDisplay;
  var $preview;
  var $param;
  var $launchUrl;
  var $url_params;
  var $tooltipAvailability;
  var $tooltipDial;
  var $closedArchive;
  var $archives = array();
  var $linkedActivities; 
	
  /*
   * Constructor
   * @param id : id of the room
   * @param nameDisplay : name of the room
   * @param closedArchive : this room
   * @param preview : avaibility of the room
   * @param archives : list of archives linked to the room
   * @param url : path of the file which manage the launch of the room
   * @param url_params : parameters needed to be able to call the specific file
   */
  function WIMBA_XmlRoom( $id, $nameDisplay, $closedArchive, $preview, $archives, $launchUrl, $url_params) {
    $this->id = $id;
    $this->nameDisplay = $nameDisplay;

    if ($preview == false) {
      $this->preview = "available";
    } else {
      $this->preview = "unavailable";
    }

    $this->launchUrl = $launchUrl;
    $this->url_params = $url_params;
    $this->archives=$archives;
    $this->closedArchive=$closedArchive;
    $this->linkedActivities = '';
  }
    
  /*
   * Return the xml element of the object 
   */
  function WIMBA_getXml($xml){
    $element = $xml->WIMBA_create_element('Element');
    	
    $product = $xml->WIMBA_create_element("product");
    $product->WIMBA_append_child($xml->WIMBA_create_text_node("liveclassroom"));
	    

    $type = $xml->WIMBA_create_element("type");
    $type->WIMBA_append_child($xml->WIMBA_create_text_node("liveclassroom"));	
	    
    $id = $xml->WIMBA_create_element("id");
    $id->WIMBA_append_child($xml->WIMBA_create_text_node($this->id));	
	    
    $nameDisplay = $xml->WIMBA_create_element("nameDisplay");
    $nameDisplay->WIMBA_append_child($xml->WIMBA_create_text_node($this->nameDisplay));	
	    
    $closedArchive = $xml->WIMBA_create_element("closedArchive");
    $closedArchive->WIMBA_append_child($xml->WIMBA_create_text_node($this->closedArchive));	
	    
    $tooltipAvailability = $xml->WIMBA_create_element("tooltipAvailability");
    $tooltipAvailability->WIMBA_append_child($xml->WIMBA_create_text_node($this->tooltipAvailability));	
	    
    $tooltipDial = $xml->WIMBA_create_element("tooltipDial");
    $tooltipDial->WIMBA_append_child($xml->WIMBA_create_text_node($this->tooltipDial));	
	    
    $preview = $xml->WIMBA_create_element("preview");
    $preview->WIMBA_append_child($xml->WIMBA_create_text_node($this->preview));	
	    
    $launchUrl = $xml->WIMBA_create_element("url");
    $launchUrl->WIMBA_append_child($xml->WIMBA_create_text_node($this->launchUrl));
	    
    $param = $xml->WIMBA_create_element("param");
    $param->WIMBA_append_child($xml->WIMBA_create_text_node($this->param));

    $linkedActivities = $xml->WIMBA_create_element("linkedActivities");
    $linkedActivities->WIMBA_append_child($xml->WIMBA_create_text_node($this->linkedActivities));
	  
    $element->WIMBA_append_child($product);
	    
    if (count($this->archives)>0) {
      $archives = $xml->WIMBA_create_element('archives');

      for ($i=0;$i<count($this->archives);$i++) {
        $archives->WIMBA_append_child($this->archives[$i]->WIMBA_getXml($xml));	    		
      }

      $element->WIMBA_append_child($archives);
    }
	         
    $element->WIMBA_append_child($id);
    $element->WIMBA_append_child($nameDisplay);
    $element->WIMBA_append_child($preview);
    $element->WIMBA_append_child($tooltipAvailability);
    $element->WIMBA_append_child($tooltipDial);
    $element->WIMBA_append_child($launchUrl);
    $element->WIMBA_append_child($param);
    $element->WIMBA_append_child($type);
    $element->WIMBA_append_child($linkedActivities);
        
    return $element;
  }
    
  function WIMBA_AddOneArchive($archive){
    $this->archives[]=$archive;
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

  function WIMBA_setLinkedActivities($linkedActivities) {
    $this->linkedActivities = $linkedActivities;
  }

  function WIMBA_setArchive($archives) {
    $this->archives=$archives;
  }
 	 	
}
 
 
?>

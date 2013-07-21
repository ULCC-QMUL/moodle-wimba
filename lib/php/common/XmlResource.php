<?php
/*
 * Created on May 30, 2007
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
class WIMBA_XmlResource{
 	
  var $id;
  var $nameDisplay;
  var $preview;
  var $url;
  var $url_params;
  var $tooltipAvailability;
  var $type;
  var $grade;
  var $linkedActivities;
	
  /*
   * Constructor
   * @param id : id of the resource
   * @param nameDisplay : name of the resource
   * @param preview : avaibility of the resource
   * @param url : path of the file which manage the launch of the resource
   * @param url_params : parameters needed to be able to call the specific file
   */
  function WIMBA_XmlResource( $id, $nameDisplay, $preview, $url, $url_params,$grade){
    $this->id = $id;
    $this->nameDisplay = $nameDisplay;

    if ($preview == 1) {
      $this->preview = "available";
    } else {
      $this->preview = "unavailable";
    }

    $this->url = $url;
    $this->url_params = $url_params;
    $this->grade = $grade;
    $this->linkedActivities = array();
    
  }
    
  /*
   * Return the xml element of the object 
   */
  function WIMBA_getXml($xml){
    
    $element = $xml->WIMBA_create_element('Element');
	    
    $product = $xml->WIMBA_create_element("product");
    $product->WIMBA_append_child($xml->WIMBA_create_text_node("voicetools"));
	 
    $type = $xml->WIMBA_create_element("type");
    $type->WIMBA_append_child($xml->WIMBA_create_text_node($this->type));	
	    
    $id = $xml->WIMBA_create_element("id");
    $id->WIMBA_append_child($xml->WIMBA_create_text_node($this->id));	
    	
    $nameDisplay = $xml->WIMBA_create_element("nameDisplay");
    $nameDisplay->WIMBA_append_child($xml->WIMBA_create_text_node($this->nameDisplay));	

    $tooltipAvailability = $xml->WIMBA_create_element("tooltipAvailability");
    $tooltipAvailability->WIMBA_append_child($xml->WIMBA_create_text_node($this->tooltipAvailability));	
	
    $preview = $xml->WIMBA_create_element("preview");
    $preview->WIMBA_append_child($xml->WIMBA_create_text_node($this->preview));	
	    
    $url = $xml->WIMBA_create_element("url");
    $url->WIMBA_append_child($xml->WIMBA_create_text_node($this->url));
	    
    $grade = $xml->WIMBA_create_element("grade");
    $grade->WIMBA_append_child($xml->WIMBA_create_text_node($this->grade));
	    
    $param = $xml->WIMBA_create_element("param");
    $param->WIMBA_append_child($xml->WIMBA_create_text_node($this->url_params));
	  
    $linkedActivities = $xml->WIMBA_create_element("linkedActivities");
    $linkedActivities->WIMBA_append_child($xml->WIMBA_create_text_node($this->linkedActivities));
	  
    $element->WIMBA_append_child($id);
    $element->WIMBA_append_child($product);
    $element->WIMBA_append_child($nameDisplay);
    $element->WIMBA_append_child($preview);
    $element->WIMBA_append_child($tooltipAvailability);
    $element->WIMBA_append_child($url);
    $element->WIMBA_append_child($grade);
    $element->WIMBA_append_child($param);
    $element->WIMBA_append_child($type);
    $element->WIMBA_append_child($linkedActivities);
        
    return $element;
  }
    
  
    
  function WIMBA_getRId() {
    return $this->id;
  }
    
  function WIMBA_getAvailability() {
    return $this->preview;
  }

  function WIMBA_getName() {
    return $this->nameDisplay;
  }
    
  function WIMBA_setTooltipAvailability($tooltipAvailability) {
    $this->tooltipAvailability = $tooltipAvailability;
  }

  function WIMBA_setLinkedActivities($linkedActivities) {
    $this->linkedActivities = $linkedActivities;
  }
    
  function WIMBA_setType($type) {
    $this->type=$type;
  }
 	
}
 
 
?>

<?php
/**
 * @file domxml-php4-php5.php
 * Require PHP5, uses built-in DOM extension.
 * To be used in PHP4 scripts using DOMXML extension.
 * Allows PHP4/DOMXML scripts to run on PHP5/DOM.
 * (Requires PHP5/XSL extension for domxml_xslt functions)
 *
 * Typical use:
 * <pre>
 * {
 *  if (version_compare(PHP_VERSION,'5','>='))
 *   require_once('domxml-php4-to-php5.php');
 * }
 * </pre>
 *
 * Version 1.5.5, 2005-01-18, http://alexandre.alapetite.net/doc-alex/domxml-php4-php5/
 *
 * ------------------------------------------------------------------<br>
 * Written by Alexandre Alapetite, http://alexandre.alapetite.net/cv/
 *
 * Copyright 2004, Licence: Creative Commons "Attribution-ShareAlike 2.0 France" BY-SA (FR),
 * http://creativecommons.org/licenses/by-sa/2.0/fr/
 * http://alexandre.alapetite.net/divers/apropos/#by-sa
 * - Attribution. You must give the original author credit
 * - Share Alike. If you alter, transform, or build upon this work,
 *   you may distribute the resulting work only under a license identical to this one
 * - The French law is authoritative
 * - Any of these conditions can be waived if you get permission from Alexandre Alapetite
 * - Please send to Alexandre Alapetite the modifications you make,
 *   in order to improve this file for the benefit of everybody
 *
 * If you want to distribute this code, please do it as a link to:
 * http://alexandre.alapetite.net/doc-alex/domxml-php4-php5/
 */

function WIMBA_domxml_new_doc($version) {return new WIMBA_php4DOMDocument('');}
function WIMBA_domxml_open_file($filename) {return new WIMBA_php4DOMDocument($filename);}
function WIMBA_domxml_open_mem($str)
{
 $dom=new WIMBA_php4DOMDocument('');
 $dom->myDOMNode->loadXML($str);
 return $dom;
}
function WIMBA_xpath_eval($xpath_context,$eval_str,$contextnode=null) {return $xpath_context->query($eval_str,$contextnode);}
function WIMBA_xpath_new_context($dom_document) {return new WIMBA_php4DOMXPath($dom_document);}

class WIMBA_php4DOMAttr extends WIMBA_php4DOMNode
{
 function WIMBA_php4DOMAttr($aDOMAttr) {$this->myDOMNode=$aDOMAttr;}
 function WIMBA_Name() {return $this->myDOMNode->name;}
 function WIMBA_Specified() {return $this->myDOMNode->specified;}
 function WIMBA_Value() {return $this->myDOMNode->value;}
}

class WIMBA_php4DOMDocument extends WIMBA_php4DOMNode
{
 function WIMBA_php4DOMDocument($filename='')
 {
  $this->myDOMNode=new DOMDocument();
  if ($filename!='') $this->myDOMNode->load($filename);
 }
 function WIMBA_create_attribute($name,$value)
 {
  $myAttr=$this->myDOMNode->createAttribute($name);
  $myAttr->value=$value;
  return new WIMBA_php4DOMAttr($myAttr,$this);
 }
 function WIMBA_create_cdata_section($content) {return new WIMBA_php4DOMNode($this->myDOMNode->createCDATASection($content),$this);}
 function WIMBA_create_comment($data) {return new WIMBA_php4DOMNode($this->myDOMNode->createComment($data),$this);}
 function WIMBA_create_element($name) {return new WIMBA_php4DOMElement($this->myDOMNode->createElement($name),$this);}
 function WIMBA_create_text_node($content) {return new WIMBA_php4DOMNode($this->myDOMNode->createTextNode($content),$this);}
 function WIMBA_document_element() {return new WIMBA_php4DOMElement($this->myDOMNode->documentElement,$this);}
 function WIMBA_dump_file($filename,$compressionmode=false,$format=false) {return $this->myDOMNode->save($filename);}
 function WIMBA_dump_mem($format=false,$encoding=false) {return $this->myDOMNode->saveXML();}
 function WIMBA_get_element_by_id($id) {return new WIMBA_php4DOMElement($this->myDOMNode->getElementById($id),$this);}
 function WIMBA_get_elements_by_tagname($name)
 {
  $myDOMNodeList=$this->myDOMNode->getElementsByTagName($name);
  $nodeSet=array();
  $i=0;
  if (isset($myDOMNodeList))
   while ($node=$myDOMNodeList->item($i))
   {
    $nodeSet[]=new WIMBA_php4DOMElement($node,$this);
    $i++;
   }
  return $nodeSet;
 }
 function WIMBA_html_dump_mem() {return $this->myDOMNode->saveHTML();}
 function WIMBA_root() {return new WIMBA_php4DOMElement($this->myDOMNode->documentElement,$this);}
}

class WIMBA_php4DOMElement extends WIMBA_php4DOMNode
{
 function WIMBA_get_attribute($name) {return $this->myDOMNode->getAttribute($name);}
 function WIMBA_get_elements_by_tagname($name)
 {
  $myDOMNodeList=$this->myDOMNode->getElementsByTagName($name);
  $nodeSet=array();
  $i=0;
  if (isset($myDOMNodeList))
   while ($node=$myDOMNodeList->item($i))
   {
    $nodeSet[]=new WIMBA_php4DOMElement($node,$this->myOwnerDocument);
    $i++;
   }
  return $nodeSet;
 }
 function WIMBA_has_attribute($name) {return $this->myDOMNode->hasAttribute($name);}
 function WIMBA_remove_attribute($name) {return $this->myDOMNode->removeAttribute($name);}
 function WIMBA_set_attribute($name,$value) {return $this->myDOMNode->setAttribute($name,$value);}
 function WIMBA_tagname() {return $this->myDOMNode->tagName;}
}

class WIMBA_php4DOMNode
{
 var $myDOMNode;
 var $myOwnerDocument;
 function WIMBA_php4DOMNode($aDomNode,$aOwnerDocument)
 {
  $this->myDOMNode=$aDomNode;
  $this->myOwnerDocument=$aOwnerDocument;
 }
 function WIMBA___get($name)
 {
  if ($name=='type') return $this->myDOMNode->nodeType;
  elseif ($name=='tagname') return $this->myDOMNode->tagName;
  elseif ($name=='content') return $this->myDOMNode->textContent;
  else
  {
   $myErrors=debug_backtrace();
   trigger_error('Undefined property: '.get_class($this).'::$'.$name.' ['.$myErrors[0]['file'].':'.$myErrors[0]['line'].']',E_USER_NOTICE);
   return false;
  }
 }
 function WIMBA_append_child($newnode) {return new WIMBA_php4DOMElement($this->myDOMNode->appendChild($newnode->myDOMNode),$this->myOwnerDocument);}
 function WIMBA_append_sibling($newnode) {return new WIMBA_php4DOMElement($this->myDOMNode->parentNode->appendChild($newnode->myDOMNode),$this->myOwnerDocument);}
 function WIMBA_attributes()
 {
  $myDOMNodeList=$this->myDOMNode->attributes;
  $nodeSet=array();
  $i=0;
  if (isset($myDOMNodeList))
   while ($node=$myDOMNodeList->item($i))
   {
    $nodeSet[]=new WIMBA_php4DOMAttr($node,$this->myOwnerDocument);
    $i++;
   }
  return $nodeSet;
 }
 function WIMBA_child_nodes()
 {
  $myDOMNodeList=$this->myDOMNode->childNodes;
  $nodeSet=array();
  $i=0;
  if (isset($myDOMNodeList))
   while ($node=$myDOMNodeList->item($i))
   {
    $nodeSet[]=new WIMBA_php4DOMElement($node,$this->myOwnerDocument);
    $i++;
   }
  return $nodeSet;
 }
 function WIMBA_children() {return $this->child_nodes();}
 function WIMBA_clone_node($deep=false) {return new WIMBA_php4DOMElement($this->myDOMNode->cloneNode($deep),$this->myOwnerDocument);}
 function WIMBA_first_child() {return new WIMBA_php4DOMElement($this->myDOMNode->firstChild,$this->myOwnerDocument);}
 function WIMBA_get_content() {return $this->myDOMNode->textContent;}
 function WIMBA_has_attributes() {return $this->myDOMNode->hasAttributes();}
 function WIMBA_has_child_nodes() {return $this->myDOMNode->hasChildNodes();}
 function WIMBA_insert_before($newnode,$refnode) {return new WIMBA_php4DOMElement($this->myDOMNode->insertBefore($newnode->myDOMNode,$refnode->myDOMNode),$this->myOwnerDocument);}
 function WIMBA_is_blank_node()
 {
  $myDOMNodeList=$this->myDOMNode->childNodes;
  $i=0;
  if (isset($myDOMNodeList))
   while ($node=$myDOMNodeList->item($i))
   {
    if (($node->nodeType==XML_ELEMENT_NODE)||
        (($node->nodeType==XML_TEXT_NODE)&&!preg_match('/^([[:cntrl:]]|[[:space:]])*$/',$node->nodeValue)))
     return false;
    $i++;
   }
  return true;
 }
 function WIMBA_last_child() {return new WIMBA_php4DOMElement($this->myDOMNode->lastChild,$this->myOwnerDocument);}
 function WIMBA_new_child($name,$content)
 {
  $mySubNode=$this->myDOMNode->ownerDocument->createElement($name);
  $mySubNode->appendChild($this->myDOMNode->ownerDocument->createTextNode($content));
  $this->myDOMNode->appendChild($mySubNode);
  return new WIMBA_php4DOMElement($mySubNode,$this->myOwnerDocument);
 }
 function WIMBA_next_sibling() {return new WIMBA_php4DOMElement($this->myDOMNode->nextSibling,$this->myOwnerDocument);}
 function WIMBA_node_name() {return $this->myDOMNode->localName;}
 function WIMBA_node_type() {return $this->myDOMNode->nodeType;}
 function WIMBA_node_value() {return $this->myDOMNode->nodeValue;}
 function WIMBA_owner_document() {return $this->myOwnerDocument;}
 function WIMBA_parent_node() {return new WIMBA_php4DOMElement($this->myDOMNode->parentNode,$this->myOwnerDocument);}
 function WIMBA_prefix() {return $this->myDOMNode->WIMBA_prefix;}
 function WIMBA_previous_sibling() {return new WIMBA_php4DOMElement($this->myDOMNode->previousSibling,$this->myOwnerDocument);}
 function WIMBA_remove_child($oldchild) {return new WIMBA_php4DOMElement($this->myDOMNode->removeChild($oldchild->myDOMNode),$this->myOwnerDocument);}
 function WIMBA_replace_child($oldnode,$newnode) {return new WIMBA_php4DOMElement($this->myDOMNode->replaceChild($oldnode->myDOMNode,$newnode->myDOMNode),$this->myOwnerDocument);}
 function WIMBA_set_content($text)
 {
  if (($this->myDOMNode->hasChildNodes())&&($this->myDOMNode->firstChild->nodeType==XML_TEXT_NODE))
   $this->myDOMNode->removeChild($this->myDOMNode->firstChild);
  return $this->myDOMNode->appendChild($this->myDOMNode->ownerDocument->createTextNode($text));
 }
}

class WIMBA_php4DOMNodelist
{
 var $myDOMNodelist;
 var $nodeset;
 function WIMBA_php4DOMNodelist($aDOMNodelist,$aOwnerDocument)
 {
  $this->myDOMNodelist=$aDOMNodelist;
  $this->nodeset=array();
  $i=0;
  if (isset($this->myDOMNodelist))
   while ($node=$this->myDOMNodelist->item($i))
   {
    $this->nodeset[]=new WIMBA_php4DOMElement($node,$aOwnerDocument);
    $i++;
   }
 }
}

class WIMBA_php4DOMXPath
{
 var $myDOMXPath;
 var $myOwnerDocument;
 function WIMBA_php4DOMXPath($dom_document)
 {
  $this->myOwnerDocument=$dom_document;
  $this->myDOMXPath=new DOMXPath($dom_document->myDOMNode);
 }
 function WIMBA_query($eval_str,$contextnode)
 {
  if (isset($contextnode)) return new WIMBA_php4DOMNodelist($this->myDOMXPath->query($eval_str,$contextnode->myDOMNode),$this->myOwnerDocument);
  else return new WIMBA_php4DOMNodelist($this->myDOMXPath->query($eval_str),$this->myOwnerDocument);
 }
 function WIMBA_xpath_register_ns($prefix,$namespaceURI) {return $this->myDOMXPath->registerNamespace($prefix,$namespaceURI);}
}

if (extension_loaded('xsl'))
{//See also: http://alexandre.alapetite.net/doc-alex/xslt-php4-php5/
 function WIMBA_domxml_xslt_stylesheet($xslstring) {return new WIMBA_php4DomXsltStylesheet(DOMDocument::loadXML($xslstring));}
 function WIMBA_domxml_xslt_stylesheet_doc($dom_document) {return new WIMBA_php4DomXsltStylesheet($dom_document);}
 function WIMBA_domxml_xslt_stylesheet_file($xslfile) {return new WIMBA_php4DomXsltStylesheet(DOMDocument::load($xslfile));}
 class WIMBA_php4DomXsltStylesheet
 {
  var $myxsltProcessor;
  function WIMBA_php4DomXsltStylesheet($dom_document)
  {
   $this->myxsltProcessor=new xsltProcessor();
   $this->myxsltProcessor->importStyleSheet($dom_document);
  }
  function WIMBA_process($dom_document,$xslt_parameters=array(),$param_is_xpath=false)
  {
   foreach ($xslt_parameters as $param=>$value)
    $this->myxsltProcessor->setParameter('',$param,$value);
   $myphp4DOMDocument=new WIMBA_php4DOMDocument();
   $myphp4DOMDocument->myDOMNode=$this->myxsltProcessor->transformToDoc($dom_document->myDOMNode);
   return $myphp4DOMDocument;
  }
  function WIMBA_result_dump_file($dom_document,$filename)
  {
   $html=$dom_document->myDOMNode->saveHTML();
   file_put_contents($filename,$html);
   return $html;
  }
  function WIMBA_result_dump_mem($dom_document) {return $dom_document->myDOMNode->saveHTML();}
 }
}
?>

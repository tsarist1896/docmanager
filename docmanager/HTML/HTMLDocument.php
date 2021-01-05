<?php
namespace docmanager\HTML;

class HTMLDocument extends \docmanager\Document {
	function __construct ($content = false, string $doc_name = '') {
		if (!$content) {
			$this->content['doctype'] = new Doctype();
			$this->content['html']    = new Html();
		} else {
			$this->set($content);
		}

		self::rememberDocument($this, 'HTML', $doc_name);
	}



	static function getHTMLDocument (string $name = '') {
		return parent::getDocument('HTML', $name);
	}



	function __get ($name) {
		$name   = mb_strtolower($name);
		$result = null;
		if ($name === 'html') {
			$result = $this->content['html'];
		} else {
			$result = $this->content['html']->$name;
		}

		return $result;
	}



	/**
	 * 
	 */
	function get () {
		return implode('', $this->content);
	}



	/**
	 * 
	 */
	function set ($content) {}



	/**
	 * 
	 */
	function getDocumentVersion () {
		return $this->content['doctype']->getVersion();
	}



	/**
	 * 
	 */
	function setDocumentVersion ($type, $version, $params = []) {
		$this->content['doctype']->setVersion($type, $version, $params);
	}



	/**
	 * 
	 */
	function closingSingleTag (bool $v) {
		HTMLElement::closingSingleTag($v);
	}



	/**
	 * 
	 */
	function setTitle ($title) {
		$this->content['html']->head->title->set($title);
	}



	/**
	 * 
	 */
	function getTitle () : string {
		return $this->content['html']->head->title->get();
	}



	/**
	 * 
	 */
	function setCharset ($charset) {
		$this->content['html']->head->setCharset($charset);
	}



	/**
	 * 
	 */
	function getCharset () {
		return $this->content['html']->head->getCharset();
	}



	/**
	 * 
	 */
	function setMeta ($attributes) {
		$this->content['html']->head->setMeta($attributes);
	}



	/**
	 * 
	 */
	function getMetaByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute ('meta', $name, $value);
	}



	/**
	 * 
	 */
	function getMetaByFilter (\Closure $filter) {
		$this->content['html']->head->getTagsByFilter('meta', $filter);
	}



	/**
	 * 
	 */
	function deleteMeta (Meta $meta) {
		$this->content['html']->head->deleteMeta($meta);
	}



	/**
	 * 
	 */
	function deleteMetaByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('meta', $name, $value);
	}



	/**
	 * 
	 */
	function addLink ($attributes, $priority = 0) {
		$this->content['html']->head->addLink($attributes, $priority);
	}



	/**
	 * 
	 */
	function getLinksByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute('link', $name, $value);
	}



	/**
	 * 
	 */
	function getLinksByFilter (\Closure $filter) {
		return $this->content['html']->head->getTagsByFilter('link', $filter);
	}



	/**
	 * 
	 */
	function deleteLink (Link $link) {
		$this->content['html']->head->deleteLink($link);
	}



	/**
	 * 
	 */
	function deleteLinksByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('link', $name, $value);
	}



	/**
	 * 
	 */
	function addStyles (string $styles, string $type = 'text/css') {
		$this->content['html']->head->addStyles($styles, $type);
	}



	/**
	 * 
	 */
	function addNewStyles (string $styles, array $attributes = [], int $priority = 0) {
		$this->content['html']->head->addNewStyles($styles, $attributes, $priority);
	}



	/**
	 * 
	 */
	function getStylesByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute('style', $name, $value);
	}



	/**
	 * 
	 */
	function getStylesByFilter (\Closure $filter) {
		return $this->content['html']->head->getTagsByFilter('style', $filter);
	}



	/**
	 * 
	 */
	function deleteStyle (Style $style) {
		$this->content['html']->head->deleteStyle($style);
	}



	/**
	 * 
	 */
	function deleteStylesByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('style', $name, $value);
	}



	/**
	 * 
	 */
	function addContent ($content) {
		$this->content['html']->body->addContent($content);
	}
}
<?php
namespace docmanager\HTML;

class HTMLDocument extends \docmanager\Document {
	function __construct ($content = false) {
		if (!$content) {
			$this->content['doctype'] = new Doctype();
			$this->content['html']    = new Html();
		} else {
			$this->set($content);
		}
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
}
<?php
namespace docmanager\HTML;

final class Body extends HTMLElement {
	function __construct ($parent = null) {
		$this->tag_name = 'body';
		$this->parent   = $parent;
	}


	function addContent ($content) {
		if (empty($this->content[0])) {
			$this->content[0] = $content;
		} else {
			$this->content[] = $content;
		}
	}



	/**
	 * 
	 */
	function getContent () {
		return implode("\n", $this->content);
	}
}
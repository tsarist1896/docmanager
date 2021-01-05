<?php
namespace docmanager\HTML;

final class Title extends HTMLElement {
	function __construct ($parent = null) {
		$this->tag_name   = 'title';
		$this->parent     = $parent;
		$this->content[0] = '';
	}



	/**
	 * 
	 */
	function get () {
		return $this->content[0];
	}



	/**
	 * 
	 */
	function set ($text = '') {
		$this->content[0] = $text ?: '';

		return $this;
	}
}
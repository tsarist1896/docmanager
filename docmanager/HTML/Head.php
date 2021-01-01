<?php
namespace docmanager\HTML;

class Head extends HTMLElement {
	function __construct ($parent = null) {
		$this->tag_name = 'head';
		$this->parent   = $parent;
	}
}
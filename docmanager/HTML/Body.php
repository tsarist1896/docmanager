<?php
namespace docmanager\HTML;

final class Body extends HTMLElement {
	function __construct ($parent = null) {
		$this->tag_name = 'body';
		$this->parent   = $parent;
	}
}
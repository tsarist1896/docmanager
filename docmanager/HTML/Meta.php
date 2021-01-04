<?php
namespace docmanager\HTML;

final class Meta extends HTMLElement {
	function __construct ($parent = null, $attributes = []) {
		$this->tag_name = 'meta';
		$this->closing  = false;
		$this->parent   = $parent;

		if (!empty($attributes)) {
			$this->attr($attributes);
		}
	}
}
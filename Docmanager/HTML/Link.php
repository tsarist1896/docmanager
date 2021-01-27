<?php
namespace Docmanager\HTML;

final class Link extends HTMLElement {
	use HTMLElementPriority;

	function __construct ($parent = null, $attributes = [], $priority = 0) {
		$this->tag_name = 'link';
		$this->closing  = false;
		$this->parent   = $parent;
		$this->priority = $priority;

		if (!empty($attributes)) {
			$this->attr($attributes);
		}
	}
}

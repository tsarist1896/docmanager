<?php
namespace docmanager\HTML;

final class Style extends HTMLElement {
	use HTMLElementPriority;

	function __construct ($parent = null, $attributes = [], $priority = 0) {
		$this->tag_name = 'style';
		$this->parent   = $parent;
		$this->priority = $priority;

		if (!empty($attributes)) {
			$this->attr($attributes);
		}
	}



	/**
	 * 
	 */
	function get () {
		return "{$this->content}";
	}



	/**
	 * 
	 */
	function add (string $styles = '') {
		if ($styles) {
			if (empty($this->content[0])) {
				$this->content[0] = "\n{$styles}\n";
			} else {
				$this->content[] = "\n{$styles}\n";
			}
		}

		return $this;
	}
}
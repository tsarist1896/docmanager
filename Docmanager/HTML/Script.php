<?php
namespace Docmanager\HTML;

final class Script extends HTMLElement {
	use HTMLElementPriority;
	const DEFAULT_TYPE = 'text/javascript';

	function __construct ($parent = null, $attributes = [], $priority = 0) {
		$this->tag_name = 'script';
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
		return implode("\n", $this->content);
	}



	/**
	 * 
	 */
	function add (string $scripts = '') {
		if (empty($this->attributes['src']) && !empty($scripts)) {
			if (empty($this->content[0])) {
				$this->content[0] = "\n{$scripts}\n";
			} else {
				$this->content[] = "\n{$scripts}\n";
			}
		}

		return $this;
	}
}

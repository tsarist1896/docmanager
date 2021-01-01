<?php
namespace docmanager\HTML;

abstract class HTMLElement {
	protected $parent           = null;
	protected $tag_name         = '';
	protected $valid_attributes = [];
	protected $attributes       = [];
	protected $content          = [];
	protected $closing          = true;



	function __get ($name) {
		$result   = null;
		$property = "__{$name}";

		if (property_exists($this, $property)) {
			$result = $this->$property;
		}

		return $result;
	}



	function __toString () {
		return $this->outerHTML();
	}



	/**
	 * 
	 */
	function parent () {
		return $this->parent;
	}



	/**
	 * 
	 */
	function setParent($parent) {
		$this->parent = $parent;
	}



	/**
	 * 
	 */
	function innerText () {
		$result = '';

		if ($this->closing) {
			foreach ($this->content as $c) {
				if (is_string($c)) {
					$result .= $result;
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function innerHTML () {
		$result = '';

		if ($this->closing) {
			foreach ($this->content as $c) {
				$result .= $result;
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function outerHTML () {
		$attrs  = $this->getAttributesAsStr();
		$result = "<{$this->tag_name}".($attrs ? " {$attrs}" : '').'>';

		if ($this->closing) {
			$result .= (implode('', $this->content) . "</{$this->tag_name}>");
		}

		return $result;
	}



	/**
	 * Return attributes
	 * @return string
	 */
	private function getAttributes () : array {
		return $this->attributes;
	}



	/**
	 * Returns attributes as a string
	 * @return string
	 */
	private function getAttributesAsStr () : string {
		$result = [];

		foreach ($this->attributes as $name => $val) {
			if (!isset($val) || $val === '') {
				$result[] = $name;
			} else {
				if (is_bool($val)) {
					$result[] = ($name . '="' . ($val ? 'true' : 'false') . '"');
				} else {
					$result[] = "{$name}=\"{$val}\"";
				}
			}
		}

		return implode(' ', $result);
	}



	/**
	 * Sets/returns the attribute value
	 */
	function attr ($name, ...$val) {
		$r = null;

		if (isset($val[0])) {
			if (empty($this->valid_attributes) || in_array($name, $this->valid_attributes)) {
				if ($val[0] !== null) {
					$this->attributes[$name] = $val[0];
				} elseif (isset($this->attributes[$name])) {
					unset($this->attributes[$name]);
				}
			}

			$r = $this;
		} else {
			$r = $this->attributes[$name] ?? null;
		}

		return $r;
	}



	/**
	 * Adds a new class
	 */
	function addClass ($name) {
		if ($this->attributes['class'] ?? false) {
			$classes = explode(' ', $this->attributes['class']);
		} else {
			$classes = [];
		}

		if (($i = array_search($name, $classes)) === false) {
			$classes[] = $name;
			$this->attributes['class'] = trim(implode(' ', $classes));
		}

		return $this;
	}



	/**
	 * Deletes a class
	 */
	function removeClass ($name) {
		if ($cls = $this->attributes['class'] ?? false) {
			$classes = explode(' ', $this->attributes['class']);
			if (($i = array_search($name, $classes)) !== false) {
				unset($classes[$i]);
				$this->attributes['class'] = trim(implode(' ', $classes));
			}
		}

		return $this;
	}



	/**
	 * Checks for the presence of a class
	 * @param bool
	 */
	function hasClass ($name) : bool {
		$result = false;

		if ($cls = $this->attributes['class'] ?? false) {
			$result = in_array($name, explode(' ', $this->attributes['class']));
		}

		return $result;
	}
}
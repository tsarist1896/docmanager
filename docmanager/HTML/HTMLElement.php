<?php
namespace docmanager\HTML;

abstract class HTMLElement {
	protected static $closing_single_tag = false;
	protected $parent           = null;
	protected $tag_name         = '';
	protected $valid_attributes = [];
	protected $attributes       = [];
	protected $content          = [];
	protected $closing          = true;
	protected $mark             = '';



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
	static function closingSingleTag (bool $v) {
		self::$closing_single_tag = $v;
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
	function setMark ($mark) {
		if (empty($this->mark)) {
			$this->mark = $mark;
		}
	}



	/**
	 * 
	 */
	function getMark () {
		return $this->mark;
	}



	function getTagName () {
		return $this->tag_name;
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

		if ($this->closing) {
			$result = "<{$this->tag_name}".($attrs ? " {$attrs}" : '').'>';
			$result .= (count($this->content) > 1 ? "\n" : '');
			$result .= (implode('', $this->content) . "</{$this->tag_name}>");
		} else {
			$result = "<{$this->tag_name}".($attrs ? " {$attrs}" : '').(self::$closing_single_tag ? ' /' : '').'>';
		}

		return $result . "\n";
	}



	/**
	 * Return attributes
	 * @return string
	 */
	function getAttributes () : array {
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

		if (is_array($name)) {
			$this->attributes = array_merge($this->attributes, $name);
			$r = $this;
		} else {
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

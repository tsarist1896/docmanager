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



	/**
	 * 
	 */
	static function closingSingleTag (bool $v) {
		self::$closing_single_tag = $v;
	}



	/**
	 * 
	 */
	function __destruct () {
		if (!empty($this->attributes['id'])) {
			$this->forgetId($this->attributes['id']);
		}
	}



	/**
	 * 
	 */
	function __get ($name) {
		$result   = null;
		$property = "__{$name}";

		if (property_exists($this, $property)) {
			$result = $this->$property;
		}

		return $result;
	}



	/**
	 * 
	 */
	function __toString () {
		return $this->outerHTML();
	}



	/**
	 * 
	 */
	private function getDocument () : ?HTMLDocument {
		$document = null;

		if ($html = $this->getHtmlElement()) {
			$document = $html->document ?? null;
		}

		return $document;
	}



	/**
	 * 
	 */
	private function getHtmlElement () : ?Html {
		$html     = null;
		$element  = $this;
		$max_i    = 100;

		do {
			if ($element->getTagName() === 'html') {
				$html = $element;
				break;
			} else {
				$element = $element->parent();
			}
			$max_i--;
		} while ($element && ($max_i > 0));

		return $html;
	}



	/**
	 * @param string $id
	 * @param bool   $status
	 */
	private function rememberId (string $id) {
		if ($document = $this->getDocument()) {
			$document->rememberElement($id, $this);
		}
	}



	/**
	 * @param string $id
	 */
	private function forgetId (string $id) {
		if ($document = $this->getDocument()) {
			$document->forgetElement($id);
		}
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
	 * @param string $attribute
	 * @param string $value
	 */
	private function setAttribute (string $attribute, string $value = '') {
		$attribute = strtolower($attribute);
		if (empty($this->valid_attributes) || in_array($attribute, $this->valid_attributes)) {
			if ($value !== null) {
				if ($attribute === 'id') {
					$old_id = $this->attributes['id'] ?? null;
					if (isset($old_id) && $old_id !== $value) {
						$this->forgetId($old_id);
					}
					$this->rememberId($value);
				}

				$this->attributes[$attribute] = $value;
			} elseif (isset($this->attributes[$attribute])) {
				unset($this->attributes[$attribute]);
			}
		}
	}



	/**
	 * Sets/returns the attribute value
	 */
	function attr ($attribute, ...$val) {
		$r = null;

		if (is_array($attribute)) {
			foreach ($attribute as $a => $v) {
				$this->setAttribute($a, $v);
			}

			$r = $this;
		} else {
			if (count($val) > 0) {
				$this->setAttribute($attribute, $val[0]);
				$r = $this;
			} else {
				$r = $this->attributes[strtolower($attribute)] ?? null;
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

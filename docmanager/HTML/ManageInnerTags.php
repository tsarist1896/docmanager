<?php
namespace docmanager\HTML;

trait ManageInnerTags {
	function __call ($method, $arguments) {
		$data = $this->_callMethods[$method];
		return $this->{$data['method']}($data['tag'], ...$arguments);
	}

	/**
	 * 
	 */
	function getTags (string $tag) {
		$result = [];

		foreach ($this->content as &$c) {
			if (is_object($c) && ($c->getTagName() === $tag)) {
				$result[] = $c;
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function getFirstTagByAttribute (string $tag, string $attribute, string $value = null) : ?HTMLElement {
		$result = null;

		if ($attribute === 'id' && ($document = $this->getDocument())) {
			$result = $document->getElementById($value);
		} else {
			foreach ($this->content as &$c) {
				if (is_object($c) && ($c->getTagName() === $tag)) {
					if (($attr_value = $c->attr($attribute)) !== null) {
						if (isset($value)) {
							if ($attr_value === $value) {
								$result = $c;
								break;
							}
						} else {
							$result = $c;
							break;
						}
					}
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function getTagsByAttribute (string $tag, string $attribute, string $value = null) : array {
		$result = [];

		if ($attribute === 'id' && ($document = $this->getDocument())) {
			if ($element = $document->getElementById($value)) {
				$result[] = $element;
			}
		} else {
			foreach ($this->content as &$c) {
				if (is_object($c) && ($c->getTagName() === $tag)) {
					if (($attr_value = $c->attr($attribute)) !== null) {
						if (isset($value)) {
							if ($attr_value === $value) {
								$result[] = $c;
							}
						} else {
							$result[] = $c;
						}
					}
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function getFirstSimilarTag (string $tag, array $attributes, int $priority = 0) : ?HTMLElement {
		$result = null;

		foreach ($this->content as &$c) {
			if (is_object($c) && ($c->getTagName() === $tag)) {
				if (method_exists($c, 'getPriority') && ($c->getPriority() === $priority)) {
					$attributes_i     = [];
					$tag_attributes_i = [];
					foreach ($attributes as $key => $value) {
						$attributes_i[strtolower($key)] = $value;
					}
					ksort($attributes_i);
	
					foreach ($c->getAttributes() as $key => $value) {
						$tag_attributes_i[strtolower($key)] = $value;
					}
					ksort($tag_attributes_i);
	
					if ($attributes_i == $tag_attributes_i) {
						$result = $c;
						break;
					}
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function getSimilarTags (string $tag, array $attributes, int $priority = 0) : array {
		$result = [];

		foreach ($this->content as &$c) {
			if (is_object($c) && ($c->getTagName() === $tag)) {
				if (method_exists($c, 'getPriority') && ($c->getPriority() === $priority)) {
					$attributes_i     = [];
					$tag_attributes_i = [];
					foreach ($attributes as $key => $value) {
						$attributes_i[strtolower($key)] = $value;
					}
					ksort($attributes_i);

					foreach ($c->getAttributes() as $key => $value) {
						$tag_attributes_i[strtolower($key)] = $value;
					}
					ksort($tag_attributes_i);

					if ($attributes_i == $tag_attributes_i) {
						$result[] = $c;
					}
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function getTagsByFilter (string $tag,\Closure $filter) {
		$result = [];

		foreach ($this->content as &$c) {
			if (is_object($c) && ($c->getTagName() === $tag)) {
				if ($filter($c)) {
					$result[] = $c;
				}
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	protected function deleteTag (string $tag, HTMLElement $element) : Head {
		if ($tag === $element->getTagName()) {
			$i = $element->getMark();
			if (!empty($this->content[$i])) {
				unset($this->content[$i]);
			}
		} else {
			throw new ErrorException('Uncaught TypeError: Argument 1 passed to delete'.ucfirst($tag).'() must be an instance of '.ucfirst($tag));
		}

		return $this;
	}



	/**
	 * 
	 */
	function deleteTagsByAttribute (string $tag, string $attribute, string $value = null) : Head {
		foreach ($this->getTagsByAttribute($tag, $attribute, $value) as &$element) {
			$this->deleteTag($tag, $element);
		}

		return $this;
	}
}

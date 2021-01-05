<?php
namespace docmanager\HTML;

final class Head extends HTMLElement {
	protected $__title   = null;
	private $callMethods = [
		'getMetaByAttribute' => [
			'method' => 'getTagsByAttribute',
			'tag'    => 'meta'
		],
		'getLinksByAttribute' => [
			'method' => 'getTagsByAttribute',
			'tag'    => 'link'
		],
		'getStylesByAttribute' => [
			'method' => 'getTagsByAttribute',
			'tag'    => 'style'
		],
		'getMetaByFilter' => [
			'method' => 'getTagsByFilter',
			'tag'    => 'meta'
		],
		'getLinksByFilter' => [
			'method' => 'getTagsByFilter',
			'tag'    => 'link'
		],
		'getStylesByFilter' => [
			'method' => 'getTagsByFilter',
			'tag'    => 'style'
		],
		'deleteMeta' => [
			'method' => 'deleteTag',
			'tag'    => 'meta'
		],
		'deleteLink' => [
			'method' => 'deleteTag',
			'tag'    => 'link'
		],
		'deleteStyle' => [
			'method' => 'deleteTag',
			'tag'    => 'style'
		],
		'deleteMetaByAttribute' => [
			'method' => 'deleteTagsByAttribute',
			'tag'    => 'meta'
		],
		'deleteLinksByAttribute' => [
			'method' => 'deleteTagsByAttribute',
			'tag'    => 'link'
		],
		'deleteStylesByAttribute' => [
			'method' => 'deleteTagsByAttribute',
			'tag'    => 'style'
		]
	];
	private $elements = [
		'title'  => 0,
		'meta'   => 1,
		'link'   => 2,
		'style'  => 3,
		'script' => 4
	];



	function __construct ($parent = null) {
		$this->tag_name = 'head';
		$this->parent   = $parent;
		$this->content['title']   = $this->__title = new Title($this);
		$this->content['charset'] = new Meta($this, ['charset' => 'utf-8']);
	}



	function __call ($method, $arguments) {
		$data = $this->callMethods[$method];
		return $this->{$data['method']}($data['tag'], ...$arguments);
	}



	function __toString () {
		$ic = implode('', $this->content);

		uasort($this->content, function ($e1, $e2) {
			$result = 0;

			$i1 = $this->elements[$e1->getTagName()] ?? PHP_INT_MAX;
			$i2 = $this->elements[$e2->getTagName()] ?? PHP_INT_MAX;

			if ($i1 === $i2) {
				if (method_exists($e1, 'getPriority')) {
					$p1 = $e1->getPriority();
					$p2 = $e2->getPriority();
					$result = $p1 < $p2 ? 1 : ($p1 === $p2 ? 0 : -1);
				}
			} else {
				$result = $i1 > $i2 ? 1 : -1;
			}

			return $result;
		});

		return parent::__toString();
	}



	/**
	 * 
	 */
	function setCharset ($charset) {
		$this->content['charset']->attr('charset', $charset);

		return $this;
	}



	/**
	 * 
	 */
	function getCharset () {
		return $this->content['charset']->attr('charset');
	}



	/**
	 * 
	 */
	function setMeta ($attributes) {
		$meta = new Meta($this, $attributes);
		$this->content[] = $meta;
		$content_index   = array_keys($this->content);
		$meta->setMark(array_pop($content_index));

		return $this;
	}



	/**
	 * 
	 */
	function addLink (array $attributes, int $priority = 0) {
		$link = new Link($this, $attributes, $priority);
		$this->content[] = $link;
		$content_index   = array_keys($this->content);
		$link->setMark(array_pop($content_index));

		return $this;
	}



	/**
	 * 
	 */
	function addStyles (string $styles, string $type = 'text/css') {
		$styles_elements = $this->getTagsByAttribute('style', 'type', $type);

		if ($style = array_shift($styles_elements)) {
			$style->add($styles);
		} else {
			$this->addNewStyles($styles, ['type' => $type]);
		}

		return $this;
	}



	/**
	 * 
	 */
	function addNewStyles (string $styles, array $attributes = [], int $priority = 0) {
		if (empty($attributes)) {
			$attributes['type'] = 'text/css';
		}
		$style = new Style($this, $attributes, $priority);
		$style->add($styles);
		$this->content[] = $style;
		$content_index   = array_keys($this->content);
		$style->setMark(array_pop($content_index));
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
	function getTagsByAttribute (string $tag, string $attribute, string $value = null) {
		$result = [];

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
	private function deleteTag (string $tag, HTMLElement $element) : Head {
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
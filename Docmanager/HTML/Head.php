<?php
namespace Docmanager\HTML;

final class Head extends HTMLElement {
	use ManageInnerTags;
	use ManageScripts;

	protected $__title   = null;
	private $_callMethods = [
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
		'getScriptByAttribute' => [
			'method' => 'getTagsByAttribute',
			'tag'    => 'script'
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
		'getScriptByFilter' => [
			'method' => 'getTagsByFilter',
			'tag'    => 'script'
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
		'deleteScript' => [
			'method' => 'deleteTag',
			'tag'    => 'script'
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
		],
		'deleteScriptByAttribute' => [
			'method' => 'deleteTagsByAttribute',
			'tag'    => 'script'
		]
	];
	private $elements = [
		'title'  => 0,
		'meta'   => 1,
		'link'   => 2,
		'style'  => 3,
		'script' => 4
	];



	/**
	 * 
	 */
	function __construct ($parent = null) {
		$this->tag_name = 'head';
		$this->parent   = $parent;
		$this->content['title']   = $this->__title = new Title($this);
		$this->content['charset'] = new Meta($this, ['charset' => 'utf-8']);
	}



	/**
	 * 
	 */
	function __toString () {
		uasort($this->content, function ($e1, $e2) {
			$result = 0;

			$i1 = $this->elements[$e1->getTagName()] ?? PHP_INT_MAX;
			$i2 = $this->elements[$e2->getTagName()] ?? PHP_INT_MAX;

			if ($i1 === $i2) {
				if (method_exists($e1, 'getPriority')) {
					$p1 = $e1->getPriority();
					$p2 = $e2->getPriority();
					$result = $p1 > $p2 ? 1 : ($p1 === $p2 ? 0 : -1);
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
	function addMeta ($attributes) {
		$element         = new Meta($this, $attributes);
		$this->content[] = $element;
		$content_index   = array_keys($this->content);
		$element->setMark(array_pop($content_index));

		return $element;
	}



	/**
	 * 
	 */
	function addLink (array $attributes, int $priority = 0) {
		$element         = new Link($this, $attributes, $priority);
		$this->content[] = $element;
		$content_index   = array_keys($this->content);
		$element->setMark(array_pop($content_index));

		return $element;
	}



	/**
	 * @param string $styles
	 * @param array $params
	 *                 * array attributes
	 *                 * int   priority
	 * @return Style
	 */
	function addStyles (string $styles, array $params = []/* string $type = 'text/css' */) : Style {
		$element = false;

		if ($wrap = HTMLDocument::checkWrapTags($styles, 'style')) {
			$styles = trim(str_replace([$wrap['start_tag'], $wrap['end_tag']], ['', ''], $styles));

			if (!empty($wrap['attributes'])) {
				if (empty($params['attributes'])) {
					$params['attributes'] = [];
				}

				$params['attributes'] += HTMLDocument::parsingAttributeString($wrap['attributes']);

				if (empty($params['attributes']['type'])) {
					$params['attributes']['type'] = Style::DEFAULT_TYPE;
				}
			}
		}

		if (empty($params) && ($element = $this->getFirstTagByAttribute('style', 'type', Style::DEFAULT_TYPE))) {
			$element->add($styles);
		} else {
			$params = array_replace_recursive([
				'attributes' => [
					'type' => Style::DEFAULT_TYPE
				],
				'priority' => 0
			], $params);

			if ($element === false && ($element = $this->getFirstSimilarTag('style', $params['attributes'], $params['priority']))) {
				$element->add($styles);
			} else {
				// create new style element
				$element = new Style($this, $params['attributes'], $params['priority']);
				$element->add($styles);
				$this->content[] = $element;
				$content_index   = array_keys($this->content);
				$element->setMark(array_pop($content_index));
			}
		}

		return $element;
	}
}

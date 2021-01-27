<?php
namespace Docmanager\HTML;

final class Body extends HTMLElement {
	use ManageInnerTags;
	use ManageScripts;

	private $_callMethods = [
		'getScriptByAttribute' => [
			'method' => 'getTagsByAttribute',
			'tag'    => 'script'
		],
		'getScriptByFilter' => [
			'method' => 'getTagsByFilter',
			'tag'    => 'script'
		],
		'deleteScript' => [
			'method' => 'deleteTag',
			'tag'    => 'script'
		],
		'deleteScriptByAttribute' => [
			'method' => 'deleteTagsByAttribute',
			'tag'    => 'script'
		]
	];



	/**
	 * 
	 */
	function __construct ($parent = null) {
		$this->tag_name = 'body';
		$this->parent   = $parent;
	}



	/**
	 * 
	 */
	function __toString () {
		uksort($this->content, function ($k1, $k2) {
			$default_priority = -10000;
			$c1 = $this->content[$k1];
			$c2 = $this->content[$k2];

			$p1 = (is_object($c1) && method_exists($c1, 'getPriority')) ? $c1->getPriority() : ($default_priority + $k1);
			$p2 = (is_object($c2) && method_exists($c2, 'getPriority')) ? $c2->getPriority() : ($default_priority + $k2);

			return ($p1 > $p2 ? 1 : ($p1 === $p2 ? 0 : -1));
		});

		return parent::__toString();
	}



	/**
	 * 
	 */
	function addContent ($content) {
		if (empty($this->content[0])) {
			$this->content[0] = $content;
		} else {
			$this->content[] = $content;
		}
	}



	/**
	 * 
	 */
	function getContent () {
		return implode("\n", $this->content);
	}
}

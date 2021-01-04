<?php
namespace docmanager\HTML;

final class Head extends HTMLElement {
	protected $__title = null;

	function __construct ($parent = null) {
		$this->tag_name = 'head';
		$this->parent   = $parent;
		$this->content['title']   = $this->__title = new Title($this);
		$this->content['charset'] = new Meta($this, ['charset' => 'utf-8']);
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
	function getMetaByAttribute ($name, $value = null) {
		$result = [];

		foreach ($this->content as &$c) {
			if (is_object($c) && (get_class($c) === 'docmanager\HTML\Meta')) {
				if (($attr_value = $c->attr($name)) !== null) {
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
	function deleteMetaByAttribute ($name, $value = null) : Head {
		foreach ($this->getMetaByAttribute($name, $value) as &$meta) {
			$i = $meta->getMark();
			if (!empty($this->content[$i])) {
				unset($this->content[$i]);
			}
		}

		return $this;
	}
}
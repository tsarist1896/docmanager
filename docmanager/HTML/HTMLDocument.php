<?php
namespace docmanager\HTML;

class HTMLDocument extends \docmanager\Document {
	/**
	 * 
	 */
	static function getHTMLDocument (string $name = '') {
		return parent::getDocument('HTML', $name);
	}



	/**
	 * 
	 */
	static function checkWrapTags(string $string, string $tag) {
		$result    = [];
		$start_tag = '(?P<start_tag><'.$tag.'\s?(?P<attributes>[^>]*)>)?';
		$end_tag   = '(?P<end_tag></'.$tag.'>)?';

		if (preg_match_all('`^\s*'.$start_tag.'.*?'.$end_tag.'\s*$`mi', $string, $m)) {
			$start_tag_text  = array_diff($m['start_tag'], ['']);
			$attributes_text = array_diff($m['attributes'], ['']);
			$end_tag_text    = array_diff($m['end_tag'], ['']);
			$result = [
				'start_tag'  => array_shift($start_tag_text) ?: '',
				'attributes' => array_shift($attributes_text) ?: '',
				'end_tag'    => array_pop($end_tag_text) ?: ''
			];
		}

		return $result;
	}



	/**
	 * 
	 */
	static function parsingAttributeString ($string) {
		$result     = [];
		$attributes = '(?P<attributes>[^\s"\'=]+)';
		$values     = '(?P<values>"[^"]*"|\'[^\']*\')';

		if (preg_match_all('`'.$attributes.'(?:\s*=\s*'.$values.')?`mi', $string, $m)) {
			foreach ($m['attributes'] as $i => $a) {
				$value      = ($m['values'][$i] ?? '');
				$result[$a] = $value ? trim($value, ($value[0] === '"' ? '"' : '\'')) : '';
			}
		}

		return $result;
	}



	/**
	 * 
	 */
	function __construct ($content = false, string $doc_name = '') {
		if (!$content) {
			$this->content['doctype'] = new Doctype();
			$this->content['html']    = new Html();
		} else {
			$this->set($content);
		}

		self::rememberDocument($this, 'HTML', $doc_name);
	}



	/**
	 * 
	 */
	function __get ($name) {
		$name   = mb_strtolower($name);
		$result = null;
		if ($name === 'html') {
			$result = $this->content['html'];
		} else {
			$result = $this->content['html']->$name;
		}

		return $result;
	}



	/**
	 * 
	 */
	function get () {
		return implode('', $this->content);
	}



	/**
	 * 
	 */
	function set ($content) {}



	/**
	 * 
	 */
	function getDocumentVersion () {
		return $this->content['doctype']->getVersion();
	}



	/**
	 * 
	 */
	function setDocumentVersion ($type, $version, $params = []) {
		$this->content['doctype']->setVersion($type, $version, $params);
	}



	/**
	 * 
	 */
	function closingSingleTag (bool $v) {
		HTMLElement::closingSingleTag($v);
	}



	/**
	 * 
	 */
	function setTitle ($title) {
		$this->content['html']->head->title->set($title);
	}



	/**
	 * 
	 */
	function getTitle () : string {
		return $this->content['html']->head->title->get();
	}



	/**
	 * 
	 */
	function setCharset ($charset) {
		$this->content['html']->head->setCharset($charset);
	}



	/**
	 * 
	 */
	function getCharset () {
		return $this->content['html']->head->getCharset();
	}



	/**
	 * 
	 */
	function addMeta ($attributes) {
		$this->content['html']->head->addMeta($attributes);
	}



	/**
	 * 
	 */
	function getMetaByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute ('meta', $name, $value);
	}



	/**
	 * 
	 */
	function getMetaByFilter (\Closure $filter) {
		$this->content['html']->head->getTagsByFilter('meta', $filter);
	}



	/**
	 * 
	 */
	function deleteMeta (Meta $meta) {
		$this->content['html']->head->deleteMeta($meta);
	}



	/**
	 * 
	 */
	function deleteMetaByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('meta', $name, $value);
	}



	/**
	 * 
	 */
	function addLink ($attributes, $priority = 0) {
		$this->content['html']->head->addLink($attributes, $priority);
	}



	/**
	 * 
	 */
	function getLinksByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute('link', $name, $value);
	}



	/**
	 * 
	 */
	function getLinksByFilter (\Closure $filter) {
		return $this->content['html']->head->getTagsByFilter('link', $filter);
	}



	/**
	 * 
	 */
	function deleteLink (Link $link) {
		$this->content['html']->head->deleteLink($link);
	}



	/**
	 * 
	 */
	function deleteLinksByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('link', $name, $value);
	}



	/**
	 * @param string $styles
	 * @param array $params
	 *                 * array attributes
	 *                 * int   priority
	 */
	function addStyles (string $styles, array $params = []) {
		$this->content['html']->head->addStyles($styles, $params);
	}



	/**
	 * 
	 */
	function getStylesByAttribute ($name, $value = null) {
		return $this->content['html']->head->getTagsByAttribute('style', $name, $value);
	}



	/**
	 * 
	 */
	function getStylesByFilter (\Closure $filter) {
		return $this->content['html']->head->getTagsByFilter('style', $filter);
	}



	/**
	 * 
	 */
	function deleteStyle (Style $style) {
		$this->content['html']->head->deleteStyle($style);
	}



	/**
	 * 
	 */
	function deleteStylesByAttribute ($name, $value = null) {
		$this->content['html']->head->deleteTagsByAttribute('style', $name, $value);
	}



	/**
	 * @param string $styles
	 * @param array $params
	 *                 * array  attributes
	 *                 * int    priority
	 *                 * string target -- ('head'|'body')
	 */
	function addScript (string $script, array $params = []) {
		$target = (!empty($params['target']) && in_array($params['target'], ['head', 'body'])) ? $params['target'] : 'body';
		$this->content['html']->{$target}->addScript($script, $params);
	}



	/**
	 * 
	 */
	function addContent ($content) {
		$this->content['html']->body->addContent($content);
	}
}

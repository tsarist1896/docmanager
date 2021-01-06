<?php
namespace docmanager\HTML;

trait ManageScripts {
	/**
	 * @param string $script
	 * @param array $params
	 *                 * array attributes
	 *                 * int   priority
	 * @return Script
	 */
	function addScript (string $script, array $params = []) : Script {
		$element        = false;
		$default_params = [
			'attributes' => [
				'type' => Script::DEFAULT_TYPE
			],
			'priority' => 0
		];

		if ($wrap = HTMLDocument::checkWrapTags($script, 'script')) {
			$script = trim(str_replace([$wrap['start_tag'], $wrap['end_tag']], ['', ''], $script));

			if (!empty($wrap['attributes'])) {
				if (empty($params['attributes'])) {
					$params['attributes'] = [];
				}

				$params['attributes'] += HTMLDocument::parsingAttributeString($wrap['attributes']);

				if (empty($params['attributes']['type'])) {
					$params['attributes']['type'] = Script::DEFAULT_TYPE;
				}
			}
		}

		if (!isset($params['attributes']['src'])) {
			if (empty($params) && ($element = $this->getFirstTagByAttribute('script', 'type', Script::DEFAULT_TYPE))) {
				$element->add($script);
			} else {
				$params = array_replace_recursive($default_params, $params);
	
				if ($element === false && ($element = $this->getFirstSimilarTag('script', $params['attributes'], $params['priority']))) {
					$element->add($script);
				} else {
					// create new script element
					$element = new Script($this, $params['attributes'], $params['priority']);
					$element->add($script);
					$this->content[] = $element;
					$content_index   = array_keys($this->content);
					$element->setMark(array_pop($content_index));
				}
			}
		} elseif (!($element = $this->getFirstTagByAttribute('script', 'src', $params['attributes']['src']))) {
			$params  = array_replace_recursive($default_params, $params);
			$element = new Script($this, $params['attributes'], $params['priority']);
			$this->content[] = $element;
			$content_index   = array_keys($this->content);
			$element->setMark(array_pop($content_index));
		}

		return $element;
	}
}

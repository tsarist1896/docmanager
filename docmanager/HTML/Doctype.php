<?php
namespace docmanager\HTML;

final class Doctype extends HTMLElement {
	private $html_type    = 'html';
	private $html_version = 5;
	private $html_mode    = null;
	private $xhtml_basic  = false;


	function __construct ($type = 'html', $version = 5, $params = []) {
		$this->tag_name = '!DOCTYPE';
		$this->closing  = false;
		$this->setVersion($type, $version, $params);
	}



	/**
	 * 
	 */
	function getVersion () {
		return [
			'type'        => $this->$html_type,
			'version'     => $this->html_version,
			'mode'        => $this->html_mode,
			'xhtml_basic' => $this->xhtml_basic
		];
	}



	/**
	 * 
	 */
	function setVersion ($type, $version, $params = []) {
		$this->html_type    = mb_strtolower($type);
		$this->html_version = $version;
		$this->xhtml_basic  = false;
		$this->html_mode   = null;
		$this->attributes  = [];

		if ($this->html_type === 'xhtml') {
			HTMLElement::closingSingleTag(true);
			switch ($version) {
				case 1:
					$this->attributes['html']   = '';
					$this->attributes['PUBLIC'] = '';
					if (!empty($params['xhtml_basic'])) {
						$this->xhtml_basic = true;
						$this->attributes['"-//W3C//DTD XHTML Basic 1.0//EN"'] = '';
						$this->attributes['"http://www.w3.org/TR/xhtml-basic/xhtml-basic10.dtd"'] = '';
					} else {
						switch (mb_strtolower($params['mode'] ?? 'transitional')) {
							case 'strict':
								$this->html_mode = 'strict';
								$this->attributes['"-//W3C//DTD XHTML 1.0 Strict//EN"'] = '';
								$this->attributes['"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"'] = '';
								break;
							case 'frameset':
								$this->html_mode = 'frameset';
								$this->attributes['"-//W3C//DTD XHTML 1.0 Frameset//EN"'] = '';
								$this->attributes['"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd"'] = '';
								break;
							case 'transitional':
							default:
								$this->html_mode = 'transitional';
								$this->attributes['"-//W3C//DTD XHTML 1.0 Transitional//EN"'] = '';
								$this->attributes['"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"'] = '';
						}
					}
					break;
				case 1.1:
				default:
					$this->attributes['html']   = '';
					$this->attributes['PUBLIC'] = '';
					if (!empty($params['xhtml_basic'])) {
						$this->xhtml_basic = true;
						$this->attributes['"-//W3C//DTD XHTML Basic 1.1//EN"'] = '';
						$this->attributes['"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd"'] = '';
					} else {
						$this->attributes['"-//W3C//DTD XHTML 1.1//EN"'] = '';
						$this->attributes['"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"'] = '';
					}
			}
		} else {
			switch ($version) {
				case 2:
					$this->attributes['html']   = '';
					$this->attributes['PUBLIC'] = '';
					$this->attributes['"-//IETF//DTD HTML 2.0//EN"'] = '';
					break;
				case 3:
				case 3.2:
					$this->attributes['html']   = '';
					$this->attributes['PUBLIC'] = '';
					$this->attributes['"-//W3C//DTD HTML 3.2 Final//EN"'] = '';
					break;
				case 4:
				case 4.01:
					$this->attributes['HTML']   = '';
					$this->attributes['PUBLIC'] = '';
					switch (mb_strtolower($params['mode'] ?? 'transitional')) {
						case 'strict':
							$this->html_mode = 'strict';
							$this->attributes['"-//W3C//DTD HTML 4.01//EN"'] = '';
							$this->attributes['"http://www.w3.org/TR/html4/strict.dtd"'] = '';
							break;
						case 'frameset':
							$this->html_mode = 'frameset';
							$this->attributes['"-//W3C//DTD HTML 4.01 Transitional//EN"'] = '';
							$this->attributes['"http://www.w3.org/TR/html4/loose.dtd"'] = '';
							break;
						case 'transitional':
						default:
							$this->html_mode = 'transitional';
							$this->attributes['"-//W3C//DTD HTML 4.01 Frameset//EN"'] = '';
							$this->attributes['"http://www.w3.org/TR/html4/frameset.dtd"'] = '';
					}
					break;
				case 5:
				default:
					$this->attributes['html'] = '';
			}
		}
	}
}

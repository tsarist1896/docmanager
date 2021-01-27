<?php #PHP 7.2
namespace Docmanager;

abstract class Document {
	private static $docs = [];
	protected $content   = [];



	/**
	 * 
	 */
	function __construct ($content = false) {
		if ($content) {
			$this->set($content);
		}

		self::rememberDocument($this);
	}



	/**
	 * 
	 */
	abstract function get ();



	/**
	 * 
	 */
	abstract function set ($content);



	/**
	 * 
	 */
	static function rememberDocument (Document $doc, string $type = '', string $name = '') {
		if (empty($type)) {
			$type = 'Document';
		}

		if (!isset(self::$docs[$type])) {
			self::$docs[$type] = [];
		}

		if (empty($name)) {
			self::$docs[$type][] = $doc;
			$names = array_keys(self::$docs[$type]);
			$name  = array_pop($names);
		} else {
			self::$docs[$type][$name] = $doc;
		}

		return $name;
	}



	/**
	 * 
	 */
	static function getDocument (string $type = '', string $name = '') {
		return self::$docs[$type][$name] ?? null;
	}
}

<?php #PHP 7.2
namespace docmanager;

abstract class Document {
	protected $content = [];

	function __construct ($content = false) {
		if ($content) {
			$this->set($content);
		}
	}

	abstract function get ();
	abstract function set ($content);
}
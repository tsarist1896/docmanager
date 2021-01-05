<?php
namespace docmanager\HTML;

trait HTMLElementPriority {
	protected $priority = 0;

	function setPriority (int $p) {
		$this->priority = $p;
	}

	function getPriority () {
		return $this->priority;
	}
}
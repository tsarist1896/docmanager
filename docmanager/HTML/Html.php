<?php
namespace docmanager\HTML;

class Html extends HTMLElement {
	protected $__head = null;
	protected $__body = null;


	function __construct () {
		$this->tag_name        = 'html';
		$this->content['head'] = $this->__head = new Head($this);
		$this->content['body'] = $this->__body = new Body($this);
	}
}
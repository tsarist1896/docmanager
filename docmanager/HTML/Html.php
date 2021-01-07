<?php
namespace docmanager\HTML;

final class Html extends HTMLElement {
	protected $__document = null;
	protected $__head     = null;
	protected $__body     = null;



	/**
	 * 
	 */
	function __construct ($document = null) {
		$this->tag_name        = 'html';
		$this->__document      = $document;
		$this->content['head'] = $this->__head = new Head($this);
		$this->content['body'] = $this->__body = new Body($this);
	}
}

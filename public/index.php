<?php
error_reporting(E_ALL); 
ini_set('display_errors', 'On');
set_time_limit(0);
define ('ROOT_DIR', pathinfo(__DIR__)['dirname']);


function dd($v, $i='') {
	die('<pre>'.print_r($v, true)."\n{$i}\n</pre>");
}


spl_autoload_register(function ($path) {
	if (strpos($path, '\\') !== false) {
		$path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
	}

	$filePath = ROOT_DIR . "/{$path}.php";
	if (is_file($filePath)) {
		require_once($filePath);
	}
});

$start = microtime(true);
$page = new \docmanager\HTML\HTMLDocument();
$page->html->attr('lang', 'ru')
           ->addClass('main')
           ->addClass('test')
           ->head->addClass('head')
                 ->attr('data-test', true)
                 ->title->set('DocManager')
                        ->parent()
                 ->setMeta(['name' => 'title', 'content' => 'DocManager'])
                 ->parent()
           ->body->addClass('body red')
                 ->removeClass('red')
                 ->attr('data-attr', 'test data attribute');
$page->setMeta(['name' => 'description', 'content' => 'Document created with DocManager']);
$end = microtime(true);

echo $page->get();
echo "\n<!-- Time: ", ($end - $start),' -->';
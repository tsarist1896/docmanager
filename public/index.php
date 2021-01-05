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
$page = new \docmanager\HTML\HTMLDocument(false, 'page');
$page->html->attr('lang', 'ru')
           ->addClass('main')
           ->addClass('test')
           ->head->addClass('head')
                 ->attr('data-test', true)
                 ->title->set('DocManager')
                        ->parent()
                 ->setMeta(['name' => 'title', 'content' => 'DocManager'])
                 ->addLink(['href' => './style.css', 'rel' => 'stylesheet'])
                 ->parent()
           ->body->addClass('body red')
                 ->removeClass('red')
                 ->attr('data-attr', 'test data attribute');
$page->setMeta(['name' => 'description', 'content' => 'Document created with DocManager']);
$page->addLink(['href' => './style-m.css', 'rel' => 'stylesheet']);
$end = microtime(true);

$links = \docmanager\HTML\HTMLDocument::getHTMLDocument('page')->getLinksByFilter(function ($l) {
	$link = $l->attr('href');
	return $link === './style-m.css';
});
foreach ($links as $l) {
	$page->deleteLink($l);
}


$styles_H1 = <<<CSS
h1 {
	margin-top: 47.7%;
	font-size: 72pt;
	text-align: center;
	text-shadow: 0 0 15px silver;
}
CSS;
$new_styles_H1 = <<<CSS
h1 {
	margin-top: calc(50vh - 1em);
	color: #b3b3b3;
}
CSS;
$styles_p = <<<CSS
p {
	text-align: center;
	color: silver;
	font-style: oblique;
	letter-spacing: 5px;
	font-family: monospace;
}
CSS;
$page->addStyles($styles_H1);
$page->addStyles($new_styles_H1);
$page->addNewStyles($styles_p);
$page->addContent('<h1>DocManager</h1><p>Document created with DocManager</p>');

echo $page->get();
echo "\n<!-- Time: ", ($end - $start),' -->';
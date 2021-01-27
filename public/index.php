<?php
use \Docmanager\HTML\HTMLDocument as HTMLDocument;

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
$page = new HTMLDocument(false, 'page');
$page->html->attr('lang', 'ru')
           ->addClass('main')
           ->addClass('test')
           ->head->addClass('head')
                 ->attr('data-test', true)
                 ->title->set('DocManager')
                        ->parent()
                 ->parent()
           ->body->addClass('body red')
                 ->removeClass('red')
                 ->attr('data-attr', 'test data attribute');

$page->addMeta(['name' => 'title', 'content' => 'DocManager']);
$page->addMeta(['name' => 'description', 'content' => 'Document created with DocManager']);
$page->addLink(['href' => './style.css', 'rel' => 'stylesheet']);
$page->addLink(['href' => './style-m.css', 'rel' => 'stylesheet']);
$page->addScript(file_get_contents('./console.js.html'), ['target' => 'head']);
$page->addScript('', ['attributes' => ['src' => 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js']]);


$links = HTMLDocument::getHTMLDocument('page')->getLinksByFilter(function ($l) {
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
$page->addStyles($new_styles_H1);
$page->addStyles($styles_H1, ['attributes' => ['id' => 'first_h1'], 'priority' => -1]);
$page->addStyles($styles_p);
$page->addContent('<h1>DocManager</h1>');
$page->addContent('<p>Document created with DocManager</p>');

echo $page->get();
$end = microtime(true);
echo "\n<!-- Time: ", ($end - $start),' -->';

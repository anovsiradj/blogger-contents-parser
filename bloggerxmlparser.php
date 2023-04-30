<?php

use yii\helpers\ArrayHelper;

require __DIR__ . '/../btemplates/php_packages/autoload.php';


// Path to Blogger Backup Blog Filename (.xml)
$filename = __DIR__ . '/blog.xml';

// parse the heck out to mixed array and object...
$parse = simplexml_load_file($filename);
if (!$parse) die("broken ${filename}");

/*
// because $parse still messy, we convert it to json
$encode = json_encode($parse);
// after convert to json, we decode that json to associative array
$dec = json_decode($encode,true);
*/

// $term is the filter for 'post' data
$termPost = 'http://schemas.google.com/blogger/2008/kind#post';

$contents = $parse;
$contents = json_decode(json_encode($parse), true);

$entries = $contents['entry'];
$posts = array_filter($entries, function($post) use ($termPost) {

	foreach (ArrayHelper::getValue($post, 'category', []) as $category) {
		if (ArrayHelper::getValue($category, 'term') === $termPost || ArrayHelper::getValue($category, '@attributes.term') === $termPost) {
			return true;
		}
	}
	return false;
});

dd($posts);
 
foreach($dec['entry'] as $id => $content){
	if($content['category'][0]['@attributes']['term'] == $term){
		$link = $content['link'][4]['href'];
		array_shift($content['category']);
		foreach($content['category'] as $keys => $tag){
			$tags[] = $content['category'][$keys]['@attributes']['term'];
		}
		echo '<h1>'.$content['title'].'</h1>';
		echo '<h2>'.$id.'</h2>';
		echo '<span>'.implode(',',$tags).'</span>';
		echo $content['content'];
		echo '<hr>';
		unset($tags);
	}
}

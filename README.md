##PHP's simple Minify
Compressing and minifying your css and javascript files

###Simple to use,

````php
/* example 
*
*	@type = css || js
*	@files = array of the files to compress
*	@file = the /path/to/savedFile.css of the file
*
*	function compress($type, $files, $file)
*
*	// the numbers is the filemtime() of the cache file
*	@return '/path/to/savedFile.css?vers=987589745';
*/

// to compress css files
$CSSFiles = array(
	'/style/css/fonts/Lilly-fontfacekit/stylesheet.css',
	'/style/css/bootstrap.css',
	'/style/css/bs_extended.css'
);
$min = new minify;
$CSSFile = $min->compress('css', $CSSFiles, '/a.homes/compressedFiles/css.global.min.css');

// to compress javascript files
$JSFiles = array(
	'/style/js/bootstrap.min.js',
	'/style/js/jquery.form.js',
	'/style/js/global.js'
);

$JSFile = $min->compress('js', $JSFiles, '/a.homes/compressedFiles/js.global.min.js');

// and then eco it out
echo '<link type="text/css" rel="stylesheet" href="'.$CSSFile.'">'.
	'<script type="text/javascript src="'.$JSFile."></script>';
````
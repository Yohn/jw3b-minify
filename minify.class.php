<?php

class minify {
	
	/*
	*	@str = all the css in 1 string
	*	
	*	Credits:: thanks to http://kitmacallister.com/2011/minify-css-with-php/
	*/
	function minifyCSS($str){
		$find = array('!/\*.*?\*/!s',
			'/\n\s*\n/',
			'/[\n\r \t]/',
			'/ +/',
			'/ ?([,:;{}]) ?/',
			'/;}/'
		);
		$repl = array('',
			"\n",
			' ',
			' ',
			'$1',
			'}'
		);
		return preg_replace($find, $repl, $str);
	}
	
	/*
	*	@str = all the js in 1 string
	*	I wrote this one out, its not perfect, but does compress the file a little.
	*/
	function minifyJS($str){
		return preg_replace(
			array(
				'!/\*.*?\*/!s', 
				"/\n\s+/", 
				"/\n(\s*\n)+/", 
				"!\n//.*?\n!s", 
				"/\n\}(.+?)\n/",
				"/;\n/"
			), array(
				'', 
				"\n", 
				"\n", 
				"\n", 
				"}\\1\n",
				';'
			), $str);
	}
	
	/*!
	*	compress, Compress the CSS and JS files into 1 file.
	*
	*	@type = css || js
	*	@files = array of the files to compress
	*	@file = the path/name of the file
	*/
	
	function compress($type, $files, $file) {
		$LastUpdate = $this->getLU($files);
		if(is_file($_SERVER['DOCUMENT_ROOT'].$file)){
			$MainLast = filemtime($_SERVER['DOCUMENT_ROOT'].$file);
		} else { $updateF = 1; $MainLast = 0; }
		if(isset($updateF) || $LastUpdate > $MainLast){
			// files have been updated so update the minified file
			switch($type){
				case 'css':
					$Contents = $this->getFCont($files);
					$NewCSS = $this->minifyCSS($Contents['con']);
					$PutIn = "/*\n  This is a minified version of Skem9s CSS ".$Contents['top']."\n*/\n".$NewCSS;
				break;
				case 'js':
					$Contents = $this->getFCont($files);
					$NewJS = $this->minifyJS($Contents['con']);
					$PutIn = "/*\n  This is a combined version of Skem9s JS ".$Contents['top']."\n*/\n".$NewJS;
				break;
			}
			$gogo = $this->saveFile($file, $PutIn);
		}
		return $file.'?vers='.$LastUpdate;
		
	}
	
	/*
	*	getLU Last Updated File
	*
	*	@files = array of all of the files
	*/
	function getLU($files){
		$LastUpdate = 0;
		foreach($files as $v){
			$ed = filemtime($_SERVER['DOCUMENT_ROOT'].$v);
			if($ed > $LastUpdate){ $LastUpdate = $ed; }
		}
		return $LastUpdate;
	}
	
	function saveFile($file, $save){
		$fp = fopen($_SERVER['DOCUMENT_ROOT'].$file, 'w');
		flock($fp, LOCK_EX);
		fwrite($fp, $save);
		flock($fp, LOCK_UN);
		fclose($fp);
		return true;
	}
	/*
	*	get File Contents
	*
	*	@files = array of all the files
	*/
	function getFCont($files){
		$cont = '';
		$FileTop = "\n\n  Last Updated:: ".date ("F d Y H:i:s.", time())."\n\n  Files include::\n";
		$root = $_SERVER['DOCUMENT_ROOT'];
		foreach($files as $v){
			$FileTop .= "  ".$v."\n";
			$cont .= is_file($root.$v) ? file_get_contents($root.$v)."\n" : '';
		}
		return array('con' => $cont, 'top' => $FileTop);
	}
	
	
}

?>
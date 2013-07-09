<?php
/**
 * @package     SEO Overrides
 * @subpackage  libs
 * @author		Robert Went http://www.robertwent.com
 * @copyright   Copyright (C) 2013 - Robert Went
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * @param string $imagePath - either a local absolute/relative path, or a remote URL (e.g. http://...flickr.com/.../ ). See SECURITY note above.
 * @param array $opts  (w(pixels), h(pixels), crop(boolean), scale(boolean), thumbnail(boolean), maxOnly(boolean), canvas-color(#abcabc), output-filename(string), cache_http_minutes(int))
 * @return new URL for resized image.
 */

function resize($imagePath,$opts=null){
	set_time_limit(0);
	ignore_user_abort(1);
	$app = JFactory::getApplication();
	$template = $app->getTemplate();
	include (JPATH_BASE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'pure'.DIRECTORY_SEPARATOR.'config.php');

	$imagePath = urldecode($imagePath);

	// start configuration........
	$mode = (int) 0755;
	$cacheRoot = JPATH_BASE.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.$template;
	if (!is_dir($cacheRoot)) {
		mkdir($cacheRoot);
		chmod($cacheRoot, $mode);
		touch($cacheRoot.'/index.html');
	}
	$cacheFolder = $cacheRoot.DIRECTORY_SEPARATOR.'images/';
	if (!is_dir($cacheFolder)) {
		mkdir($cacheFolder, $mode);
		touch($cacheFolder.'index.html');
	}
	$remoteFolder = $cacheFolder.'remote/';
	if (!is_dir($remoteFolder)) {
		mkdir($remoteFolder, $mode);
		touch($remoteFolder.'index.html');
	}

	//setting script defaults
	$defaults['crop']				= false;
	$defaults['scale']				= false;
	$defaults['thumbnail']			= false;
	$defaults['maxOnly']			= false;
	$defaults['canvas-color']		= 'transparent';
	$defaults['cacheFolder']		= $cacheFolder;
	$defaults['remoteFolder']		= $remoteFolder;
	$defaults['quality'] 			= $imageQuality;
	$defaults['cache_http_minutes']	= $imageCacheTime;
	$opts = array_merge($defaults, $opts);
	$path_to_convert = $pathConvert;
	// configuration ends...

	//processing begins
	$purl = parse_url($imagePath);
	$finfo = pathinfo($imagePath);
	$filename = $finfo['filename'];
	$path = $finfo['dirname'];
	$ext = $finfo['extension'];
	// check for remote image..
	if(isset($purl['scheme']) && ($purl['scheme'] == 'http' || $purl['scheme'] == 'https')){
	// grab the image, and cache it so we have something to work with..
		list($filename) = explode('?',$finfo['basename']);
		$local_filepath = $remoteFolder.$filename;
		$download_image = true;
		if(file_exists($local_filepath)){
			if(filemtime($local_filepath) < strtotime('+'.$opts['cache_http_minutes'].' minutes')){
				$download_image = false;
			}
		}
		if($download_image){
			file_put_contents($local_filepath,file_get_contents($imagePath));
		}
		$imagePath = $local_filepath;
	}
	if(!file_exists($imagePath)){
		$imagePath = $_SERVER['DOCUMENT_ROOT'].$imagePath;
		if(!file_exists($imagePath)){
			return 'image not found';
		}
	}
	if(isset($opts['w'])){ $w = $opts['w']; };
	if(isset($opts['h'])){ $h = $opts['h']; };

	//create the path structure in the cache folder if it doesn't exist
	if (!is_dir($cacheRoot.'/'.$path)) {
		mkdir($cacheRoot.'/'.$path, $mode, true);
	}

	//	$newPath = $cacheRoot.'/'.$path.'/'.$filename.'.'.$ext;

	if(!empty($w) and !empty($h)){
		$newPath = $cacheRoot.'/'.$path.'/'.$filename.'-w'.$w.'-h'.$h;
	}else if(!empty($w)){
		$newPath = $cacheRoot.'/'.$path.'/'.$filename.'-w'.$w;
	}else if(!empty($h)){
		$newPath = $cacheRoot.'/'.$path.'/'.$filename.'-h'.$h;
	}else{
		return false;
	}
	$newPath .= '.'.$ext;

	$create = true;
	if(file_exists($newPath)){
		$create = false;
		$origFileTime = date("YmdHis",filemtime($imagePath));
		$newFileTime = date("YmdHis",filemtime($newPath));
        if(($newFileTime < $origFileTime) && ($imageCacheTime != 0)){					# Not using $opts['expire-time'] ??
        $create = true;
    }
}
if($create){
	if(!empty($w) && !empty($h)){
		list($width,$height) = getimagesize($imagePath);
		$resize = $w;
		if($width > $height){
			$ww = $w;
			$hh = round(($height/$width) * $ww);
			$resize = $w;
			if($opts['crop']){
				$resize = "x".$h;
			}
		}else{
			$hh = $h;
			$ww = round(($width/$height) * $hh);
			$resize = "x".$h;
			if($opts['crop']){
				$resize = $w;
			}
		}
		if($opts['scale']){
			$cmd = $path_to_convert." ".escapeshellarg($imagePath)." -resize ".escapeshellarg($resize)." -quality ". escapeshellarg($opts['quality'])." " .escapeshellarg($newPath);
		}else if($opts['canvas-color'] == 'transparent' && !$opts['crop'] && !$opts['scale']){
			$cmd = $path_to_convert." ".escapeshellarg($imagePath)." -resize ".escapeshellarg($resize)." -size ".escapeshellarg($ww ."x". $hh)." xc:". escapeshellarg($opts['canvas-color'])." +swap -gravity center -composite -quality ".escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);
		}else{
			$cmd = $path_to_convert." ".escapeshellarg($imagePath)." -resize ".escapeshellarg($resize)." -size ".escapeshellarg($w ."x". $h)." xc:". escapeshellarg($opts['canvas-color'])." +swap -gravity center -composite -quality ".escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);
		}
	}else{
		$cmd = $path_to_convert." " . escapeshellarg($imagePath).
		" -thumbnail ".(!empty($w) ? 'x':'').$w." ".($opts['maxOnly'] == true ? "\>" : "")." -quality ".escapeshellarg($opts['quality'])." ".escapeshellarg($newPath);
	}
	$c = exec($cmd, $output, $return_code);
	if($return_code != 0) {
		error_log("Tried to execute : $cmd, return code: $return_code, output: " . print_r($output, true));
		return false;
	}

	if($imageResizeSmush){
		require (JPATH_SITE.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template.DIRECTORY_SEPARATOR.'pure'.DIRECTORY_SEPARATOR.'libs'.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'SmushIt.class.php');
		$smushit = new SmushIt($newPath);
		$smushit->get();
		$src = pathinfo($smushit->source, PATHINFO_EXTENSION);
		$dst = pathinfo($smushit->destination, PATHINFO_EXTENSION);
		if ($src == $dst) {
			copy($smushit->destination, $newPath);
		}
	}
}

return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
}

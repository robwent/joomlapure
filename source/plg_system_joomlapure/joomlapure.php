<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.joomlapure
 *
 * @copyright   Copyright (C) Robert Went. All rights reserved.
 * @license     WTFPL http://www.wtfpl.net/txt/copying/
 *
 * JoomlaPure::isMobile()
 * JoomlaPure::getUserAgent()
 * JoomlaPure::getPhoneDevices()
 * JoomlaPure::getTabletDevices()
 * JoomlaPure::isTablet($userAgent, $httpHeaders)
 * JoomlaPure::is($key, $userAgent, $httpHeaders)
 * JoomlaPure::getOperatingSystems()
 * JoomlaPure::isBot()
 *
 * JoomlaPure::getImages($content)
 * JoomlaPure::resize($imagePath,$opts)
 */
defined('_JEXEC') or die;

//jimport( 'joomla.plugin.plugin' );

class plgSystemJoomlapure extends JPlugin
{

	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );

		parent::__construct( $subject );
	}

	public function onAfterInitialise()
	{
		if (!defined('DS')) {
			define('DS', DIRECTORY_SEPARATOR);
		}
	//	$this->_mainframe= JFactory::getApplication();
	//	$this->_template = $this->_mainframe->getTemplate();
	//	$pureTemplate = file_exists(JPATH_SITE.DS.'templates'.DS.$this->_template.DS.'pure'.DS.'config.php') ? true : false;

	//	if(!($pureTemplate)) return;

		require_once 'joomlapure/classes/images/SmushIt.class.php';
		require_once 'joomlapure/classes/mobiledetect/Mobile_Detect.php';
	//	return true;
	}

}

class JoomlaPure
{
	// Template parameters
	public static function getTplParams(){
		$app = JFactory::getApplication();
		$template = $app->getTemplate(true);
		return $template->params;
	}

	// Mobile Detection
	public static function isMobile()
	{
		$detect = new Mobile_Detect;
		return $detect->isMobile();
	}

	public static function getUserAgent()
	{
		$detect = new Mobile_Detect;
		return $detect->getUserAgent();
	}

	public static function getPhoneDevices()
	{
		$detect = new Mobile_Detect;
		return $detect->getPhoneDevices();
	}

	public static function getTabletDevices()
	{
		$detect = new Mobile_Detect;
		return $detect->getTabletDevices();
	}

	public static function isTablet($userAgent = null, $httpHeaders = null)
	{
		$detect = new Mobile_Detect;
		return $detect->isTablet($userAgent, $httpHeaders);
	}

	public static function is($key, $userAgent = null, $httpHeaders = null)
	{
		$detect = new Mobile_Detect;
		return $detect->is($key, $userAgent, $httpHeaders);
	}

	public static function getOperatingSystems()
	{
		$detect = new Mobile_Detect;
		return $detect->getOperatingSystems();
	}

	public static function isBot()
	{
		$detect = new Mobile_Detect;
		return $detect->is('Bot');
	}

	// Search string for Images
	public static function getImages($content){
		$images = array();
		preg_match_all('/src=[\\"\']([-0-9A-Za-z\/_\:\.]*.(jpg|png|gif|jpeg))/i', $content, $images);
		if (array_key_exists(1, $images)) {
			return  $images;
		}
		return false;
	}

	// Resize and cache images
	/**
	* @param string $imagePath - either a local absolute/relative path, or a remote URL (e.g. http://...flickr.com/.../ )
	* @param array $opts  (w(pixels), h(pixels), crop(boolean), scale(boolean), thumbnail(boolean), maxOnly(boolean), canvas-color(#abcabc), smush(boolean), cache_http_minutes(int))
	* @return new URL for resized image
	**/
	public static function resize($imagePath,$opts=null){
		set_time_limit(0);
		ignore_user_abort(1);

		$imagePath = urldecode($imagePath);

		$app = JFactory::getApplication();
		$template = $app->getTemplate();

	// start configuration........
		$mode = (int) 0755;
		$cacheRoot = JPATH_BASE.DS.'images'.DS.'cache';
		if (!is_dir($cacheRoot)) {
			mkdir($cacheRoot);
			chmod($cacheRoot, $mode);
			touch($cacheRoot.'/index.html');
		}
		$cacheFolder = $cacheRoot.DS.'images'.DS;
		if (!is_dir($cacheFolder)) {
			mkdir($cacheFolder, $mode);
			touch($cacheFolder.'index.html');
		}
		$remoteFolder = $cacheFolder.'remote'.DS;
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
		$defaults['quality'] 			= '80';
		$defaults['smush'] 				= false;
		$defaults['cache_http_minutes']	= '0';
		$opts = array_merge($defaults, $opts);
		$path_to_convert = '/usr/bin/convert';
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

		if(!empty($w) && !empty($h)){
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
        if(($newFileTime < $origFileTime) && ($imageCacheTime != 0)){	# Check age of file and if cache is disabled
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

    	if($opts['smush']){
    		$smushit = new SmushIt(JURI::base().ltrim(str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath), '/'));
    		$smushit->get();
    		$src = pathinfo($smushit->source, PATHINFO_EXTENSION);
    		$dst = pathinfo($smushit->destination, PATHINFO_EXTENSION);
    		if ($src == $dst ) {
    			copy($smushit->destination, $newPath);
    		}
    	}
    }
    return str_replace($_SERVER['DOCUMENT_ROOT'],'',$newPath);
}

}
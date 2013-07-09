<?php
/**
 * @package     SEO Overrides
 * @author		Robert Went http://www.robertwent.com
 * @copyright   Copyright (C) 2013 - Robert Went
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

// Get language code
$lang = JFactory::getLanguage();
$languages = JLanguageHelper::getLanguages('lang_code');
$languagetag = $lang->getTag();
$languageCode = $languages[ $lang->getTag() ]->sef;
// Start SEO Extras
$doc = JFactory::getDocument();
$app = JFactory::getApplication();
// Get the meta description or introtext if meta is empty
if ($this->item->metadesc != null) {
	$description = preg_replace('/\s+?(\S+)?$/', '', substr($this->item->metadesc, 0, $descriptionMax));
} else {
	$description = preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($this->item->introtext), 0, $descriptionMax));
}
//Check for featured image or fall back to introtext
if (isset($images->image_fulltext) && !empty($images->image_fulltext)) {
	$ogImage = JURI::base().$images->image_fulltext;
} else {
	//preg_match('/src=[\\"\']([-0-9A-Za-z\/_\:\.]*.(jpg|png|gif|jpeg))/i', $this->item->introtext, $image);
	if (getImages($this->item->introtext) === true) {
		$introImages = getImages($this->item->introtext);
		if (substr($introImages[1][0], 0, 4) != 'http') {
			$introImages[1][0] = JURI::base().$introImages[1][0];
		}
		$ogImage = $introImages[1][0];
	} else {
		$ogImage = null;
	}
}
// Facebook
if ($openGraph) {
	$exMeta = '<meta property="og:site_name" content="'.$ogSitename.'"/>'."\n";
	$exMeta .= '<meta property="og:title" content="'.$this->item->title.'"/>'."\n";
	$exMeta .= '<meta property="og:url" content="'.$pageUrl.'"/>'."\n";
	$exMeta .= '<meta property="og:type" content="article"/>'."\n";
	$exMeta .= '<meta property="og:description" content="'.$description.'"/>'."\n";
//	$exMeta .= '<meta property="og:author" content="100005501707310"/>'."\n";
	$exMeta .= '<meta property="og:locale" content="'.$languageCode.'">'."\n";
	if ($ogImage) {
		$exMeta .= '<meta property="og:image"  content="'.$ogImage.'" />' ."\n";
	}
	if ($ogAdmins) {
		$exMeta .= '<meta property="fb:admins" content="'.$ogAdmins.'">'."\n";
	}
	if ($this->item->publish_down != '0000-00-00 00:00:00') {
		$exMeta .= '<meta property="article:published_time"  content="'.JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC4')).'" />'."\n";
		$exMeta .= '<meta property="article:modified_time"  content="'.JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC4')).'" />' ."\n";
		$exMeta .= '<meta property="article:expiration_time"  content="'.JHtml::_('date', $this->item->publish_down, JText::_('DATE_FORMAT_LC4')).'" />' ."\n";
	}
	$exMeta .= '<meta property="article:section"  content="'.$this->escape($this->item->category_title).'" />' ."\n";
	if ($this->item->tags->itemTags != null) {
		$this->item->ogtagLayout = new JLayoutFile('joomla.content.ogTags');
		$exMeta .= $this->item->ogtagLayout->render($this->item->tags->itemTags);
	}

// Twitter
	if ($twitterTags && $openGraph) {
		//Check for twitter user
		if ($twitterTags && $twittercreator) {
			$db = JFactory::getDbo();
			$query = 'SELECT `params` FROM `#__contact_details` WHERE `id` = '. (int) $this->item->contactid;
			$db->setQuery($query);
			$contactParams = $db->loadResult();
			$contactParams = json_decode($contactParams, true);
//print_r($contactParams);
			if (preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $contactParams['linka'], $matches)) {
				$twittercreator = $matches[3];
			} elseif (preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $contactParams['linkb'], $matches)) {
				$twittercreator = '@'.$matches[3];
			} elseif (preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $contactParams['linkc'], $matches)) {
				$twittercreator = $matches[3];
			} elseif (preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $contactParams['linkd'], $matches)) {
				$twittercreator = $matches[3];
			} elseif (preg_match("|https?://(www\.)?twitter\.com/(#!/)?@?([^/]*)|", $contactParams['linke'], $matches)) {
				$twittercreator = $matches[3];
			}
			if ($twittercreator !=1) {
				$twittercreator = '@'.$twittercreator;
			} else {
				$twittercreator = $twittercreatorDf;
			}

		}

		if ($defaultTCard == 'gallery') {
			$introImages = getImages($this->item->introtext);
			if (count($introImages[1]) >=4) {
				$doc->setMetaData( 'twitter:card', 'gallery' );
				$doc->setMetaData( 'twitter:image0:src', substr($introImages[1][0], 0, 4) == 'http' ? $introImages[1][0] : JURI::base().$introImages[1][0] );
				$doc->setMetaData( 'twitter:image1:src', substr($introImages[1][1], 0, 4) == 'http' ? $introImages[1][1] : JURI::base().$introImages[1][1] );
				$doc->setMetaData( 'twitter:image2:src', substr($introImages[1][2], 0, 4) == 'http' ? $introImages[1][2] : JURI::base().$introImages[1][2] );
				$doc->setMetaData( 'twitter:image3:src', substr($introImages[1][3], 0, 4) == 'http' ? $introImages[1][3] : JURI::base().$introImages[1][3] );
			} else {
				$defaultTCard = 'photo';
			}
		}
		if ($defaultTCard == 'photo') {
			$introImages = getImages($this->item->introtext);
			if ($images->image_fulltext || $introImages[1][0] ) {
				$doc->setMetaData( 'twitter:card', $defaultTCard );
				if ($introImages[1][0]) {
					$doc->setMetaData( 'twitter:image:src', substr($introImages[1][0], 0, 4) == 'http' ? $introImages[1][0] : JURI::base().$introImages[1][0] );
				} else {
					$doc->setMetaData( 'twitter:image:src', JURI::base().$images->image_fulltext );
				}
			} else {
				$defaultTCard = 'summary';
			}
		}

		if ($defaultTCard == 'summary') {
			$doc->setMetaData( 'twitter:card', $defaultTCard );
		}
		$doc->setMetaData( 'twitter:creator', $twittercreator );
		if ($twitterSite) {
			$doc->setMetaData( 'twitter:site', $twitterSite );
		}
		$doc->setMetaData( 'twitter:description', $description );

	}
	$doc->addCustomTag($exMeta);
}

//Check for Google+ user details
if ($relAuthor) {
	$db = JFactory::getDbo();
	$query = 'SELECT `webpage` FROM `#__contact_details` WHERE `id` = '. (int) $this->item->contactid;
	$db->setQuery($query);
	$plus = $db->loadResult();
	if (strpos($plus, 'plus.google.com')) {
		$googlePage = $plus.'?rel=author';
	} else {
		$query = 'SELECT `params` FROM `#__contact_details` WHERE `id` = '. (int) $this->item->contactid;
		$db->setQuery($query);
		$contactParams = $db->loadResult();
		$contactParams = json_decode($contactParams, true);
		if ($contactParams['linka']) {
			if (strpos($contactParams['linka'], 'plus.google.com')) {
				$googlePage = $contactParams['linka'].'?rel=author';
			}
		} elseif ($contactParams['linkb']) {
			if (strpos($contactParams['linkb'], 'plus.google.com')) {
				$googlePage = $contactParams['linkb'].'?rel=author';
			}
		} elseif ($contactParams['linkc']) {
			if (strpos($contactParams['linkc'], 'plus.google.com')) {
				$googlePage = $contactParams['linkc'].'?rel=author';
			}
		} elseif ($contactParams['linkd']) {
			if (strpos($contactParams['linkd'], 'plus.google.com')) {
				$googlePage = $contactParams['linkd'].'?rel=author';
			}
		} elseif ($contactParams['linke']) {
			if (strpos($contactParams['linke'], 'plus.google.com')) {
				$googlePage = $contactParams['linke'].'?rel=author';
			}
		}

	}
}

// SocialCount stuff
if ($SocialCountJs) {
	$doc->addScript($this->baseurl.'/templates/'.$app->getTemplate().'/pure/libs/socialcount/socialcount.js');
}
if ($SocialCountCss) {
	$doc->addStyleSheet($this->baseurl.'/templates/'.$app->getTemplate().'/pure/libs/socialcount/socialcount.css');
	if ($SocialCountIcons) {
		$doc->addStyleSheet($this->baseurl.'/templates/'.$app->getTemplate().'/pure/libs/socialcount/socialcount-icons.css');
	}
}
function getImages($content){ //Check for images
	$images = array();
	preg_match_all('/src=[\\"\']([-0-9A-Za-z\/_\:\.]*.(jpg|png|gif|jpeg))/i', $content, $images);
	if (array_key_exists(1, $images)) {
		return  $images;
	}
	return false;
}
?>
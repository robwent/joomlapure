<?php
/**
 * @package     SEO Overrides
 * @subpackage  com_content
 * @author		Robert Went http://www.robertwent.com
 * @copyright   Copyright (C) 2013 - Robert Went
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
// Include the seo config file
$app = JFactory::getApplication();
$template = $app->getTemplate();
$images  = json_decode($this->item->images);
$urls    = json_decode($this->item->urls);
$user    = JFactory::getUser();

include (JPATH_BASE.DS.'templates'.DS.$template.DS.'pure'.DS.'config.php');
// Include the specific include
include (JPATH_BASE.DS.'templates'.DS.$template.DS.'pure'.DS.'views'.DS.'article_default.php');

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$info    = $params->get('info_block_position', 0);

if (!$removeCaption) {
	JHtml::_('behavior.caption');
}
?>
<div class="item-page<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<section>
		<header class="page-header">
			<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</header>
		<?php endif;
		if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && $this->item->paginationrelative)
		{
			echo $this->item->pagination;
		}
		?>
		<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
		<header <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="page-header">
			<<?php if (!$this->params->get('show_page_heading')) {echo 'h1'; }else {echo 'h2';} ?> <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemprop="name"'; ?>>
			<?php if ($this->item->state == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
		<?php if ($params->get('show_title')) : ?>
		<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
		<a href="<?php echo $this->item->readmore_link; ?>"> <?php echo $this->escape($this->item->title); ?></a>
	<?php else : ?>
	<?php echo $this->escape($this->item->title); ?>
<?php endif; ?>
<?php endif; ?>
</<?php if (!$this->params->get('show_page_heading')) {echo 'h1'; }else {echo 'h2';} ?>>
</header>
<?php endif; ?>
<?php if (!$this->print) : ?>
	<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
	<div class="btn-group pull-right">
		<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
		<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
		<ul class="dropdown-menu actions">
			<?php if ($params->get('show_print_icon')) : ?>
			<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
		<?php endif; ?>
		<?php if ($params->get('show_email_icon')) : ?>
		<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
	<?php endif; ?>
	<?php if ($canEdit) : ?>
	<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
<?php endif; ?>
</ul>
</div>
<?php endif; ?>
<?php else : ?>
	<div class="pull-right">
		<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
	</div>
<?php endif; ?>
<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author')); ?>
<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
	<footer class="article-info muted">
		<ul class="article-info list plain">
			<li class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></li>

			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<li class="createdby" <?php if ($microdata = 1 && $microAuthor = 1)  echo 'itemscope itemtype="http://schema.org/Person"'; ?>>
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
				<?php
				$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
				$menu = JFactory::getApplication()->getMenu();
				$item = $menu->getItems('link', $needle, true);
				$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
				?>
				<?php echo JText::_('TPL_JOOMLAPURE_WRITTEN_BY');?>
				<a <?php if ($googlePage && $relAuthorDirect) echo 'target="_blank" ';?> href="<?php if ($googlePage && $relAuthorDirect) {echo $googlePage;} else { echo JRoute::_($cntlink);} ?>" <?php if ($microdata = 1 && $microAuthor = 1) echo 'itemprop="name"';?> ><?php echo trim($author); ?></a>
			<?php else: ?>
			<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
		<?php endif; ?>
	</li>
<?php endif; ?>
<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
	<li class="parent-category-name">
		<?php $title = $this->escape($this->item->parent_title);
		$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';?>
		<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
		<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
	<?php else : ?>
	<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
	<li class="category-name">
		<?php $title = $this->escape($this->item->category_title);
		$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
		<?php if ($params->get('link_category') && $this->item->catslug) : ?>
		<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
	<?php else : ?>
	<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
<?php endif; ?>
</li>
<?php endif; ?>

<?php if ($params->get('show_publish_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="published">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>

<?php if ($info == 0) : ?>
	<?php if ($params->get('show_modify_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="modified">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_create_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="create">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>

<?php if ($params->get('show_hits')) : ?>
	<li class="hits">
		<span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
	</li>
<?php endif; ?>
<?php endif; ?>
</ul>
</footer>
<?php endif; ?>

<?php if ($params->get('show_tags', 1) && !empty($this->item->tags) && ($removeArticleTags ==0)) : ?>
	<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

	<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
<?php endif; ?>
<?php if ($SocialCount && ($SocialCountPosition == 1 || $SocialCountPosition == 3)) {
	echo $SocialCountMarkup;
} ?>
<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '0')) || ($params->get('urls_position') == '0' && empty($urls->urls_position)))
	|| (empty($urls->urls_position) && (!$params->get('urls_position')))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>
<?php if ($params->get('access-view')):?>
	<?php if (isset($images->image_fulltext) && !empty($images->image_fulltext)) : ?>
	<?php $imgfloat = (empty($images->float_fulltext)) ? $params->get('float_fulltext') : $images->float_fulltext; ?>
	<figure class="pull-<?php echo htmlspecialchars($imgfloat); ?> item-image" <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope'; ?>> <img
		<?php if ($images->image_fulltext_caption):
		echo 'class="caption"'.' title="' .htmlspecialchars($images->image_fulltext_caption) . '"';
		endif; ?>
		<?php if ($imageResizeMain) {
			$settings = array();
			if ($imageWidthMain) {
				$settings['w'] = $imageWidthMain;
			}
			if ($imageHeightMain) {
				$settings['h'] = $imageHeightMain;
			}
			if ($imageScaleMain) {
				$settings['scale'] = 1;
			}
			if ($imageCropMain) {
				$settings['crop'] = 1;
			}
			if ($imageResizeSmush) {
				$settings['smush'] = 1;
			}
			if ($canvasColor) {
				$settings['canvas-color'] = $canvasColor;
			}
			if ($imageCacheTime) {
				$settings['cache_http_minutes'] = $imageCacheTime;
			}
			//$settings = array('w'=>$imageWidthMain,'h'=>$imageHeightMain);
			$original = $images->image_fulltext;
			$images->image_fulltext = JoomlaPure::resize($images->image_fulltext,$settings);
		} ?>
		src="<?php if ($cdnUrl && $cdnFeaturedImages) {echo $cdnUrl.'/'.ltrim(htmlspecialchars($images->image_fulltext), '/');} else echo htmlspecialchars($images->image_fulltext);  ?>" <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemprop="image"'; ?> <?php if($imageResizeMain && $original) echo 'data-original="'.htmlspecialchars($original).'"'; ?> alt="<?php echo htmlspecialchars($images->image_fulltext_alt); ?>"/>
	</figure>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) && $this->item->pagination && !$this->item->paginationposition && !$this->item->paginationrelative):
	echo $this->item->pagination;
endif;
?>
<?php if (isset ($this->item->toc)) :
echo $this->item->toc;
endif; ?>
<?php echo $this->item->text; ?>

<?php if ($SocialCount && ($SocialCountPosition == 2 || $SocialCountPosition == 3)) {
	echo $SocialCountMarkup;
} ?>

<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
	<footer class="article-info muted" <?php if ($waiAriaRoles) echo 'role="contentinfo"'; ?>>
		<ul class="article-info list plain">
			<li class="article-info-term"><?php echo JText::_('COM_CONTENT_ARTICLE_INFO'); ?></li>

			<?php if ($info == 1) : ?>
			<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
			<li class="createdby" <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Person"'; ?>>
				<?php $author = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author; ?>
				<?php if (!empty($this->item->contactid) && $params->get('link_author') == true) : ?>
				<?php
				$needle = 'index.php?option=com_contact&view=contact&id=' . $this->item->contactid;
				$menu = JFactory::getApplication()->getMenu();
				$item = $menu->getItems('link', $needle, true);
				$cntlink = !empty($item) ? $needle . '&Itemid=' . $item->id : $needle;
				?>
				<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', JHtml::_('link', JRoute::_($cntlink), $author)); ?>
			<?php else: ?>
			<?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?>
		<?php endif; ?>
	</li>
<?php endif; ?>
<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
	<li class="parent-category-name">
		<?php	$title = $this->escape($this->item->parent_title);
		$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
		<?php if ($params->get('link_parent_category') && $this->item->parent_slug) : ?>
		<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
	<?php else : ?>
	<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_category')) : ?>
	<li class="category-name">
		<?php 	$title = $this->escape($this->item->category_title);
		$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
		<?php if ($params->get('link_category') && $this->item->catslug) : ?>
		<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $url); ?>
	<?php else : ?>
	<?php echo JText::sprintf('COM_CONTENT_CATEGORY', $title); ?>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_publish_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="published">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>
<?php endif; ?>

<?php if ($params->get('show_create_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="create">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_modify_date')) : ?>
	<li <?php if ($microdata = 1 && $microPublishing = 1)  echo 'itemscope itemtype="http://schema.org/Article"'; ?> class="modified">
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
		<time itemprop="datePublished" datetime="<?php echo JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC4')); ?>">
		<?php endif; ?>
		<span class="icon-calendar"></span> <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
		<?php if ($microdata = 1 && $microPublishing = 1) : ?>
	</time>
<?php endif; ?>
</li>
<?php endif; ?>
<?php if ($params->get('show_hits')) : ?>
	<li class="hits">
		<span class="icon-eye-open"></span> <?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
	</li>
<?php endif; ?>
</ul>
</footer>
<?php endif; ?>

<?php
if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && !$this->item->paginationrelative):
	echo $this->item->pagination;
?>
<?php endif; ?>
<?php if (isset($urls) && ((!empty($urls->urls_position) && ($urls->urls_position == '1')) || ($params->get('urls_position') == '1'))) : ?>
	<?php echo $this->loadTemplate('links'); ?>
<?php endif; ?>
<?php // Optional teaser intro text for guests ?>
<?php elseif ($params->get('show_noauth') == true && $user->get('guest')) : ?>
	<?php echo $this->item->introtext; ?>
	<?php //Optional link to let them register to see the whole article. ?>
	<?php if ($params->get('show_readmore') && $this->item->fulltext != null) :
	$link1 = JRoute::_('index.php?option=com_users&view=login');
	$link = new JURI($link1);?>
	<p class="readmore">
		<a href="<?php echo $link; ?>">
			<?php $attribs = json_decode($this->item->attribs); ?>
			<?php
			if ($attribs->alternative_readmore == null) :
				echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
			elseif ($readmore = $this->item->alternative_readmore) :
				echo $readmore;
			if ($params->get('show_readmore_title', 0) != 0) :
				echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif;
			elseif ($params->get('show_readmore_title', 0) == 0) :
				echo JText::sprintf('COM_CONTENT_READ_MORE_TITLE');
			else :
				echo JText::_('COM_CONTENT_READ_MORE');
			echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
			endif; ?>
		</a>
	</p>
<?php endif; ?>
<?php endif; ?>
<?php
if (!empty($this->item->pagination) && $this->item->pagination && $this->item->paginationposition && $this->item->paginationrelative) :
	echo $this->item->pagination;
?>
<?php endif; ?>
<?php echo $this->item->event->afterDisplayContent; ?> </div>
<?php if (!(JoomlaPure::isMobile() && !JoomlaPure::isTablet() && $mobileRemoveComments)) {
	if($commentCode) {echo $commentCode;}
}
?>
<?php if ($this->params->get('show_page_heading')) : ?>
</section>
<?php endif; ?>

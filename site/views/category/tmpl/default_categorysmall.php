<?php
/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_categorysmall.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<article class="uk-comment">
    <header class="uk-comment-header">
		<?php if ($this->params->get('category_icon')): ?>
			<?php $this->category->icon = (isset($this->category->icon) && $this->category->icon) ? $this->category->icon : $this->params->get('category_default_icon'); ?>
			<?php if ($this->category->icon): ?>
				<img class="uk-comment-avatar" src="<?php echo $this->category->icon; ?>" alt="<?php echo $this->category->name; ?>">
			<?php endif; ?>
		<?php endif; ?>
		<h1 class="uk-comment-title"><?php echo $this->category->name; ?></h1>
		<?php if ($this->params->get('category_hits') || $this->params->get('category_sermon_count')): ?>
		<div class="uk-comment-meta">
			<?php if ($this->params->get('category_hits')): ?>
				<?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $this->category->hits; ?><br />
			<?php endif; ?>
			<?php if ($this->params->get('category_sermon_count')): ?>
				<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo $this->sermonTotal; ?><br />
			<?php endif; ?>
			<?php if ($this->params->get('category_sermons_download_counter')): ?>
				<?php echo JText::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS'); ?>: <?php echo $this->downloadTotal; ?><br />
			<?php endif; ?>
		</div>
		<?php endif; ?>
	 </header>
	<?php if ($this->params->get('category_desc') && $this->category->description): ?>
		<div class="uk-comment-body"><?php echo $this->category->description; ?></div>
	<?php endif; ?>
</article><br />

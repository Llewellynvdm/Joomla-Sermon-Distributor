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

	@version		2.1.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_sermons-grid.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<ul id="preacher-sermons-sort-menu" class="uk-tab" data-uk-tab>
	<li data-uk-sort="sermon"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME_ASC'); ?></a></li>
	<li data-uk-sort="sermon:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME_DESC'); ?></a></li>
	<?php if ($this->params->get('preacher_sermons_preacher', 0)): ?>
		<li data-uk-sort="preacher"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_PREACHER_ASC'); ?></a></li>
		<li data-uk-sort="preacher:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_PREACHER_DESC'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->params->get('preacher_sermons_category', 0)): ?>
		<li data-uk-sort="category"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_CATEGORY_ASC'); ?></a></li>
		<li data-uk-sort="category:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_CATEGORY_DESC'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->params->get('preacher_sermons_series', 0)): ?>
		<li data-uk-sort="series"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERIES_ASC'); ?></a></li>
		<li data-uk-sort="series:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERIES_DESC'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->params->get('preacher_sermons_hits', 0)): ?>
		<li data-uk-sort="hits"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS_ASC'); ?></a></li>
		<li data-uk-sort="hits:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS_DESC'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->params->get('preacher_sermons_download_counter', 0)): ?>
		<li data-uk-sort="downloads"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOADS_ASC'); ?></a></li>
		<li data-uk-sort="downloads:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOADS_DESC'); ?></a></li>
	<?php endif; ?>
</ul>

<div data-uk-check-display class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4" data-uk-grid="{controls: '#preacher-sermons-sort-menu',gutter: 15}">
	<?php foreach ($this->items as $item): ?>
		<div data-sermon="<?php echo $this->escape($item->name); ?>" <?php if ($this->params->get('preacher_sermons_preacher')): ?>data-preacher="<?php echo $this->escape($item->preacher_name); ?>" <?php endif; ?><?php if ($this->params->get('preacher_sermons_category')): ?> data-category="<?php echo $this->escape($item->category); ?>" <?php endif; ?><?php if ($this->params->get('preacher_sermons_series')): ?> data-series="<?php echo $this->escape($item->series_name); ?>" <?php endif; ?>data-hits="<?php echo (int) $item->hits; ?>" data-downloads="<?php echo (int) $item->statisticTotal; ?>"><?php $item->params = $this->params; $item->desc = $this->escape($item->description, true, 65); echo JLayoutHelper::render('sermonspanel', $item); ?></div>
	<?php endforeach; ?>
</div>

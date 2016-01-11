<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		1.3.0
	@build			11th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_series-grid.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

?>
<ul id="series-sort-menu" class="uk-subnav">
	<li data-uk-sort="series"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME_ASC'); ?></a></li>
	<li data-uk-sort="series:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME_DESC'); ?></a></li>
	<?php if ($this->params->get('list_series_hits')): ?>
		<li data-uk-sort="hits"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS_ASC'); ?></a></li>
		<li data-uk-sort="hits:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS_DESC'); ?></a></li>
	<?php endif; ?>
	<?php if ($this->params->get('list_series_sermon_count')): ?>
		<li data-uk-sort="sermons"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMONS_COUNT_ASC'); ?></a></li>
		<li data-uk-sort="sermons:desc"><a href=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMONS_COUNT_DESC'); ?></a></li>
	<?php endif; ?>
</ul>
<div data-uk-check-display class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4" data-uk-grid="{controls: '#series-sort-menu',gutter: 15}">
	<?php foreach ($this->items as $item): ?>
		<div data-series="<?php echo $this->escape($item->name); ?>" data-hits="<?php echo (int) $item->hits; ?>" data-sermons="<?php echo count($item->idSeriesSermonB); ?>"><?php $item->params = $this->params; $item->desc = $this->escape($item->description, true, 65); echo JLayoutHelper::render('seriespanel', $item); ?></div>
	<?php endforeach; ?>
</div>

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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;

?>
<div id="j-main-container">
	<div class="form-horizontal">
	<?php echo Html::_('bootstrap.startTabSet', 'cpanel_tab', array('active' => 'cpanel')); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'cpanel', Text::_('cPanel', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<?php echo Html::_('bootstrap.startAccordion', 'dashboard_left', array('active' => 'main')); ?>
					<?php echo Html::_('bootstrap.addSlide', 'dashboard_left', 'Control Panel', 'main'); ?>
						<?php echo $this->loadTemplate('main');?>
					<?php echo Html::_('bootstrap.endSlide'); ?>
				<?php echo Html::_('bootstrap.endAccordion'); ?>
			</div>
			<div class="span3">
				<?php echo Html::_('bootstrap.startAccordion', 'dashboard_right', array('active' => 'vdm')); ?>
					<?php echo Html::_('bootstrap.addSlide', 'dashboard_right', 'Vast Development Method', 'vdm'); ?>
						<?php echo $this->loadTemplate('vdm');?>
					<?php echo Html::_('bootstrap.endSlide'); ?>
				<?php echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'open_issues', Text::_('Open Issues', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<?php  echo Html::_('bootstrap.startAccordion', 'open_issues_accordian', array('active' => 'open_issues_one')); ?>
					<?php  echo Html::_('bootstrap.addSlide', 'open_issues_accordian', 'The open issues on GitHub', 'open_issues_one'); ?>
						<?php echo $this->loadTemplate('open_issues_the_open_issues_on_github');?>
					<?php  echo Html::_('bootstrap.endSlide'); ?>
				<?php  echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'closed_issues', Text::_('Closed Issues', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<?php  echo Html::_('bootstrap.startAccordion', 'closed_issues_accordian', array('active' => 'closed_issues_one')); ?>
					<?php  echo Html::_('bootstrap.addSlide', 'closed_issues_accordian', 'The closed issues on GitHub', 'closed_issues_one'); ?>
						<?php echo $this->loadTemplate('closed_issues_the_closed_issues_on_github');?>
					<?php  echo Html::_('bootstrap.endSlide'); ?>
				<?php  echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'releases', Text::_('Releases', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<?php  echo Html::_('bootstrap.startAccordion', 'releases_accordian', array('active' => 'releases_one')); ?>
					<?php  echo Html::_('bootstrap.addSlide', 'releases_accordian', 'Information', 'releases_one'); ?>
						<?php echo $this->loadTemplate('releases_information');?>
					<?php  echo Html::_('bootstrap.endSlide'); ?>
				<?php  echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'vast_development_method', Text::_('Vast Development Method', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<?php  echo Html::_('bootstrap.startAccordion', 'vast_development_method_accordian', array('active' => 'vast_development_method_one')); ?>
					<?php  echo Html::_('bootstrap.addSlide', 'vast_development_method_accordian', 'Notice Board', 'vast_development_method_one'); ?>
						<?php echo $this->loadTemplate('vast_development_method_notice_board');?>
					<?php  echo Html::_('bootstrap.endSlide'); ?>
				<?php  echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

		<?php echo Html::_('bootstrap.addTab', 'cpanel_tab', 'readme', Text::_('Readme', true)); ?>
		<div class="row-fluid">
			<div class="span12">
				<?php  echo Html::_('bootstrap.startAccordion', 'readme_accordian', array('active' => 'readme_one')); ?>
					<?php  echo Html::_('bootstrap.addSlide', 'readme_accordian', 'Information', 'readme_one'); ?>
						<?php echo $this->loadTemplate('readme_information');?>
					<?php  echo Html::_('bootstrap.endSlide'); ?>
				<?php  echo Html::_('bootstrap.endAccordion'); ?>
			</div>
		</div>
		<?php echo Html::_('bootstrap.endTab'); ?>

	<?php echo Html::_('bootstrap.endTabSet'); ?>
	</div>
</div>
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

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		demolookfiledisplay.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/



use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;

// No direct access to this file
defined('JPATH_BASE') or die;


/***[JCBGUI.layout.php_view.133.$$$$]***/
// Initialize
$data   = $displayData['data'] ?? [];
$removeDelete = $displayData['remove_delete'] ?? false;

// Group images by base key (icon__, thumb__, main__)
$bucket = [];
$files =  [];
foreach ($data as $file)
{
	$key  = str_replace(['icon__', 'thumb__', 'main__'], '', $file->name);
	$type = str_starts_with($file->name, 'icon__')
		? 'icon'
		: (str_starts_with($file->name, 'thumb__')
			? 'thumb'
			: (str_starts_with($file->name, 'main__') ? 'main' : null));

	if ($type !== null) { $bucket[$key][$type] = $file; } else { $files[] = $file; }
}

// Extract grouped images for display
$images = !empty($bucket) ? array_values($bucket) : [];/***[/JCBGUI$$$$]***/


?>

<!--[JCBGUI.layout.layout.133.$$$$]-->
<?php if (!empty($data)) : ?>
<?php if (!empty($files)) : ?>
<ul class="uk-list uk-list-divider">
<?php foreach ($files as $file) : ?>
	<li>
	<?php if ($removeDelete) : ?>
	<a class="uk-button uk-button-default uk-width-1-1" href="<?php echo $file->link; ?>" download>
		(<?php echo $file->type_name; ?>)
		<?php echo $file->name; ?>
	</a>
	<?php else : ?>
	<div id="<?php echo $file->guid; ?>"
		 class="uk-button-group uk-width-1-1 uk-margin-small-bottom">
		<a class="uk-button uk-button-default uk-width-3-4"
		   href="<?php echo $file->link; ?>" download>
			(<?php echo $file->type_name; ?>)
			<?php echo $file->name; ?>
		</a>
		<button type="button"
				class="uk-button uk-button-danger uk-width-1-4"
				uk-icon="trash"
				onclick="VDMDeleteFile('file_vdm_uploader', '<?php echo $file->guid; ?>');">
		</button>
	</div>
	<?php endif; ?>
	</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
<?php if (!empty($images)) : ?>
<div class="uk-margin">
	<div class="uk-slider-container-offset" uk-slider>
		<div class="uk-position-relative uk-visible-toggle">
			<div class="uk-slider-items uk-child-width-1-3@s" uk-grid uk-lightbox="animation: slide">
			<?php foreach ($images as $fileSet) : ?>
			<?php
				$name       = $fileSet['main']->name ?? 'unknown';
				$mainGuid   = $fileSet['main']->guid ?? null;
				$thumbGuid  = $fileSet['thumb']->guid ?? null;
				$iconGuid   = $fileSet['icon']->guid ?? null;
				$mainLink   = $fileSet['main']->link ?? null;
				$thumbLink  = $fileSet['thumb']->link ?? null;
				$iconLink   = $fileSet['icon']->link ?? null;
				$mediaLink  = $mainLink ?? $thumbLink ?? $iconLink;
				$deleteList = array_filter([$mainGuid, $thumbGuid, $iconGuid]);
				$buttonSize = count($deleteList);
			?>
			<div id="<?php echo $mainGuid ?? uniqid('img_'); ?>">
				<div class="uk-card uk-card-body uk-box-shadow-small uk-box-shadow-hover-large">
					<div class="uk-card-media-top uk-flex uk-flex-center uk-margin-small-bottom">
						<a class="uk-inline"
						   href="<?php echo $mainLink ?? $mediaLink; ?>"
						   data-caption="<?php echo $name; ?>">
							<img src="<?php echo $mediaLink; ?>"
								 width="300"
								 height="300"
								 alt="<?php echo $name; ?>">
						</a>
					</div>

					<div class="uk-card-body uk-padding-remove">
						<div class="uk-margin">
							<div class="uk-button-group uk-width-1-1">
							<?php if ($mainLink) : ?>
								<a class="uk-button uk-button-primary uk-button-small uk-width-1-<?php echo $buttonSize; ?>"
								   href="<?php echo $mainLink; ?>" download>
									<?php echo Text::_('COM_SERMONDISTRIBUTOR_LARGE'); ?>
								</a>
							<?php endif; ?>

							<?php if ($thumbLink) : ?>
								<a class="uk-button uk-button-primary uk-button-small uk-width-1-<?php echo $buttonSize; ?>"
								   href="<?php echo $thumbLink; ?>" download>
									<?php echo Text::_('COM_SERMONDISTRIBUTOR_THUMB'); ?>
								</a>
							<?php endif; ?>

							<?php if ($iconLink) : ?>
								<a class="uk-button uk-button-primary uk-button-small uk-width-1-<?php echo $buttonSize; ?>"
								   href="<?php echo $iconLink; ?>" download>
									<?php echo Text::_('COM_SERMONDISTRIBUTOR_ICON'); ?>
								</a>
							<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if ($removeDelete === false) : ?>
						<div class="uk-card-footer">
							<button class="uk-button uk-button-danger uk-width-1-1"
								type="button" uk-icon="trash"
								onclick="VDMDeleteFiles('file_vdm_uploader', <?php echo $this->escape(json_encode($deleteList)); ?>);">
							</button>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
			</div>
		</div>
		<ul class="uk-slider-nav uk-dotnav uk-flex-center uk-margin"></ul>
	</div>
</div>
<?php endif; ?>
<?php endif; ?><!--[/JCBGUI$$$$]-->


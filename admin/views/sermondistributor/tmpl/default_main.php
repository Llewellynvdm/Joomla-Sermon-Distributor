<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_main.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<?php if(is_array($this->icons['main'])) :?>
	<?php foreach($this->icons['main'] as $icon): ?>
        <div class="dashboard-wraper">
           <div class="dashboard-content"> 
                <a class="icon hasTip" href="<?php echo $icon->url; ?>">
                    <img alt="<?php echo $icon->alt; ?>" src="components/com_sermondistributor/assets/images/icons/<?php  echo $icon->image; ?>">
                    <span class="dashboard-title"><?php echo JText::_($icon->name); ?></span>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<div class="clearfix"></div>
<?php endif; ?>
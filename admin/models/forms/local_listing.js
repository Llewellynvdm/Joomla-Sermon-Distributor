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

	@version		2.0.x
	@build			3rd March, 2018
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		local_listing.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/



jQuery(document).ready(function()
{
	var sharedurls = jQuery('#jform_sharedurl').val();
	if (sharedurls) {
		getBuildTable(sharedurls,'jform_sharedurl');
	}
	var folders = jQuery('#jform_folder').val();
	if (folders) {
		getBuildTable(folders,'jform_folder');
	}
	jQuery('.save-modal-data').text('Done');
});
function getBuildTable_server(string,idName){
	var getUrl = "index.php?option=com_sermondistributor&task=ajax.getBuildTable&format=json";
	if(token.length > 0 && string.length > 0 && idName.length > 0){
		var request = 'token='+token+'&idName='+idName+'&oject='+string;
	}
	return jQuery.ajax({
		type: 'GET',
		url: getUrl,
		dataType: 'jsonp',
		data: request,
		jsonp: 'callback'
	});
}
function getBuildTable(string,idName){
	getBuildTable_server(string,idName).done(function(result) {
		if(result){
			buildTable(result,idName);
		} else {
			jQuery('#table_'+idName).remove();			
		}
	})
}
function buildTable(result,idName){
	jQuery('#table_'+idName).remove();
	jQuery('#'+idName).closest('.control-group').append(result);
} 

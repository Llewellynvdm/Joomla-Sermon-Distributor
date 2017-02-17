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

	@version		@update number 10 of this MVC
	@build			27th November, 2016
	@created		20th November, 2016
	@package		Sermon Distributor
	@subpackage		local_listing.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Initial Script
jQuery(document).ready(function()
{
	var build_vvvvvwx = jQuery("#jform_build").val();
	vvvvvwx(build_vvvvvwx);

	var build_vvvvvwy = jQuery("#jform_build").val();
	vvvvvwy(build_vvvvvwy);

	var build_vvvvvwz = jQuery("#jform_build").val();
	vvvvvwz(build_vvvvvwz);
});

// the vvvvvwx function
function vvvvvwx(build_vvvvvwx)
{
	if (isSet(build_vvvvvwx) && build_vvvvvwx.constructor !== Array)
	{
		var temp_vvvvvwx = build_vvvvvwx;
		var build_vvvvvwx = [];
		build_vvvvvwx.push(temp_vvvvvwx);
	}
	else if (!isSet(build_vvvvvwx))
	{
		var build_vvvvvwx = [];
	}
	var build = build_vvvvvwx.some(build_vvvvvwx_SomeFunc);


	// set this function logic
	if (build)
	{
	}
	else
	{
	}
}

// the vvvvvwx Some function
function build_vvvvvwx_SomeFunc(build_vvvvvwx)
{
	// set the function logic
	if (build_vvvvvwx == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwy function
function vvvvvwy(build_vvvvvwy)
{
	if (isSet(build_vvvvvwy) && build_vvvvvwy.constructor !== Array)
	{
		var temp_vvvvvwy = build_vvvvvwy;
		var build_vvvvvwy = [];
		build_vvvvvwy.push(temp_vvvvvwy);
	}
	else if (!isSet(build_vvvvvwy))
	{
		var build_vvvvvwy = [];
	}
	var build = build_vvvvvwy.some(build_vvvvvwy_SomeFunc);


	// set this function logic
	if (build)
	{
	}
	else
	{
	}
}

// the vvvvvwy Some function
function build_vvvvvwy_SomeFunc(build_vvvvvwy)
{
	// set the function logic
	if (build_vvvvvwy == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwz function
function vvvvvwz(build_vvvvvwz)
{
	if (isSet(build_vvvvvwz) && build_vvvvvwz.constructor !== Array)
	{
		var temp_vvvvvwz = build_vvvvvwz;
		var build_vvvvvwz = [];
		build_vvvvvwz.push(temp_vvvvvwz);
	}
	else if (!isSet(build_vvvvvwz))
	{
		var build_vvvvvwz = [];
	}
	var build = build_vvvvvwz.some(build_vvvvvwz_SomeFunc);


	// set this function logic
	if (build)
	{
	}
	else
	{
	}
}

// the vvvvvwz Some function
function build_vvvvvwz_SomeFunc(build_vvvvvwz)
{
	// set the function logic
	if (build_vvvvvwz != '')
	{
		return true;
	}
	return false;
}

// the isSet function
function isSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
}

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

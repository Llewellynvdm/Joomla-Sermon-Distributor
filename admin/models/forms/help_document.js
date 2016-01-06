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
	@build			6th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_AfASZHxduZ_required = false;
jform_MNpsVGIzeT_required = false;
jform_tmgJZVUxWG_required = false;
jform_sDQSpOzUUb_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_LsqSkrj = jQuery("#jform_location input[type='radio']:checked").val();
	LsqSkrj(location_LsqSkrj);

	var location_IKmEbKP = jQuery("#jform_location input[type='radio']:checked").val();
	IKmEbKP(location_IKmEbKP);

	var type_AfASZHx = jQuery("#jform_type").val();
	AfASZHx(type_AfASZHx);

	var type_MNpsVGI = jQuery("#jform_type").val();
	MNpsVGI(type_MNpsVGI);

	var type_tmgJZVU = jQuery("#jform_type").val();
	tmgJZVU(type_tmgJZVU);

	var target_sDQSpOz = jQuery("#jform_target input[type='radio']:checked").val();
	sDQSpOz(target_sDQSpOz);
});

// the LsqSkrj function
function LsqSkrj(location_LsqSkrj)
{
	// [8269] set the function logic
	if (location_LsqSkrj == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the IKmEbKP function
function IKmEbKP(location_IKmEbKP)
{
	// [8269] set the function logic
	if (location_IKmEbKP == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the AfASZHx function
function AfASZHx(type_AfASZHx)
{
	if (isSet(type_AfASZHx) && type_AfASZHx.constructor !== Array)
	{
		var temp_AfASZHx = type_AfASZHx;
		var type_AfASZHx = [];
		type_AfASZHx.push(temp_AfASZHx);
	}
	else if (!isSet(type_AfASZHx))
	{
		var type_AfASZHx = [];
	}
	var type = type_AfASZHx.some(type_AfASZHx_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_AfASZHxduZ_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_AfASZHxduZ_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_AfASZHxduZ_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_AfASZHxduZ_required = true;
		}
	}
}

// the AfASZHx Some function
function type_AfASZHx_SomeFunc(type_AfASZHx)
{
	// [8234] set the function logic
	if (type_AfASZHx == 3)
	{
		return true;
	}
	return false;
}

// the MNpsVGI function
function MNpsVGI(type_MNpsVGI)
{
	if (isSet(type_MNpsVGI) && type_MNpsVGI.constructor !== Array)
	{
		var temp_MNpsVGI = type_MNpsVGI;
		var type_MNpsVGI = [];
		type_MNpsVGI.push(temp_MNpsVGI);
	}
	else if (!isSet(type_MNpsVGI))
	{
		var type_MNpsVGI = [];
	}
	var type = type_MNpsVGI.some(type_MNpsVGI_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_MNpsVGIzeT_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_MNpsVGIzeT_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_MNpsVGIzeT_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_MNpsVGIzeT_required = true;
		}
	}
}

// the MNpsVGI Some function
function type_MNpsVGI_SomeFunc(type_MNpsVGI)
{
	// [8234] set the function logic
	if (type_MNpsVGI == 1)
	{
		return true;
	}
	return false;
}

// the tmgJZVU function
function tmgJZVU(type_tmgJZVU)
{
	if (isSet(type_tmgJZVU) && type_tmgJZVU.constructor !== Array)
	{
		var temp_tmgJZVU = type_tmgJZVU;
		var type_tmgJZVU = [];
		type_tmgJZVU.push(temp_tmgJZVU);
	}
	else if (!isSet(type_tmgJZVU))
	{
		var type_tmgJZVU = [];
	}
	var type = type_tmgJZVU.some(type_tmgJZVU_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_tmgJZVUxWG_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_tmgJZVUxWG_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_tmgJZVUxWG_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_tmgJZVUxWG_required = true;
		}
	}
}

// the tmgJZVU Some function
function type_tmgJZVU_SomeFunc(type_tmgJZVU)
{
	// [8234] set the function logic
	if (type_tmgJZVU == 2)
	{
		return true;
	}
	return false;
}

// the sDQSpOz function
function sDQSpOz(target_sDQSpOz)
{
	// [8269] set the function logic
	if (target_sDQSpOz == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_sDQSpOzUUb_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_sDQSpOzUUb_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_sDQSpOzUUb_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_sDQSpOzUUb_required = true;
		}
	}
}

// update required fields
function updateFieldRequired(name,status)
{
	var not_required = jQuery('#jform_not_required').val();

	if(status == 1)
	{
		if (isSet(not_required) && not_required != 0)
		{
			not_required = not_required+','+name;
		}
		else
		{
			not_required = ','+name;
		}
	}
	else
	{
		if (isSet(not_required) && not_required != 0)
		{
			not_required = not_required.replace(','+name,'');
		}
	}

	jQuery('#jform_not_required').val(not_required);
}

// the isSet function
function isSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
} 

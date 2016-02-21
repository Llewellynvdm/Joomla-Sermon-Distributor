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
	@build			20th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_LRbFdTDVsa_required = false;
jform_SVZGybebRw_required = false;
jform_ULuYNQgQpE_required = false;
jform_HWyJIuCeuW_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_KPuQSWe = jQuery("#jform_location input[type='radio']:checked").val();
	KPuQSWe(location_KPuQSWe);

	var location_exhczAS = jQuery("#jform_location input[type='radio']:checked").val();
	exhczAS(location_exhczAS);

	var type_LRbFdTD = jQuery("#jform_type").val();
	LRbFdTD(type_LRbFdTD);

	var type_SVZGybe = jQuery("#jform_type").val();
	SVZGybe(type_SVZGybe);

	var type_ULuYNQg = jQuery("#jform_type").val();
	ULuYNQg(type_ULuYNQg);

	var target_HWyJIuC = jQuery("#jform_target input[type='radio']:checked").val();
	HWyJIuC(target_HWyJIuC);
});

// the KPuQSWe function
function KPuQSWe(location_KPuQSWe)
{
	// [8696] set the function logic
	if (location_KPuQSWe == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the exhczAS function
function exhczAS(location_exhczAS)
{
	// [8696] set the function logic
	if (location_exhczAS == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the LRbFdTD function
function LRbFdTD(type_LRbFdTD)
{
	if (isSet(type_LRbFdTD) && type_LRbFdTD.constructor !== Array)
	{
		var temp_LRbFdTD = type_LRbFdTD;
		var type_LRbFdTD = [];
		type_LRbFdTD.push(temp_LRbFdTD);
	}
	else if (!isSet(type_LRbFdTD))
	{
		var type_LRbFdTD = [];
	}
	var type = type_LRbFdTD.some(type_LRbFdTD_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_LRbFdTDVsa_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_LRbFdTDVsa_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_LRbFdTDVsa_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_LRbFdTDVsa_required = true;
		}
	}
}

// the LRbFdTD Some function
function type_LRbFdTD_SomeFunc(type_LRbFdTD)
{
	// [8661] set the function logic
	if (type_LRbFdTD == 3)
	{
		return true;
	}
	return false;
}

// the SVZGybe function
function SVZGybe(type_SVZGybe)
{
	if (isSet(type_SVZGybe) && type_SVZGybe.constructor !== Array)
	{
		var temp_SVZGybe = type_SVZGybe;
		var type_SVZGybe = [];
		type_SVZGybe.push(temp_SVZGybe);
	}
	else if (!isSet(type_SVZGybe))
	{
		var type_SVZGybe = [];
	}
	var type = type_SVZGybe.some(type_SVZGybe_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_SVZGybebRw_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_SVZGybebRw_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_SVZGybebRw_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_SVZGybebRw_required = true;
		}
	}
}

// the SVZGybe Some function
function type_SVZGybe_SomeFunc(type_SVZGybe)
{
	// [8661] set the function logic
	if (type_SVZGybe == 1)
	{
		return true;
	}
	return false;
}

// the ULuYNQg function
function ULuYNQg(type_ULuYNQg)
{
	if (isSet(type_ULuYNQg) && type_ULuYNQg.constructor !== Array)
	{
		var temp_ULuYNQg = type_ULuYNQg;
		var type_ULuYNQg = [];
		type_ULuYNQg.push(temp_ULuYNQg);
	}
	else if (!isSet(type_ULuYNQg))
	{
		var type_ULuYNQg = [];
	}
	var type = type_ULuYNQg.some(type_ULuYNQg_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_ULuYNQgQpE_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_ULuYNQgQpE_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_ULuYNQgQpE_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_ULuYNQgQpE_required = true;
		}
	}
}

// the ULuYNQg Some function
function type_ULuYNQg_SomeFunc(type_ULuYNQg)
{
	// [8661] set the function logic
	if (type_ULuYNQg == 2)
	{
		return true;
	}
	return false;
}

// the HWyJIuC function
function HWyJIuC(target_HWyJIuC)
{
	// [8696] set the function logic
	if (target_HWyJIuC == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_HWyJIuCeuW_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_HWyJIuCeuW_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_HWyJIuCeuW_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_HWyJIuCeuW_required = true;
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

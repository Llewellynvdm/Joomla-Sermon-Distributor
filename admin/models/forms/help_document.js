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

	@version		1.3.2
	@build			11th April, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvwgvwd_required = false;
jform_vvvvvwhvwe_required = false;
jform_vvvvvwivwf_required = false;
jform_vvvvvwjvwg_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_vvvvvwe = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwe(location_vvvvvwe);

	var location_vvvvvwf = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwf(location_vvvvvwf);

	var type_vvvvvwg = jQuery("#jform_type").val();
	vvvvvwg(type_vvvvvwg);

	var type_vvvvvwh = jQuery("#jform_type").val();
	vvvvvwh(type_vvvvvwh);

	var type_vvvvvwi = jQuery("#jform_type").val();
	vvvvvwi(type_vvvvvwi);

	var target_vvvvvwj = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvwj(target_vvvvvwj);
});

// the vvvvvwe function
function vvvvvwe(location_vvvvvwe)
{
	// [Interpretation 7326] set the function logic
	if (location_vvvvvwe == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the vvvvvwf function
function vvvvvwf(location_vvvvvwf)
{
	// [Interpretation 7326] set the function logic
	if (location_vvvvvwf == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the vvvvvwg function
function vvvvvwg(type_vvvvvwg)
{
	if (isSet(type_vvvvvwg) && type_vvvvvwg.constructor !== Array)
	{
		var temp_vvvvvwg = type_vvvvvwg;
		var type_vvvvvwg = [];
		type_vvvvvwg.push(temp_vvvvvwg);
	}
	else if (!isSet(type_vvvvvwg))
	{
		var type_vvvvvwg = [];
	}
	var type = type_vvvvvwg.some(type_vvvvvwg_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_vvvvvwgvwd_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwgvwd_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_vvvvvwgvwd_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwgvwd_required = true;
		}
	}
}

// the vvvvvwg Some function
function type_vvvvvwg_SomeFunc(type_vvvvvwg)
{
	// [Interpretation 7291] set the function logic
	if (type_vvvvvwg == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvwh function
function vvvvvwh(type_vvvvvwh)
{
	if (isSet(type_vvvvvwh) && type_vvvvvwh.constructor !== Array)
	{
		var temp_vvvvvwh = type_vvvvvwh;
		var type_vvvvvwh = [];
		type_vvvvvwh.push(temp_vvvvvwh);
	}
	else if (!isSet(type_vvvvvwh))
	{
		var type_vvvvvwh = [];
	}
	var type = type_vvvvvwh.some(type_vvvvvwh_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_vvvvvwhvwe_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvwhvwe_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_vvvvvwhvwe_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvwhvwe_required = true;
		}
	}
}

// the vvvvvwh Some function
function type_vvvvvwh_SomeFunc(type_vvvvvwh)
{
	// [Interpretation 7291] set the function logic
	if (type_vvvvvwh == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwi function
function vvvvvwi(type_vvvvvwi)
{
	if (isSet(type_vvvvvwi) && type_vvvvvwi.constructor !== Array)
	{
		var temp_vvvvvwi = type_vvvvvwi;
		var type_vvvvvwi = [];
		type_vvvvvwi.push(temp_vvvvvwi);
	}
	else if (!isSet(type_vvvvvwi))
	{
		var type_vvvvvwi = [];
	}
	var type = type_vvvvvwi.some(type_vvvvvwi_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_vvvvvwivwf_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_vvvvvwivwf_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_vvvvvwivwf_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_vvvvvwivwf_required = true;
		}
	}
}

// the vvvvvwi Some function
function type_vvvvvwi_SomeFunc(type_vvvvvwi)
{
	// [Interpretation 7291] set the function logic
	if (type_vvvvvwi == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwj function
function vvvvvwj(target_vvvvvwj)
{
	// [Interpretation 7326] set the function logic
	if (target_vvvvvwj == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_vvvvvwjvwg_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvwjvwg_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vvvvvwjvwg_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvwjvwg_required = true;
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

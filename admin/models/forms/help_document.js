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
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvwxvwf_required = false;
jform_vvvvvwyvwg_required = false;
jform_vvvvvwzvwh_required = false;
jform_vvvvvxavwi_required = false;
jform_vvvvvxbvwj_required = false;
jform_vvvvvxcvwk_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_vvvvvwx = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwx(location_vvvvvwx);

	var location_vvvvvwy = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwy(location_vvvvvwy);

	var type_vvvvvwz = jQuery("#jform_type").val();
	vvvvvwz(type_vvvvvwz);

	var type_vvvvvxa = jQuery("#jform_type").val();
	vvvvvxa(type_vvvvvxa);

	var type_vvvvvxb = jQuery("#jform_type").val();
	vvvvvxb(type_vvvvvxb);

	var target_vvvvvxc = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvxc(target_vvvvvxc);
});

// the vvvvvwx function
function vvvvvwx(location_vvvvvwx)
{
	// set the function logic
	if (location_vvvvvwx == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
		if (jform_vvvvvwxvwf_required)
		{
			updateFieldRequired('admin_view',0);
			jQuery('#jform_admin_view').prop('required','required');
			jQuery('#jform_admin_view').attr('aria-required',true);
			jQuery('#jform_admin_view').addClass('required');
			jform_vvvvvwxvwf_required = false;
		}

	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
		if (!jform_vvvvvwxvwf_required)
		{
			updateFieldRequired('admin_view',1);
			jQuery('#jform_admin_view').removeAttr('required');
			jQuery('#jform_admin_view').removeAttr('aria-required');
			jQuery('#jform_admin_view').removeClass('required');
			jform_vvvvvwxvwf_required = true;
		}
	}
}

// the vvvvvwy function
function vvvvvwy(location_vvvvvwy)
{
	// set the function logic
	if (location_vvvvvwy == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
		if (jform_vvvvvwyvwg_required)
		{
			updateFieldRequired('site_view',0);
			jQuery('#jform_site_view').prop('required','required');
			jQuery('#jform_site_view').attr('aria-required',true);
			jQuery('#jform_site_view').addClass('required');
			jform_vvvvvwyvwg_required = false;
		}

	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
		if (!jform_vvvvvwyvwg_required)
		{
			updateFieldRequired('site_view',1);
			jQuery('#jform_site_view').removeAttr('required');
			jQuery('#jform_site_view').removeAttr('aria-required');
			jQuery('#jform_site_view').removeClass('required');
			jform_vvvvvwyvwg_required = true;
		}
	}
}

// the vvvvvwz function
function vvvvvwz(type_vvvvvwz)
{
	if (isSet(type_vvvvvwz) && type_vvvvvwz.constructor !== Array)
	{
		var temp_vvvvvwz = type_vvvvvwz;
		var type_vvvvvwz = [];
		type_vvvvvwz.push(temp_vvvvvwz);
	}
	else if (!isSet(type_vvvvvwz))
	{
		var type_vvvvvwz = [];
	}
	var type = type_vvvvvwz.some(type_vvvvvwz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_vvvvvwzvwh_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwzvwh_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_vvvvvwzvwh_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwzvwh_required = true;
		}
	}
}

// the vvvvvwz Some function
function type_vvvvvwz_SomeFunc(type_vvvvvwz)
{
	// set the function logic
	if (type_vvvvvwz == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvxa function
function vvvvvxa(type_vvvvvxa)
{
	if (isSet(type_vvvvvxa) && type_vvvvvxa.constructor !== Array)
	{
		var temp_vvvvvxa = type_vvvvvxa;
		var type_vvvvvxa = [];
		type_vvvvvxa.push(temp_vvvvvxa);
	}
	else if (!isSet(type_vvvvvxa))
	{
		var type_vvvvvxa = [];
	}
	var type = type_vvvvvxa.some(type_vvvvvxa_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_vvvvvxavwi_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvxavwi_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_vvvvvxavwi_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvxavwi_required = true;
		}
	}
}

// the vvvvvxa Some function
function type_vvvvvxa_SomeFunc(type_vvvvvxa)
{
	// set the function logic
	if (type_vvvvvxa == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvxb function
function vvvvvxb(type_vvvvvxb)
{
	if (isSet(type_vvvvvxb) && type_vvvvvxb.constructor !== Array)
	{
		var temp_vvvvvxb = type_vvvvvxb;
		var type_vvvvvxb = [];
		type_vvvvvxb.push(temp_vvvvvxb);
	}
	else if (!isSet(type_vvvvvxb))
	{
		var type_vvvvvxb = [];
	}
	var type = type_vvvvvxb.some(type_vvvvvxb_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_vvvvvxbvwj_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_vvvvvxbvwj_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_vvvvvxbvwj_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_vvvvvxbvwj_required = true;
		}
	}
}

// the vvvvvxb Some function
function type_vvvvvxb_SomeFunc(type_vvvvvxb)
{
	// set the function logic
	if (type_vvvvvxb == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvxc function
function vvvvvxc(target_vvvvvxc)
{
	// set the function logic
	if (target_vvvvvxc == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_vvvvvxcvwk_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvxcvwk_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vvvvvxcvwk_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvxcvwk_required = true;
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

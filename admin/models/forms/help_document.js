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

	@version		1.4.0
	@build			27th November, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvxavwe_required = false;
jform_vvvvvxbvwf_required = false;
jform_vvvvvxcvwg_required = false;
jform_vvvvvxdvwh_required = false;
jform_vvvvvxevwi_required = false;
jform_vvvvvxfvwj_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_vvvvvxa = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvxa(location_vvvvvxa);

	var location_vvvvvxb = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvxb(location_vvvvvxb);

	var type_vvvvvxc = jQuery("#jform_type").val();
	vvvvvxc(type_vvvvvxc);

	var type_vvvvvxd = jQuery("#jform_type").val();
	vvvvvxd(type_vvvvvxd);

	var type_vvvvvxe = jQuery("#jform_type").val();
	vvvvvxe(type_vvvvvxe);

	var target_vvvvvxf = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvxf(target_vvvvvxf);
});

// the vvvvvxa function
function vvvvvxa(location_vvvvvxa)
{
	// set the function logic
	if (location_vvvvvxa == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
		if (jform_vvvvvxavwe_required)
		{
			updateFieldRequired('admin_view',0);
			jQuery('#jform_admin_view').prop('required','required');
			jQuery('#jform_admin_view').attr('aria-required',true);
			jQuery('#jform_admin_view').addClass('required');
			jform_vvvvvxavwe_required = false;
		}

	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
		if (!jform_vvvvvxavwe_required)
		{
			updateFieldRequired('admin_view',1);
			jQuery('#jform_admin_view').removeAttr('required');
			jQuery('#jform_admin_view').removeAttr('aria-required');
			jQuery('#jform_admin_view').removeClass('required');
			jform_vvvvvxavwe_required = true;
		}
	}
}

// the vvvvvxb function
function vvvvvxb(location_vvvvvxb)
{
	// set the function logic
	if (location_vvvvvxb == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
		if (jform_vvvvvxbvwf_required)
		{
			updateFieldRequired('site_view',0);
			jQuery('#jform_site_view').prop('required','required');
			jQuery('#jform_site_view').attr('aria-required',true);
			jQuery('#jform_site_view').addClass('required');
			jform_vvvvvxbvwf_required = false;
		}

	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
		if (!jform_vvvvvxbvwf_required)
		{
			updateFieldRequired('site_view',1);
			jQuery('#jform_site_view').removeAttr('required');
			jQuery('#jform_site_view').removeAttr('aria-required');
			jQuery('#jform_site_view').removeClass('required');
			jform_vvvvvxbvwf_required = true;
		}
	}
}

// the vvvvvxc function
function vvvvvxc(type_vvvvvxc)
{
	if (isSet(type_vvvvvxc) && type_vvvvvxc.constructor !== Array)
	{
		var temp_vvvvvxc = type_vvvvvxc;
		var type_vvvvvxc = [];
		type_vvvvvxc.push(temp_vvvvvxc);
	}
	else if (!isSet(type_vvvvvxc))
	{
		var type_vvvvvxc = [];
	}
	var type = type_vvvvvxc.some(type_vvvvvxc_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_vvvvvxcvwg_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvxcvwg_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_vvvvvxcvwg_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvxcvwg_required = true;
		}
	}
}

// the vvvvvxc Some function
function type_vvvvvxc_SomeFunc(type_vvvvvxc)
{
	// set the function logic
	if (type_vvvvvxc == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvxd function
function vvvvvxd(type_vvvvvxd)
{
	if (isSet(type_vvvvvxd) && type_vvvvvxd.constructor !== Array)
	{
		var temp_vvvvvxd = type_vvvvvxd;
		var type_vvvvvxd = [];
		type_vvvvvxd.push(temp_vvvvvxd);
	}
	else if (!isSet(type_vvvvvxd))
	{
		var type_vvvvvxd = [];
	}
	var type = type_vvvvvxd.some(type_vvvvvxd_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_vvvvvxdvwh_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvxdvwh_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_vvvvvxdvwh_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvxdvwh_required = true;
		}
	}
}

// the vvvvvxd Some function
function type_vvvvvxd_SomeFunc(type_vvvvvxd)
{
	// set the function logic
	if (type_vvvvvxd == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvxe function
function vvvvvxe(type_vvvvvxe)
{
	if (isSet(type_vvvvvxe) && type_vvvvvxe.constructor !== Array)
	{
		var temp_vvvvvxe = type_vvvvvxe;
		var type_vvvvvxe = [];
		type_vvvvvxe.push(temp_vvvvvxe);
	}
	else if (!isSet(type_vvvvvxe))
	{
		var type_vvvvvxe = [];
	}
	var type = type_vvvvvxe.some(type_vvvvvxe_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_vvvvvxevwi_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_vvvvvxevwi_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_vvvvvxevwi_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_vvvvvxevwi_required = true;
		}
	}
}

// the vvvvvxe Some function
function type_vvvvvxe_SomeFunc(type_vvvvvxe)
{
	// set the function logic
	if (type_vvvvvxe == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvxf function
function vvvvvxf(target_vvvvvxf)
{
	// set the function logic
	if (target_vvvvvxf == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_vvvvvxfvwj_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvxfvwj_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_vvvvvxfvwj_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvxfvwj_required = true;
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

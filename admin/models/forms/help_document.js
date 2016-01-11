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
	@build			11th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_tEOArjdilp_required = false;
jform_risVnXlByL_required = false;
jform_FQgiSipwQJ_required = false;
jform_OlgATsKWIb_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_qusgtuA = jQuery("#jform_location input[type='radio']:checked").val();
	qusgtuA(location_qusgtuA);

	var location_acgwGxu = jQuery("#jform_location input[type='radio']:checked").val();
	acgwGxu(location_acgwGxu);

	var type_tEOArjd = jQuery("#jform_type").val();
	tEOArjd(type_tEOArjd);

	var type_risVnXl = jQuery("#jform_type").val();
	risVnXl(type_risVnXl);

	var type_FQgiSip = jQuery("#jform_type").val();
	FQgiSip(type_FQgiSip);

	var target_OlgATsK = jQuery("#jform_target input[type='radio']:checked").val();
	OlgATsK(target_OlgATsK);
});

// the qusgtuA function
function qusgtuA(location_qusgtuA)
{
	// [8307] set the function logic
	if (location_qusgtuA == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the acgwGxu function
function acgwGxu(location_acgwGxu)
{
	// [8307] set the function logic
	if (location_acgwGxu == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the tEOArjd function
function tEOArjd(type_tEOArjd)
{
	if (isSet(type_tEOArjd) && type_tEOArjd.constructor !== Array)
	{
		var temp_tEOArjd = type_tEOArjd;
		var type_tEOArjd = [];
		type_tEOArjd.push(temp_tEOArjd);
	}
	else if (!isSet(type_tEOArjd))
	{
		var type_tEOArjd = [];
	}
	var type = type_tEOArjd.some(type_tEOArjd_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_tEOArjdilp_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_tEOArjdilp_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_tEOArjdilp_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_tEOArjdilp_required = true;
		}
	}
}

// the tEOArjd Some function
function type_tEOArjd_SomeFunc(type_tEOArjd)
{
	// [8272] set the function logic
	if (type_tEOArjd == 3)
	{
		return true;
	}
	return false;
}

// the risVnXl function
function risVnXl(type_risVnXl)
{
	if (isSet(type_risVnXl) && type_risVnXl.constructor !== Array)
	{
		var temp_risVnXl = type_risVnXl;
		var type_risVnXl = [];
		type_risVnXl.push(temp_risVnXl);
	}
	else if (!isSet(type_risVnXl))
	{
		var type_risVnXl = [];
	}
	var type = type_risVnXl.some(type_risVnXl_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_risVnXlByL_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_risVnXlByL_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_risVnXlByL_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_risVnXlByL_required = true;
		}
	}
}

// the risVnXl Some function
function type_risVnXl_SomeFunc(type_risVnXl)
{
	// [8272] set the function logic
	if (type_risVnXl == 1)
	{
		return true;
	}
	return false;
}

// the FQgiSip function
function FQgiSip(type_FQgiSip)
{
	if (isSet(type_FQgiSip) && type_FQgiSip.constructor !== Array)
	{
		var temp_FQgiSip = type_FQgiSip;
		var type_FQgiSip = [];
		type_FQgiSip.push(temp_FQgiSip);
	}
	else if (!isSet(type_FQgiSip))
	{
		var type_FQgiSip = [];
	}
	var type = type_FQgiSip.some(type_FQgiSip_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_FQgiSipwQJ_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_FQgiSipwQJ_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_FQgiSipwQJ_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_FQgiSipwQJ_required = true;
		}
	}
}

// the FQgiSip Some function
function type_FQgiSip_SomeFunc(type_FQgiSip)
{
	// [8272] set the function logic
	if (type_FQgiSip == 2)
	{
		return true;
	}
	return false;
}

// the OlgATsK function
function OlgATsK(target_OlgATsK)
{
	// [8307] set the function logic
	if (target_OlgATsK == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_OlgATsKWIb_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_OlgATsKWIb_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_OlgATsKWIb_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_OlgATsKWIb_required = true;
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

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
jform_BfkHtbrzNw_required = false;
jform_jQYQkGYHPT_required = false;
jform_mCjbCJGjdd_required = false;
jform_nyhEiHGyjm_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_plVMLqN = jQuery("#jform_location input[type='radio']:checked").val();
	plVMLqN(location_plVMLqN);

	var location_gCEFCDg = jQuery("#jform_location input[type='radio']:checked").val();
	gCEFCDg(location_gCEFCDg);

	var type_BfkHtbr = jQuery("#jform_type").val();
	BfkHtbr(type_BfkHtbr);

	var type_jQYQkGY = jQuery("#jform_type").val();
	jQYQkGY(type_jQYQkGY);

	var type_mCjbCJG = jQuery("#jform_type").val();
	mCjbCJG(type_mCjbCJG);

	var target_nyhEiHG = jQuery("#jform_target input[type='radio']:checked").val();
	nyhEiHG(target_nyhEiHG);
});

// the plVMLqN function
function plVMLqN(location_plVMLqN)
{
	// [8307] set the function logic
	if (location_plVMLqN == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the gCEFCDg function
function gCEFCDg(location_gCEFCDg)
{
	// [8307] set the function logic
	if (location_gCEFCDg == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the BfkHtbr function
function BfkHtbr(type_BfkHtbr)
{
	if (isSet(type_BfkHtbr) && type_BfkHtbr.constructor !== Array)
	{
		var temp_BfkHtbr = type_BfkHtbr;
		var type_BfkHtbr = [];
		type_BfkHtbr.push(temp_BfkHtbr);
	}
	else if (!isSet(type_BfkHtbr))
	{
		var type_BfkHtbr = [];
	}
	var type = type_BfkHtbr.some(type_BfkHtbr_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_BfkHtbrzNw_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_BfkHtbrzNw_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_BfkHtbrzNw_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_BfkHtbrzNw_required = true;
		}
	}
}

// the BfkHtbr Some function
function type_BfkHtbr_SomeFunc(type_BfkHtbr)
{
	// [8272] set the function logic
	if (type_BfkHtbr == 3)
	{
		return true;
	}
	return false;
}

// the jQYQkGY function
function jQYQkGY(type_jQYQkGY)
{
	if (isSet(type_jQYQkGY) && type_jQYQkGY.constructor !== Array)
	{
		var temp_jQYQkGY = type_jQYQkGY;
		var type_jQYQkGY = [];
		type_jQYQkGY.push(temp_jQYQkGY);
	}
	else if (!isSet(type_jQYQkGY))
	{
		var type_jQYQkGY = [];
	}
	var type = type_jQYQkGY.some(type_jQYQkGY_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_jQYQkGYHPT_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_jQYQkGYHPT_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_jQYQkGYHPT_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_jQYQkGYHPT_required = true;
		}
	}
}

// the jQYQkGY Some function
function type_jQYQkGY_SomeFunc(type_jQYQkGY)
{
	// [8272] set the function logic
	if (type_jQYQkGY == 1)
	{
		return true;
	}
	return false;
}

// the mCjbCJG function
function mCjbCJG(type_mCjbCJG)
{
	if (isSet(type_mCjbCJG) && type_mCjbCJG.constructor !== Array)
	{
		var temp_mCjbCJG = type_mCjbCJG;
		var type_mCjbCJG = [];
		type_mCjbCJG.push(temp_mCjbCJG);
	}
	else if (!isSet(type_mCjbCJG))
	{
		var type_mCjbCJG = [];
	}
	var type = type_mCjbCJG.some(type_mCjbCJG_SomeFunc);


	// [8285] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_mCjbCJGjdd_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_mCjbCJGjdd_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_mCjbCJGjdd_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_mCjbCJGjdd_required = true;
		}
	}
}

// the mCjbCJG Some function
function type_mCjbCJG_SomeFunc(type_mCjbCJG)
{
	// [8272] set the function logic
	if (type_mCjbCJG == 2)
	{
		return true;
	}
	return false;
}

// the nyhEiHG function
function nyhEiHG(target_nyhEiHG)
{
	// [8307] set the function logic
	if (target_nyhEiHG == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_nyhEiHGyjm_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_nyhEiHGyjm_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_nyhEiHGyjm_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_nyhEiHGyjm_required = true;
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

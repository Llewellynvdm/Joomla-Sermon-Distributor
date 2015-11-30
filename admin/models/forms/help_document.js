/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_dXNGPABnBk_required = false;
jform_lUjJAvVFIk_required = false;
jform_ILzlnJWAHY_required = false;
jform_wQUmqIUYUo_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_fpmeWpw = jQuery("#jform_location input[type='radio']:checked").val();
	fpmeWpw(location_fpmeWpw);

	var location_ynMoYbd = jQuery("#jform_location input[type='radio']:checked").val();
	ynMoYbd(location_ynMoYbd);

	var type_dXNGPAB = jQuery("#jform_type").val();
	dXNGPAB(type_dXNGPAB);

	var type_lUjJAvV = jQuery("#jform_type").val();
	lUjJAvV(type_lUjJAvV);

	var type_ILzlnJW = jQuery("#jform_type").val();
	ILzlnJW(type_ILzlnJW);

	var target_wQUmqIU = jQuery("#jform_target input[type='radio']:checked").val();
	wQUmqIU(target_wQUmqIU);
});

// the fpmeWpw function
function fpmeWpw(location_fpmeWpw)
{
	// [7974] set the function logic
	if (location_fpmeWpw == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the ynMoYbd function
function ynMoYbd(location_ynMoYbd)
{
	// [7974] set the function logic
	if (location_ynMoYbd == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the dXNGPAB function
function dXNGPAB(type_dXNGPAB)
{
	if (isSet(type_dXNGPAB) && type_dXNGPAB.constructor !== Array)
	{
		var temp_dXNGPAB = type_dXNGPAB;
		var type_dXNGPAB = [];
		type_dXNGPAB.push(temp_dXNGPAB);
	}
	else if (!isSet(type_dXNGPAB))
	{
		var type_dXNGPAB = [];
	}
	var type = type_dXNGPAB.some(type_dXNGPAB_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_dXNGPABnBk_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_dXNGPABnBk_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_dXNGPABnBk_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_dXNGPABnBk_required = true;
		}
	}
}

// the dXNGPAB Some function
function type_dXNGPAB_SomeFunc(type_dXNGPAB)
{
	// [7939] set the function logic
	if (type_dXNGPAB == 3)
	{
		return true;
	}
	return false;
}

// the lUjJAvV function
function lUjJAvV(type_lUjJAvV)
{
	if (isSet(type_lUjJAvV) && type_lUjJAvV.constructor !== Array)
	{
		var temp_lUjJAvV = type_lUjJAvV;
		var type_lUjJAvV = [];
		type_lUjJAvV.push(temp_lUjJAvV);
	}
	else if (!isSet(type_lUjJAvV))
	{
		var type_lUjJAvV = [];
	}
	var type = type_lUjJAvV.some(type_lUjJAvV_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_lUjJAvVFIk_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_lUjJAvVFIk_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_lUjJAvVFIk_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_lUjJAvVFIk_required = true;
		}
	}
}

// the lUjJAvV Some function
function type_lUjJAvV_SomeFunc(type_lUjJAvV)
{
	// [7939] set the function logic
	if (type_lUjJAvV == 1)
	{
		return true;
	}
	return false;
}

// the ILzlnJW function
function ILzlnJW(type_ILzlnJW)
{
	if (isSet(type_ILzlnJW) && type_ILzlnJW.constructor !== Array)
	{
		var temp_ILzlnJW = type_ILzlnJW;
		var type_ILzlnJW = [];
		type_ILzlnJW.push(temp_ILzlnJW);
	}
	else if (!isSet(type_ILzlnJW))
	{
		var type_ILzlnJW = [];
	}
	var type = type_ILzlnJW.some(type_ILzlnJW_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_ILzlnJWAHY_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_ILzlnJWAHY_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_ILzlnJWAHY_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_ILzlnJWAHY_required = true;
		}
	}
}

// the ILzlnJW Some function
function type_ILzlnJW_SomeFunc(type_ILzlnJW)
{
	// [7939] set the function logic
	if (type_ILzlnJW == 2)
	{
		return true;
	}
	return false;
}

// the wQUmqIU function
function wQUmqIU(target_wQUmqIU)
{
	// [7974] set the function logic
	if (target_wQUmqIU == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_wQUmqIUYUo_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_wQUmqIUYUo_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_wQUmqIUYUo_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_wQUmqIUYUo_required = true;
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

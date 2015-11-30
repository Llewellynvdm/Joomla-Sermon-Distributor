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
jform_lzodvvEVro_required = false;
jform_OgkLNZDeHC_required = false;
jform_cKsbxVZDmX_required = false;
jform_mFxICagiWx_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_xIXXXXY = jQuery("#jform_location input[type='radio']:checked").val();
	xIXXXXY(location_xIXXXXY);

	var location_UvYYxXP = jQuery("#jform_location input[type='radio']:checked").val();
	UvYYxXP(location_UvYYxXP);

	var type_lzodvvE = jQuery("#jform_type").val();
	lzodvvE(type_lzodvvE);

	var type_OgkLNZD = jQuery("#jform_type").val();
	OgkLNZD(type_OgkLNZD);

	var type_cKsbxVZ = jQuery("#jform_type").val();
	cKsbxVZ(type_cKsbxVZ);

	var target_mFxICag = jQuery("#jform_target input[type='radio']:checked").val();
	mFxICag(target_mFxICag);
});

// the xIXXXXY function
function xIXXXXY(location_xIXXXXY)
{
	// [7974] set the function logic
	if (location_xIXXXXY == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the UvYYxXP function
function UvYYxXP(location_UvYYxXP)
{
	// [7974] set the function logic
	if (location_UvYYxXP == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the lzodvvE function
function lzodvvE(type_lzodvvE)
{
	if (isSet(type_lzodvvE) && type_lzodvvE.constructor !== Array)
	{
		var temp_lzodvvE = type_lzodvvE;
		var type_lzodvvE = [];
		type_lzodvvE.push(temp_lzodvvE);
	}
	else if (!isSet(type_lzodvvE))
	{
		var type_lzodvvE = [];
	}
	var type = type_lzodvvE.some(type_lzodvvE_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_lzodvvEVro_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_lzodvvEVro_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_lzodvvEVro_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_lzodvvEVro_required = true;
		}
	}
}

// the lzodvvE Some function
function type_lzodvvE_SomeFunc(type_lzodvvE)
{
	// [7939] set the function logic
	if (type_lzodvvE == 3)
	{
		return true;
	}
	return false;
}

// the OgkLNZD function
function OgkLNZD(type_OgkLNZD)
{
	if (isSet(type_OgkLNZD) && type_OgkLNZD.constructor !== Array)
	{
		var temp_OgkLNZD = type_OgkLNZD;
		var type_OgkLNZD = [];
		type_OgkLNZD.push(temp_OgkLNZD);
	}
	else if (!isSet(type_OgkLNZD))
	{
		var type_OgkLNZD = [];
	}
	var type = type_OgkLNZD.some(type_OgkLNZD_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_OgkLNZDeHC_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_OgkLNZDeHC_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_OgkLNZDeHC_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_OgkLNZDeHC_required = true;
		}
	}
}

// the OgkLNZD Some function
function type_OgkLNZD_SomeFunc(type_OgkLNZD)
{
	// [7939] set the function logic
	if (type_OgkLNZD == 1)
	{
		return true;
	}
	return false;
}

// the cKsbxVZ function
function cKsbxVZ(type_cKsbxVZ)
{
	if (isSet(type_cKsbxVZ) && type_cKsbxVZ.constructor !== Array)
	{
		var temp_cKsbxVZ = type_cKsbxVZ;
		var type_cKsbxVZ = [];
		type_cKsbxVZ.push(temp_cKsbxVZ);
	}
	else if (!isSet(type_cKsbxVZ))
	{
		var type_cKsbxVZ = [];
	}
	var type = type_cKsbxVZ.some(type_cKsbxVZ_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_cKsbxVZDmX_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_cKsbxVZDmX_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_cKsbxVZDmX_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_cKsbxVZDmX_required = true;
		}
	}
}

// the cKsbxVZ Some function
function type_cKsbxVZ_SomeFunc(type_cKsbxVZ)
{
	// [7939] set the function logic
	if (type_cKsbxVZ == 2)
	{
		return true;
	}
	return false;
}

// the mFxICag function
function mFxICag(target_mFxICag)
{
	// [7974] set the function logic
	if (target_mFxICag == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_mFxICagiWx_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_mFxICagiWx_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_mFxICagiWx_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_mFxICagiWx_required = true;
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

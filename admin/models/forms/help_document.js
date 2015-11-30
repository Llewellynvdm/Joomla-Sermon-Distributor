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
jform_jfXdjKEkcj_required = false;
jform_neiBLaklKG_required = false;
jform_SWAPKmPZHb_required = false;
jform_dOgbQmLwDI_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_HJpAkRW = jQuery("#jform_location input[type='radio']:checked").val();
	HJpAkRW(location_HJpAkRW);

	var location_MTapnld = jQuery("#jform_location input[type='radio']:checked").val();
	MTapnld(location_MTapnld);

	var type_jfXdjKE = jQuery("#jform_type").val();
	jfXdjKE(type_jfXdjKE);

	var type_neiBLak = jQuery("#jform_type").val();
	neiBLak(type_neiBLak);

	var type_SWAPKmP = jQuery("#jform_type").val();
	SWAPKmP(type_SWAPKmP);

	var target_dOgbQmL = jQuery("#jform_target input[type='radio']:checked").val();
	dOgbQmL(target_dOgbQmL);
});

// the HJpAkRW function
function HJpAkRW(location_HJpAkRW)
{
	// [8000] set the function logic
	if (location_HJpAkRW == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the MTapnld function
function MTapnld(location_MTapnld)
{
	// [8000] set the function logic
	if (location_MTapnld == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the jfXdjKE function
function jfXdjKE(type_jfXdjKE)
{
	if (isSet(type_jfXdjKE) && type_jfXdjKE.constructor !== Array)
	{
		var temp_jfXdjKE = type_jfXdjKE;
		var type_jfXdjKE = [];
		type_jfXdjKE.push(temp_jfXdjKE);
	}
	else if (!isSet(type_jfXdjKE))
	{
		var type_jfXdjKE = [];
	}
	var type = type_jfXdjKE.some(type_jfXdjKE_SomeFunc);


	// [7978] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_jfXdjKEkcj_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_jfXdjKEkcj_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_jfXdjKEkcj_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_jfXdjKEkcj_required = true;
		}
	}
}

// the jfXdjKE Some function
function type_jfXdjKE_SomeFunc(type_jfXdjKE)
{
	// [7965] set the function logic
	if (type_jfXdjKE == 3)
	{
		return true;
	}
	return false;
}

// the neiBLak function
function neiBLak(type_neiBLak)
{
	if (isSet(type_neiBLak) && type_neiBLak.constructor !== Array)
	{
		var temp_neiBLak = type_neiBLak;
		var type_neiBLak = [];
		type_neiBLak.push(temp_neiBLak);
	}
	else if (!isSet(type_neiBLak))
	{
		var type_neiBLak = [];
	}
	var type = type_neiBLak.some(type_neiBLak_SomeFunc);


	// [7978] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_neiBLaklKG_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_neiBLaklKG_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_neiBLaklKG_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_neiBLaklKG_required = true;
		}
	}
}

// the neiBLak Some function
function type_neiBLak_SomeFunc(type_neiBLak)
{
	// [7965] set the function logic
	if (type_neiBLak == 1)
	{
		return true;
	}
	return false;
}

// the SWAPKmP function
function SWAPKmP(type_SWAPKmP)
{
	if (isSet(type_SWAPKmP) && type_SWAPKmP.constructor !== Array)
	{
		var temp_SWAPKmP = type_SWAPKmP;
		var type_SWAPKmP = [];
		type_SWAPKmP.push(temp_SWAPKmP);
	}
	else if (!isSet(type_SWAPKmP))
	{
		var type_SWAPKmP = [];
	}
	var type = type_SWAPKmP.some(type_SWAPKmP_SomeFunc);


	// [7978] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_SWAPKmPZHb_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_SWAPKmPZHb_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_SWAPKmPZHb_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_SWAPKmPZHb_required = true;
		}
	}
}

// the SWAPKmP Some function
function type_SWAPKmP_SomeFunc(type_SWAPKmP)
{
	// [7965] set the function logic
	if (type_SWAPKmP == 2)
	{
		return true;
	}
	return false;
}

// the dOgbQmL function
function dOgbQmL(target_dOgbQmL)
{
	// [8000] set the function logic
	if (target_dOgbQmL == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_dOgbQmLwDI_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_dOgbQmLwDI_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_dOgbQmLwDI_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_dOgbQmLwDI_required = true;
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

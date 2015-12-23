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
	@build			23rd December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_KVFJfFjKLp_required = false;
jform_PVcvKoRLlz_required = false;
jform_RaenoglczP_required = false;
jform_roNdXRBgDs_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_VcFiOMv = jQuery("#jform_location input[type='radio']:checked").val();
	VcFiOMv(location_VcFiOMv);

	var location_nbWIzGw = jQuery("#jform_location input[type='radio']:checked").val();
	nbWIzGw(location_nbWIzGw);

	var type_KVFJfFj = jQuery("#jform_type").val();
	KVFJfFj(type_KVFJfFj);

	var type_PVcvKoR = jQuery("#jform_type").val();
	PVcvKoR(type_PVcvKoR);

	var type_Raenogl = jQuery("#jform_type").val();
	Raenogl(type_Raenogl);

	var target_roNdXRB = jQuery("#jform_target input[type='radio']:checked").val();
	roNdXRB(target_roNdXRB);
});

// the VcFiOMv function
function VcFiOMv(location_VcFiOMv)
{
	// [8260] set the function logic
	if (location_VcFiOMv == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the nbWIzGw function
function nbWIzGw(location_nbWIzGw)
{
	// [8260] set the function logic
	if (location_nbWIzGw == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the KVFJfFj function
function KVFJfFj(type_KVFJfFj)
{
	if (isSet(type_KVFJfFj) && type_KVFJfFj.constructor !== Array)
	{
		var temp_KVFJfFj = type_KVFJfFj;
		var type_KVFJfFj = [];
		type_KVFJfFj.push(temp_KVFJfFj);
	}
	else if (!isSet(type_KVFJfFj))
	{
		var type_KVFJfFj = [];
	}
	var type = type_KVFJfFj.some(type_KVFJfFj_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_KVFJfFjKLp_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_KVFJfFjKLp_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_KVFJfFjKLp_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_KVFJfFjKLp_required = true;
		}
	}
}

// the KVFJfFj Some function
function type_KVFJfFj_SomeFunc(type_KVFJfFj)
{
	// [8225] set the function logic
	if (type_KVFJfFj == 3)
	{
		return true;
	}
	return false;
}

// the PVcvKoR function
function PVcvKoR(type_PVcvKoR)
{
	if (isSet(type_PVcvKoR) && type_PVcvKoR.constructor !== Array)
	{
		var temp_PVcvKoR = type_PVcvKoR;
		var type_PVcvKoR = [];
		type_PVcvKoR.push(temp_PVcvKoR);
	}
	else if (!isSet(type_PVcvKoR))
	{
		var type_PVcvKoR = [];
	}
	var type = type_PVcvKoR.some(type_PVcvKoR_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_PVcvKoRLlz_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_PVcvKoRLlz_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_PVcvKoRLlz_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_PVcvKoRLlz_required = true;
		}
	}
}

// the PVcvKoR Some function
function type_PVcvKoR_SomeFunc(type_PVcvKoR)
{
	// [8225] set the function logic
	if (type_PVcvKoR == 1)
	{
		return true;
	}
	return false;
}

// the Raenogl function
function Raenogl(type_Raenogl)
{
	if (isSet(type_Raenogl) && type_Raenogl.constructor !== Array)
	{
		var temp_Raenogl = type_Raenogl;
		var type_Raenogl = [];
		type_Raenogl.push(temp_Raenogl);
	}
	else if (!isSet(type_Raenogl))
	{
		var type_Raenogl = [];
	}
	var type = type_Raenogl.some(type_Raenogl_SomeFunc);


	// [8238] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_RaenoglczP_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_RaenoglczP_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_RaenoglczP_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_RaenoglczP_required = true;
		}
	}
}

// the Raenogl Some function
function type_Raenogl_SomeFunc(type_Raenogl)
{
	// [8225] set the function logic
	if (type_Raenogl == 2)
	{
		return true;
	}
	return false;
}

// the roNdXRB function
function roNdXRB(target_roNdXRB)
{
	// [8260] set the function logic
	if (target_roNdXRB == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_roNdXRBgDs_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_roNdXRBgDs_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_roNdXRBgDs_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_roNdXRBgDs_required = true;
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

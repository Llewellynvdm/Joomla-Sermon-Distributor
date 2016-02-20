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
jform_yTuwfuDjcU_required = false;
jform_lXSBThsfQv_required = false;
jform_Qynvtauxsf_required = false;
jform_wPaXjizLsB_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_lEjKOMF = jQuery("#jform_location input[type='radio']:checked").val();
	lEjKOMF(location_lEjKOMF);

	var location_OQSkzQZ = jQuery("#jform_location input[type='radio']:checked").val();
	OQSkzQZ(location_OQSkzQZ);

	var type_yTuwfuD = jQuery("#jform_type").val();
	yTuwfuD(type_yTuwfuD);

	var type_lXSBThs = jQuery("#jform_type").val();
	lXSBThs(type_lXSBThs);

	var type_Qynvtau = jQuery("#jform_type").val();
	Qynvtau(type_Qynvtau);

	var target_wPaXjiz = jQuery("#jform_target input[type='radio']:checked").val();
	wPaXjiz(target_wPaXjiz);
});

// the lEjKOMF function
function lEjKOMF(location_lEjKOMF)
{
	// [8696] set the function logic
	if (location_lEjKOMF == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the OQSkzQZ function
function OQSkzQZ(location_OQSkzQZ)
{
	// [8696] set the function logic
	if (location_OQSkzQZ == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the yTuwfuD function
function yTuwfuD(type_yTuwfuD)
{
	if (isSet(type_yTuwfuD) && type_yTuwfuD.constructor !== Array)
	{
		var temp_yTuwfuD = type_yTuwfuD;
		var type_yTuwfuD = [];
		type_yTuwfuD.push(temp_yTuwfuD);
	}
	else if (!isSet(type_yTuwfuD))
	{
		var type_yTuwfuD = [];
	}
	var type = type_yTuwfuD.some(type_yTuwfuD_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_yTuwfuDjcU_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_yTuwfuDjcU_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_yTuwfuDjcU_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_yTuwfuDjcU_required = true;
		}
	}
}

// the yTuwfuD Some function
function type_yTuwfuD_SomeFunc(type_yTuwfuD)
{
	// [8661] set the function logic
	if (type_yTuwfuD == 3)
	{
		return true;
	}
	return false;
}

// the lXSBThs function
function lXSBThs(type_lXSBThs)
{
	if (isSet(type_lXSBThs) && type_lXSBThs.constructor !== Array)
	{
		var temp_lXSBThs = type_lXSBThs;
		var type_lXSBThs = [];
		type_lXSBThs.push(temp_lXSBThs);
	}
	else if (!isSet(type_lXSBThs))
	{
		var type_lXSBThs = [];
	}
	var type = type_lXSBThs.some(type_lXSBThs_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_lXSBThsfQv_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_lXSBThsfQv_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_lXSBThsfQv_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_lXSBThsfQv_required = true;
		}
	}
}

// the lXSBThs Some function
function type_lXSBThs_SomeFunc(type_lXSBThs)
{
	// [8661] set the function logic
	if (type_lXSBThs == 1)
	{
		return true;
	}
	return false;
}

// the Qynvtau function
function Qynvtau(type_Qynvtau)
{
	if (isSet(type_Qynvtau) && type_Qynvtau.constructor !== Array)
	{
		var temp_Qynvtau = type_Qynvtau;
		var type_Qynvtau = [];
		type_Qynvtau.push(temp_Qynvtau);
	}
	else if (!isSet(type_Qynvtau))
	{
		var type_Qynvtau = [];
	}
	var type = type_Qynvtau.some(type_Qynvtau_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_Qynvtauxsf_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_Qynvtauxsf_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_Qynvtauxsf_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_Qynvtauxsf_required = true;
		}
	}
}

// the Qynvtau Some function
function type_Qynvtau_SomeFunc(type_Qynvtau)
{
	// [8661] set the function logic
	if (type_Qynvtau == 2)
	{
		return true;
	}
	return false;
}

// the wPaXjiz function
function wPaXjiz(target_wPaXjiz)
{
	// [8696] set the function logic
	if (target_wPaXjiz == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_wPaXjizLsB_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_wPaXjizLsB_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_wPaXjizLsB_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_wPaXjizLsB_required = true;
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

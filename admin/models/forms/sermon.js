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
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_IBFygJNuDM_required = false;
jform_kcwFchXKIB_required = false;
jform_nUoduMOIRJ_required = false;
jform_FLgnCOUeKh_required = false;
jform_NXjdDlkAaX_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_IBFygJN = jQuery("#jform_source").val();
	IBFygJN(source_IBFygJN);

	var source_rfSRRvE = jQuery("#jform_source").val();
	var build_rfSRRvE = jQuery("#jform_build input[type='radio']:checked").val();
	rfSRRvE(source_rfSRRvE,build_rfSRRvE);

	var source_kcwFchX = jQuery("#jform_source").val();
	var build_kcwFchX = jQuery("#jform_build input[type='radio']:checked").val();
	kcwFchX(source_kcwFchX,build_kcwFchX);

	var build_nUoduMO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_nUoduMO = jQuery("#jform_source").val();
	nUoduMO(build_nUoduMO,source_nUoduMO);

	var source_FLgnCOU = jQuery("#jform_source").val();
	FLgnCOU(source_FLgnCOU);

	var source_NXjdDlk = jQuery("#jform_source").val();
	NXjdDlk(source_NXjdDlk);

	var link_type_ctPrsiZ = jQuery("#jform_link_type input[type='radio']:checked").val();
	ctPrsiZ(link_type_ctPrsiZ);

	var link_type_jSSQzwW = jQuery("#jform_link_type input[type='radio']:checked").val();
	jSSQzwW(link_type_jSSQzwW);
});

// the IBFygJN function
function IBFygJN(source_IBFygJN)
{
	if (isSet(source_IBFygJN) && source_IBFygJN.constructor !== Array)
	{
		var temp_IBFygJN = source_IBFygJN;
		var source_IBFygJN = [];
		source_IBFygJN.push(temp_IBFygJN);
	}
	else if (!isSet(source_IBFygJN))
	{
		var source_IBFygJN = [];
	}
	var source = source_IBFygJN.some(source_IBFygJN_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_IBFygJNuDM_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_IBFygJNuDM_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_IBFygJNuDM_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_IBFygJNuDM_required = true;
		}
	}
}

// the IBFygJN Some function
function source_IBFygJN_SomeFunc(source_IBFygJN)
{
	// [8661] set the function logic
	if (source_IBFygJN == 2)
	{
		return true;
	}
	return false;
}

// the rfSRRvE function
function rfSRRvE(source_rfSRRvE,build_rfSRRvE)
{
	if (isSet(source_rfSRRvE) && source_rfSRRvE.constructor !== Array)
	{
		var temp_rfSRRvE = source_rfSRRvE;
		var source_rfSRRvE = [];
		source_rfSRRvE.push(temp_rfSRRvE);
	}
	else if (!isSet(source_rfSRRvE))
	{
		var source_rfSRRvE = [];
	}
	var source = source_rfSRRvE.some(source_rfSRRvE_SomeFunc);

	if (isSet(build_rfSRRvE) && build_rfSRRvE.constructor !== Array)
	{
		var temp_rfSRRvE = build_rfSRRvE;
		var build_rfSRRvE = [];
		build_rfSRRvE.push(temp_rfSRRvE);
	}
	else if (!isSet(build_rfSRRvE))
	{
		var build_rfSRRvE = [];
	}
	var build = build_rfSRRvE.some(build_rfSRRvE_SomeFunc);


	// [8674] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the rfSRRvE Some function
function source_rfSRRvE_SomeFunc(source_rfSRRvE)
{
	// [8661] set the function logic
	if (source_rfSRRvE == 2)
	{
		return true;
	}
	return false;
}

// the rfSRRvE Some function
function build_rfSRRvE_SomeFunc(build_rfSRRvE)
{
	// [8661] set the function logic
	if (build_rfSRRvE == 2)
	{
		return true;
	}
	return false;
}

// the kcwFchX function
function kcwFchX(source_kcwFchX,build_kcwFchX)
{
	if (isSet(source_kcwFchX) && source_kcwFchX.constructor !== Array)
	{
		var temp_kcwFchX = source_kcwFchX;
		var source_kcwFchX = [];
		source_kcwFchX.push(temp_kcwFchX);
	}
	else if (!isSet(source_kcwFchX))
	{
		var source_kcwFchX = [];
	}
	var source = source_kcwFchX.some(source_kcwFchX_SomeFunc);

	if (isSet(build_kcwFchX) && build_kcwFchX.constructor !== Array)
	{
		var temp_kcwFchX = build_kcwFchX;
		var build_kcwFchX = [];
		build_kcwFchX.push(temp_kcwFchX);
	}
	else if (!isSet(build_kcwFchX))
	{
		var build_kcwFchX = [];
	}
	var build = build_kcwFchX.some(build_kcwFchX_SomeFunc);


	// [8674] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_kcwFchXKIB_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_kcwFchXKIB_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_kcwFchXKIB_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_kcwFchXKIB_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the kcwFchX Some function
function source_kcwFchX_SomeFunc(source_kcwFchX)
{
	// [8661] set the function logic
	if (source_kcwFchX == 2)
	{
		return true;
	}
	return false;
}

// the kcwFchX Some function
function build_kcwFchX_SomeFunc(build_kcwFchX)
{
	// [8661] set the function logic
	if (build_kcwFchX == 1)
	{
		return true;
	}
	return false;
}

// the nUoduMO function
function nUoduMO(build_nUoduMO,source_nUoduMO)
{
	if (isSet(build_nUoduMO) && build_nUoduMO.constructor !== Array)
	{
		var temp_nUoduMO = build_nUoduMO;
		var build_nUoduMO = [];
		build_nUoduMO.push(temp_nUoduMO);
	}
	else if (!isSet(build_nUoduMO))
	{
		var build_nUoduMO = [];
	}
	var build = build_nUoduMO.some(build_nUoduMO_SomeFunc);

	if (isSet(source_nUoduMO) && source_nUoduMO.constructor !== Array)
	{
		var temp_nUoduMO = source_nUoduMO;
		var source_nUoduMO = [];
		source_nUoduMO.push(temp_nUoduMO);
	}
	else if (!isSet(source_nUoduMO))
	{
		var source_nUoduMO = [];
	}
	var source = source_nUoduMO.some(source_nUoduMO_SomeFunc);


	// [8674] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_nUoduMOIRJ_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_nUoduMOIRJ_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_nUoduMOIRJ_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_nUoduMOIRJ_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the nUoduMO Some function
function build_nUoduMO_SomeFunc(build_nUoduMO)
{
	// [8661] set the function logic
	if (build_nUoduMO == 1)
	{
		return true;
	}
	return false;
}

// the nUoduMO Some function
function source_nUoduMO_SomeFunc(source_nUoduMO)
{
	// [8661] set the function logic
	if (source_nUoduMO == 2)
	{
		return true;
	}
	return false;
}

// the FLgnCOU function
function FLgnCOU(source_FLgnCOU)
{
	if (isSet(source_FLgnCOU) && source_FLgnCOU.constructor !== Array)
	{
		var temp_FLgnCOU = source_FLgnCOU;
		var source_FLgnCOU = [];
		source_FLgnCOU.push(temp_FLgnCOU);
	}
	else if (!isSet(source_FLgnCOU))
	{
		var source_FLgnCOU = [];
	}
	var source = source_FLgnCOU.some(source_FLgnCOU_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_FLgnCOUeKh_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_FLgnCOUeKh_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_FLgnCOUeKh_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_FLgnCOUeKh_required = true;
		}
	}
}

// the FLgnCOU Some function
function source_FLgnCOU_SomeFunc(source_FLgnCOU)
{
	// [8661] set the function logic
	if (source_FLgnCOU == 1)
	{
		return true;
	}
	return false;
}

// the NXjdDlk function
function NXjdDlk(source_NXjdDlk)
{
	if (isSet(source_NXjdDlk) && source_NXjdDlk.constructor !== Array)
	{
		var temp_NXjdDlk = source_NXjdDlk;
		var source_NXjdDlk = [];
		source_NXjdDlk.push(temp_NXjdDlk);
	}
	else if (!isSet(source_NXjdDlk))
	{
		var source_NXjdDlk = [];
	}
	var source = source_NXjdDlk.some(source_NXjdDlk_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_NXjdDlkAaX_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_NXjdDlkAaX_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_NXjdDlkAaX_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_NXjdDlkAaX_required = true;
		}
	}
}

// the NXjdDlk Some function
function source_NXjdDlk_SomeFunc(source_NXjdDlk)
{
	// [8661] set the function logic
	if (source_NXjdDlk == 3)
	{
		return true;
	}
	return false;
}

// the ctPrsiZ function
function ctPrsiZ(link_type_ctPrsiZ)
{
	// [8696] set the function logic
	if (link_type_ctPrsiZ == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the jSSQzwW function
function jSSQzwW(link_type_jSSQzwW)
{
	// [8696] set the function logic
	if (link_type_jSSQzwW == 1)
	{
		jQuery('.note_link_encrypted').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_encrypted').closest('.control-group').hide();
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

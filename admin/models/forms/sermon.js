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
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_dOVAunrpjk_required = false;
jform_iqNHLdUKVO_required = false;
jform_kkZAuiNOLQ_required = false;
jform_SmvypXshHY_required = false;
jform_ENosobDoOr_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_dOVAunr = jQuery("#jform_source").val();
	dOVAunr(source_dOVAunr);

	var source_mDbfJMD = jQuery("#jform_source").val();
	var build_mDbfJMD = jQuery("#jform_build input[type='radio']:checked").val();
	mDbfJMD(source_mDbfJMD,build_mDbfJMD);

	var source_iqNHLdU = jQuery("#jform_source").val();
	var build_iqNHLdU = jQuery("#jform_build input[type='radio']:checked").val();
	iqNHLdU(source_iqNHLdU,build_iqNHLdU);

	var build_kkZAuiN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_kkZAuiN = jQuery("#jform_source").val();
	kkZAuiN(build_kkZAuiN,source_kkZAuiN);

	var source_SmvypXs = jQuery("#jform_source").val();
	SmvypXs(source_SmvypXs);

	var source_ENosobD = jQuery("#jform_source").val();
	ENosobD(source_ENosobD);

	var link_type_aYIZzei = jQuery("#jform_link_type input[type='radio']:checked").val();
	aYIZzei(link_type_aYIZzei);

	var link_type_oRQiMgE = jQuery("#jform_link_type input[type='radio']:checked").val();
	oRQiMgE(link_type_oRQiMgE);
});

// the dOVAunr function
function dOVAunr(source_dOVAunr)
{
	if (isSet(source_dOVAunr) && source_dOVAunr.constructor !== Array)
	{
		var temp_dOVAunr = source_dOVAunr;
		var source_dOVAunr = [];
		source_dOVAunr.push(temp_dOVAunr);
	}
	else if (!isSet(source_dOVAunr))
	{
		var source_dOVAunr = [];
	}
	var source = source_dOVAunr.some(source_dOVAunr_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_dOVAunrpjk_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_dOVAunrpjk_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_dOVAunrpjk_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_dOVAunrpjk_required = true;
		}
	}
}

// the dOVAunr Some function
function source_dOVAunr_SomeFunc(source_dOVAunr)
{
	// [8272] set the function logic
	if (source_dOVAunr == 2)
	{
		return true;
	}
	return false;
}

// the mDbfJMD function
function mDbfJMD(source_mDbfJMD,build_mDbfJMD)
{
	if (isSet(source_mDbfJMD) && source_mDbfJMD.constructor !== Array)
	{
		var temp_mDbfJMD = source_mDbfJMD;
		var source_mDbfJMD = [];
		source_mDbfJMD.push(temp_mDbfJMD);
	}
	else if (!isSet(source_mDbfJMD))
	{
		var source_mDbfJMD = [];
	}
	var source = source_mDbfJMD.some(source_mDbfJMD_SomeFunc);

	if (isSet(build_mDbfJMD) && build_mDbfJMD.constructor !== Array)
	{
		var temp_mDbfJMD = build_mDbfJMD;
		var build_mDbfJMD = [];
		build_mDbfJMD.push(temp_mDbfJMD);
	}
	else if (!isSet(build_mDbfJMD))
	{
		var build_mDbfJMD = [];
	}
	var build = build_mDbfJMD.some(build_mDbfJMD_SomeFunc);


	// [8285] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the mDbfJMD Some function
function source_mDbfJMD_SomeFunc(source_mDbfJMD)
{
	// [8272] set the function logic
	if (source_mDbfJMD == 2)
	{
		return true;
	}
	return false;
}

// the mDbfJMD Some function
function build_mDbfJMD_SomeFunc(build_mDbfJMD)
{
	// [8272] set the function logic
	if (build_mDbfJMD == 2)
	{
		return true;
	}
	return false;
}

// the iqNHLdU function
function iqNHLdU(source_iqNHLdU,build_iqNHLdU)
{
	if (isSet(source_iqNHLdU) && source_iqNHLdU.constructor !== Array)
	{
		var temp_iqNHLdU = source_iqNHLdU;
		var source_iqNHLdU = [];
		source_iqNHLdU.push(temp_iqNHLdU);
	}
	else if (!isSet(source_iqNHLdU))
	{
		var source_iqNHLdU = [];
	}
	var source = source_iqNHLdU.some(source_iqNHLdU_SomeFunc);

	if (isSet(build_iqNHLdU) && build_iqNHLdU.constructor !== Array)
	{
		var temp_iqNHLdU = build_iqNHLdU;
		var build_iqNHLdU = [];
		build_iqNHLdU.push(temp_iqNHLdU);
	}
	else if (!isSet(build_iqNHLdU))
	{
		var build_iqNHLdU = [];
	}
	var build = build_iqNHLdU.some(build_iqNHLdU_SomeFunc);


	// [8285] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_iqNHLdUKVO_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_iqNHLdUKVO_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_iqNHLdUKVO_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_iqNHLdUKVO_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the iqNHLdU Some function
function source_iqNHLdU_SomeFunc(source_iqNHLdU)
{
	// [8272] set the function logic
	if (source_iqNHLdU == 2)
	{
		return true;
	}
	return false;
}

// the iqNHLdU Some function
function build_iqNHLdU_SomeFunc(build_iqNHLdU)
{
	// [8272] set the function logic
	if (build_iqNHLdU == 1)
	{
		return true;
	}
	return false;
}

// the kkZAuiN function
function kkZAuiN(build_kkZAuiN,source_kkZAuiN)
{
	if (isSet(build_kkZAuiN) && build_kkZAuiN.constructor !== Array)
	{
		var temp_kkZAuiN = build_kkZAuiN;
		var build_kkZAuiN = [];
		build_kkZAuiN.push(temp_kkZAuiN);
	}
	else if (!isSet(build_kkZAuiN))
	{
		var build_kkZAuiN = [];
	}
	var build = build_kkZAuiN.some(build_kkZAuiN_SomeFunc);

	if (isSet(source_kkZAuiN) && source_kkZAuiN.constructor !== Array)
	{
		var temp_kkZAuiN = source_kkZAuiN;
		var source_kkZAuiN = [];
		source_kkZAuiN.push(temp_kkZAuiN);
	}
	else if (!isSet(source_kkZAuiN))
	{
		var source_kkZAuiN = [];
	}
	var source = source_kkZAuiN.some(source_kkZAuiN_SomeFunc);


	// [8285] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_kkZAuiNOLQ_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_kkZAuiNOLQ_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_kkZAuiNOLQ_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_kkZAuiNOLQ_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the kkZAuiN Some function
function build_kkZAuiN_SomeFunc(build_kkZAuiN)
{
	// [8272] set the function logic
	if (build_kkZAuiN == 1)
	{
		return true;
	}
	return false;
}

// the kkZAuiN Some function
function source_kkZAuiN_SomeFunc(source_kkZAuiN)
{
	// [8272] set the function logic
	if (source_kkZAuiN == 2)
	{
		return true;
	}
	return false;
}

// the SmvypXs function
function SmvypXs(source_SmvypXs)
{
	if (isSet(source_SmvypXs) && source_SmvypXs.constructor !== Array)
	{
		var temp_SmvypXs = source_SmvypXs;
		var source_SmvypXs = [];
		source_SmvypXs.push(temp_SmvypXs);
	}
	else if (!isSet(source_SmvypXs))
	{
		var source_SmvypXs = [];
	}
	var source = source_SmvypXs.some(source_SmvypXs_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_SmvypXshHY_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_SmvypXshHY_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_SmvypXshHY_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_SmvypXshHY_required = true;
		}
	}
}

// the SmvypXs Some function
function source_SmvypXs_SomeFunc(source_SmvypXs)
{
	// [8272] set the function logic
	if (source_SmvypXs == 1)
	{
		return true;
	}
	return false;
}

// the ENosobD function
function ENosobD(source_ENosobD)
{
	if (isSet(source_ENosobD) && source_ENosobD.constructor !== Array)
	{
		var temp_ENosobD = source_ENosobD;
		var source_ENosobD = [];
		source_ENosobD.push(temp_ENosobD);
	}
	else if (!isSet(source_ENosobD))
	{
		var source_ENosobD = [];
	}
	var source = source_ENosobD.some(source_ENosobD_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_ENosobDoOr_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_ENosobDoOr_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_ENosobDoOr_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_ENosobDoOr_required = true;
		}
	}
}

// the ENosobD Some function
function source_ENosobD_SomeFunc(source_ENosobD)
{
	// [8272] set the function logic
	if (source_ENosobD == 3)
	{
		return true;
	}
	return false;
}

// the aYIZzei function
function aYIZzei(link_type_aYIZzei)
{
	// [8307] set the function logic
	if (link_type_aYIZzei == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the oRQiMgE function
function oRQiMgE(link_type_oRQiMgE)
{
	// [8307] set the function logic
	if (link_type_oRQiMgE == 1)
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

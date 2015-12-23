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
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_pRjJkFbxsO_required = false;
jform_UwNFZmFkhM_required = false;
jform_XolZULNBiI_required = false;
jform_BxTsmCrfEH_required = false;
jform_DrXlXWEDhG_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_pRjJkFb = jQuery("#jform_source").val();
	pRjJkFb(source_pRjJkFb);

	var source_NvRTrqR = jQuery("#jform_source").val();
	var build_NvRTrqR = jQuery("#jform_build input[type='radio']:checked").val();
	NvRTrqR(source_NvRTrqR,build_NvRTrqR);

	var source_UwNFZmF = jQuery("#jform_source").val();
	var build_UwNFZmF = jQuery("#jform_build input[type='radio']:checked").val();
	UwNFZmF(source_UwNFZmF,build_UwNFZmF);

	var build_XolZULN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_XolZULN = jQuery("#jform_source").val();
	XolZULN(build_XolZULN,source_XolZULN);

	var source_BxTsmCr = jQuery("#jform_source").val();
	BxTsmCr(source_BxTsmCr);

	var source_DrXlXWE = jQuery("#jform_source").val();
	DrXlXWE(source_DrXlXWE);

	var link_type_jgbBgTf = jQuery("#jform_link_type input[type='radio']:checked").val();
	jgbBgTf(link_type_jgbBgTf);

	var link_type_TvlDXQo = jQuery("#jform_link_type input[type='radio']:checked").val();
	TvlDXQo(link_type_TvlDXQo);
});

// the pRjJkFb function
function pRjJkFb(source_pRjJkFb)
{
	if (isSet(source_pRjJkFb) && source_pRjJkFb.constructor !== Array)
	{
		var temp_pRjJkFb = source_pRjJkFb;
		var source_pRjJkFb = [];
		source_pRjJkFb.push(temp_pRjJkFb);
	}
	else if (!isSet(source_pRjJkFb))
	{
		var source_pRjJkFb = [];
	}
	var source = source_pRjJkFb.some(source_pRjJkFb_SomeFunc);


	// [8226] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_pRjJkFbxsO_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_pRjJkFbxsO_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_pRjJkFbxsO_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_pRjJkFbxsO_required = true;
		}
	}
}

// the pRjJkFb Some function
function source_pRjJkFb_SomeFunc(source_pRjJkFb)
{
	// [8213] set the function logic
	if (source_pRjJkFb == 2)
	{
		return true;
	}
	return false;
}

// the NvRTrqR function
function NvRTrqR(source_NvRTrqR,build_NvRTrqR)
{
	if (isSet(source_NvRTrqR) && source_NvRTrqR.constructor !== Array)
	{
		var temp_NvRTrqR = source_NvRTrqR;
		var source_NvRTrqR = [];
		source_NvRTrqR.push(temp_NvRTrqR);
	}
	else if (!isSet(source_NvRTrqR))
	{
		var source_NvRTrqR = [];
	}
	var source = source_NvRTrqR.some(source_NvRTrqR_SomeFunc);

	if (isSet(build_NvRTrqR) && build_NvRTrqR.constructor !== Array)
	{
		var temp_NvRTrqR = build_NvRTrqR;
		var build_NvRTrqR = [];
		build_NvRTrqR.push(temp_NvRTrqR);
	}
	else if (!isSet(build_NvRTrqR))
	{
		var build_NvRTrqR = [];
	}
	var build = build_NvRTrqR.some(build_NvRTrqR_SomeFunc);


	// [8226] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the NvRTrqR Some function
function source_NvRTrqR_SomeFunc(source_NvRTrqR)
{
	// [8213] set the function logic
	if (source_NvRTrqR == 2)
	{
		return true;
	}
	return false;
}

// the NvRTrqR Some function
function build_NvRTrqR_SomeFunc(build_NvRTrqR)
{
	// [8213] set the function logic
	if (build_NvRTrqR == 2)
	{
		return true;
	}
	return false;
}

// the UwNFZmF function
function UwNFZmF(source_UwNFZmF,build_UwNFZmF)
{
	if (isSet(source_UwNFZmF) && source_UwNFZmF.constructor !== Array)
	{
		var temp_UwNFZmF = source_UwNFZmF;
		var source_UwNFZmF = [];
		source_UwNFZmF.push(temp_UwNFZmF);
	}
	else if (!isSet(source_UwNFZmF))
	{
		var source_UwNFZmF = [];
	}
	var source = source_UwNFZmF.some(source_UwNFZmF_SomeFunc);

	if (isSet(build_UwNFZmF) && build_UwNFZmF.constructor !== Array)
	{
		var temp_UwNFZmF = build_UwNFZmF;
		var build_UwNFZmF = [];
		build_UwNFZmF.push(temp_UwNFZmF);
	}
	else if (!isSet(build_UwNFZmF))
	{
		var build_UwNFZmF = [];
	}
	var build = build_UwNFZmF.some(build_UwNFZmF_SomeFunc);


	// [8226] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_UwNFZmFkhM_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_UwNFZmFkhM_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_UwNFZmFkhM_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_UwNFZmFkhM_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the UwNFZmF Some function
function source_UwNFZmF_SomeFunc(source_UwNFZmF)
{
	// [8213] set the function logic
	if (source_UwNFZmF == 2)
	{
		return true;
	}
	return false;
}

// the UwNFZmF Some function
function build_UwNFZmF_SomeFunc(build_UwNFZmF)
{
	// [8213] set the function logic
	if (build_UwNFZmF == 1)
	{
		return true;
	}
	return false;
}

// the XolZULN function
function XolZULN(build_XolZULN,source_XolZULN)
{
	if (isSet(build_XolZULN) && build_XolZULN.constructor !== Array)
	{
		var temp_XolZULN = build_XolZULN;
		var build_XolZULN = [];
		build_XolZULN.push(temp_XolZULN);
	}
	else if (!isSet(build_XolZULN))
	{
		var build_XolZULN = [];
	}
	var build = build_XolZULN.some(build_XolZULN_SomeFunc);

	if (isSet(source_XolZULN) && source_XolZULN.constructor !== Array)
	{
		var temp_XolZULN = source_XolZULN;
		var source_XolZULN = [];
		source_XolZULN.push(temp_XolZULN);
	}
	else if (!isSet(source_XolZULN))
	{
		var source_XolZULN = [];
	}
	var source = source_XolZULN.some(source_XolZULN_SomeFunc);


	// [8226] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_XolZULNBiI_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_XolZULNBiI_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_XolZULNBiI_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_XolZULNBiI_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the XolZULN Some function
function build_XolZULN_SomeFunc(build_XolZULN)
{
	// [8213] set the function logic
	if (build_XolZULN == 1)
	{
		return true;
	}
	return false;
}

// the XolZULN Some function
function source_XolZULN_SomeFunc(source_XolZULN)
{
	// [8213] set the function logic
	if (source_XolZULN == 2)
	{
		return true;
	}
	return false;
}

// the BxTsmCr function
function BxTsmCr(source_BxTsmCr)
{
	if (isSet(source_BxTsmCr) && source_BxTsmCr.constructor !== Array)
	{
		var temp_BxTsmCr = source_BxTsmCr;
		var source_BxTsmCr = [];
		source_BxTsmCr.push(temp_BxTsmCr);
	}
	else if (!isSet(source_BxTsmCr))
	{
		var source_BxTsmCr = [];
	}
	var source = source_BxTsmCr.some(source_BxTsmCr_SomeFunc);


	// [8226] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_BxTsmCrfEH_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_BxTsmCrfEH_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_BxTsmCrfEH_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_BxTsmCrfEH_required = true;
		}
	}
}

// the BxTsmCr Some function
function source_BxTsmCr_SomeFunc(source_BxTsmCr)
{
	// [8213] set the function logic
	if (source_BxTsmCr == 1)
	{
		return true;
	}
	return false;
}

// the DrXlXWE function
function DrXlXWE(source_DrXlXWE)
{
	if (isSet(source_DrXlXWE) && source_DrXlXWE.constructor !== Array)
	{
		var temp_DrXlXWE = source_DrXlXWE;
		var source_DrXlXWE = [];
		source_DrXlXWE.push(temp_DrXlXWE);
	}
	else if (!isSet(source_DrXlXWE))
	{
		var source_DrXlXWE = [];
	}
	var source = source_DrXlXWE.some(source_DrXlXWE_SomeFunc);


	// [8226] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_DrXlXWEDhG_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_DrXlXWEDhG_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_DrXlXWEDhG_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_DrXlXWEDhG_required = true;
		}
	}
}

// the DrXlXWE Some function
function source_DrXlXWE_SomeFunc(source_DrXlXWE)
{
	// [8213] set the function logic
	if (source_DrXlXWE == 3)
	{
		return true;
	}
	return false;
}

// the jgbBgTf function
function jgbBgTf(link_type_jgbBgTf)
{
	// [8248] set the function logic
	if (link_type_jgbBgTf == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the TvlDXQo function
function TvlDXQo(link_type_TvlDXQo)
{
	// [8248] set the function logic
	if (link_type_TvlDXQo == 1)
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

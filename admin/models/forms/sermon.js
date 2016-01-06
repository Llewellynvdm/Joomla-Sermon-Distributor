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
	@build			6th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_JEmAiDFgAz_required = false;
jform_mBQbiOvPmP_required = false;
jform_wsmVGIDntn_required = false;
jform_ENzGNYPiLA_required = false;
jform_YYiOanxvgR_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_JEmAiDF = jQuery("#jform_source").val();
	JEmAiDF(source_JEmAiDF);

	var source_NIIuwpV = jQuery("#jform_source").val();
	var build_NIIuwpV = jQuery("#jform_build input[type='radio']:checked").val();
	NIIuwpV(source_NIIuwpV,build_NIIuwpV);

	var source_mBQbiOv = jQuery("#jform_source").val();
	var build_mBQbiOv = jQuery("#jform_build input[type='radio']:checked").val();
	mBQbiOv(source_mBQbiOv,build_mBQbiOv);

	var build_wsmVGID = jQuery("#jform_build input[type='radio']:checked").val();
	var source_wsmVGID = jQuery("#jform_source").val();
	wsmVGID(build_wsmVGID,source_wsmVGID);

	var source_ENzGNYP = jQuery("#jform_source").val();
	ENzGNYP(source_ENzGNYP);

	var source_YYiOanx = jQuery("#jform_source").val();
	YYiOanx(source_YYiOanx);

	var link_type_rCpyqQl = jQuery("#jform_link_type input[type='radio']:checked").val();
	rCpyqQl(link_type_rCpyqQl);

	var link_type_RgLeKCE = jQuery("#jform_link_type input[type='radio']:checked").val();
	RgLeKCE(link_type_RgLeKCE);
});

// the JEmAiDF function
function JEmAiDF(source_JEmAiDF)
{
	if (isSet(source_JEmAiDF) && source_JEmAiDF.constructor !== Array)
	{
		var temp_JEmAiDF = source_JEmAiDF;
		var source_JEmAiDF = [];
		source_JEmAiDF.push(temp_JEmAiDF);
	}
	else if (!isSet(source_JEmAiDF))
	{
		var source_JEmAiDF = [];
	}
	var source = source_JEmAiDF.some(source_JEmAiDF_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_JEmAiDFgAz_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_JEmAiDFgAz_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_JEmAiDFgAz_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_JEmAiDFgAz_required = true;
		}
	}
}

// the JEmAiDF Some function
function source_JEmAiDF_SomeFunc(source_JEmAiDF)
{
	// [8234] set the function logic
	if (source_JEmAiDF == 2)
	{
		return true;
	}
	return false;
}

// the NIIuwpV function
function NIIuwpV(source_NIIuwpV,build_NIIuwpV)
{
	if (isSet(source_NIIuwpV) && source_NIIuwpV.constructor !== Array)
	{
		var temp_NIIuwpV = source_NIIuwpV;
		var source_NIIuwpV = [];
		source_NIIuwpV.push(temp_NIIuwpV);
	}
	else if (!isSet(source_NIIuwpV))
	{
		var source_NIIuwpV = [];
	}
	var source = source_NIIuwpV.some(source_NIIuwpV_SomeFunc);

	if (isSet(build_NIIuwpV) && build_NIIuwpV.constructor !== Array)
	{
		var temp_NIIuwpV = build_NIIuwpV;
		var build_NIIuwpV = [];
		build_NIIuwpV.push(temp_NIIuwpV);
	}
	else if (!isSet(build_NIIuwpV))
	{
		var build_NIIuwpV = [];
	}
	var build = build_NIIuwpV.some(build_NIIuwpV_SomeFunc);


	// [8247] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the NIIuwpV Some function
function source_NIIuwpV_SomeFunc(source_NIIuwpV)
{
	// [8234] set the function logic
	if (source_NIIuwpV == 2)
	{
		return true;
	}
	return false;
}

// the NIIuwpV Some function
function build_NIIuwpV_SomeFunc(build_NIIuwpV)
{
	// [8234] set the function logic
	if (build_NIIuwpV == 2)
	{
		return true;
	}
	return false;
}

// the mBQbiOv function
function mBQbiOv(source_mBQbiOv,build_mBQbiOv)
{
	if (isSet(source_mBQbiOv) && source_mBQbiOv.constructor !== Array)
	{
		var temp_mBQbiOv = source_mBQbiOv;
		var source_mBQbiOv = [];
		source_mBQbiOv.push(temp_mBQbiOv);
	}
	else if (!isSet(source_mBQbiOv))
	{
		var source_mBQbiOv = [];
	}
	var source = source_mBQbiOv.some(source_mBQbiOv_SomeFunc);

	if (isSet(build_mBQbiOv) && build_mBQbiOv.constructor !== Array)
	{
		var temp_mBQbiOv = build_mBQbiOv;
		var build_mBQbiOv = [];
		build_mBQbiOv.push(temp_mBQbiOv);
	}
	else if (!isSet(build_mBQbiOv))
	{
		var build_mBQbiOv = [];
	}
	var build = build_mBQbiOv.some(build_mBQbiOv_SomeFunc);


	// [8247] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_mBQbiOvPmP_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_mBQbiOvPmP_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_mBQbiOvPmP_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_mBQbiOvPmP_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the mBQbiOv Some function
function source_mBQbiOv_SomeFunc(source_mBQbiOv)
{
	// [8234] set the function logic
	if (source_mBQbiOv == 2)
	{
		return true;
	}
	return false;
}

// the mBQbiOv Some function
function build_mBQbiOv_SomeFunc(build_mBQbiOv)
{
	// [8234] set the function logic
	if (build_mBQbiOv == 1)
	{
		return true;
	}
	return false;
}

// the wsmVGID function
function wsmVGID(build_wsmVGID,source_wsmVGID)
{
	if (isSet(build_wsmVGID) && build_wsmVGID.constructor !== Array)
	{
		var temp_wsmVGID = build_wsmVGID;
		var build_wsmVGID = [];
		build_wsmVGID.push(temp_wsmVGID);
	}
	else if (!isSet(build_wsmVGID))
	{
		var build_wsmVGID = [];
	}
	var build = build_wsmVGID.some(build_wsmVGID_SomeFunc);

	if (isSet(source_wsmVGID) && source_wsmVGID.constructor !== Array)
	{
		var temp_wsmVGID = source_wsmVGID;
		var source_wsmVGID = [];
		source_wsmVGID.push(temp_wsmVGID);
	}
	else if (!isSet(source_wsmVGID))
	{
		var source_wsmVGID = [];
	}
	var source = source_wsmVGID.some(source_wsmVGID_SomeFunc);


	// [8247] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_wsmVGIDntn_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_wsmVGIDntn_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_wsmVGIDntn_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_wsmVGIDntn_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the wsmVGID Some function
function build_wsmVGID_SomeFunc(build_wsmVGID)
{
	// [8234] set the function logic
	if (build_wsmVGID == 1)
	{
		return true;
	}
	return false;
}

// the wsmVGID Some function
function source_wsmVGID_SomeFunc(source_wsmVGID)
{
	// [8234] set the function logic
	if (source_wsmVGID == 2)
	{
		return true;
	}
	return false;
}

// the ENzGNYP function
function ENzGNYP(source_ENzGNYP)
{
	if (isSet(source_ENzGNYP) && source_ENzGNYP.constructor !== Array)
	{
		var temp_ENzGNYP = source_ENzGNYP;
		var source_ENzGNYP = [];
		source_ENzGNYP.push(temp_ENzGNYP);
	}
	else if (!isSet(source_ENzGNYP))
	{
		var source_ENzGNYP = [];
	}
	var source = source_ENzGNYP.some(source_ENzGNYP_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_ENzGNYPiLA_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_ENzGNYPiLA_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_ENzGNYPiLA_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_ENzGNYPiLA_required = true;
		}
	}
}

// the ENzGNYP Some function
function source_ENzGNYP_SomeFunc(source_ENzGNYP)
{
	// [8234] set the function logic
	if (source_ENzGNYP == 1)
	{
		return true;
	}
	return false;
}

// the YYiOanx function
function YYiOanx(source_YYiOanx)
{
	if (isSet(source_YYiOanx) && source_YYiOanx.constructor !== Array)
	{
		var temp_YYiOanx = source_YYiOanx;
		var source_YYiOanx = [];
		source_YYiOanx.push(temp_YYiOanx);
	}
	else if (!isSet(source_YYiOanx))
	{
		var source_YYiOanx = [];
	}
	var source = source_YYiOanx.some(source_YYiOanx_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_YYiOanxvgR_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_YYiOanxvgR_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_YYiOanxvgR_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_YYiOanxvgR_required = true;
		}
	}
}

// the YYiOanx Some function
function source_YYiOanx_SomeFunc(source_YYiOanx)
{
	// [8234] set the function logic
	if (source_YYiOanx == 3)
	{
		return true;
	}
	return false;
}

// the rCpyqQl function
function rCpyqQl(link_type_rCpyqQl)
{
	// [8269] set the function logic
	if (link_type_rCpyqQl == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the RgLeKCE function
function RgLeKCE(link_type_RgLeKCE)
{
	// [8269] set the function logic
	if (link_type_RgLeKCE == 1)
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

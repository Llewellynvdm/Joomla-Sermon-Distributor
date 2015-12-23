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
jform_iZRZzQyybj_required = false;
jform_EnSYbTjjQc_required = false;
jform_jqQIKTSzxn_required = false;
jform_lAIfOHIxFe_required = false;
jform_UkrHisGoCy_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_iZRZzQy = jQuery("#jform_source").val();
	iZRZzQy(source_iZRZzQy);

	var source_WwtGkmR = jQuery("#jform_source").val();
	var build_WwtGkmR = jQuery("#jform_build input[type='radio']:checked").val();
	WwtGkmR(source_WwtGkmR,build_WwtGkmR);

	var source_EnSYbTj = jQuery("#jform_source").val();
	var build_EnSYbTj = jQuery("#jform_build input[type='radio']:checked").val();
	EnSYbTj(source_EnSYbTj,build_EnSYbTj);

	var build_jqQIKTS = jQuery("#jform_build input[type='radio']:checked").val();
	var source_jqQIKTS = jQuery("#jform_source").val();
	jqQIKTS(build_jqQIKTS,source_jqQIKTS);

	var source_lAIfOHI = jQuery("#jform_source").val();
	lAIfOHI(source_lAIfOHI);

	var source_UkrHisG = jQuery("#jform_source").val();
	UkrHisG(source_UkrHisG);

	var link_type_xJKoumd = jQuery("#jform_link_type input[type='radio']:checked").val();
	xJKoumd(link_type_xJKoumd);

	var link_type_oUuxich = jQuery("#jform_link_type input[type='radio']:checked").val();
	oUuxich(link_type_oUuxich);
});

// the iZRZzQy function
function iZRZzQy(source_iZRZzQy)
{
	if (isSet(source_iZRZzQy) && source_iZRZzQy.constructor !== Array)
	{
		var temp_iZRZzQy = source_iZRZzQy;
		var source_iZRZzQy = [];
		source_iZRZzQy.push(temp_iZRZzQy);
	}
	else if (!isSet(source_iZRZzQy))
	{
		var source_iZRZzQy = [];
	}
	var source = source_iZRZzQy.some(source_iZRZzQy_SomeFunc);


	// [8238] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_iZRZzQyybj_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_iZRZzQyybj_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_iZRZzQyybj_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_iZRZzQyybj_required = true;
		}
	}
}

// the iZRZzQy Some function
function source_iZRZzQy_SomeFunc(source_iZRZzQy)
{
	// [8225] set the function logic
	if (source_iZRZzQy == 2)
	{
		return true;
	}
	return false;
}

// the WwtGkmR function
function WwtGkmR(source_WwtGkmR,build_WwtGkmR)
{
	if (isSet(source_WwtGkmR) && source_WwtGkmR.constructor !== Array)
	{
		var temp_WwtGkmR = source_WwtGkmR;
		var source_WwtGkmR = [];
		source_WwtGkmR.push(temp_WwtGkmR);
	}
	else if (!isSet(source_WwtGkmR))
	{
		var source_WwtGkmR = [];
	}
	var source = source_WwtGkmR.some(source_WwtGkmR_SomeFunc);

	if (isSet(build_WwtGkmR) && build_WwtGkmR.constructor !== Array)
	{
		var temp_WwtGkmR = build_WwtGkmR;
		var build_WwtGkmR = [];
		build_WwtGkmR.push(temp_WwtGkmR);
	}
	else if (!isSet(build_WwtGkmR))
	{
		var build_WwtGkmR = [];
	}
	var build = build_WwtGkmR.some(build_WwtGkmR_SomeFunc);


	// [8238] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the WwtGkmR Some function
function source_WwtGkmR_SomeFunc(source_WwtGkmR)
{
	// [8225] set the function logic
	if (source_WwtGkmR == 2)
	{
		return true;
	}
	return false;
}

// the WwtGkmR Some function
function build_WwtGkmR_SomeFunc(build_WwtGkmR)
{
	// [8225] set the function logic
	if (build_WwtGkmR == 2)
	{
		return true;
	}
	return false;
}

// the EnSYbTj function
function EnSYbTj(source_EnSYbTj,build_EnSYbTj)
{
	if (isSet(source_EnSYbTj) && source_EnSYbTj.constructor !== Array)
	{
		var temp_EnSYbTj = source_EnSYbTj;
		var source_EnSYbTj = [];
		source_EnSYbTj.push(temp_EnSYbTj);
	}
	else if (!isSet(source_EnSYbTj))
	{
		var source_EnSYbTj = [];
	}
	var source = source_EnSYbTj.some(source_EnSYbTj_SomeFunc);

	if (isSet(build_EnSYbTj) && build_EnSYbTj.constructor !== Array)
	{
		var temp_EnSYbTj = build_EnSYbTj;
		var build_EnSYbTj = [];
		build_EnSYbTj.push(temp_EnSYbTj);
	}
	else if (!isSet(build_EnSYbTj))
	{
		var build_EnSYbTj = [];
	}
	var build = build_EnSYbTj.some(build_EnSYbTj_SomeFunc);


	// [8238] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_EnSYbTjjQc_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_EnSYbTjjQc_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_EnSYbTjjQc_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_EnSYbTjjQc_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the EnSYbTj Some function
function source_EnSYbTj_SomeFunc(source_EnSYbTj)
{
	// [8225] set the function logic
	if (source_EnSYbTj == 2)
	{
		return true;
	}
	return false;
}

// the EnSYbTj Some function
function build_EnSYbTj_SomeFunc(build_EnSYbTj)
{
	// [8225] set the function logic
	if (build_EnSYbTj == 1)
	{
		return true;
	}
	return false;
}

// the jqQIKTS function
function jqQIKTS(build_jqQIKTS,source_jqQIKTS)
{
	if (isSet(build_jqQIKTS) && build_jqQIKTS.constructor !== Array)
	{
		var temp_jqQIKTS = build_jqQIKTS;
		var build_jqQIKTS = [];
		build_jqQIKTS.push(temp_jqQIKTS);
	}
	else if (!isSet(build_jqQIKTS))
	{
		var build_jqQIKTS = [];
	}
	var build = build_jqQIKTS.some(build_jqQIKTS_SomeFunc);

	if (isSet(source_jqQIKTS) && source_jqQIKTS.constructor !== Array)
	{
		var temp_jqQIKTS = source_jqQIKTS;
		var source_jqQIKTS = [];
		source_jqQIKTS.push(temp_jqQIKTS);
	}
	else if (!isSet(source_jqQIKTS))
	{
		var source_jqQIKTS = [];
	}
	var source = source_jqQIKTS.some(source_jqQIKTS_SomeFunc);


	// [8238] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_jqQIKTSzxn_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_jqQIKTSzxn_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_jqQIKTSzxn_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_jqQIKTSzxn_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the jqQIKTS Some function
function build_jqQIKTS_SomeFunc(build_jqQIKTS)
{
	// [8225] set the function logic
	if (build_jqQIKTS == 1)
	{
		return true;
	}
	return false;
}

// the jqQIKTS Some function
function source_jqQIKTS_SomeFunc(source_jqQIKTS)
{
	// [8225] set the function logic
	if (source_jqQIKTS == 2)
	{
		return true;
	}
	return false;
}

// the lAIfOHI function
function lAIfOHI(source_lAIfOHI)
{
	if (isSet(source_lAIfOHI) && source_lAIfOHI.constructor !== Array)
	{
		var temp_lAIfOHI = source_lAIfOHI;
		var source_lAIfOHI = [];
		source_lAIfOHI.push(temp_lAIfOHI);
	}
	else if (!isSet(source_lAIfOHI))
	{
		var source_lAIfOHI = [];
	}
	var source = source_lAIfOHI.some(source_lAIfOHI_SomeFunc);


	// [8238] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_lAIfOHIxFe_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_lAIfOHIxFe_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_lAIfOHIxFe_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_lAIfOHIxFe_required = true;
		}
	}
}

// the lAIfOHI Some function
function source_lAIfOHI_SomeFunc(source_lAIfOHI)
{
	// [8225] set the function logic
	if (source_lAIfOHI == 1)
	{
		return true;
	}
	return false;
}

// the UkrHisG function
function UkrHisG(source_UkrHisG)
{
	if (isSet(source_UkrHisG) && source_UkrHisG.constructor !== Array)
	{
		var temp_UkrHisG = source_UkrHisG;
		var source_UkrHisG = [];
		source_UkrHisG.push(temp_UkrHisG);
	}
	else if (!isSet(source_UkrHisG))
	{
		var source_UkrHisG = [];
	}
	var source = source_UkrHisG.some(source_UkrHisG_SomeFunc);


	// [8238] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_UkrHisGoCy_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_UkrHisGoCy_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_UkrHisGoCy_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_UkrHisGoCy_required = true;
		}
	}
}

// the UkrHisG Some function
function source_UkrHisG_SomeFunc(source_UkrHisG)
{
	// [8225] set the function logic
	if (source_UkrHisG == 3)
	{
		return true;
	}
	return false;
}

// the xJKoumd function
function xJKoumd(link_type_xJKoumd)
{
	// [8260] set the function logic
	if (link_type_xJKoumd == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the oUuxich function
function oUuxich(link_type_oUuxich)
{
	// [8260] set the function logic
	if (link_type_oUuxich == 1)
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

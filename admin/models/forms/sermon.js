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
	@build			21st February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_faEnFllAiT_required = false;
jform_DQWFhNVlPF_required = false;
jform_uoNLWYHarm_required = false;
jform_WZlRgqxKFH_required = false;
jform_ZkxVREMNVv_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_faEnFll = jQuery("#jform_source").val();
	faEnFll(source_faEnFll);

	var source_dmLWMYS = jQuery("#jform_source").val();
	var build_dmLWMYS = jQuery("#jform_build input[type='radio']:checked").val();
	dmLWMYS(source_dmLWMYS,build_dmLWMYS);

	var source_DQWFhNV = jQuery("#jform_source").val();
	var build_DQWFhNV = jQuery("#jform_build input[type='radio']:checked").val();
	DQWFhNV(source_DQWFhNV,build_DQWFhNV);

	var build_uoNLWYH = jQuery("#jform_build input[type='radio']:checked").val();
	var source_uoNLWYH = jQuery("#jform_source").val();
	uoNLWYH(build_uoNLWYH,source_uoNLWYH);

	var source_WZlRgqx = jQuery("#jform_source").val();
	WZlRgqx(source_WZlRgqx);

	var source_ZkxVREM = jQuery("#jform_source").val();
	ZkxVREM(source_ZkxVREM);

	var link_type_muRdbJc = jQuery("#jform_link_type input[type='radio']:checked").val();
	muRdbJc(link_type_muRdbJc);

	var link_type_CJtVLni = jQuery("#jform_link_type input[type='radio']:checked").val();
	CJtVLni(link_type_CJtVLni);
});

// the faEnFll function
function faEnFll(source_faEnFll)
{
	if (isSet(source_faEnFll) && source_faEnFll.constructor !== Array)
	{
		var temp_faEnFll = source_faEnFll;
		var source_faEnFll = [];
		source_faEnFll.push(temp_faEnFll);
	}
	else if (!isSet(source_faEnFll))
	{
		var source_faEnFll = [];
	}
	var source = source_faEnFll.some(source_faEnFll_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_faEnFllAiT_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_faEnFllAiT_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_faEnFllAiT_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_faEnFllAiT_required = true;
		}
	}
}

// the faEnFll Some function
function source_faEnFll_SomeFunc(source_faEnFll)
{
	// [8661] set the function logic
	if (source_faEnFll == 2)
	{
		return true;
	}
	return false;
}

// the dmLWMYS function
function dmLWMYS(source_dmLWMYS,build_dmLWMYS)
{
	if (isSet(source_dmLWMYS) && source_dmLWMYS.constructor !== Array)
	{
		var temp_dmLWMYS = source_dmLWMYS;
		var source_dmLWMYS = [];
		source_dmLWMYS.push(temp_dmLWMYS);
	}
	else if (!isSet(source_dmLWMYS))
	{
		var source_dmLWMYS = [];
	}
	var source = source_dmLWMYS.some(source_dmLWMYS_SomeFunc);

	if (isSet(build_dmLWMYS) && build_dmLWMYS.constructor !== Array)
	{
		var temp_dmLWMYS = build_dmLWMYS;
		var build_dmLWMYS = [];
		build_dmLWMYS.push(temp_dmLWMYS);
	}
	else if (!isSet(build_dmLWMYS))
	{
		var build_dmLWMYS = [];
	}
	var build = build_dmLWMYS.some(build_dmLWMYS_SomeFunc);


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

// the dmLWMYS Some function
function source_dmLWMYS_SomeFunc(source_dmLWMYS)
{
	// [8661] set the function logic
	if (source_dmLWMYS == 2)
	{
		return true;
	}
	return false;
}

// the dmLWMYS Some function
function build_dmLWMYS_SomeFunc(build_dmLWMYS)
{
	// [8661] set the function logic
	if (build_dmLWMYS == 2)
	{
		return true;
	}
	return false;
}

// the DQWFhNV function
function DQWFhNV(source_DQWFhNV,build_DQWFhNV)
{
	if (isSet(source_DQWFhNV) && source_DQWFhNV.constructor !== Array)
	{
		var temp_DQWFhNV = source_DQWFhNV;
		var source_DQWFhNV = [];
		source_DQWFhNV.push(temp_DQWFhNV);
	}
	else if (!isSet(source_DQWFhNV))
	{
		var source_DQWFhNV = [];
	}
	var source = source_DQWFhNV.some(source_DQWFhNV_SomeFunc);

	if (isSet(build_DQWFhNV) && build_DQWFhNV.constructor !== Array)
	{
		var temp_DQWFhNV = build_DQWFhNV;
		var build_DQWFhNV = [];
		build_DQWFhNV.push(temp_DQWFhNV);
	}
	else if (!isSet(build_DQWFhNV))
	{
		var build_DQWFhNV = [];
	}
	var build = build_DQWFhNV.some(build_DQWFhNV_SomeFunc);


	// [8674] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_DQWFhNVlPF_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_DQWFhNVlPF_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_DQWFhNVlPF_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_DQWFhNVlPF_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the DQWFhNV Some function
function source_DQWFhNV_SomeFunc(source_DQWFhNV)
{
	// [8661] set the function logic
	if (source_DQWFhNV == 2)
	{
		return true;
	}
	return false;
}

// the DQWFhNV Some function
function build_DQWFhNV_SomeFunc(build_DQWFhNV)
{
	// [8661] set the function logic
	if (build_DQWFhNV == 1)
	{
		return true;
	}
	return false;
}

// the uoNLWYH function
function uoNLWYH(build_uoNLWYH,source_uoNLWYH)
{
	if (isSet(build_uoNLWYH) && build_uoNLWYH.constructor !== Array)
	{
		var temp_uoNLWYH = build_uoNLWYH;
		var build_uoNLWYH = [];
		build_uoNLWYH.push(temp_uoNLWYH);
	}
	else if (!isSet(build_uoNLWYH))
	{
		var build_uoNLWYH = [];
	}
	var build = build_uoNLWYH.some(build_uoNLWYH_SomeFunc);

	if (isSet(source_uoNLWYH) && source_uoNLWYH.constructor !== Array)
	{
		var temp_uoNLWYH = source_uoNLWYH;
		var source_uoNLWYH = [];
		source_uoNLWYH.push(temp_uoNLWYH);
	}
	else if (!isSet(source_uoNLWYH))
	{
		var source_uoNLWYH = [];
	}
	var source = source_uoNLWYH.some(source_uoNLWYH_SomeFunc);


	// [8674] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_uoNLWYHarm_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_uoNLWYHarm_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_uoNLWYHarm_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_uoNLWYHarm_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the uoNLWYH Some function
function build_uoNLWYH_SomeFunc(build_uoNLWYH)
{
	// [8661] set the function logic
	if (build_uoNLWYH == 1)
	{
		return true;
	}
	return false;
}

// the uoNLWYH Some function
function source_uoNLWYH_SomeFunc(source_uoNLWYH)
{
	// [8661] set the function logic
	if (source_uoNLWYH == 2)
	{
		return true;
	}
	return false;
}

// the WZlRgqx function
function WZlRgqx(source_WZlRgqx)
{
	if (isSet(source_WZlRgqx) && source_WZlRgqx.constructor !== Array)
	{
		var temp_WZlRgqx = source_WZlRgqx;
		var source_WZlRgqx = [];
		source_WZlRgqx.push(temp_WZlRgqx);
	}
	else if (!isSet(source_WZlRgqx))
	{
		var source_WZlRgqx = [];
	}
	var source = source_WZlRgqx.some(source_WZlRgqx_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_WZlRgqxKFH_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_WZlRgqxKFH_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_WZlRgqxKFH_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_WZlRgqxKFH_required = true;
		}
	}
}

// the WZlRgqx Some function
function source_WZlRgqx_SomeFunc(source_WZlRgqx)
{
	// [8661] set the function logic
	if (source_WZlRgqx == 1)
	{
		return true;
	}
	return false;
}

// the ZkxVREM function
function ZkxVREM(source_ZkxVREM)
{
	if (isSet(source_ZkxVREM) && source_ZkxVREM.constructor !== Array)
	{
		var temp_ZkxVREM = source_ZkxVREM;
		var source_ZkxVREM = [];
		source_ZkxVREM.push(temp_ZkxVREM);
	}
	else if (!isSet(source_ZkxVREM))
	{
		var source_ZkxVREM = [];
	}
	var source = source_ZkxVREM.some(source_ZkxVREM_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_ZkxVREMNVv_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_ZkxVREMNVv_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_ZkxVREMNVv_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_ZkxVREMNVv_required = true;
		}
	}
}

// the ZkxVREM Some function
function source_ZkxVREM_SomeFunc(source_ZkxVREM)
{
	// [8661] set the function logic
	if (source_ZkxVREM == 3)
	{
		return true;
	}
	return false;
}

// the muRdbJc function
function muRdbJc(link_type_muRdbJc)
{
	// [8696] set the function logic
	if (link_type_muRdbJc == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the CJtVLni function
function CJtVLni(link_type_CJtVLni)
{
	// [8696] set the function logic
	if (link_type_CJtVLni == 1)
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

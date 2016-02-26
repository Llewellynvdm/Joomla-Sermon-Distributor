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
	@build			26th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_LeYQWzTzQy_required = false;
jform_nqLwYLVFNU_required = false;
jform_vGBwfrOvWo_required = false;
jform_sqkUWYdCaz_required = false;
jform_htLRPCvMmp_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_LeYQWzT = jQuery("#jform_source").val();
	LeYQWzT(source_LeYQWzT);

	var source_qFuRtXH = jQuery("#jform_source").val();
	var build_qFuRtXH = jQuery("#jform_build input[type='radio']:checked").val();
	qFuRtXH(source_qFuRtXH,build_qFuRtXH);

	var source_nqLwYLV = jQuery("#jform_source").val();
	var build_nqLwYLV = jQuery("#jform_build input[type='radio']:checked").val();
	nqLwYLV(source_nqLwYLV,build_nqLwYLV);

	var build_vGBwfrO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vGBwfrO = jQuery("#jform_source").val();
	vGBwfrO(build_vGBwfrO,source_vGBwfrO);

	var source_sqkUWYd = jQuery("#jform_source").val();
	sqkUWYd(source_sqkUWYd);

	var source_htLRPCv = jQuery("#jform_source").val();
	htLRPCv(source_htLRPCv);

	var link_type_BPUcgat = jQuery("#jform_link_type input[type='radio']:checked").val();
	BPUcgat(link_type_BPUcgat);

	var link_type_TvqhKCs = jQuery("#jform_link_type input[type='radio']:checked").val();
	TvqhKCs(link_type_TvqhKCs);
});

// the LeYQWzT function
function LeYQWzT(source_LeYQWzT)
{
	if (isSet(source_LeYQWzT) && source_LeYQWzT.constructor !== Array)
	{
		var temp_LeYQWzT = source_LeYQWzT;
		var source_LeYQWzT = [];
		source_LeYQWzT.push(temp_LeYQWzT);
	}
	else if (!isSet(source_LeYQWzT))
	{
		var source_LeYQWzT = [];
	}
	var source = source_LeYQWzT.some(source_LeYQWzT_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_LeYQWzTzQy_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_LeYQWzTzQy_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_LeYQWzTzQy_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_LeYQWzTzQy_required = true;
		}
	}
}

// the LeYQWzT Some function
function source_LeYQWzT_SomeFunc(source_LeYQWzT)
{
	// [Interpretation 7291] set the function logic
	if (source_LeYQWzT == 2)
	{
		return true;
	}
	return false;
}

// the qFuRtXH function
function qFuRtXH(source_qFuRtXH,build_qFuRtXH)
{
	if (isSet(source_qFuRtXH) && source_qFuRtXH.constructor !== Array)
	{
		var temp_qFuRtXH = source_qFuRtXH;
		var source_qFuRtXH = [];
		source_qFuRtXH.push(temp_qFuRtXH);
	}
	else if (!isSet(source_qFuRtXH))
	{
		var source_qFuRtXH = [];
	}
	var source = source_qFuRtXH.some(source_qFuRtXH_SomeFunc);

	if (isSet(build_qFuRtXH) && build_qFuRtXH.constructor !== Array)
	{
		var temp_qFuRtXH = build_qFuRtXH;
		var build_qFuRtXH = [];
		build_qFuRtXH.push(temp_qFuRtXH);
	}
	else if (!isSet(build_qFuRtXH))
	{
		var build_qFuRtXH = [];
	}
	var build = build_qFuRtXH.some(build_qFuRtXH_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the qFuRtXH Some function
function source_qFuRtXH_SomeFunc(source_qFuRtXH)
{
	// [Interpretation 7291] set the function logic
	if (source_qFuRtXH == 2)
	{
		return true;
	}
	return false;
}

// the qFuRtXH Some function
function build_qFuRtXH_SomeFunc(build_qFuRtXH)
{
	// [Interpretation 7291] set the function logic
	if (build_qFuRtXH == 2)
	{
		return true;
	}
	return false;
}

// the nqLwYLV function
function nqLwYLV(source_nqLwYLV,build_nqLwYLV)
{
	if (isSet(source_nqLwYLV) && source_nqLwYLV.constructor !== Array)
	{
		var temp_nqLwYLV = source_nqLwYLV;
		var source_nqLwYLV = [];
		source_nqLwYLV.push(temp_nqLwYLV);
	}
	else if (!isSet(source_nqLwYLV))
	{
		var source_nqLwYLV = [];
	}
	var source = source_nqLwYLV.some(source_nqLwYLV_SomeFunc);

	if (isSet(build_nqLwYLV) && build_nqLwYLV.constructor !== Array)
	{
		var temp_nqLwYLV = build_nqLwYLV;
		var build_nqLwYLV = [];
		build_nqLwYLV.push(temp_nqLwYLV);
	}
	else if (!isSet(build_nqLwYLV))
	{
		var build_nqLwYLV = [];
	}
	var build = build_nqLwYLV.some(build_nqLwYLV_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_nqLwYLVFNU_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_nqLwYLVFNU_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_nqLwYLVFNU_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_nqLwYLVFNU_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the nqLwYLV Some function
function source_nqLwYLV_SomeFunc(source_nqLwYLV)
{
	// [Interpretation 7291] set the function logic
	if (source_nqLwYLV == 2)
	{
		return true;
	}
	return false;
}

// the nqLwYLV Some function
function build_nqLwYLV_SomeFunc(build_nqLwYLV)
{
	// [Interpretation 7291] set the function logic
	if (build_nqLwYLV == 1)
	{
		return true;
	}
	return false;
}

// the vGBwfrO function
function vGBwfrO(build_vGBwfrO,source_vGBwfrO)
{
	if (isSet(build_vGBwfrO) && build_vGBwfrO.constructor !== Array)
	{
		var temp_vGBwfrO = build_vGBwfrO;
		var build_vGBwfrO = [];
		build_vGBwfrO.push(temp_vGBwfrO);
	}
	else if (!isSet(build_vGBwfrO))
	{
		var build_vGBwfrO = [];
	}
	var build = build_vGBwfrO.some(build_vGBwfrO_SomeFunc);

	if (isSet(source_vGBwfrO) && source_vGBwfrO.constructor !== Array)
	{
		var temp_vGBwfrO = source_vGBwfrO;
		var source_vGBwfrO = [];
		source_vGBwfrO.push(temp_vGBwfrO);
	}
	else if (!isSet(source_vGBwfrO))
	{
		var source_vGBwfrO = [];
	}
	var source = source_vGBwfrO.some(source_vGBwfrO_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_vGBwfrOvWo_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_vGBwfrOvWo_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_vGBwfrOvWo_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_vGBwfrOvWo_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the vGBwfrO Some function
function build_vGBwfrO_SomeFunc(build_vGBwfrO)
{
	// [Interpretation 7291] set the function logic
	if (build_vGBwfrO == 1)
	{
		return true;
	}
	return false;
}

// the vGBwfrO Some function
function source_vGBwfrO_SomeFunc(source_vGBwfrO)
{
	// [Interpretation 7291] set the function logic
	if (source_vGBwfrO == 2)
	{
		return true;
	}
	return false;
}

// the sqkUWYd function
function sqkUWYd(source_sqkUWYd)
{
	if (isSet(source_sqkUWYd) && source_sqkUWYd.constructor !== Array)
	{
		var temp_sqkUWYd = source_sqkUWYd;
		var source_sqkUWYd = [];
		source_sqkUWYd.push(temp_sqkUWYd);
	}
	else if (!isSet(source_sqkUWYd))
	{
		var source_sqkUWYd = [];
	}
	var source = source_sqkUWYd.some(source_sqkUWYd_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_sqkUWYdCaz_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_sqkUWYdCaz_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_sqkUWYdCaz_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_sqkUWYdCaz_required = true;
		}
	}
}

// the sqkUWYd Some function
function source_sqkUWYd_SomeFunc(source_sqkUWYd)
{
	// [Interpretation 7291] set the function logic
	if (source_sqkUWYd == 1)
	{
		return true;
	}
	return false;
}

// the htLRPCv function
function htLRPCv(source_htLRPCv)
{
	if (isSet(source_htLRPCv) && source_htLRPCv.constructor !== Array)
	{
		var temp_htLRPCv = source_htLRPCv;
		var source_htLRPCv = [];
		source_htLRPCv.push(temp_htLRPCv);
	}
	else if (!isSet(source_htLRPCv))
	{
		var source_htLRPCv = [];
	}
	var source = source_htLRPCv.some(source_htLRPCv_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_htLRPCvMmp_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_htLRPCvMmp_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_htLRPCvMmp_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_htLRPCvMmp_required = true;
		}
	}
}

// the htLRPCv Some function
function source_htLRPCv_SomeFunc(source_htLRPCv)
{
	// [Interpretation 7291] set the function logic
	if (source_htLRPCv == 3)
	{
		return true;
	}
	return false;
}

// the BPUcgat function
function BPUcgat(link_type_BPUcgat)
{
	// [Interpretation 7326] set the function logic
	if (link_type_BPUcgat == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the TvqhKCs function
function TvqhKCs(link_type_TvqhKCs)
{
	// [Interpretation 7326] set the function logic
	if (link_type_TvqhKCs == 1)
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

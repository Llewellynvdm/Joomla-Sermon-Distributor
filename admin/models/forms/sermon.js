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
	@build			5th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_FEFzgVWDPW_required = false;
jform_pXwBipvOab_required = false;
jform_njVgKMfRGW_required = false;
jform_BGnLThLamW_required = false;
jform_kwSAfbWIQX_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_FEFzgVW = jQuery("#jform_source").val();
	FEFzgVW(source_FEFzgVW);

	var source_kXSJVyd = jQuery("#jform_source").val();
	var build_kXSJVyd = jQuery("#jform_build input[type='radio']:checked").val();
	kXSJVyd(source_kXSJVyd,build_kXSJVyd);

	var source_pXwBipv = jQuery("#jform_source").val();
	var build_pXwBipv = jQuery("#jform_build input[type='radio']:checked").val();
	pXwBipv(source_pXwBipv,build_pXwBipv);

	var build_njVgKMf = jQuery("#jform_build input[type='radio']:checked").val();
	var source_njVgKMf = jQuery("#jform_source").val();
	njVgKMf(build_njVgKMf,source_njVgKMf);

	var source_BGnLThL = jQuery("#jform_source").val();
	BGnLThL(source_BGnLThL);

	var source_kwSAfbW = jQuery("#jform_source").val();
	kwSAfbW(source_kwSAfbW);

	var link_type_KcgziOs = jQuery("#jform_link_type input[type='radio']:checked").val();
	KcgziOs(link_type_KcgziOs);

	var link_type_mIYnoFx = jQuery("#jform_link_type input[type='radio']:checked").val();
	mIYnoFx(link_type_mIYnoFx);
});

// the FEFzgVW function
function FEFzgVW(source_FEFzgVW)
{
	if (isSet(source_FEFzgVW) && source_FEFzgVW.constructor !== Array)
	{
		var temp_FEFzgVW = source_FEFzgVW;
		var source_FEFzgVW = [];
		source_FEFzgVW.push(temp_FEFzgVW);
	}
	else if (!isSet(source_FEFzgVW))
	{
		var source_FEFzgVW = [];
	}
	var source = source_FEFzgVW.some(source_FEFzgVW_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_FEFzgVWDPW_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_FEFzgVWDPW_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_FEFzgVWDPW_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_FEFzgVWDPW_required = true;
		}
	}
}

// the FEFzgVW Some function
function source_FEFzgVW_SomeFunc(source_FEFzgVW)
{
	// [8234] set the function logic
	if (source_FEFzgVW == 2)
	{
		return true;
	}
	return false;
}

// the kXSJVyd function
function kXSJVyd(source_kXSJVyd,build_kXSJVyd)
{
	if (isSet(source_kXSJVyd) && source_kXSJVyd.constructor !== Array)
	{
		var temp_kXSJVyd = source_kXSJVyd;
		var source_kXSJVyd = [];
		source_kXSJVyd.push(temp_kXSJVyd);
	}
	else if (!isSet(source_kXSJVyd))
	{
		var source_kXSJVyd = [];
	}
	var source = source_kXSJVyd.some(source_kXSJVyd_SomeFunc);

	if (isSet(build_kXSJVyd) && build_kXSJVyd.constructor !== Array)
	{
		var temp_kXSJVyd = build_kXSJVyd;
		var build_kXSJVyd = [];
		build_kXSJVyd.push(temp_kXSJVyd);
	}
	else if (!isSet(build_kXSJVyd))
	{
		var build_kXSJVyd = [];
	}
	var build = build_kXSJVyd.some(build_kXSJVyd_SomeFunc);


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

// the kXSJVyd Some function
function source_kXSJVyd_SomeFunc(source_kXSJVyd)
{
	// [8234] set the function logic
	if (source_kXSJVyd == 2)
	{
		return true;
	}
	return false;
}

// the kXSJVyd Some function
function build_kXSJVyd_SomeFunc(build_kXSJVyd)
{
	// [8234] set the function logic
	if (build_kXSJVyd == 2)
	{
		return true;
	}
	return false;
}

// the pXwBipv function
function pXwBipv(source_pXwBipv,build_pXwBipv)
{
	if (isSet(source_pXwBipv) && source_pXwBipv.constructor !== Array)
	{
		var temp_pXwBipv = source_pXwBipv;
		var source_pXwBipv = [];
		source_pXwBipv.push(temp_pXwBipv);
	}
	else if (!isSet(source_pXwBipv))
	{
		var source_pXwBipv = [];
	}
	var source = source_pXwBipv.some(source_pXwBipv_SomeFunc);

	if (isSet(build_pXwBipv) && build_pXwBipv.constructor !== Array)
	{
		var temp_pXwBipv = build_pXwBipv;
		var build_pXwBipv = [];
		build_pXwBipv.push(temp_pXwBipv);
	}
	else if (!isSet(build_pXwBipv))
	{
		var build_pXwBipv = [];
	}
	var build = build_pXwBipv.some(build_pXwBipv_SomeFunc);


	// [8247] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_pXwBipvOab_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_pXwBipvOab_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_pXwBipvOab_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_pXwBipvOab_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the pXwBipv Some function
function source_pXwBipv_SomeFunc(source_pXwBipv)
{
	// [8234] set the function logic
	if (source_pXwBipv == 2)
	{
		return true;
	}
	return false;
}

// the pXwBipv Some function
function build_pXwBipv_SomeFunc(build_pXwBipv)
{
	// [8234] set the function logic
	if (build_pXwBipv == 1)
	{
		return true;
	}
	return false;
}

// the njVgKMf function
function njVgKMf(build_njVgKMf,source_njVgKMf)
{
	if (isSet(build_njVgKMf) && build_njVgKMf.constructor !== Array)
	{
		var temp_njVgKMf = build_njVgKMf;
		var build_njVgKMf = [];
		build_njVgKMf.push(temp_njVgKMf);
	}
	else if (!isSet(build_njVgKMf))
	{
		var build_njVgKMf = [];
	}
	var build = build_njVgKMf.some(build_njVgKMf_SomeFunc);

	if (isSet(source_njVgKMf) && source_njVgKMf.constructor !== Array)
	{
		var temp_njVgKMf = source_njVgKMf;
		var source_njVgKMf = [];
		source_njVgKMf.push(temp_njVgKMf);
	}
	else if (!isSet(source_njVgKMf))
	{
		var source_njVgKMf = [];
	}
	var source = source_njVgKMf.some(source_njVgKMf_SomeFunc);


	// [8247] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_njVgKMfRGW_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_njVgKMfRGW_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_njVgKMfRGW_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_njVgKMfRGW_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the njVgKMf Some function
function build_njVgKMf_SomeFunc(build_njVgKMf)
{
	// [8234] set the function logic
	if (build_njVgKMf == 1)
	{
		return true;
	}
	return false;
}

// the njVgKMf Some function
function source_njVgKMf_SomeFunc(source_njVgKMf)
{
	// [8234] set the function logic
	if (source_njVgKMf == 2)
	{
		return true;
	}
	return false;
}

// the BGnLThL function
function BGnLThL(source_BGnLThL)
{
	if (isSet(source_BGnLThL) && source_BGnLThL.constructor !== Array)
	{
		var temp_BGnLThL = source_BGnLThL;
		var source_BGnLThL = [];
		source_BGnLThL.push(temp_BGnLThL);
	}
	else if (!isSet(source_BGnLThL))
	{
		var source_BGnLThL = [];
	}
	var source = source_BGnLThL.some(source_BGnLThL_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_BGnLThLamW_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_BGnLThLamW_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_BGnLThLamW_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_BGnLThLamW_required = true;
		}
	}
}

// the BGnLThL Some function
function source_BGnLThL_SomeFunc(source_BGnLThL)
{
	// [8234] set the function logic
	if (source_BGnLThL == 1)
	{
		return true;
	}
	return false;
}

// the kwSAfbW function
function kwSAfbW(source_kwSAfbW)
{
	if (isSet(source_kwSAfbW) && source_kwSAfbW.constructor !== Array)
	{
		var temp_kwSAfbW = source_kwSAfbW;
		var source_kwSAfbW = [];
		source_kwSAfbW.push(temp_kwSAfbW);
	}
	else if (!isSet(source_kwSAfbW))
	{
		var source_kwSAfbW = [];
	}
	var source = source_kwSAfbW.some(source_kwSAfbW_SomeFunc);


	// [8247] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_kwSAfbWIQX_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_kwSAfbWIQX_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_kwSAfbWIQX_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_kwSAfbWIQX_required = true;
		}
	}
}

// the kwSAfbW Some function
function source_kwSAfbW_SomeFunc(source_kwSAfbW)
{
	// [8234] set the function logic
	if (source_kwSAfbW == 3)
	{
		return true;
	}
	return false;
}

// the KcgziOs function
function KcgziOs(link_type_KcgziOs)
{
	// [8269] set the function logic
	if (link_type_KcgziOs == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the mIYnoFx function
function mIYnoFx(link_type_mIYnoFx)
{
	// [8269] set the function logic
	if (link_type_mIYnoFx == 1)
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

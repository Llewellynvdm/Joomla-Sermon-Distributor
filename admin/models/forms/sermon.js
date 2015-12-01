/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_krBYtbjBSg_required = false;
jform_fTVMCkKOvd_required = false;
jform_NPhYsagIYW_required = false;
jform_kEIQntmwGo_required = false;
jform_RQkNwSYiKv_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_krBYtbj = jQuery("#jform_source").val();
	krBYtbj(source_krBYtbj);

	var source_sYPBGNJ = jQuery("#jform_source").val();
	var build_sYPBGNJ = jQuery("#jform_build input[type='radio']:checked").val();
	sYPBGNJ(source_sYPBGNJ,build_sYPBGNJ);

	var source_fTVMCkK = jQuery("#jform_source").val();
	var build_fTVMCkK = jQuery("#jform_build input[type='radio']:checked").val();
	fTVMCkK(source_fTVMCkK,build_fTVMCkK);

	var build_NPhYsag = jQuery("#jform_build input[type='radio']:checked").val();
	var source_NPhYsag = jQuery("#jform_source").val();
	NPhYsag(build_NPhYsag,source_NPhYsag);

	var source_kEIQntm = jQuery("#jform_source").val();
	kEIQntm(source_kEIQntm);

	var source_RQkNwSY = jQuery("#jform_source").val();
	RQkNwSY(source_RQkNwSY);

	var link_type_lzmprMp = jQuery("#jform_link_type input[type='radio']:checked").val();
	lzmprMp(link_type_lzmprMp);

	var link_type_wAnuIRh = jQuery("#jform_link_type input[type='radio']:checked").val();
	wAnuIRh(link_type_wAnuIRh);
});

// the krBYtbj function
function krBYtbj(source_krBYtbj)
{
	if (isSet(source_krBYtbj) && source_krBYtbj.constructor !== Array)
	{
		var temp_krBYtbj = source_krBYtbj;
		var source_krBYtbj = [];
		source_krBYtbj.push(temp_krBYtbj);
	}
	else if (!isSet(source_krBYtbj))
	{
		var source_krBYtbj = [];
	}
	var source = source_krBYtbj.some(source_krBYtbj_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_krBYtbjBSg_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_krBYtbjBSg_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_krBYtbjBSg_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_krBYtbjBSg_required = true;
		}
	}
}

// the krBYtbj Some function
function source_krBYtbj_SomeFunc(source_krBYtbj)
{
	// [7966] set the function logic
	if (source_krBYtbj == 2)
	{
		return true;
	}
	return false;
}

// the sYPBGNJ function
function sYPBGNJ(source_sYPBGNJ,build_sYPBGNJ)
{
	if (isSet(source_sYPBGNJ) && source_sYPBGNJ.constructor !== Array)
	{
		var temp_sYPBGNJ = source_sYPBGNJ;
		var source_sYPBGNJ = [];
		source_sYPBGNJ.push(temp_sYPBGNJ);
	}
	else if (!isSet(source_sYPBGNJ))
	{
		var source_sYPBGNJ = [];
	}
	var source = source_sYPBGNJ.some(source_sYPBGNJ_SomeFunc);

	if (isSet(build_sYPBGNJ) && build_sYPBGNJ.constructor !== Array)
	{
		var temp_sYPBGNJ = build_sYPBGNJ;
		var build_sYPBGNJ = [];
		build_sYPBGNJ.push(temp_sYPBGNJ);
	}
	else if (!isSet(build_sYPBGNJ))
	{
		var build_sYPBGNJ = [];
	}
	var build = build_sYPBGNJ.some(build_sYPBGNJ_SomeFunc);


	// [7979] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the sYPBGNJ Some function
function source_sYPBGNJ_SomeFunc(source_sYPBGNJ)
{
	// [7966] set the function logic
	if (source_sYPBGNJ == 2)
	{
		return true;
	}
	return false;
}

// the sYPBGNJ Some function
function build_sYPBGNJ_SomeFunc(build_sYPBGNJ)
{
	// [7966] set the function logic
	if (build_sYPBGNJ == 2)
	{
		return true;
	}
	return false;
}

// the fTVMCkK function
function fTVMCkK(source_fTVMCkK,build_fTVMCkK)
{
	if (isSet(source_fTVMCkK) && source_fTVMCkK.constructor !== Array)
	{
		var temp_fTVMCkK = source_fTVMCkK;
		var source_fTVMCkK = [];
		source_fTVMCkK.push(temp_fTVMCkK);
	}
	else if (!isSet(source_fTVMCkK))
	{
		var source_fTVMCkK = [];
	}
	var source = source_fTVMCkK.some(source_fTVMCkK_SomeFunc);

	if (isSet(build_fTVMCkK) && build_fTVMCkK.constructor !== Array)
	{
		var temp_fTVMCkK = build_fTVMCkK;
		var build_fTVMCkK = [];
		build_fTVMCkK.push(temp_fTVMCkK);
	}
	else if (!isSet(build_fTVMCkK))
	{
		var build_fTVMCkK = [];
	}
	var build = build_fTVMCkK.some(build_fTVMCkK_SomeFunc);


	// [7979] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_fTVMCkKOvd_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_fTVMCkKOvd_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_fTVMCkKOvd_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_fTVMCkKOvd_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the fTVMCkK Some function
function source_fTVMCkK_SomeFunc(source_fTVMCkK)
{
	// [7966] set the function logic
	if (source_fTVMCkK == 2)
	{
		return true;
	}
	return false;
}

// the fTVMCkK Some function
function build_fTVMCkK_SomeFunc(build_fTVMCkK)
{
	// [7966] set the function logic
	if (build_fTVMCkK == 1)
	{
		return true;
	}
	return false;
}

// the NPhYsag function
function NPhYsag(build_NPhYsag,source_NPhYsag)
{
	if (isSet(build_NPhYsag) && build_NPhYsag.constructor !== Array)
	{
		var temp_NPhYsag = build_NPhYsag;
		var build_NPhYsag = [];
		build_NPhYsag.push(temp_NPhYsag);
	}
	else if (!isSet(build_NPhYsag))
	{
		var build_NPhYsag = [];
	}
	var build = build_NPhYsag.some(build_NPhYsag_SomeFunc);

	if (isSet(source_NPhYsag) && source_NPhYsag.constructor !== Array)
	{
		var temp_NPhYsag = source_NPhYsag;
		var source_NPhYsag = [];
		source_NPhYsag.push(temp_NPhYsag);
	}
	else if (!isSet(source_NPhYsag))
	{
		var source_NPhYsag = [];
	}
	var source = source_NPhYsag.some(source_NPhYsag_SomeFunc);


	// [7979] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_NPhYsagIYW_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_NPhYsagIYW_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_NPhYsagIYW_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_NPhYsagIYW_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the NPhYsag Some function
function build_NPhYsag_SomeFunc(build_NPhYsag)
{
	// [7966] set the function logic
	if (build_NPhYsag == 1)
	{
		return true;
	}
	return false;
}

// the NPhYsag Some function
function source_NPhYsag_SomeFunc(source_NPhYsag)
{
	// [7966] set the function logic
	if (source_NPhYsag == 2)
	{
		return true;
	}
	return false;
}

// the kEIQntm function
function kEIQntm(source_kEIQntm)
{
	if (isSet(source_kEIQntm) && source_kEIQntm.constructor !== Array)
	{
		var temp_kEIQntm = source_kEIQntm;
		var source_kEIQntm = [];
		source_kEIQntm.push(temp_kEIQntm);
	}
	else if (!isSet(source_kEIQntm))
	{
		var source_kEIQntm = [];
	}
	var source = source_kEIQntm.some(source_kEIQntm_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_kEIQntmwGo_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_kEIQntmwGo_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_kEIQntmwGo_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_kEIQntmwGo_required = true;
		}
	}
}

// the kEIQntm Some function
function source_kEIQntm_SomeFunc(source_kEIQntm)
{
	// [7966] set the function logic
	if (source_kEIQntm == 1)
	{
		return true;
	}
	return false;
}

// the RQkNwSY function
function RQkNwSY(source_RQkNwSY)
{
	if (isSet(source_RQkNwSY) && source_RQkNwSY.constructor !== Array)
	{
		var temp_RQkNwSY = source_RQkNwSY;
		var source_RQkNwSY = [];
		source_RQkNwSY.push(temp_RQkNwSY);
	}
	else if (!isSet(source_RQkNwSY))
	{
		var source_RQkNwSY = [];
	}
	var source = source_RQkNwSY.some(source_RQkNwSY_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_RQkNwSYiKv_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_RQkNwSYiKv_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_RQkNwSYiKv_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_RQkNwSYiKv_required = true;
		}
	}
}

// the RQkNwSY Some function
function source_RQkNwSY_SomeFunc(source_RQkNwSY)
{
	// [7966] set the function logic
	if (source_RQkNwSY == 3)
	{
		return true;
	}
	return false;
}

// the lzmprMp function
function lzmprMp(link_type_lzmprMp)
{
	// [8001] set the function logic
	if (link_type_lzmprMp == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the wAnuIRh function
function wAnuIRh(link_type_wAnuIRh)
{
	// [8001] set the function logic
	if (link_type_wAnuIRh == 1)
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

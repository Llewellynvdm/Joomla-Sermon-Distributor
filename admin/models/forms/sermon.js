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
jform_mWnyiWrQzD_required = false;
jform_USEyiqHsmP_required = false;
jform_PuIkmmJLYI_required = false;
jform_rZyqFpzhBR_required = false;
jform_pwLZOSxoqG_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_mWnyiWr = jQuery("#jform_source").val();
	mWnyiWr(source_mWnyiWr);

	var source_qGZCbLd = jQuery("#jform_source").val();
	var build_qGZCbLd = jQuery("#jform_build input[type='radio']:checked").val();
	qGZCbLd(source_qGZCbLd,build_qGZCbLd);

	var source_USEyiqH = jQuery("#jform_source").val();
	var build_USEyiqH = jQuery("#jform_build input[type='radio']:checked").val();
	USEyiqH(source_USEyiqH,build_USEyiqH);

	var build_PuIkmmJ = jQuery("#jform_build input[type='radio']:checked").val();
	var source_PuIkmmJ = jQuery("#jform_source").val();
	PuIkmmJ(build_PuIkmmJ,source_PuIkmmJ);

	var source_rZyqFpz = jQuery("#jform_source").val();
	rZyqFpz(source_rZyqFpz);

	var source_pwLZOSx = jQuery("#jform_source").val();
	pwLZOSx(source_pwLZOSx);

	var link_type_hfgQmzf = jQuery("#jform_link_type input[type='radio']:checked").val();
	hfgQmzf(link_type_hfgQmzf);

	var link_type_bdfNqfg = jQuery("#jform_link_type input[type='radio']:checked").val();
	bdfNqfg(link_type_bdfNqfg);
});

// the mWnyiWr function
function mWnyiWr(source_mWnyiWr)
{
	if (isSet(source_mWnyiWr) && source_mWnyiWr.constructor !== Array)
	{
		var temp_mWnyiWr = source_mWnyiWr;
		var source_mWnyiWr = [];
		source_mWnyiWr.push(temp_mWnyiWr);
	}
	else if (!isSet(source_mWnyiWr))
	{
		var source_mWnyiWr = [];
	}
	var source = source_mWnyiWr.some(source_mWnyiWr_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_mWnyiWrQzD_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_mWnyiWrQzD_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_mWnyiWrQzD_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_mWnyiWrQzD_required = true;
		}
	}
}

// the mWnyiWr Some function
function source_mWnyiWr_SomeFunc(source_mWnyiWr)
{
	// [7966] set the function logic
	if (source_mWnyiWr == 2)
	{
		return true;
	}
	return false;
}

// the qGZCbLd function
function qGZCbLd(source_qGZCbLd,build_qGZCbLd)
{
	if (isSet(source_qGZCbLd) && source_qGZCbLd.constructor !== Array)
	{
		var temp_qGZCbLd = source_qGZCbLd;
		var source_qGZCbLd = [];
		source_qGZCbLd.push(temp_qGZCbLd);
	}
	else if (!isSet(source_qGZCbLd))
	{
		var source_qGZCbLd = [];
	}
	var source = source_qGZCbLd.some(source_qGZCbLd_SomeFunc);

	if (isSet(build_qGZCbLd) && build_qGZCbLd.constructor !== Array)
	{
		var temp_qGZCbLd = build_qGZCbLd;
		var build_qGZCbLd = [];
		build_qGZCbLd.push(temp_qGZCbLd);
	}
	else if (!isSet(build_qGZCbLd))
	{
		var build_qGZCbLd = [];
	}
	var build = build_qGZCbLd.some(build_qGZCbLd_SomeFunc);


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

// the qGZCbLd Some function
function source_qGZCbLd_SomeFunc(source_qGZCbLd)
{
	// [7966] set the function logic
	if (source_qGZCbLd == 2)
	{
		return true;
	}
	return false;
}

// the qGZCbLd Some function
function build_qGZCbLd_SomeFunc(build_qGZCbLd)
{
	// [7966] set the function logic
	if (build_qGZCbLd == 2)
	{
		return true;
	}
	return false;
}

// the USEyiqH function
function USEyiqH(source_USEyiqH,build_USEyiqH)
{
	if (isSet(source_USEyiqH) && source_USEyiqH.constructor !== Array)
	{
		var temp_USEyiqH = source_USEyiqH;
		var source_USEyiqH = [];
		source_USEyiqH.push(temp_USEyiqH);
	}
	else if (!isSet(source_USEyiqH))
	{
		var source_USEyiqH = [];
	}
	var source = source_USEyiqH.some(source_USEyiqH_SomeFunc);

	if (isSet(build_USEyiqH) && build_USEyiqH.constructor !== Array)
	{
		var temp_USEyiqH = build_USEyiqH;
		var build_USEyiqH = [];
		build_USEyiqH.push(temp_USEyiqH);
	}
	else if (!isSet(build_USEyiqH))
	{
		var build_USEyiqH = [];
	}
	var build = build_USEyiqH.some(build_USEyiqH_SomeFunc);


	// [7979] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_USEyiqHsmP_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_USEyiqHsmP_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_USEyiqHsmP_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_USEyiqHsmP_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the USEyiqH Some function
function source_USEyiqH_SomeFunc(source_USEyiqH)
{
	// [7966] set the function logic
	if (source_USEyiqH == 2)
	{
		return true;
	}
	return false;
}

// the USEyiqH Some function
function build_USEyiqH_SomeFunc(build_USEyiqH)
{
	// [7966] set the function logic
	if (build_USEyiqH == 1)
	{
		return true;
	}
	return false;
}

// the PuIkmmJ function
function PuIkmmJ(build_PuIkmmJ,source_PuIkmmJ)
{
	if (isSet(build_PuIkmmJ) && build_PuIkmmJ.constructor !== Array)
	{
		var temp_PuIkmmJ = build_PuIkmmJ;
		var build_PuIkmmJ = [];
		build_PuIkmmJ.push(temp_PuIkmmJ);
	}
	else if (!isSet(build_PuIkmmJ))
	{
		var build_PuIkmmJ = [];
	}
	var build = build_PuIkmmJ.some(build_PuIkmmJ_SomeFunc);

	if (isSet(source_PuIkmmJ) && source_PuIkmmJ.constructor !== Array)
	{
		var temp_PuIkmmJ = source_PuIkmmJ;
		var source_PuIkmmJ = [];
		source_PuIkmmJ.push(temp_PuIkmmJ);
	}
	else if (!isSet(source_PuIkmmJ))
	{
		var source_PuIkmmJ = [];
	}
	var source = source_PuIkmmJ.some(source_PuIkmmJ_SomeFunc);


	// [7979] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_PuIkmmJLYI_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_PuIkmmJLYI_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_PuIkmmJLYI_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_PuIkmmJLYI_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the PuIkmmJ Some function
function build_PuIkmmJ_SomeFunc(build_PuIkmmJ)
{
	// [7966] set the function logic
	if (build_PuIkmmJ == 1)
	{
		return true;
	}
	return false;
}

// the PuIkmmJ Some function
function source_PuIkmmJ_SomeFunc(source_PuIkmmJ)
{
	// [7966] set the function logic
	if (source_PuIkmmJ == 2)
	{
		return true;
	}
	return false;
}

// the rZyqFpz function
function rZyqFpz(source_rZyqFpz)
{
	if (isSet(source_rZyqFpz) && source_rZyqFpz.constructor !== Array)
	{
		var temp_rZyqFpz = source_rZyqFpz;
		var source_rZyqFpz = [];
		source_rZyqFpz.push(temp_rZyqFpz);
	}
	else if (!isSet(source_rZyqFpz))
	{
		var source_rZyqFpz = [];
	}
	var source = source_rZyqFpz.some(source_rZyqFpz_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_rZyqFpzhBR_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_rZyqFpzhBR_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_rZyqFpzhBR_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_rZyqFpzhBR_required = true;
		}
	}
}

// the rZyqFpz Some function
function source_rZyqFpz_SomeFunc(source_rZyqFpz)
{
	// [7966] set the function logic
	if (source_rZyqFpz == 1)
	{
		return true;
	}
	return false;
}

// the pwLZOSx function
function pwLZOSx(source_pwLZOSx)
{
	if (isSet(source_pwLZOSx) && source_pwLZOSx.constructor !== Array)
	{
		var temp_pwLZOSx = source_pwLZOSx;
		var source_pwLZOSx = [];
		source_pwLZOSx.push(temp_pwLZOSx);
	}
	else if (!isSet(source_pwLZOSx))
	{
		var source_pwLZOSx = [];
	}
	var source = source_pwLZOSx.some(source_pwLZOSx_SomeFunc);


	// [7979] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_pwLZOSxoqG_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_pwLZOSxoqG_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_pwLZOSxoqG_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_pwLZOSxoqG_required = true;
		}
	}
}

// the pwLZOSx Some function
function source_pwLZOSx_SomeFunc(source_pwLZOSx)
{
	// [7966] set the function logic
	if (source_pwLZOSx == 3)
	{
		return true;
	}
	return false;
}

// the hfgQmzf function
function hfgQmzf(link_type_hfgQmzf)
{
	// [8001] set the function logic
	if (link_type_hfgQmzf == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the bdfNqfg function
function bdfNqfg(link_type_bdfNqfg)
{
	// [8001] set the function logic
	if (link_type_bdfNqfg == 1)
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

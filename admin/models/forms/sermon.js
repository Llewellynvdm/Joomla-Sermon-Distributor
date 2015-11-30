/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
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
jform_rftyiTCGUp_required = false;
jform_jAMSvXiJcA_required = false;
jform_hkBIRwXeyD_required = false;
jform_cyLHBtpmUy_required = false;
jform_XcZLWuJdiL_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_rftyiTC = jQuery("#jform_source").val();
	rftyiTC(source_rftyiTC);

	var source_liGWajx = jQuery("#jform_source").val();
	var build_liGWajx = jQuery("#jform_build input[type='radio']:checked").val();
	liGWajx(source_liGWajx,build_liGWajx);

	var source_jAMSvXi = jQuery("#jform_source").val();
	var build_jAMSvXi = jQuery("#jform_build input[type='radio']:checked").val();
	jAMSvXi(source_jAMSvXi,build_jAMSvXi);

	var build_hkBIRwX = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hkBIRwX = jQuery("#jform_source").val();
	hkBIRwX(build_hkBIRwX,source_hkBIRwX);

	var source_cyLHBtp = jQuery("#jform_source").val();
	cyLHBtp(source_cyLHBtp);

	var source_XcZLWuJ = jQuery("#jform_source").val();
	XcZLWuJ(source_XcZLWuJ);

	var link_type_LnTfUMv = jQuery("#jform_link_type input[type='radio']:checked").val();
	LnTfUMv(link_type_LnTfUMv);

	var link_type_SPbxTGc = jQuery("#jform_link_type input[type='radio']:checked").val();
	SPbxTGc(link_type_SPbxTGc);
});

// the rftyiTC function
function rftyiTC(source_rftyiTC)
{
	if (isSet(source_rftyiTC) && source_rftyiTC.constructor !== Array)
	{
		var temp_rftyiTC = source_rftyiTC;
		var source_rftyiTC = [];
		source_rftyiTC.push(temp_rftyiTC);
	}
	else if (!isSet(source_rftyiTC))
	{
		var source_rftyiTC = [];
	}
	var source = source_rftyiTC.some(source_rftyiTC_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_rftyiTCGUp_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_rftyiTCGUp_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_rftyiTCGUp_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_rftyiTCGUp_required = true;
		}
	}
}

// the rftyiTC Some function
function source_rftyiTC_SomeFunc(source_rftyiTC)
{
	// [7939] set the function logic
	if (source_rftyiTC == 2)
	{
		return true;
	}
	return false;
}

// the liGWajx function
function liGWajx(source_liGWajx,build_liGWajx)
{
	if (isSet(source_liGWajx) && source_liGWajx.constructor !== Array)
	{
		var temp_liGWajx = source_liGWajx;
		var source_liGWajx = [];
		source_liGWajx.push(temp_liGWajx);
	}
	else if (!isSet(source_liGWajx))
	{
		var source_liGWajx = [];
	}
	var source = source_liGWajx.some(source_liGWajx_SomeFunc);

	if (isSet(build_liGWajx) && build_liGWajx.constructor !== Array)
	{
		var temp_liGWajx = build_liGWajx;
		var build_liGWajx = [];
		build_liGWajx.push(temp_liGWajx);
	}
	else if (!isSet(build_liGWajx))
	{
		var build_liGWajx = [];
	}
	var build = build_liGWajx.some(build_liGWajx_SomeFunc);


	// [7952] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the liGWajx Some function
function source_liGWajx_SomeFunc(source_liGWajx)
{
	// [7939] set the function logic
	if (source_liGWajx == 2)
	{
		return true;
	}
	return false;
}

// the liGWajx Some function
function build_liGWajx_SomeFunc(build_liGWajx)
{
	// [7939] set the function logic
	if (build_liGWajx == 2)
	{
		return true;
	}
	return false;
}

// the jAMSvXi function
function jAMSvXi(source_jAMSvXi,build_jAMSvXi)
{
	if (isSet(source_jAMSvXi) && source_jAMSvXi.constructor !== Array)
	{
		var temp_jAMSvXi = source_jAMSvXi;
		var source_jAMSvXi = [];
		source_jAMSvXi.push(temp_jAMSvXi);
	}
	else if (!isSet(source_jAMSvXi))
	{
		var source_jAMSvXi = [];
	}
	var source = source_jAMSvXi.some(source_jAMSvXi_SomeFunc);

	if (isSet(build_jAMSvXi) && build_jAMSvXi.constructor !== Array)
	{
		var temp_jAMSvXi = build_jAMSvXi;
		var build_jAMSvXi = [];
		build_jAMSvXi.push(temp_jAMSvXi);
	}
	else if (!isSet(build_jAMSvXi))
	{
		var build_jAMSvXi = [];
	}
	var build = build_jAMSvXi.some(build_jAMSvXi_SomeFunc);


	// [7952] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_jAMSvXiJcA_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_jAMSvXiJcA_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_jAMSvXiJcA_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_jAMSvXiJcA_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the jAMSvXi Some function
function source_jAMSvXi_SomeFunc(source_jAMSvXi)
{
	// [7939] set the function logic
	if (source_jAMSvXi == 2)
	{
		return true;
	}
	return false;
}

// the jAMSvXi Some function
function build_jAMSvXi_SomeFunc(build_jAMSvXi)
{
	// [7939] set the function logic
	if (build_jAMSvXi == 1)
	{
		return true;
	}
	return false;
}

// the hkBIRwX function
function hkBIRwX(build_hkBIRwX,source_hkBIRwX)
{
	if (isSet(build_hkBIRwX) && build_hkBIRwX.constructor !== Array)
	{
		var temp_hkBIRwX = build_hkBIRwX;
		var build_hkBIRwX = [];
		build_hkBIRwX.push(temp_hkBIRwX);
	}
	else if (!isSet(build_hkBIRwX))
	{
		var build_hkBIRwX = [];
	}
	var build = build_hkBIRwX.some(build_hkBIRwX_SomeFunc);

	if (isSet(source_hkBIRwX) && source_hkBIRwX.constructor !== Array)
	{
		var temp_hkBIRwX = source_hkBIRwX;
		var source_hkBIRwX = [];
		source_hkBIRwX.push(temp_hkBIRwX);
	}
	else if (!isSet(source_hkBIRwX))
	{
		var source_hkBIRwX = [];
	}
	var source = source_hkBIRwX.some(source_hkBIRwX_SomeFunc);


	// [7952] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_hkBIRwXeyD_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_hkBIRwXeyD_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_hkBIRwXeyD_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_hkBIRwXeyD_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the hkBIRwX Some function
function build_hkBIRwX_SomeFunc(build_hkBIRwX)
{
	// [7939] set the function logic
	if (build_hkBIRwX == 1)
	{
		return true;
	}
	return false;
}

// the hkBIRwX Some function
function source_hkBIRwX_SomeFunc(source_hkBIRwX)
{
	// [7939] set the function logic
	if (source_hkBIRwX == 2)
	{
		return true;
	}
	return false;
}

// the cyLHBtp function
function cyLHBtp(source_cyLHBtp)
{
	if (isSet(source_cyLHBtp) && source_cyLHBtp.constructor !== Array)
	{
		var temp_cyLHBtp = source_cyLHBtp;
		var source_cyLHBtp = [];
		source_cyLHBtp.push(temp_cyLHBtp);
	}
	else if (!isSet(source_cyLHBtp))
	{
		var source_cyLHBtp = [];
	}
	var source = source_cyLHBtp.some(source_cyLHBtp_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_cyLHBtpmUy_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_cyLHBtpmUy_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_cyLHBtpmUy_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_cyLHBtpmUy_required = true;
		}
	}
}

// the cyLHBtp Some function
function source_cyLHBtp_SomeFunc(source_cyLHBtp)
{
	// [7939] set the function logic
	if (source_cyLHBtp == 1)
	{
		return true;
	}
	return false;
}

// the XcZLWuJ function
function XcZLWuJ(source_XcZLWuJ)
{
	if (isSet(source_XcZLWuJ) && source_XcZLWuJ.constructor !== Array)
	{
		var temp_XcZLWuJ = source_XcZLWuJ;
		var source_XcZLWuJ = [];
		source_XcZLWuJ.push(temp_XcZLWuJ);
	}
	else if (!isSet(source_XcZLWuJ))
	{
		var source_XcZLWuJ = [];
	}
	var source = source_XcZLWuJ.some(source_XcZLWuJ_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_XcZLWuJdiL_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_XcZLWuJdiL_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_XcZLWuJdiL_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_XcZLWuJdiL_required = true;
		}
	}
}

// the XcZLWuJ Some function
function source_XcZLWuJ_SomeFunc(source_XcZLWuJ)
{
	// [7939] set the function logic
	if (source_XcZLWuJ == 3)
	{
		return true;
	}
	return false;
}

// the LnTfUMv function
function LnTfUMv(link_type_LnTfUMv)
{
	// [7974] set the function logic
	if (link_type_LnTfUMv == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the SPbxTGc function
function SPbxTGc(link_type_SPbxTGc)
{
	// [7974] set the function logic
	if (link_type_SPbxTGc == 1)
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

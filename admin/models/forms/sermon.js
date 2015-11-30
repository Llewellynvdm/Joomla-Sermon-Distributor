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
jform_xbZtXWzeFM_required = false;
jform_WZwWpgDJhv_required = false;
jform_hdyAkcsOFV_required = false;
jform_MkfKpczQEa_required = false;
jform_VHZADuEZeK_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_xbZtXWz = jQuery("#jform_source").val();
	xbZtXWz(source_xbZtXWz);

	var source_CDIVEjW = jQuery("#jform_source").val();
	var build_CDIVEjW = jQuery("#jform_build input[type='radio']:checked").val();
	CDIVEjW(source_CDIVEjW,build_CDIVEjW);

	var source_WZwWpgD = jQuery("#jform_source").val();
	var build_WZwWpgD = jQuery("#jform_build input[type='radio']:checked").val();
	WZwWpgD(source_WZwWpgD,build_WZwWpgD);

	var build_hdyAkcs = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hdyAkcs = jQuery("#jform_source").val();
	hdyAkcs(build_hdyAkcs,source_hdyAkcs);

	var source_MkfKpcz = jQuery("#jform_source").val();
	MkfKpcz(source_MkfKpcz);

	var source_VHZADuE = jQuery("#jform_source").val();
	VHZADuE(source_VHZADuE);

	var link_type_BlITSTV = jQuery("#jform_link_type input[type='radio']:checked").val();
	BlITSTV(link_type_BlITSTV);

	var link_type_fGuayEg = jQuery("#jform_link_type input[type='radio']:checked").val();
	fGuayEg(link_type_fGuayEg);
});

// the xbZtXWz function
function xbZtXWz(source_xbZtXWz)
{
	if (isSet(source_xbZtXWz) && source_xbZtXWz.constructor !== Array)
	{
		var temp_xbZtXWz = source_xbZtXWz;
		var source_xbZtXWz = [];
		source_xbZtXWz.push(temp_xbZtXWz);
	}
	else if (!isSet(source_xbZtXWz))
	{
		var source_xbZtXWz = [];
	}
	var source = source_xbZtXWz.some(source_xbZtXWz_SomeFunc);


	// [7978] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_xbZtXWzeFM_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_xbZtXWzeFM_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_xbZtXWzeFM_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_xbZtXWzeFM_required = true;
		}
	}
}

// the xbZtXWz Some function
function source_xbZtXWz_SomeFunc(source_xbZtXWz)
{
	// [7965] set the function logic
	if (source_xbZtXWz == 2)
	{
		return true;
	}
	return false;
}

// the CDIVEjW function
function CDIVEjW(source_CDIVEjW,build_CDIVEjW)
{
	if (isSet(source_CDIVEjW) && source_CDIVEjW.constructor !== Array)
	{
		var temp_CDIVEjW = source_CDIVEjW;
		var source_CDIVEjW = [];
		source_CDIVEjW.push(temp_CDIVEjW);
	}
	else if (!isSet(source_CDIVEjW))
	{
		var source_CDIVEjW = [];
	}
	var source = source_CDIVEjW.some(source_CDIVEjW_SomeFunc);

	if (isSet(build_CDIVEjW) && build_CDIVEjW.constructor !== Array)
	{
		var temp_CDIVEjW = build_CDIVEjW;
		var build_CDIVEjW = [];
		build_CDIVEjW.push(temp_CDIVEjW);
	}
	else if (!isSet(build_CDIVEjW))
	{
		var build_CDIVEjW = [];
	}
	var build = build_CDIVEjW.some(build_CDIVEjW_SomeFunc);


	// [7978] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the CDIVEjW Some function
function source_CDIVEjW_SomeFunc(source_CDIVEjW)
{
	// [7965] set the function logic
	if (source_CDIVEjW == 2)
	{
		return true;
	}
	return false;
}

// the CDIVEjW Some function
function build_CDIVEjW_SomeFunc(build_CDIVEjW)
{
	// [7965] set the function logic
	if (build_CDIVEjW == 2)
	{
		return true;
	}
	return false;
}

// the WZwWpgD function
function WZwWpgD(source_WZwWpgD,build_WZwWpgD)
{
	if (isSet(source_WZwWpgD) && source_WZwWpgD.constructor !== Array)
	{
		var temp_WZwWpgD = source_WZwWpgD;
		var source_WZwWpgD = [];
		source_WZwWpgD.push(temp_WZwWpgD);
	}
	else if (!isSet(source_WZwWpgD))
	{
		var source_WZwWpgD = [];
	}
	var source = source_WZwWpgD.some(source_WZwWpgD_SomeFunc);

	if (isSet(build_WZwWpgD) && build_WZwWpgD.constructor !== Array)
	{
		var temp_WZwWpgD = build_WZwWpgD;
		var build_WZwWpgD = [];
		build_WZwWpgD.push(temp_WZwWpgD);
	}
	else if (!isSet(build_WZwWpgD))
	{
		var build_WZwWpgD = [];
	}
	var build = build_WZwWpgD.some(build_WZwWpgD_SomeFunc);


	// [7978] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_WZwWpgDJhv_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_WZwWpgDJhv_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_WZwWpgDJhv_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_WZwWpgDJhv_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the WZwWpgD Some function
function source_WZwWpgD_SomeFunc(source_WZwWpgD)
{
	// [7965] set the function logic
	if (source_WZwWpgD == 2)
	{
		return true;
	}
	return false;
}

// the WZwWpgD Some function
function build_WZwWpgD_SomeFunc(build_WZwWpgD)
{
	// [7965] set the function logic
	if (build_WZwWpgD == 1)
	{
		return true;
	}
	return false;
}

// the hdyAkcs function
function hdyAkcs(build_hdyAkcs,source_hdyAkcs)
{
	if (isSet(build_hdyAkcs) && build_hdyAkcs.constructor !== Array)
	{
		var temp_hdyAkcs = build_hdyAkcs;
		var build_hdyAkcs = [];
		build_hdyAkcs.push(temp_hdyAkcs);
	}
	else if (!isSet(build_hdyAkcs))
	{
		var build_hdyAkcs = [];
	}
	var build = build_hdyAkcs.some(build_hdyAkcs_SomeFunc);

	if (isSet(source_hdyAkcs) && source_hdyAkcs.constructor !== Array)
	{
		var temp_hdyAkcs = source_hdyAkcs;
		var source_hdyAkcs = [];
		source_hdyAkcs.push(temp_hdyAkcs);
	}
	else if (!isSet(source_hdyAkcs))
	{
		var source_hdyAkcs = [];
	}
	var source = source_hdyAkcs.some(source_hdyAkcs_SomeFunc);


	// [7978] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_hdyAkcsOFV_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_hdyAkcsOFV_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_hdyAkcsOFV_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_hdyAkcsOFV_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the hdyAkcs Some function
function build_hdyAkcs_SomeFunc(build_hdyAkcs)
{
	// [7965] set the function logic
	if (build_hdyAkcs == 1)
	{
		return true;
	}
	return false;
}

// the hdyAkcs Some function
function source_hdyAkcs_SomeFunc(source_hdyAkcs)
{
	// [7965] set the function logic
	if (source_hdyAkcs == 2)
	{
		return true;
	}
	return false;
}

// the MkfKpcz function
function MkfKpcz(source_MkfKpcz)
{
	if (isSet(source_MkfKpcz) && source_MkfKpcz.constructor !== Array)
	{
		var temp_MkfKpcz = source_MkfKpcz;
		var source_MkfKpcz = [];
		source_MkfKpcz.push(temp_MkfKpcz);
	}
	else if (!isSet(source_MkfKpcz))
	{
		var source_MkfKpcz = [];
	}
	var source = source_MkfKpcz.some(source_MkfKpcz_SomeFunc);


	// [7978] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_MkfKpczQEa_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_MkfKpczQEa_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_MkfKpczQEa_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_MkfKpczQEa_required = true;
		}
	}
}

// the MkfKpcz Some function
function source_MkfKpcz_SomeFunc(source_MkfKpcz)
{
	// [7965] set the function logic
	if (source_MkfKpcz == 1)
	{
		return true;
	}
	return false;
}

// the VHZADuE function
function VHZADuE(source_VHZADuE)
{
	if (isSet(source_VHZADuE) && source_VHZADuE.constructor !== Array)
	{
		var temp_VHZADuE = source_VHZADuE;
		var source_VHZADuE = [];
		source_VHZADuE.push(temp_VHZADuE);
	}
	else if (!isSet(source_VHZADuE))
	{
		var source_VHZADuE = [];
	}
	var source = source_VHZADuE.some(source_VHZADuE_SomeFunc);


	// [7978] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_VHZADuEZeK_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_VHZADuEZeK_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_VHZADuEZeK_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_VHZADuEZeK_required = true;
		}
	}
}

// the VHZADuE Some function
function source_VHZADuE_SomeFunc(source_VHZADuE)
{
	// [7965] set the function logic
	if (source_VHZADuE == 3)
	{
		return true;
	}
	return false;
}

// the BlITSTV function
function BlITSTV(link_type_BlITSTV)
{
	// [8000] set the function logic
	if (link_type_BlITSTV == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the fGuayEg function
function fGuayEg(link_type_fGuayEg)
{
	// [8000] set the function logic
	if (link_type_fGuayEg == 1)
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

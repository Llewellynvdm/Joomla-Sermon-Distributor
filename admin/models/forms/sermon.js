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
	@build			20th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_TAXvQRxyCa_required = false;
jform_nkNZrUGmCx_required = false;
jform_PswgMZiFjO_required = false;
jform_HmcnElZntu_required = false;
jform_xEDfwTZfiB_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_TAXvQRx = jQuery("#jform_source").val();
	TAXvQRx(source_TAXvQRx);

	var source_RkEbgqN = jQuery("#jform_source").val();
	var build_RkEbgqN = jQuery("#jform_build input[type='radio']:checked").val();
	RkEbgqN(source_RkEbgqN,build_RkEbgqN);

	var source_nkNZrUG = jQuery("#jform_source").val();
	var build_nkNZrUG = jQuery("#jform_build input[type='radio']:checked").val();
	nkNZrUG(source_nkNZrUG,build_nkNZrUG);

	var build_PswgMZi = jQuery("#jform_build input[type='radio']:checked").val();
	var source_PswgMZi = jQuery("#jform_source").val();
	PswgMZi(build_PswgMZi,source_PswgMZi);

	var source_HmcnElZ = jQuery("#jform_source").val();
	HmcnElZ(source_HmcnElZ);

	var source_xEDfwTZ = jQuery("#jform_source").val();
	xEDfwTZ(source_xEDfwTZ);

	var link_type_CXOYeuY = jQuery("#jform_link_type input[type='radio']:checked").val();
	CXOYeuY(link_type_CXOYeuY);

	var link_type_lahbHtd = jQuery("#jform_link_type input[type='radio']:checked").val();
	lahbHtd(link_type_lahbHtd);
});

// the TAXvQRx function
function TAXvQRx(source_TAXvQRx)
{
	if (isSet(source_TAXvQRx) && source_TAXvQRx.constructor !== Array)
	{
		var temp_TAXvQRx = source_TAXvQRx;
		var source_TAXvQRx = [];
		source_TAXvQRx.push(temp_TAXvQRx);
	}
	else if (!isSet(source_TAXvQRx))
	{
		var source_TAXvQRx = [];
	}
	var source = source_TAXvQRx.some(source_TAXvQRx_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_TAXvQRxyCa_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_TAXvQRxyCa_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_TAXvQRxyCa_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_TAXvQRxyCa_required = true;
		}
	}
}

// the TAXvQRx Some function
function source_TAXvQRx_SomeFunc(source_TAXvQRx)
{
	// [8661] set the function logic
	if (source_TAXvQRx == 2)
	{
		return true;
	}
	return false;
}

// the RkEbgqN function
function RkEbgqN(source_RkEbgqN,build_RkEbgqN)
{
	if (isSet(source_RkEbgqN) && source_RkEbgqN.constructor !== Array)
	{
		var temp_RkEbgqN = source_RkEbgqN;
		var source_RkEbgqN = [];
		source_RkEbgqN.push(temp_RkEbgqN);
	}
	else if (!isSet(source_RkEbgqN))
	{
		var source_RkEbgqN = [];
	}
	var source = source_RkEbgqN.some(source_RkEbgqN_SomeFunc);

	if (isSet(build_RkEbgqN) && build_RkEbgqN.constructor !== Array)
	{
		var temp_RkEbgqN = build_RkEbgqN;
		var build_RkEbgqN = [];
		build_RkEbgqN.push(temp_RkEbgqN);
	}
	else if (!isSet(build_RkEbgqN))
	{
		var build_RkEbgqN = [];
	}
	var build = build_RkEbgqN.some(build_RkEbgqN_SomeFunc);


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

// the RkEbgqN Some function
function source_RkEbgqN_SomeFunc(source_RkEbgqN)
{
	// [8661] set the function logic
	if (source_RkEbgqN == 2)
	{
		return true;
	}
	return false;
}

// the RkEbgqN Some function
function build_RkEbgqN_SomeFunc(build_RkEbgqN)
{
	// [8661] set the function logic
	if (build_RkEbgqN == 2)
	{
		return true;
	}
	return false;
}

// the nkNZrUG function
function nkNZrUG(source_nkNZrUG,build_nkNZrUG)
{
	if (isSet(source_nkNZrUG) && source_nkNZrUG.constructor !== Array)
	{
		var temp_nkNZrUG = source_nkNZrUG;
		var source_nkNZrUG = [];
		source_nkNZrUG.push(temp_nkNZrUG);
	}
	else if (!isSet(source_nkNZrUG))
	{
		var source_nkNZrUG = [];
	}
	var source = source_nkNZrUG.some(source_nkNZrUG_SomeFunc);

	if (isSet(build_nkNZrUG) && build_nkNZrUG.constructor !== Array)
	{
		var temp_nkNZrUG = build_nkNZrUG;
		var build_nkNZrUG = [];
		build_nkNZrUG.push(temp_nkNZrUG);
	}
	else if (!isSet(build_nkNZrUG))
	{
		var build_nkNZrUG = [];
	}
	var build = build_nkNZrUG.some(build_nkNZrUG_SomeFunc);


	// [8674] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_nkNZrUGmCx_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_nkNZrUGmCx_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_nkNZrUGmCx_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_nkNZrUGmCx_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the nkNZrUG Some function
function source_nkNZrUG_SomeFunc(source_nkNZrUG)
{
	// [8661] set the function logic
	if (source_nkNZrUG == 2)
	{
		return true;
	}
	return false;
}

// the nkNZrUG Some function
function build_nkNZrUG_SomeFunc(build_nkNZrUG)
{
	// [8661] set the function logic
	if (build_nkNZrUG == 1)
	{
		return true;
	}
	return false;
}

// the PswgMZi function
function PswgMZi(build_PswgMZi,source_PswgMZi)
{
	if (isSet(build_PswgMZi) && build_PswgMZi.constructor !== Array)
	{
		var temp_PswgMZi = build_PswgMZi;
		var build_PswgMZi = [];
		build_PswgMZi.push(temp_PswgMZi);
	}
	else if (!isSet(build_PswgMZi))
	{
		var build_PswgMZi = [];
	}
	var build = build_PswgMZi.some(build_PswgMZi_SomeFunc);

	if (isSet(source_PswgMZi) && source_PswgMZi.constructor !== Array)
	{
		var temp_PswgMZi = source_PswgMZi;
		var source_PswgMZi = [];
		source_PswgMZi.push(temp_PswgMZi);
	}
	else if (!isSet(source_PswgMZi))
	{
		var source_PswgMZi = [];
	}
	var source = source_PswgMZi.some(source_PswgMZi_SomeFunc);


	// [8674] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_PswgMZiFjO_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_PswgMZiFjO_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_PswgMZiFjO_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_PswgMZiFjO_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the PswgMZi Some function
function build_PswgMZi_SomeFunc(build_PswgMZi)
{
	// [8661] set the function logic
	if (build_PswgMZi == 1)
	{
		return true;
	}
	return false;
}

// the PswgMZi Some function
function source_PswgMZi_SomeFunc(source_PswgMZi)
{
	// [8661] set the function logic
	if (source_PswgMZi == 2)
	{
		return true;
	}
	return false;
}

// the HmcnElZ function
function HmcnElZ(source_HmcnElZ)
{
	if (isSet(source_HmcnElZ) && source_HmcnElZ.constructor !== Array)
	{
		var temp_HmcnElZ = source_HmcnElZ;
		var source_HmcnElZ = [];
		source_HmcnElZ.push(temp_HmcnElZ);
	}
	else if (!isSet(source_HmcnElZ))
	{
		var source_HmcnElZ = [];
	}
	var source = source_HmcnElZ.some(source_HmcnElZ_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_HmcnElZntu_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_HmcnElZntu_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_HmcnElZntu_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_HmcnElZntu_required = true;
		}
	}
}

// the HmcnElZ Some function
function source_HmcnElZ_SomeFunc(source_HmcnElZ)
{
	// [8661] set the function logic
	if (source_HmcnElZ == 1)
	{
		return true;
	}
	return false;
}

// the xEDfwTZ function
function xEDfwTZ(source_xEDfwTZ)
{
	if (isSet(source_xEDfwTZ) && source_xEDfwTZ.constructor !== Array)
	{
		var temp_xEDfwTZ = source_xEDfwTZ;
		var source_xEDfwTZ = [];
		source_xEDfwTZ.push(temp_xEDfwTZ);
	}
	else if (!isSet(source_xEDfwTZ))
	{
		var source_xEDfwTZ = [];
	}
	var source = source_xEDfwTZ.some(source_xEDfwTZ_SomeFunc);


	// [8674] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_xEDfwTZfiB_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_xEDfwTZfiB_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_xEDfwTZfiB_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_xEDfwTZfiB_required = true;
		}
	}
}

// the xEDfwTZ Some function
function source_xEDfwTZ_SomeFunc(source_xEDfwTZ)
{
	// [8661] set the function logic
	if (source_xEDfwTZ == 3)
	{
		return true;
	}
	return false;
}

// the CXOYeuY function
function CXOYeuY(link_type_CXOYeuY)
{
	// [8696] set the function logic
	if (link_type_CXOYeuY == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the lahbHtd function
function lahbHtd(link_type_lahbHtd)
{
	// [8696] set the function logic
	if (link_type_lahbHtd == 1)
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

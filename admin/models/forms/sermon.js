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
jform_FvQYyEFbXL_required = false;
jform_kOrUAMyZju_required = false;
jform_YIZCNWsBCx_required = false;
jform_KqzedcNUWP_required = false;
jform_XhHkZbWFaf_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_FvQYyEF = jQuery("#jform_source").val();
	FvQYyEF(source_FvQYyEF);

	var source_TLlcJUt = jQuery("#jform_source").val();
	var build_TLlcJUt = jQuery("#jform_build input[type='radio']:checked").val();
	TLlcJUt(source_TLlcJUt,build_TLlcJUt);

	var source_kOrUAMy = jQuery("#jform_source").val();
	var build_kOrUAMy = jQuery("#jform_build input[type='radio']:checked").val();
	kOrUAMy(source_kOrUAMy,build_kOrUAMy);

	var build_YIZCNWs = jQuery("#jform_build input[type='radio']:checked").val();
	var source_YIZCNWs = jQuery("#jform_source").val();
	YIZCNWs(build_YIZCNWs,source_YIZCNWs);

	var source_KqzedcN = jQuery("#jform_source").val();
	KqzedcN(source_KqzedcN);

	var source_XhHkZbW = jQuery("#jform_source").val();
	XhHkZbW(source_XhHkZbW);

	var link_type_VZPUBFP = jQuery("#jform_link_type input[type='radio']:checked").val();
	VZPUBFP(link_type_VZPUBFP);

	var link_type_UiutQRQ = jQuery("#jform_link_type input[type='radio']:checked").val();
	UiutQRQ(link_type_UiutQRQ);
});

// the FvQYyEF function
function FvQYyEF(source_FvQYyEF)
{
	if (isSet(source_FvQYyEF) && source_FvQYyEF.constructor !== Array)
	{
		var temp_FvQYyEF = source_FvQYyEF;
		var source_FvQYyEF = [];
		source_FvQYyEF.push(temp_FvQYyEF);
	}
	else if (!isSet(source_FvQYyEF))
	{
		var source_FvQYyEF = [];
	}
	var source = source_FvQYyEF.some(source_FvQYyEF_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_FvQYyEFbXL_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_FvQYyEFbXL_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_FvQYyEFbXL_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_FvQYyEFbXL_required = true;
		}
	}
}

// the FvQYyEF Some function
function source_FvQYyEF_SomeFunc(source_FvQYyEF)
{
	// [7939] set the function logic
	if (source_FvQYyEF == 2)
	{
		return true;
	}
	return false;
}

// the TLlcJUt function
function TLlcJUt(source_TLlcJUt,build_TLlcJUt)
{
	if (isSet(source_TLlcJUt) && source_TLlcJUt.constructor !== Array)
	{
		var temp_TLlcJUt = source_TLlcJUt;
		var source_TLlcJUt = [];
		source_TLlcJUt.push(temp_TLlcJUt);
	}
	else if (!isSet(source_TLlcJUt))
	{
		var source_TLlcJUt = [];
	}
	var source = source_TLlcJUt.some(source_TLlcJUt_SomeFunc);

	if (isSet(build_TLlcJUt) && build_TLlcJUt.constructor !== Array)
	{
		var temp_TLlcJUt = build_TLlcJUt;
		var build_TLlcJUt = [];
		build_TLlcJUt.push(temp_TLlcJUt);
	}
	else if (!isSet(build_TLlcJUt))
	{
		var build_TLlcJUt = [];
	}
	var build = build_TLlcJUt.some(build_TLlcJUt_SomeFunc);


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

// the TLlcJUt Some function
function source_TLlcJUt_SomeFunc(source_TLlcJUt)
{
	// [7939] set the function logic
	if (source_TLlcJUt == 2)
	{
		return true;
	}
	return false;
}

// the TLlcJUt Some function
function build_TLlcJUt_SomeFunc(build_TLlcJUt)
{
	// [7939] set the function logic
	if (build_TLlcJUt == 2)
	{
		return true;
	}
	return false;
}

// the kOrUAMy function
function kOrUAMy(source_kOrUAMy,build_kOrUAMy)
{
	if (isSet(source_kOrUAMy) && source_kOrUAMy.constructor !== Array)
	{
		var temp_kOrUAMy = source_kOrUAMy;
		var source_kOrUAMy = [];
		source_kOrUAMy.push(temp_kOrUAMy);
	}
	else if (!isSet(source_kOrUAMy))
	{
		var source_kOrUAMy = [];
	}
	var source = source_kOrUAMy.some(source_kOrUAMy_SomeFunc);

	if (isSet(build_kOrUAMy) && build_kOrUAMy.constructor !== Array)
	{
		var temp_kOrUAMy = build_kOrUAMy;
		var build_kOrUAMy = [];
		build_kOrUAMy.push(temp_kOrUAMy);
	}
	else if (!isSet(build_kOrUAMy))
	{
		var build_kOrUAMy = [];
	}
	var build = build_kOrUAMy.some(build_kOrUAMy_SomeFunc);


	// [7952] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_kOrUAMyZju_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_kOrUAMyZju_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_kOrUAMyZju_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_kOrUAMyZju_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the kOrUAMy Some function
function source_kOrUAMy_SomeFunc(source_kOrUAMy)
{
	// [7939] set the function logic
	if (source_kOrUAMy == 2)
	{
		return true;
	}
	return false;
}

// the kOrUAMy Some function
function build_kOrUAMy_SomeFunc(build_kOrUAMy)
{
	// [7939] set the function logic
	if (build_kOrUAMy == 1)
	{
		return true;
	}
	return false;
}

// the YIZCNWs function
function YIZCNWs(build_YIZCNWs,source_YIZCNWs)
{
	if (isSet(build_YIZCNWs) && build_YIZCNWs.constructor !== Array)
	{
		var temp_YIZCNWs = build_YIZCNWs;
		var build_YIZCNWs = [];
		build_YIZCNWs.push(temp_YIZCNWs);
	}
	else if (!isSet(build_YIZCNWs))
	{
		var build_YIZCNWs = [];
	}
	var build = build_YIZCNWs.some(build_YIZCNWs_SomeFunc);

	if (isSet(source_YIZCNWs) && source_YIZCNWs.constructor !== Array)
	{
		var temp_YIZCNWs = source_YIZCNWs;
		var source_YIZCNWs = [];
		source_YIZCNWs.push(temp_YIZCNWs);
	}
	else if (!isSet(source_YIZCNWs))
	{
		var source_YIZCNWs = [];
	}
	var source = source_YIZCNWs.some(source_YIZCNWs_SomeFunc);


	// [7952] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_YIZCNWsBCx_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_YIZCNWsBCx_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_YIZCNWsBCx_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_YIZCNWsBCx_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the YIZCNWs Some function
function build_YIZCNWs_SomeFunc(build_YIZCNWs)
{
	// [7939] set the function logic
	if (build_YIZCNWs == 1)
	{
		return true;
	}
	return false;
}

// the YIZCNWs Some function
function source_YIZCNWs_SomeFunc(source_YIZCNWs)
{
	// [7939] set the function logic
	if (source_YIZCNWs == 2)
	{
		return true;
	}
	return false;
}

// the KqzedcN function
function KqzedcN(source_KqzedcN)
{
	if (isSet(source_KqzedcN) && source_KqzedcN.constructor !== Array)
	{
		var temp_KqzedcN = source_KqzedcN;
		var source_KqzedcN = [];
		source_KqzedcN.push(temp_KqzedcN);
	}
	else if (!isSet(source_KqzedcN))
	{
		var source_KqzedcN = [];
	}
	var source = source_KqzedcN.some(source_KqzedcN_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_KqzedcNUWP_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_KqzedcNUWP_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_KqzedcNUWP_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_KqzedcNUWP_required = true;
		}
	}
}

// the KqzedcN Some function
function source_KqzedcN_SomeFunc(source_KqzedcN)
{
	// [7939] set the function logic
	if (source_KqzedcN == 1)
	{
		return true;
	}
	return false;
}

// the XhHkZbW function
function XhHkZbW(source_XhHkZbW)
{
	if (isSet(source_XhHkZbW) && source_XhHkZbW.constructor !== Array)
	{
		var temp_XhHkZbW = source_XhHkZbW;
		var source_XhHkZbW = [];
		source_XhHkZbW.push(temp_XhHkZbW);
	}
	else if (!isSet(source_XhHkZbW))
	{
		var source_XhHkZbW = [];
	}
	var source = source_XhHkZbW.some(source_XhHkZbW_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_XhHkZbWFaf_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_XhHkZbWFaf_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_XhHkZbWFaf_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_XhHkZbWFaf_required = true;
		}
	}
}

// the XhHkZbW Some function
function source_XhHkZbW_SomeFunc(source_XhHkZbW)
{
	// [7939] set the function logic
	if (source_XhHkZbW == 3)
	{
		return true;
	}
	return false;
}

// the VZPUBFP function
function VZPUBFP(link_type_VZPUBFP)
{
	// [7974] set the function logic
	if (link_type_VZPUBFP == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the UiutQRQ function
function UiutQRQ(link_type_UiutQRQ)
{
	// [7974] set the function logic
	if (link_type_UiutQRQ == 1)
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

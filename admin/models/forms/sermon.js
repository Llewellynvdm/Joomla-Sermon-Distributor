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
jform_uZsflMfgTr_required = false;
jform_CqfSUiFuhT_required = false;
jform_uqyxvuOdbK_required = false;
jform_uqVcAgSnSM_required = false;
jform_HCeOmWWRle_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_uZsflMf = jQuery("#jform_source").val();
	uZsflMf(source_uZsflMf);

	var source_BzXllfH = jQuery("#jform_source").val();
	var build_BzXllfH = jQuery("#jform_build input[type='radio']:checked").val();
	BzXllfH(source_BzXllfH,build_BzXllfH);

	var source_CqfSUiF = jQuery("#jform_source").val();
	var build_CqfSUiF = jQuery("#jform_build input[type='radio']:checked").val();
	CqfSUiF(source_CqfSUiF,build_CqfSUiF);

	var build_uqyxvuO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_uqyxvuO = jQuery("#jform_source").val();
	uqyxvuO(build_uqyxvuO,source_uqyxvuO);

	var source_uqVcAgS = jQuery("#jform_source").val();
	uqVcAgS(source_uqVcAgS);

	var source_HCeOmWW = jQuery("#jform_source").val();
	HCeOmWW(source_HCeOmWW);

	var link_type_MMtrqVI = jQuery("#jform_link_type input[type='radio']:checked").val();
	MMtrqVI(link_type_MMtrqVI);

	var link_type_cZNPuaM = jQuery("#jform_link_type input[type='radio']:checked").val();
	cZNPuaM(link_type_cZNPuaM);
});

// the uZsflMf function
function uZsflMf(source_uZsflMf)
{
	if (isSet(source_uZsflMf) && source_uZsflMf.constructor !== Array)
	{
		var temp_uZsflMf = source_uZsflMf;
		var source_uZsflMf = [];
		source_uZsflMf.push(temp_uZsflMf);
	}
	else if (!isSet(source_uZsflMf))
	{
		var source_uZsflMf = [];
	}
	var source = source_uZsflMf.some(source_uZsflMf_SomeFunc);


	// [7980] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_uZsflMfgTr_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_uZsflMfgTr_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_uZsflMfgTr_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_uZsflMfgTr_required = true;
		}
	}
}

// the uZsflMf Some function
function source_uZsflMf_SomeFunc(source_uZsflMf)
{
	// [7967] set the function logic
	if (source_uZsflMf == 2)
	{
		return true;
	}
	return false;
}

// the BzXllfH function
function BzXllfH(source_BzXllfH,build_BzXllfH)
{
	if (isSet(source_BzXllfH) && source_BzXllfH.constructor !== Array)
	{
		var temp_BzXllfH = source_BzXllfH;
		var source_BzXllfH = [];
		source_BzXllfH.push(temp_BzXllfH);
	}
	else if (!isSet(source_BzXllfH))
	{
		var source_BzXllfH = [];
	}
	var source = source_BzXllfH.some(source_BzXllfH_SomeFunc);

	if (isSet(build_BzXllfH) && build_BzXllfH.constructor !== Array)
	{
		var temp_BzXllfH = build_BzXllfH;
		var build_BzXllfH = [];
		build_BzXllfH.push(temp_BzXllfH);
	}
	else if (!isSet(build_BzXllfH))
	{
		var build_BzXllfH = [];
	}
	var build = build_BzXllfH.some(build_BzXllfH_SomeFunc);


	// [7980] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the BzXllfH Some function
function source_BzXllfH_SomeFunc(source_BzXllfH)
{
	// [7967] set the function logic
	if (source_BzXllfH == 2)
	{
		return true;
	}
	return false;
}

// the BzXllfH Some function
function build_BzXllfH_SomeFunc(build_BzXllfH)
{
	// [7967] set the function logic
	if (build_BzXllfH == 2)
	{
		return true;
	}
	return false;
}

// the CqfSUiF function
function CqfSUiF(source_CqfSUiF,build_CqfSUiF)
{
	if (isSet(source_CqfSUiF) && source_CqfSUiF.constructor !== Array)
	{
		var temp_CqfSUiF = source_CqfSUiF;
		var source_CqfSUiF = [];
		source_CqfSUiF.push(temp_CqfSUiF);
	}
	else if (!isSet(source_CqfSUiF))
	{
		var source_CqfSUiF = [];
	}
	var source = source_CqfSUiF.some(source_CqfSUiF_SomeFunc);

	if (isSet(build_CqfSUiF) && build_CqfSUiF.constructor !== Array)
	{
		var temp_CqfSUiF = build_CqfSUiF;
		var build_CqfSUiF = [];
		build_CqfSUiF.push(temp_CqfSUiF);
	}
	else if (!isSet(build_CqfSUiF))
	{
		var build_CqfSUiF = [];
	}
	var build = build_CqfSUiF.some(build_CqfSUiF_SomeFunc);


	// [7980] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_CqfSUiFuhT_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_CqfSUiFuhT_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_CqfSUiFuhT_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_CqfSUiFuhT_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the CqfSUiF Some function
function source_CqfSUiF_SomeFunc(source_CqfSUiF)
{
	// [7967] set the function logic
	if (source_CqfSUiF == 2)
	{
		return true;
	}
	return false;
}

// the CqfSUiF Some function
function build_CqfSUiF_SomeFunc(build_CqfSUiF)
{
	// [7967] set the function logic
	if (build_CqfSUiF == 1)
	{
		return true;
	}
	return false;
}

// the uqyxvuO function
function uqyxvuO(build_uqyxvuO,source_uqyxvuO)
{
	if (isSet(build_uqyxvuO) && build_uqyxvuO.constructor !== Array)
	{
		var temp_uqyxvuO = build_uqyxvuO;
		var build_uqyxvuO = [];
		build_uqyxvuO.push(temp_uqyxvuO);
	}
	else if (!isSet(build_uqyxvuO))
	{
		var build_uqyxvuO = [];
	}
	var build = build_uqyxvuO.some(build_uqyxvuO_SomeFunc);

	if (isSet(source_uqyxvuO) && source_uqyxvuO.constructor !== Array)
	{
		var temp_uqyxvuO = source_uqyxvuO;
		var source_uqyxvuO = [];
		source_uqyxvuO.push(temp_uqyxvuO);
	}
	else if (!isSet(source_uqyxvuO))
	{
		var source_uqyxvuO = [];
	}
	var source = source_uqyxvuO.some(source_uqyxvuO_SomeFunc);


	// [7980] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_uqyxvuOdbK_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_uqyxvuOdbK_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_uqyxvuOdbK_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_uqyxvuOdbK_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the uqyxvuO Some function
function build_uqyxvuO_SomeFunc(build_uqyxvuO)
{
	// [7967] set the function logic
	if (build_uqyxvuO == 1)
	{
		return true;
	}
	return false;
}

// the uqyxvuO Some function
function source_uqyxvuO_SomeFunc(source_uqyxvuO)
{
	// [7967] set the function logic
	if (source_uqyxvuO == 2)
	{
		return true;
	}
	return false;
}

// the uqVcAgS function
function uqVcAgS(source_uqVcAgS)
{
	if (isSet(source_uqVcAgS) && source_uqVcAgS.constructor !== Array)
	{
		var temp_uqVcAgS = source_uqVcAgS;
		var source_uqVcAgS = [];
		source_uqVcAgS.push(temp_uqVcAgS);
	}
	else if (!isSet(source_uqVcAgS))
	{
		var source_uqVcAgS = [];
	}
	var source = source_uqVcAgS.some(source_uqVcAgS_SomeFunc);


	// [7980] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_uqVcAgSnSM_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_uqVcAgSnSM_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_uqVcAgSnSM_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_uqVcAgSnSM_required = true;
		}
	}
}

// the uqVcAgS Some function
function source_uqVcAgS_SomeFunc(source_uqVcAgS)
{
	// [7967] set the function logic
	if (source_uqVcAgS == 1)
	{
		return true;
	}
	return false;
}

// the HCeOmWW function
function HCeOmWW(source_HCeOmWW)
{
	if (isSet(source_HCeOmWW) && source_HCeOmWW.constructor !== Array)
	{
		var temp_HCeOmWW = source_HCeOmWW;
		var source_HCeOmWW = [];
		source_HCeOmWW.push(temp_HCeOmWW);
	}
	else if (!isSet(source_HCeOmWW))
	{
		var source_HCeOmWW = [];
	}
	var source = source_HCeOmWW.some(source_HCeOmWW_SomeFunc);


	// [7980] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_HCeOmWWRle_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_HCeOmWWRle_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_HCeOmWWRle_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_HCeOmWWRle_required = true;
		}
	}
}

// the HCeOmWW Some function
function source_HCeOmWW_SomeFunc(source_HCeOmWW)
{
	// [7967] set the function logic
	if (source_HCeOmWW == 3)
	{
		return true;
	}
	return false;
}

// the MMtrqVI function
function MMtrqVI(link_type_MMtrqVI)
{
	// [8002] set the function logic
	if (link_type_MMtrqVI == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the cZNPuaM function
function cZNPuaM(link_type_cZNPuaM)
{
	// [8002] set the function logic
	if (link_type_cZNPuaM == 1)
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

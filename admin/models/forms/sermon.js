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
jform_yiajFDoAlz_required = false;
jform_BpsslKyQPr_required = false;
jform_ZwSoQfKCIO_required = false;
jform_gVxKSDylWj_required = false;
jform_RyyiYEPpBI_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_yiajFDo = jQuery("#jform_source").val();
	yiajFDo(source_yiajFDo);

	var source_GHhCiBF = jQuery("#jform_source").val();
	var build_GHhCiBF = jQuery("#jform_build input[type='radio']:checked").val();
	GHhCiBF(source_GHhCiBF,build_GHhCiBF);

	var source_BpsslKy = jQuery("#jform_source").val();
	var build_BpsslKy = jQuery("#jform_build input[type='radio']:checked").val();
	BpsslKy(source_BpsslKy,build_BpsslKy);

	var build_ZwSoQfK = jQuery("#jform_build input[type='radio']:checked").val();
	var source_ZwSoQfK = jQuery("#jform_source").val();
	ZwSoQfK(build_ZwSoQfK,source_ZwSoQfK);

	var source_gVxKSDy = jQuery("#jform_source").val();
	gVxKSDy(source_gVxKSDy);

	var source_RyyiYEP = jQuery("#jform_source").val();
	RyyiYEP(source_RyyiYEP);

	var link_type_NAXJLJJ = jQuery("#jform_link_type input[type='radio']:checked").val();
	NAXJLJJ(link_type_NAXJLJJ);

	var link_type_mouetwA = jQuery("#jform_link_type input[type='radio']:checked").val();
	mouetwA(link_type_mouetwA);
});

// the yiajFDo function
function yiajFDo(source_yiajFDo)
{
	if (isSet(source_yiajFDo) && source_yiajFDo.constructor !== Array)
	{
		var temp_yiajFDo = source_yiajFDo;
		var source_yiajFDo = [];
		source_yiajFDo.push(temp_yiajFDo);
	}
	else if (!isSet(source_yiajFDo))
	{
		var source_yiajFDo = [];
	}
	var source = source_yiajFDo.some(source_yiajFDo_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_yiajFDoAlz_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_yiajFDoAlz_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_yiajFDoAlz_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_yiajFDoAlz_required = true;
		}
	}
}

// the yiajFDo Some function
function source_yiajFDo_SomeFunc(source_yiajFDo)
{
	// [Interpretation 7291] set the function logic
	if (source_yiajFDo == 2)
	{
		return true;
	}
	return false;
}

// the GHhCiBF function
function GHhCiBF(source_GHhCiBF,build_GHhCiBF)
{
	if (isSet(source_GHhCiBF) && source_GHhCiBF.constructor !== Array)
	{
		var temp_GHhCiBF = source_GHhCiBF;
		var source_GHhCiBF = [];
		source_GHhCiBF.push(temp_GHhCiBF);
	}
	else if (!isSet(source_GHhCiBF))
	{
		var source_GHhCiBF = [];
	}
	var source = source_GHhCiBF.some(source_GHhCiBF_SomeFunc);

	if (isSet(build_GHhCiBF) && build_GHhCiBF.constructor !== Array)
	{
		var temp_GHhCiBF = build_GHhCiBF;
		var build_GHhCiBF = [];
		build_GHhCiBF.push(temp_GHhCiBF);
	}
	else if (!isSet(build_GHhCiBF))
	{
		var build_GHhCiBF = [];
	}
	var build = build_GHhCiBF.some(build_GHhCiBF_SomeFunc);


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

// the GHhCiBF Some function
function source_GHhCiBF_SomeFunc(source_GHhCiBF)
{
	// [Interpretation 7291] set the function logic
	if (source_GHhCiBF == 2)
	{
		return true;
	}
	return false;
}

// the GHhCiBF Some function
function build_GHhCiBF_SomeFunc(build_GHhCiBF)
{
	// [Interpretation 7291] set the function logic
	if (build_GHhCiBF == 2)
	{
		return true;
	}
	return false;
}

// the BpsslKy function
function BpsslKy(source_BpsslKy,build_BpsslKy)
{
	if (isSet(source_BpsslKy) && source_BpsslKy.constructor !== Array)
	{
		var temp_BpsslKy = source_BpsslKy;
		var source_BpsslKy = [];
		source_BpsslKy.push(temp_BpsslKy);
	}
	else if (!isSet(source_BpsslKy))
	{
		var source_BpsslKy = [];
	}
	var source = source_BpsslKy.some(source_BpsslKy_SomeFunc);

	if (isSet(build_BpsslKy) && build_BpsslKy.constructor !== Array)
	{
		var temp_BpsslKy = build_BpsslKy;
		var build_BpsslKy = [];
		build_BpsslKy.push(temp_BpsslKy);
	}
	else if (!isSet(build_BpsslKy))
	{
		var build_BpsslKy = [];
	}
	var build = build_BpsslKy.some(build_BpsslKy_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_BpsslKyQPr_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_BpsslKyQPr_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_BpsslKyQPr_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_BpsslKyQPr_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the BpsslKy Some function
function source_BpsslKy_SomeFunc(source_BpsslKy)
{
	// [Interpretation 7291] set the function logic
	if (source_BpsslKy == 2)
	{
		return true;
	}
	return false;
}

// the BpsslKy Some function
function build_BpsslKy_SomeFunc(build_BpsslKy)
{
	// [Interpretation 7291] set the function logic
	if (build_BpsslKy == 1)
	{
		return true;
	}
	return false;
}

// the ZwSoQfK function
function ZwSoQfK(build_ZwSoQfK,source_ZwSoQfK)
{
	if (isSet(build_ZwSoQfK) && build_ZwSoQfK.constructor !== Array)
	{
		var temp_ZwSoQfK = build_ZwSoQfK;
		var build_ZwSoQfK = [];
		build_ZwSoQfK.push(temp_ZwSoQfK);
	}
	else if (!isSet(build_ZwSoQfK))
	{
		var build_ZwSoQfK = [];
	}
	var build = build_ZwSoQfK.some(build_ZwSoQfK_SomeFunc);

	if (isSet(source_ZwSoQfK) && source_ZwSoQfK.constructor !== Array)
	{
		var temp_ZwSoQfK = source_ZwSoQfK;
		var source_ZwSoQfK = [];
		source_ZwSoQfK.push(temp_ZwSoQfK);
	}
	else if (!isSet(source_ZwSoQfK))
	{
		var source_ZwSoQfK = [];
	}
	var source = source_ZwSoQfK.some(source_ZwSoQfK_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_ZwSoQfKCIO_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_ZwSoQfKCIO_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_ZwSoQfKCIO_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_ZwSoQfKCIO_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the ZwSoQfK Some function
function build_ZwSoQfK_SomeFunc(build_ZwSoQfK)
{
	// [Interpretation 7291] set the function logic
	if (build_ZwSoQfK == 1)
	{
		return true;
	}
	return false;
}

// the ZwSoQfK Some function
function source_ZwSoQfK_SomeFunc(source_ZwSoQfK)
{
	// [Interpretation 7291] set the function logic
	if (source_ZwSoQfK == 2)
	{
		return true;
	}
	return false;
}

// the gVxKSDy function
function gVxKSDy(source_gVxKSDy)
{
	if (isSet(source_gVxKSDy) && source_gVxKSDy.constructor !== Array)
	{
		var temp_gVxKSDy = source_gVxKSDy;
		var source_gVxKSDy = [];
		source_gVxKSDy.push(temp_gVxKSDy);
	}
	else if (!isSet(source_gVxKSDy))
	{
		var source_gVxKSDy = [];
	}
	var source = source_gVxKSDy.some(source_gVxKSDy_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_gVxKSDylWj_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_gVxKSDylWj_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_gVxKSDylWj_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_gVxKSDylWj_required = true;
		}
	}
}

// the gVxKSDy Some function
function source_gVxKSDy_SomeFunc(source_gVxKSDy)
{
	// [Interpretation 7291] set the function logic
	if (source_gVxKSDy == 1)
	{
		return true;
	}
	return false;
}

// the RyyiYEP function
function RyyiYEP(source_RyyiYEP)
{
	if (isSet(source_RyyiYEP) && source_RyyiYEP.constructor !== Array)
	{
		var temp_RyyiYEP = source_RyyiYEP;
		var source_RyyiYEP = [];
		source_RyyiYEP.push(temp_RyyiYEP);
	}
	else if (!isSet(source_RyyiYEP))
	{
		var source_RyyiYEP = [];
	}
	var source = source_RyyiYEP.some(source_RyyiYEP_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_RyyiYEPpBI_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_RyyiYEPpBI_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_RyyiYEPpBI_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_RyyiYEPpBI_required = true;
		}
	}
}

// the RyyiYEP Some function
function source_RyyiYEP_SomeFunc(source_RyyiYEP)
{
	// [Interpretation 7291] set the function logic
	if (source_RyyiYEP == 3)
	{
		return true;
	}
	return false;
}

// the NAXJLJJ function
function NAXJLJJ(link_type_NAXJLJJ)
{
	// [Interpretation 7326] set the function logic
	if (link_type_NAXJLJJ == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the mouetwA function
function mouetwA(link_type_mouetwA)
{
	// [Interpretation 7326] set the function logic
	if (link_type_mouetwA == 1)
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

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
jform_eERxCZssJl_required = false;
jform_WKvxfsZjRQ_required = false;
jform_zmOSMyfqmP_required = false;
jform_bNlgLehdtA_required = false;
jform_nqdRJneIrW_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_eERxCZs = jQuery("#jform_source").val();
	eERxCZs(source_eERxCZs);

	var source_yKLKtte = jQuery("#jform_source").val();
	var build_yKLKtte = jQuery("#jform_build input[type='radio']:checked").val();
	yKLKtte(source_yKLKtte,build_yKLKtte);

	var source_WKvxfsZ = jQuery("#jform_source").val();
	var build_WKvxfsZ = jQuery("#jform_build input[type='radio']:checked").val();
	WKvxfsZ(source_WKvxfsZ,build_WKvxfsZ);

	var build_zmOSMyf = jQuery("#jform_build input[type='radio']:checked").val();
	var source_zmOSMyf = jQuery("#jform_source").val();
	zmOSMyf(build_zmOSMyf,source_zmOSMyf);

	var source_bNlgLeh = jQuery("#jform_source").val();
	bNlgLeh(source_bNlgLeh);

	var source_nqdRJne = jQuery("#jform_source").val();
	nqdRJne(source_nqdRJne);

	var link_type_EYkuTXS = jQuery("#jform_link_type input[type='radio']:checked").val();
	EYkuTXS(link_type_EYkuTXS);

	var link_type_YoiOpWc = jQuery("#jform_link_type input[type='radio']:checked").val();
	YoiOpWc(link_type_YoiOpWc);
});

// the eERxCZs function
function eERxCZs(source_eERxCZs)
{
	if (isSet(source_eERxCZs) && source_eERxCZs.constructor !== Array)
	{
		var temp_eERxCZs = source_eERxCZs;
		var source_eERxCZs = [];
		source_eERxCZs.push(temp_eERxCZs);
	}
	else if (!isSet(source_eERxCZs))
	{
		var source_eERxCZs = [];
	}
	var source = source_eERxCZs.some(source_eERxCZs_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_eERxCZssJl_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_eERxCZssJl_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_eERxCZssJl_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_eERxCZssJl_required = true;
		}
	}
}

// the eERxCZs Some function
function source_eERxCZs_SomeFunc(source_eERxCZs)
{
	// [7939] set the function logic
	if (source_eERxCZs == 2)
	{
		return true;
	}
	return false;
}

// the yKLKtte function
function yKLKtte(source_yKLKtte,build_yKLKtte)
{
	if (isSet(source_yKLKtte) && source_yKLKtte.constructor !== Array)
	{
		var temp_yKLKtte = source_yKLKtte;
		var source_yKLKtte = [];
		source_yKLKtte.push(temp_yKLKtte);
	}
	else if (!isSet(source_yKLKtte))
	{
		var source_yKLKtte = [];
	}
	var source = source_yKLKtte.some(source_yKLKtte_SomeFunc);

	if (isSet(build_yKLKtte) && build_yKLKtte.constructor !== Array)
	{
		var temp_yKLKtte = build_yKLKtte;
		var build_yKLKtte = [];
		build_yKLKtte.push(temp_yKLKtte);
	}
	else if (!isSet(build_yKLKtte))
	{
		var build_yKLKtte = [];
	}
	var build = build_yKLKtte.some(build_yKLKtte_SomeFunc);


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

// the yKLKtte Some function
function source_yKLKtte_SomeFunc(source_yKLKtte)
{
	// [7939] set the function logic
	if (source_yKLKtte == 2)
	{
		return true;
	}
	return false;
}

// the yKLKtte Some function
function build_yKLKtte_SomeFunc(build_yKLKtte)
{
	// [7939] set the function logic
	if (build_yKLKtte == 2)
	{
		return true;
	}
	return false;
}

// the WKvxfsZ function
function WKvxfsZ(source_WKvxfsZ,build_WKvxfsZ)
{
	if (isSet(source_WKvxfsZ) && source_WKvxfsZ.constructor !== Array)
	{
		var temp_WKvxfsZ = source_WKvxfsZ;
		var source_WKvxfsZ = [];
		source_WKvxfsZ.push(temp_WKvxfsZ);
	}
	else if (!isSet(source_WKvxfsZ))
	{
		var source_WKvxfsZ = [];
	}
	var source = source_WKvxfsZ.some(source_WKvxfsZ_SomeFunc);

	if (isSet(build_WKvxfsZ) && build_WKvxfsZ.constructor !== Array)
	{
		var temp_WKvxfsZ = build_WKvxfsZ;
		var build_WKvxfsZ = [];
		build_WKvxfsZ.push(temp_WKvxfsZ);
	}
	else if (!isSet(build_WKvxfsZ))
	{
		var build_WKvxfsZ = [];
	}
	var build = build_WKvxfsZ.some(build_WKvxfsZ_SomeFunc);


	// [7952] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_WKvxfsZjRQ_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_WKvxfsZjRQ_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_WKvxfsZjRQ_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_WKvxfsZjRQ_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the WKvxfsZ Some function
function source_WKvxfsZ_SomeFunc(source_WKvxfsZ)
{
	// [7939] set the function logic
	if (source_WKvxfsZ == 2)
	{
		return true;
	}
	return false;
}

// the WKvxfsZ Some function
function build_WKvxfsZ_SomeFunc(build_WKvxfsZ)
{
	// [7939] set the function logic
	if (build_WKvxfsZ == 1)
	{
		return true;
	}
	return false;
}

// the zmOSMyf function
function zmOSMyf(build_zmOSMyf,source_zmOSMyf)
{
	if (isSet(build_zmOSMyf) && build_zmOSMyf.constructor !== Array)
	{
		var temp_zmOSMyf = build_zmOSMyf;
		var build_zmOSMyf = [];
		build_zmOSMyf.push(temp_zmOSMyf);
	}
	else if (!isSet(build_zmOSMyf))
	{
		var build_zmOSMyf = [];
	}
	var build = build_zmOSMyf.some(build_zmOSMyf_SomeFunc);

	if (isSet(source_zmOSMyf) && source_zmOSMyf.constructor !== Array)
	{
		var temp_zmOSMyf = source_zmOSMyf;
		var source_zmOSMyf = [];
		source_zmOSMyf.push(temp_zmOSMyf);
	}
	else if (!isSet(source_zmOSMyf))
	{
		var source_zmOSMyf = [];
	}
	var source = source_zmOSMyf.some(source_zmOSMyf_SomeFunc);


	// [7952] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_zmOSMyfqmP_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_zmOSMyfqmP_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_zmOSMyfqmP_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_zmOSMyfqmP_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the zmOSMyf Some function
function build_zmOSMyf_SomeFunc(build_zmOSMyf)
{
	// [7939] set the function logic
	if (build_zmOSMyf == 1)
	{
		return true;
	}
	return false;
}

// the zmOSMyf Some function
function source_zmOSMyf_SomeFunc(source_zmOSMyf)
{
	// [7939] set the function logic
	if (source_zmOSMyf == 2)
	{
		return true;
	}
	return false;
}

// the bNlgLeh function
function bNlgLeh(source_bNlgLeh)
{
	if (isSet(source_bNlgLeh) && source_bNlgLeh.constructor !== Array)
	{
		var temp_bNlgLeh = source_bNlgLeh;
		var source_bNlgLeh = [];
		source_bNlgLeh.push(temp_bNlgLeh);
	}
	else if (!isSet(source_bNlgLeh))
	{
		var source_bNlgLeh = [];
	}
	var source = source_bNlgLeh.some(source_bNlgLeh_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_bNlgLehdtA_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_bNlgLehdtA_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_bNlgLehdtA_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_bNlgLehdtA_required = true;
		}
	}
}

// the bNlgLeh Some function
function source_bNlgLeh_SomeFunc(source_bNlgLeh)
{
	// [7939] set the function logic
	if (source_bNlgLeh == 1)
	{
		return true;
	}
	return false;
}

// the nqdRJne function
function nqdRJne(source_nqdRJne)
{
	if (isSet(source_nqdRJne) && source_nqdRJne.constructor !== Array)
	{
		var temp_nqdRJne = source_nqdRJne;
		var source_nqdRJne = [];
		source_nqdRJne.push(temp_nqdRJne);
	}
	else if (!isSet(source_nqdRJne))
	{
		var source_nqdRJne = [];
	}
	var source = source_nqdRJne.some(source_nqdRJne_SomeFunc);


	// [7952] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_nqdRJneIrW_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_nqdRJneIrW_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_nqdRJneIrW_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_nqdRJneIrW_required = true;
		}
	}
}

// the nqdRJne Some function
function source_nqdRJne_SomeFunc(source_nqdRJne)
{
	// [7939] set the function logic
	if (source_nqdRJne == 3)
	{
		return true;
	}
	return false;
}

// the EYkuTXS function
function EYkuTXS(link_type_EYkuTXS)
{
	// [7974] set the function logic
	if (link_type_EYkuTXS == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the YoiOpWc function
function YoiOpWc(link_type_YoiOpWc)
{
	// [7974] set the function logic
	if (link_type_YoiOpWc == 1)
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

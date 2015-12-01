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
jform_NKOinBhjcR_required = false;
jform_tcPmpLTZoG_required = false;
jform_gxilNjehSq_required = false;
jform_oQgboDxRIY_required = false;
jform_YcaNoltkqP_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_NKOinBh = jQuery("#jform_source").val();
	NKOinBh(source_NKOinBh);

	var source_ALgjVVL = jQuery("#jform_source").val();
	var build_ALgjVVL = jQuery("#jform_build input[type='radio']:checked").val();
	ALgjVVL(source_ALgjVVL,build_ALgjVVL);

	var source_tcPmpLT = jQuery("#jform_source").val();
	var build_tcPmpLT = jQuery("#jform_build input[type='radio']:checked").val();
	tcPmpLT(source_tcPmpLT,build_tcPmpLT);

	var build_gxilNje = jQuery("#jform_build input[type='radio']:checked").val();
	var source_gxilNje = jQuery("#jform_source").val();
	gxilNje(build_gxilNje,source_gxilNje);

	var source_oQgboDx = jQuery("#jform_source").val();
	oQgboDx(source_oQgboDx);

	var source_YcaNolt = jQuery("#jform_source").val();
	YcaNolt(source_YcaNolt);

	var link_type_VrtcKcp = jQuery("#jform_link_type input[type='radio']:checked").val();
	VrtcKcp(link_type_VrtcKcp);

	var link_type_KohbyVh = jQuery("#jform_link_type input[type='radio']:checked").val();
	KohbyVh(link_type_KohbyVh);
});

// the NKOinBh function
function NKOinBh(source_NKOinBh)
{
	if (isSet(source_NKOinBh) && source_NKOinBh.constructor !== Array)
	{
		var temp_NKOinBh = source_NKOinBh;
		var source_NKOinBh = [];
		source_NKOinBh.push(temp_NKOinBh);
	}
	else if (!isSet(source_NKOinBh))
	{
		var source_NKOinBh = [];
	}
	var source = source_NKOinBh.some(source_NKOinBh_SomeFunc);


	// [7986] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_NKOinBhjcR_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_NKOinBhjcR_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_NKOinBhjcR_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_NKOinBhjcR_required = true;
		}
	}
}

// the NKOinBh Some function
function source_NKOinBh_SomeFunc(source_NKOinBh)
{
	// [7973] set the function logic
	if (source_NKOinBh == 2)
	{
		return true;
	}
	return false;
}

// the ALgjVVL function
function ALgjVVL(source_ALgjVVL,build_ALgjVVL)
{
	if (isSet(source_ALgjVVL) && source_ALgjVVL.constructor !== Array)
	{
		var temp_ALgjVVL = source_ALgjVVL;
		var source_ALgjVVL = [];
		source_ALgjVVL.push(temp_ALgjVVL);
	}
	else if (!isSet(source_ALgjVVL))
	{
		var source_ALgjVVL = [];
	}
	var source = source_ALgjVVL.some(source_ALgjVVL_SomeFunc);

	if (isSet(build_ALgjVVL) && build_ALgjVVL.constructor !== Array)
	{
		var temp_ALgjVVL = build_ALgjVVL;
		var build_ALgjVVL = [];
		build_ALgjVVL.push(temp_ALgjVVL);
	}
	else if (!isSet(build_ALgjVVL))
	{
		var build_ALgjVVL = [];
	}
	var build = build_ALgjVVL.some(build_ALgjVVL_SomeFunc);


	// [7986] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the ALgjVVL Some function
function source_ALgjVVL_SomeFunc(source_ALgjVVL)
{
	// [7973] set the function logic
	if (source_ALgjVVL == 2)
	{
		return true;
	}
	return false;
}

// the ALgjVVL Some function
function build_ALgjVVL_SomeFunc(build_ALgjVVL)
{
	// [7973] set the function logic
	if (build_ALgjVVL == 2)
	{
		return true;
	}
	return false;
}

// the tcPmpLT function
function tcPmpLT(source_tcPmpLT,build_tcPmpLT)
{
	if (isSet(source_tcPmpLT) && source_tcPmpLT.constructor !== Array)
	{
		var temp_tcPmpLT = source_tcPmpLT;
		var source_tcPmpLT = [];
		source_tcPmpLT.push(temp_tcPmpLT);
	}
	else if (!isSet(source_tcPmpLT))
	{
		var source_tcPmpLT = [];
	}
	var source = source_tcPmpLT.some(source_tcPmpLT_SomeFunc);

	if (isSet(build_tcPmpLT) && build_tcPmpLT.constructor !== Array)
	{
		var temp_tcPmpLT = build_tcPmpLT;
		var build_tcPmpLT = [];
		build_tcPmpLT.push(temp_tcPmpLT);
	}
	else if (!isSet(build_tcPmpLT))
	{
		var build_tcPmpLT = [];
	}
	var build = build_tcPmpLT.some(build_tcPmpLT_SomeFunc);


	// [7986] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_tcPmpLTZoG_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_tcPmpLTZoG_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_tcPmpLTZoG_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_tcPmpLTZoG_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the tcPmpLT Some function
function source_tcPmpLT_SomeFunc(source_tcPmpLT)
{
	// [7973] set the function logic
	if (source_tcPmpLT == 2)
	{
		return true;
	}
	return false;
}

// the tcPmpLT Some function
function build_tcPmpLT_SomeFunc(build_tcPmpLT)
{
	// [7973] set the function logic
	if (build_tcPmpLT == 1)
	{
		return true;
	}
	return false;
}

// the gxilNje function
function gxilNje(build_gxilNje,source_gxilNje)
{
	if (isSet(build_gxilNje) && build_gxilNje.constructor !== Array)
	{
		var temp_gxilNje = build_gxilNje;
		var build_gxilNje = [];
		build_gxilNje.push(temp_gxilNje);
	}
	else if (!isSet(build_gxilNje))
	{
		var build_gxilNje = [];
	}
	var build = build_gxilNje.some(build_gxilNje_SomeFunc);

	if (isSet(source_gxilNje) && source_gxilNje.constructor !== Array)
	{
		var temp_gxilNje = source_gxilNje;
		var source_gxilNje = [];
		source_gxilNje.push(temp_gxilNje);
	}
	else if (!isSet(source_gxilNje))
	{
		var source_gxilNje = [];
	}
	var source = source_gxilNje.some(source_gxilNje_SomeFunc);


	// [7986] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_gxilNjehSq_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_gxilNjehSq_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_gxilNjehSq_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_gxilNjehSq_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the gxilNje Some function
function build_gxilNje_SomeFunc(build_gxilNje)
{
	// [7973] set the function logic
	if (build_gxilNje == 1)
	{
		return true;
	}
	return false;
}

// the gxilNje Some function
function source_gxilNje_SomeFunc(source_gxilNje)
{
	// [7973] set the function logic
	if (source_gxilNje == 2)
	{
		return true;
	}
	return false;
}

// the oQgboDx function
function oQgboDx(source_oQgboDx)
{
	if (isSet(source_oQgboDx) && source_oQgboDx.constructor !== Array)
	{
		var temp_oQgboDx = source_oQgboDx;
		var source_oQgboDx = [];
		source_oQgboDx.push(temp_oQgboDx);
	}
	else if (!isSet(source_oQgboDx))
	{
		var source_oQgboDx = [];
	}
	var source = source_oQgboDx.some(source_oQgboDx_SomeFunc);


	// [7986] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_oQgboDxRIY_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_oQgboDxRIY_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_oQgboDxRIY_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_oQgboDxRIY_required = true;
		}
	}
}

// the oQgboDx Some function
function source_oQgboDx_SomeFunc(source_oQgboDx)
{
	// [7973] set the function logic
	if (source_oQgboDx == 1)
	{
		return true;
	}
	return false;
}

// the YcaNolt function
function YcaNolt(source_YcaNolt)
{
	if (isSet(source_YcaNolt) && source_YcaNolt.constructor !== Array)
	{
		var temp_YcaNolt = source_YcaNolt;
		var source_YcaNolt = [];
		source_YcaNolt.push(temp_YcaNolt);
	}
	else if (!isSet(source_YcaNolt))
	{
		var source_YcaNolt = [];
	}
	var source = source_YcaNolt.some(source_YcaNolt_SomeFunc);


	// [7986] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_YcaNoltkqP_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_YcaNoltkqP_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_YcaNoltkqP_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_YcaNoltkqP_required = true;
		}
	}
}

// the YcaNolt Some function
function source_YcaNolt_SomeFunc(source_YcaNolt)
{
	// [7973] set the function logic
	if (source_YcaNolt == 3)
	{
		return true;
	}
	return false;
}

// the VrtcKcp function
function VrtcKcp(link_type_VrtcKcp)
{
	// [8008] set the function logic
	if (link_type_VrtcKcp == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the KohbyVh function
function KohbyVh(link_type_KohbyVh)
{
	// [8008] set the function logic
	if (link_type_KohbyVh == 1)
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

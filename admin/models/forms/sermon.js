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
	@build			11th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_qnBNDdgYsI_required = false;
jform_tIcVEBHbWb_required = false;
jform_KtgSzzAboh_required = false;
jform_MEmRboexQl_required = false;
jform_UeQYbvAIDD_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_qnBNDdg = jQuery("#jform_source").val();
	qnBNDdg(source_qnBNDdg);

	var source_CWzLshf = jQuery("#jform_source").val();
	var build_CWzLshf = jQuery("#jform_build input[type='radio']:checked").val();
	CWzLshf(source_CWzLshf,build_CWzLshf);

	var source_tIcVEBH = jQuery("#jform_source").val();
	var build_tIcVEBH = jQuery("#jform_build input[type='radio']:checked").val();
	tIcVEBH(source_tIcVEBH,build_tIcVEBH);

	var build_KtgSzzA = jQuery("#jform_build input[type='radio']:checked").val();
	var source_KtgSzzA = jQuery("#jform_source").val();
	KtgSzzA(build_KtgSzzA,source_KtgSzzA);

	var source_MEmRboe = jQuery("#jform_source").val();
	MEmRboe(source_MEmRboe);

	var source_UeQYbvA = jQuery("#jform_source").val();
	UeQYbvA(source_UeQYbvA);

	var link_type_CrWJiuj = jQuery("#jform_link_type input[type='radio']:checked").val();
	CrWJiuj(link_type_CrWJiuj);

	var link_type_JxwVegn = jQuery("#jform_link_type input[type='radio']:checked").val();
	JxwVegn(link_type_JxwVegn);
});

// the qnBNDdg function
function qnBNDdg(source_qnBNDdg)
{
	if (isSet(source_qnBNDdg) && source_qnBNDdg.constructor !== Array)
	{
		var temp_qnBNDdg = source_qnBNDdg;
		var source_qnBNDdg = [];
		source_qnBNDdg.push(temp_qnBNDdg);
	}
	else if (!isSet(source_qnBNDdg))
	{
		var source_qnBNDdg = [];
	}
	var source = source_qnBNDdg.some(source_qnBNDdg_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_qnBNDdgYsI_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_qnBNDdgYsI_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_qnBNDdgYsI_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_qnBNDdgYsI_required = true;
		}
	}
}

// the qnBNDdg Some function
function source_qnBNDdg_SomeFunc(source_qnBNDdg)
{
	// [8272] set the function logic
	if (source_qnBNDdg == 2)
	{
		return true;
	}
	return false;
}

// the CWzLshf function
function CWzLshf(source_CWzLshf,build_CWzLshf)
{
	if (isSet(source_CWzLshf) && source_CWzLshf.constructor !== Array)
	{
		var temp_CWzLshf = source_CWzLshf;
		var source_CWzLshf = [];
		source_CWzLshf.push(temp_CWzLshf);
	}
	else if (!isSet(source_CWzLshf))
	{
		var source_CWzLshf = [];
	}
	var source = source_CWzLshf.some(source_CWzLshf_SomeFunc);

	if (isSet(build_CWzLshf) && build_CWzLshf.constructor !== Array)
	{
		var temp_CWzLshf = build_CWzLshf;
		var build_CWzLshf = [];
		build_CWzLshf.push(temp_CWzLshf);
	}
	else if (!isSet(build_CWzLshf))
	{
		var build_CWzLshf = [];
	}
	var build = build_CWzLshf.some(build_CWzLshf_SomeFunc);


	// [8285] set this function logic
	if (source && build)
	{
		jQuery('.note_auto_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_dropbox').closest('.control-group').hide();
	}
}

// the CWzLshf Some function
function source_CWzLshf_SomeFunc(source_CWzLshf)
{
	// [8272] set the function logic
	if (source_CWzLshf == 2)
	{
		return true;
	}
	return false;
}

// the CWzLshf Some function
function build_CWzLshf_SomeFunc(build_CWzLshf)
{
	// [8272] set the function logic
	if (build_CWzLshf == 2)
	{
		return true;
	}
	return false;
}

// the tIcVEBH function
function tIcVEBH(source_tIcVEBH,build_tIcVEBH)
{
	if (isSet(source_tIcVEBH) && source_tIcVEBH.constructor !== Array)
	{
		var temp_tIcVEBH = source_tIcVEBH;
		var source_tIcVEBH = [];
		source_tIcVEBH.push(temp_tIcVEBH);
	}
	else if (!isSet(source_tIcVEBH))
	{
		var source_tIcVEBH = [];
	}
	var source = source_tIcVEBH.some(source_tIcVEBH_SomeFunc);

	if (isSet(build_tIcVEBH) && build_tIcVEBH.constructor !== Array)
	{
		var temp_tIcVEBH = build_tIcVEBH;
		var build_tIcVEBH = [];
		build_tIcVEBH.push(temp_tIcVEBH);
	}
	else if (!isSet(build_tIcVEBH))
	{
		var build_tIcVEBH = [];
	}
	var build = build_tIcVEBH.some(build_tIcVEBH_SomeFunc);


	// [8285] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_tIcVEBHbWb_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_tIcVEBHbWb_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_tIcVEBHbWb_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_tIcVEBHbWb_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the tIcVEBH Some function
function source_tIcVEBH_SomeFunc(source_tIcVEBH)
{
	// [8272] set the function logic
	if (source_tIcVEBH == 2)
	{
		return true;
	}
	return false;
}

// the tIcVEBH Some function
function build_tIcVEBH_SomeFunc(build_tIcVEBH)
{
	// [8272] set the function logic
	if (build_tIcVEBH == 1)
	{
		return true;
	}
	return false;
}

// the KtgSzzA function
function KtgSzzA(build_KtgSzzA,source_KtgSzzA)
{
	if (isSet(build_KtgSzzA) && build_KtgSzzA.constructor !== Array)
	{
		var temp_KtgSzzA = build_KtgSzzA;
		var build_KtgSzzA = [];
		build_KtgSzzA.push(temp_KtgSzzA);
	}
	else if (!isSet(build_KtgSzzA))
	{
		var build_KtgSzzA = [];
	}
	var build = build_KtgSzzA.some(build_KtgSzzA_SomeFunc);

	if (isSet(source_KtgSzzA) && source_KtgSzzA.constructor !== Array)
	{
		var temp_KtgSzzA = source_KtgSzzA;
		var source_KtgSzzA = [];
		source_KtgSzzA.push(temp_KtgSzzA);
	}
	else if (!isSet(source_KtgSzzA))
	{
		var source_KtgSzzA = [];
	}
	var source = source_KtgSzzA.some(source_KtgSzzA_SomeFunc);


	// [8285] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_KtgSzzAboh_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_KtgSzzAboh_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_KtgSzzAboh_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_KtgSzzAboh_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the KtgSzzA Some function
function build_KtgSzzA_SomeFunc(build_KtgSzzA)
{
	// [8272] set the function logic
	if (build_KtgSzzA == 1)
	{
		return true;
	}
	return false;
}

// the KtgSzzA Some function
function source_KtgSzzA_SomeFunc(source_KtgSzzA)
{
	// [8272] set the function logic
	if (source_KtgSzzA == 2)
	{
		return true;
	}
	return false;
}

// the MEmRboe function
function MEmRboe(source_MEmRboe)
{
	if (isSet(source_MEmRboe) && source_MEmRboe.constructor !== Array)
	{
		var temp_MEmRboe = source_MEmRboe;
		var source_MEmRboe = [];
		source_MEmRboe.push(temp_MEmRboe);
	}
	else if (!isSet(source_MEmRboe))
	{
		var source_MEmRboe = [];
	}
	var source = source_MEmRboe.some(source_MEmRboe_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_MEmRboexQl_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_MEmRboexQl_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_MEmRboexQl_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_MEmRboexQl_required = true;
		}
	}
}

// the MEmRboe Some function
function source_MEmRboe_SomeFunc(source_MEmRboe)
{
	// [8272] set the function logic
	if (source_MEmRboe == 1)
	{
		return true;
	}
	return false;
}

// the UeQYbvA function
function UeQYbvA(source_UeQYbvA)
{
	if (isSet(source_UeQYbvA) && source_UeQYbvA.constructor !== Array)
	{
		var temp_UeQYbvA = source_UeQYbvA;
		var source_UeQYbvA = [];
		source_UeQYbvA.push(temp_UeQYbvA);
	}
	else if (!isSet(source_UeQYbvA))
	{
		var source_UeQYbvA = [];
	}
	var source = source_UeQYbvA.some(source_UeQYbvA_SomeFunc);


	// [8285] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_UeQYbvAIDD_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_UeQYbvAIDD_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_UeQYbvAIDD_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_UeQYbvAIDD_required = true;
		}
	}
}

// the UeQYbvA Some function
function source_UeQYbvA_SomeFunc(source_UeQYbvA)
{
	// [8272] set the function logic
	if (source_UeQYbvA == 3)
	{
		return true;
	}
	return false;
}

// the CrWJiuj function
function CrWJiuj(link_type_CrWJiuj)
{
	// [8307] set the function logic
	if (link_type_CrWJiuj == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the JxwVegn function
function JxwVegn(link_type_JxwVegn)
{
	// [8307] set the function logic
	if (link_type_JxwVegn == 1)
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

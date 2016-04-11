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

	@version		1.3.2
	@build			11th April, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvvvvvw_required = false;
jform_vvvvvvyvvx_required = false;
jform_vvvvvvzvvy_required = false;
jform_vvvvvwavvz_required = false;
jform_vvvvvwbvwa_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var source_vvvvvvv = jQuery("#jform_source").val();
	vvvvvvv(source_vvvvvvv);

	var source_vvvvvvw = jQuery("#jform_source").val();
	var build_vvvvvvw = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvw(source_vvvvvvw,build_vvvvvvw);

	var source_vvvvvvy = jQuery("#jform_source").val();
	var build_vvvvvvy = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvy(source_vvvvvvy,build_vvvvvvy);

	var build_vvvvvvz = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vvvvvvz = jQuery("#jform_source").val();
	vvvvvvz(build_vvvvvvz,source_vvvvvvz);

	var source_vvvvvwa = jQuery("#jform_source").val();
	vvvvvwa(source_vvvvvwa);

	var source_vvvvvwb = jQuery("#jform_source").val();
	vvvvvwb(source_vvvvvwb);

	var link_type_vvvvvwc = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwc(link_type_vvvvvwc);

	var link_type_vvvvvwd = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwd(link_type_vvvvvwd);
});

// the vvvvvvv function
function vvvvvvv(source_vvvvvvv)
{
	if (isSet(source_vvvvvvv) && source_vvvvvvv.constructor !== Array)
	{
		var temp_vvvvvvv = source_vvvvvvv;
		var source_vvvvvvv = [];
		source_vvvvvvv.push(temp_vvvvvvv);
	}
	else if (!isSet(source_vvvvvvv))
	{
		var source_vvvvvvv = [];
	}
	var source = source_vvvvvvv.some(source_vvvvvvv_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_build').closest('.control-group').show();
		if (jform_vvvvvvvvvw_required)
		{
			updateFieldRequired('build',0);
			jQuery('#jform_build').prop('required','required');
			jQuery('#jform_build').attr('aria-required',true);
			jQuery('#jform_build').addClass('required');
			jform_vvvvvvvvvw_required = false;
		}

	}
	else
	{
		jQuery('#jform_build').closest('.control-group').hide();
		if (!jform_vvvvvvvvvw_required)
		{
			updateFieldRequired('build',1);
			jQuery('#jform_build').removeAttr('required');
			jQuery('#jform_build').removeAttr('aria-required');
			jQuery('#jform_build').removeClass('required');
			jform_vvvvvvvvvw_required = true;
		}
	}
}

// the vvvvvvv Some function
function source_vvvvvvv_SomeFunc(source_vvvvvvv)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvvv == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvvw function
function vvvvvvw(source_vvvvvvw,build_vvvvvvw)
{
	if (isSet(source_vvvvvvw) && source_vvvvvvw.constructor !== Array)
	{
		var temp_vvvvvvw = source_vvvvvvw;
		var source_vvvvvvw = [];
		source_vvvvvvw.push(temp_vvvvvvw);
	}
	else if (!isSet(source_vvvvvvw))
	{
		var source_vvvvvvw = [];
	}
	var source = source_vvvvvvw.some(source_vvvvvvw_SomeFunc);

	if (isSet(build_vvvvvvw) && build_vvvvvvw.constructor !== Array)
	{
		var temp_vvvvvvw = build_vvvvvvw;
		var build_vvvvvvw = [];
		build_vvvvvvw.push(temp_vvvvvvw);
	}
	else if (!isSet(build_vvvvvvw))
	{
		var build_vvvvvvw = [];
	}
	var build = build_vvvvvvw.some(build_vvvvvvw_SomeFunc);


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

// the vvvvvvw Some function
function source_vvvvvvw_SomeFunc(source_vvvvvvw)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvvw == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvvw Some function
function build_vvvvvvw_SomeFunc(build_vvvvvvw)
{
	// [Interpretation 7291] set the function logic
	if (build_vvvvvvw == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvvy function
function vvvvvvy(source_vvvvvvy,build_vvvvvvy)
{
	if (isSet(source_vvvvvvy) && source_vvvvvvy.constructor !== Array)
	{
		var temp_vvvvvvy = source_vvvvvvy;
		var source_vvvvvvy = [];
		source_vvvvvvy.push(temp_vvvvvvy);
	}
	else if (!isSet(source_vvvvvvy))
	{
		var source_vvvvvvy = [];
	}
	var source = source_vvvvvvy.some(source_vvvvvvy_SomeFunc);

	if (isSet(build_vvvvvvy) && build_vvvvvvy.constructor !== Array)
	{
		var temp_vvvvvvy = build_vvvvvvy;
		var build_vvvvvvy = [];
		build_vvvvvvy.push(temp_vvvvvvy);
	}
	else if (!isSet(build_vvvvvvy))
	{
		var build_vvvvvvy = [];
	}
	var build = build_vvvvvvy.some(build_vvvvvvy_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source && build)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_vvvvvvyvvx_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_vvvvvvyvvx_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_vvvvvvyvvx_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_vvvvvvyvvx_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the vvvvvvy Some function
function source_vvvvvvy_SomeFunc(source_vvvvvvy)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvvy == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvvy Some function
function build_vvvvvvy_SomeFunc(build_vvvvvvy)
{
	// [Interpretation 7291] set the function logic
	if (build_vvvvvvy == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvvz function
function vvvvvvz(build_vvvvvvz,source_vvvvvvz)
{
	if (isSet(build_vvvvvvz) && build_vvvvvvz.constructor !== Array)
	{
		var temp_vvvvvvz = build_vvvvvvz;
		var build_vvvvvvz = [];
		build_vvvvvvz.push(temp_vvvvvvz);
	}
	else if (!isSet(build_vvvvvvz))
	{
		var build_vvvvvvz = [];
	}
	var build = build_vvvvvvz.some(build_vvvvvvz_SomeFunc);

	if (isSet(source_vvvvvvz) && source_vvvvvvz.constructor !== Array)
	{
		var temp_vvvvvvz = source_vvvvvvz;
		var source_vvvvvvz = [];
		source_vvvvvvz.push(temp_vvvvvvz);
	}
	else if (!isSet(source_vvvvvvz))
	{
		var source_vvvvvvz = [];
	}
	var source = source_vvvvvvz.some(source_vvvvvvz_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (build && source)
	{
		jQuery('#jform_manual_files').closest('.control-group').show();
		if (jform_vvvvvvzvvy_required)
		{
			updateFieldRequired('manual_files',0);
			jQuery('#jform_manual_files').prop('required','required');
			jQuery('#jform_manual_files').attr('aria-required',true);
			jQuery('#jform_manual_files').addClass('required');
			jform_vvvvvvzvvy_required = false;
		}

		jQuery('.note_manual_dropbox').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_manual_files').closest('.control-group').hide();
		if (!jform_vvvvvvzvvy_required)
		{
			updateFieldRequired('manual_files',1);
			jQuery('#jform_manual_files').removeAttr('required');
			jQuery('#jform_manual_files').removeAttr('aria-required');
			jQuery('#jform_manual_files').removeClass('required');
			jform_vvvvvvzvvy_required = true;
		}
		jQuery('.note_manual_dropbox').closest('.control-group').hide();
	}
}

// the vvvvvvz Some function
function build_vvvvvvz_SomeFunc(build_vvvvvvz)
{
	// [Interpretation 7291] set the function logic
	if (build_vvvvvvz == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvvz Some function
function source_vvvvvvz_SomeFunc(source_vvvvvvz)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvvz == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwa function
function vvvvvwa(source_vvvvvwa)
{
	if (isSet(source_vvvvvwa) && source_vvvvvwa.constructor !== Array)
	{
		var temp_vvvvvwa = source_vvvvvwa;
		var source_vvvvvwa = [];
		source_vvvvvwa.push(temp_vvvvvwa);
	}
	else if (!isSet(source_vvvvvwa))
	{
		var source_vvvvvwa = [];
	}
	var source = source_vvvvvwa.some(source_vvvvvwa_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_local_files').closest('.control-group').show();
		if (jform_vvvvvwavvz_required)
		{
			updateFieldRequired('local_files',0);
			jQuery('#jform_local_files').prop('required','required');
			jQuery('#jform_local_files').attr('aria-required',true);
			jQuery('#jform_local_files').addClass('required');
			jform_vvvvvwavvz_required = false;
		}

	}
	else
	{
		jQuery('#jform_local_files').closest('.control-group').hide();
		if (!jform_vvvvvwavvz_required)
		{
			updateFieldRequired('local_files',1);
			jQuery('#jform_local_files').removeAttr('required');
			jQuery('#jform_local_files').removeAttr('aria-required');
			jQuery('#jform_local_files').removeClass('required');
			jform_vvvvvwavvz_required = true;
		}
	}
}

// the vvvvvwa Some function
function source_vvvvvwa_SomeFunc(source_vvvvvwa)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvwa == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwb function
function vvvvvwb(source_vvvvvwb)
{
	if (isSet(source_vvvvvwb) && source_vvvvvwb.constructor !== Array)
	{
		var temp_vvvvvwb = source_vvvvvwb;
		var source_vvvvvwb = [];
		source_vvvvvwb.push(temp_vvvvvwb);
	}
	else if (!isSet(source_vvvvvwb))
	{
		var source_vvvvvwb = [];
	}
	var source = source_vvvvvwb.some(source_vvvvvwb_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (source)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_vvvvvwbvwa_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwbvwa_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_vvvvvwbvwa_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwbvwa_required = true;
		}
	}
}

// the vvvvvwb Some function
function source_vvvvvwb_SomeFunc(source_vvvvvwb)
{
	// [Interpretation 7291] set the function logic
	if (source_vvvvvwb == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvwc function
function vvvvvwc(link_type_vvvvvwc)
{
	// [Interpretation 7326] set the function logic
	if (link_type_vvvvvwc == 2)
	{
		jQuery('.note_link_directed').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_link_directed').closest('.control-group').hide();
	}
}

// the vvvvvwd function
function vvvvvwd(link_type_vvvvvwd)
{
	// [Interpretation 7326] set the function logic
	if (link_type_vvvvvwd == 1)
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

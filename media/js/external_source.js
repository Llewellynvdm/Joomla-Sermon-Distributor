/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		4.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		external_source.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvwjvwd_required = false;
jform_vvvvvwmvwe_required = false;

// Initial Script
document.addEventListener('DOMContentLoaded', function()
{
	var externalsources_vvvvvwe = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwe = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe);

	var externalsources_vvvvvwg = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwg = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg);

	var externalsources_vvvvvwi = jQuery("#jform_externalsources").val();
	vvvvvwi(externalsources_vvvvvwi);

	var update_method_vvvvvwj = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwj(update_method_vvvvvwj);

	var build_vvvvvwk = jQuery("#jform_build").val();
	vvvvvwk(build_vvvvvwk);

	var build_vvvvvwl = jQuery("#jform_build").val();
	vvvvvwl(build_vvvvvwl);

	var externalsources_vvvvvwm = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwm = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm);

	var externalsources_vvvvvwo = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwo = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo);

	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

	var update_method_vvvvvww = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvww(update_method_vvvvvww);
});

// the vvvvvwe function
function vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe)
{
	if (isSet(externalsources_vvvvvwe) && externalsources_vvvvvwe.constructor !== Array)
	{
		var temp_vvvvvwe = externalsources_vvvvvwe;
		var externalsources_vvvvvwe = [];
		externalsources_vvvvvwe.push(temp_vvvvvwe);
	}
	else if (!isSet(externalsources_vvvvvwe))
	{
		var externalsources_vvvvvwe = [];
	}
	var externalsources = externalsources_vvvvvwe.some(externalsources_vvvvvwe_SomeFunc);

	if (isSet(permissiontype_vvvvvwe) && permissiontype_vvvvvwe.constructor !== Array)
	{
		var temp_vvvvvwe = permissiontype_vvvvvwe;
		var permissiontype_vvvvvwe = [];
		permissiontype_vvvvvwe.push(temp_vvvvvwe);
	}
	else if (!isSet(permissiontype_vvvvvwe))
	{
		var permissiontype_vvvvvwe = [];
	}
	var permissiontype = permissiontype_vvvvvwe.some(permissiontype_vvvvvwe_SomeFunc);


	// set this function logic
	if (externalsources && permissiontype)
	{
		jQuery('#jform_dropboxoptions').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_dropboxoptions').closest('.control-group').hide();
	}
}

// the vvvvvwe Some function
function externalsources_vvvvvwe_SomeFunc(externalsources_vvvvvwe)
{
	// set the function logic
	if (externalsources_vvvvvwe == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwe Some function
function permissiontype_vvvvvwe_SomeFunc(permissiontype_vvvvvwe)
{
	// set the function logic
	if (permissiontype_vvvvvwe == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvwg function
function vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg)
{
	if (isSet(externalsources_vvvvvwg) && externalsources_vvvvvwg.constructor !== Array)
	{
		var temp_vvvvvwg = externalsources_vvvvvwg;
		var externalsources_vvvvvwg = [];
		externalsources_vvvvvwg.push(temp_vvvvvwg);
	}
	else if (!isSet(externalsources_vvvvvwg))
	{
		var externalsources_vvvvvwg = [];
	}
	var externalsources = externalsources_vvvvvwg.some(externalsources_vvvvvwg_SomeFunc);

	if (isSet(permissiontype_vvvvvwg) && permissiontype_vvvvvwg.constructor !== Array)
	{
		var temp_vvvvvwg = permissiontype_vvvvvwg;
		var permissiontype_vvvvvwg = [];
		permissiontype_vvvvvwg.push(temp_vvvvvwg);
	}
	else if (!isSet(permissiontype_vvvvvwg))
	{
		var permissiontype_vvvvvwg = [];
	}
	var permissiontype = permissiontype_vvvvvwg.some(permissiontype_vvvvvwg_SomeFunc);


	// set this function logic
	if (externalsources && permissiontype)
	{
		jQuery('.app_limitation_note').closest('.control-group').show();
	}
	else
	{
		jQuery('.app_limitation_note').closest('.control-group').hide();
	}
}

// the vvvvvwg Some function
function externalsources_vvvvvwg_SomeFunc(externalsources_vvvvvwg)
{
	// set the function logic
	if (externalsources_vvvvvwg == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwg Some function
function permissiontype_vvvvvwg_SomeFunc(permissiontype_vvvvvwg)
{
	// set the function logic
	if (permissiontype_vvvvvwg == 'app')
	{
		return true;
	}
	return false;
}

// the vvvvvwi function
function vvvvvwi(externalsources_vvvvvwi)
{
	if (isSet(externalsources_vvvvvwi) && externalsources_vvvvvwi.constructor !== Array)
	{
		var temp_vvvvvwi = externalsources_vvvvvwi;
		var externalsources_vvvvvwi = [];
		externalsources_vvvvvwi.push(temp_vvvvvwi);
	}
	else if (!isSet(externalsources_vvvvvwi))
	{
		var externalsources_vvvvvwi = [];
	}
	var externalsources = externalsources_vvvvvwi.some(externalsources_vvvvvwi_SomeFunc);


	// set this function logic
	if (externalsources)
	{
		jQuery('#jform_permissiontype').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_permissiontype').closest('.control-group').hide();
	}
}

// the vvvvvwi Some function
function externalsources_vvvvvwi_SomeFunc(externalsources_vvvvvwi)
{
	// set the function logic
	if (externalsources_vvvvvwi == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwj function
function vvvvvwj(update_method_vvvvvwj)
{
	// set the function logic
	if (update_method_vvvvvwj == 2)
	{
		jQuery('#jform_update_timer').closest('.control-group').show();
		// add required attribute to update_timer field
		if (jform_vvvvvwjvwd_required)
		{
			updateFieldRequired('update_timer',0);
			jQuery('#jform_update_timer').prop('required','required');
			jQuery('#jform_update_timer').attr('aria-required',true);
			jQuery('#jform_update_timer').addClass('required');
			jform_vvvvvwjvwd_required = false;
		}
	}
	else
	{
		jQuery('#jform_update_timer').closest('.control-group').hide();
		// remove required attribute from update_timer field
		if (!jform_vvvvvwjvwd_required)
		{
			updateFieldRequired('update_timer',1);
			jQuery('#jform_update_timer').removeAttr('required');
			jQuery('#jform_update_timer').removeAttr('aria-required');
			jQuery('#jform_update_timer').removeClass('required');
			jform_vvvvvwjvwd_required = true;
		}
	}
}

// the vvvvvwk function
function vvvvvwk(build_vvvvvwk)
{
	if (isSet(build_vvvvvwk) && build_vvvvvwk.constructor !== Array)
	{
		var temp_vvvvvwk = build_vvvvvwk;
		var build_vvvvvwk = [];
		build_vvvvvwk.push(temp_vvvvvwk);
	}
	else if (!isSet(build_vvvvvwk))
	{
		var build_vvvvvwk = [];
	}
	var build = build_vvvvvwk.some(build_vvvvvwk_SomeFunc);


	// set this function logic
	if (build)
	{
		jQuery('.note_auto_externalsource').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_auto_externalsource').closest('.control-group').hide();
	}
}

// the vvvvvwk Some function
function build_vvvvvwk_SomeFunc(build_vvvvvwk)
{
	// set the function logic
	if (build_vvvvvwk == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwl function
function vvvvvwl(build_vvvvvwl)
{
	if (isSet(build_vvvvvwl) && build_vvvvvwl.constructor !== Array)
	{
		var temp_vvvvvwl = build_vvvvvwl;
		var build_vvvvvwl = [];
		build_vvvvvwl.push(temp_vvvvvwl);
	}
	else if (!isSet(build_vvvvvwl))
	{
		var build_vvvvvwl = [];
	}
	var build = build_vvvvvwl.some(build_vvvvvwl_SomeFunc);


	// set this function logic
	if (build)
	{
		jQuery('.note_manual_externalsource').closest('.control-group').show();
	}
	else
	{
		jQuery('.note_manual_externalsource').closest('.control-group').hide();
	}
}

// the vvvvvwl Some function
function build_vvvvvwl_SomeFunc(build_vvvvvwl)
{
	// set the function logic
	if (build_vvvvvwl == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwm function
function vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm)
{
	if (isSet(externalsources_vvvvvwm) && externalsources_vvvvvwm.constructor !== Array)
	{
		var temp_vvvvvwm = externalsources_vvvvvwm;
		var externalsources_vvvvvwm = [];
		externalsources_vvvvvwm.push(temp_vvvvvwm);
	}
	else if (!isSet(externalsources_vvvvvwm))
	{
		var externalsources_vvvvvwm = [];
	}
	var externalsources = externalsources_vvvvvwm.some(externalsources_vvvvvwm_SomeFunc);

	if (isSet(update_method_vvvvvwm) && update_method_vvvvvwm.constructor !== Array)
	{
		var temp_vvvvvwm = update_method_vvvvvwm;
		var update_method_vvvvvwm = [];
		update_method_vvvvvwm.push(temp_vvvvvwm);
	}
	else if (!isSet(update_method_vvvvvwm))
	{
		var update_method_vvvvvwm = [];
	}
	var update_method = update_method_vvvvvwm.some(update_method_vvvvvwm_SomeFunc);


	// set this function logic
	if (externalsources && update_method)
	{
		jQuery('#jform_oauthtoken').closest('.control-group').show();
		// add required attribute to oauthtoken field
		if (jform_vvvvvwmvwe_required)
		{
			updateFieldRequired('oauthtoken',0);
			jQuery('#jform_oauthtoken').prop('required','required');
			jQuery('#jform_oauthtoken').attr('aria-required',true);
			jQuery('#jform_oauthtoken').addClass('required');
			jform_vvvvvwmvwe_required = false;
		}
	}
	else
	{
		jQuery('#jform_oauthtoken').closest('.control-group').hide();
		// remove required attribute from oauthtoken field
		if (!jform_vvvvvwmvwe_required)
		{
			updateFieldRequired('oauthtoken',1);
			jQuery('#jform_oauthtoken').removeAttr('required');
			jQuery('#jform_oauthtoken').removeAttr('aria-required');
			jQuery('#jform_oauthtoken').removeClass('required');
			jform_vvvvvwmvwe_required = true;
		}
	}
}

// the vvvvvwm Some function
function externalsources_vvvvvwm_SomeFunc(externalsources_vvvvvwm)
{
	// set the function logic
	if (externalsources_vvvvvwm == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwm Some function
function update_method_vvvvvwm_SomeFunc(update_method_vvvvvwm)
{
	// set the function logic
	if (update_method_vvvvvwm == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwo function
function vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo)
{
	if (isSet(externalsources_vvvvvwo) && externalsources_vvvvvwo.constructor !== Array)
	{
		var temp_vvvvvwo = externalsources_vvvvvwo;
		var externalsources_vvvvvwo = [];
		externalsources_vvvvvwo.push(temp_vvvvvwo);
	}
	else if (!isSet(externalsources_vvvvvwo))
	{
		var externalsources_vvvvvwo = [];
	}
	var externalsources = externalsources_vvvvvwo.some(externalsources_vvvvvwo_SomeFunc);

	if (isSet(update_method_vvvvvwo) && update_method_vvvvvwo.constructor !== Array)
	{
		var temp_vvvvvwo = update_method_vvvvvwo;
		var update_method_vvvvvwo = [];
		update_method_vvvvvwo.push(temp_vvvvvwo);
	}
	else if (!isSet(update_method_vvvvvwo))
	{
		var update_method_vvvvvwo = [];
	}
	var update_method = update_method_vvvvvwo.some(update_method_vvvvvwo_SomeFunc);


	// set this function logic
	if (externalsources && update_method)
	{
		jQuery('.generated_access_token_note').closest('.control-group').show();
	}
	else
	{
		jQuery('.generated_access_token_note').closest('.control-group').hide();
	}
}

// the vvvvvwo Some function
function externalsources_vvvvvwo_SomeFunc(externalsources_vvvvvwo)
{
	// set the function logic
	if (externalsources_vvvvvwo == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwo Some function
function update_method_vvvvvwo_SomeFunc(update_method_vvvvvwo)
{
	// set the function logic
	if (update_method_vvvvvwo == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwq function
function vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq)
{
	if (isSet(externalsources_vvvvvwq) && externalsources_vvvvvwq.constructor !== Array)
	{
		var temp_vvvvvwq = externalsources_vvvvvwq;
		var externalsources_vvvvvwq = [];
		externalsources_vvvvvwq.push(temp_vvvvvwq);
	}
	else if (!isSet(externalsources_vvvvvwq))
	{
		var externalsources_vvvvvwq = [];
	}
	var externalsources = externalsources_vvvvvwq.some(externalsources_vvvvvwq_SomeFunc);

	if (isSet(dropboxoptions_vvvvvwq) && dropboxoptions_vvvvvwq.constructor !== Array)
	{
		var temp_vvvvvwq = dropboxoptions_vvvvvwq;
		var dropboxoptions_vvvvvwq = [];
		dropboxoptions_vvvvvwq.push(temp_vvvvvwq);
	}
	else if (!isSet(dropboxoptions_vvvvvwq))
	{
		var dropboxoptions_vvvvvwq = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvwq.some(dropboxoptions_vvvvvwq_SomeFunc);

	if (isSet(permissiontype_vvvvvwq) && permissiontype_vvvvvwq.constructor !== Array)
	{
		var temp_vvvvvwq = permissiontype_vvvvvwq;
		var permissiontype_vvvvvwq = [];
		permissiontype_vvvvvwq.push(temp_vvvvvwq);
	}
	else if (!isSet(permissiontype_vvvvvwq))
	{
		var permissiontype_vvvvvwq = [];
	}
	var permissiontype = permissiontype_vvvvvwq.some(permissiontype_vvvvvwq_SomeFunc);


	// set this function logic
	if (externalsources && dropboxoptions && permissiontype)
	{
		jQuery('#jform_sharedurl').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_sharedurl').closest('.control-group').hide();
	}
}

// the vvvvvwq Some function
function externalsources_vvvvvwq_SomeFunc(externalsources_vvvvvwq)
{
	// set the function logic
	if (externalsources_vvvvvwq == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwq Some function
function dropboxoptions_vvvvvwq_SomeFunc(dropboxoptions_vvvvvwq)
{
	// set the function logic
	if (dropboxoptions_vvvvvwq == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwq Some function
function permissiontype_vvvvvwq_SomeFunc(permissiontype_vvvvvwq)
{
	// set the function logic
	if (permissiontype_vvvvvwq == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvwr function
function vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr)
{
	if (isSet(dropboxoptions_vvvvvwr) && dropboxoptions_vvvvvwr.constructor !== Array)
	{
		var temp_vvvvvwr = dropboxoptions_vvvvvwr;
		var dropboxoptions_vvvvvwr = [];
		dropboxoptions_vvvvvwr.push(temp_vvvvvwr);
	}
	else if (!isSet(dropboxoptions_vvvvvwr))
	{
		var dropboxoptions_vvvvvwr = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvwr.some(dropboxoptions_vvvvvwr_SomeFunc);

	if (isSet(externalsources_vvvvvwr) && externalsources_vvvvvwr.constructor !== Array)
	{
		var temp_vvvvvwr = externalsources_vvvvvwr;
		var externalsources_vvvvvwr = [];
		externalsources_vvvvvwr.push(temp_vvvvvwr);
	}
	else if (!isSet(externalsources_vvvvvwr))
	{
		var externalsources_vvvvvwr = [];
	}
	var externalsources = externalsources_vvvvvwr.some(externalsources_vvvvvwr_SomeFunc);

	if (isSet(permissiontype_vvvvvwr) && permissiontype_vvvvvwr.constructor !== Array)
	{
		var temp_vvvvvwr = permissiontype_vvvvvwr;
		var permissiontype_vvvvvwr = [];
		permissiontype_vvvvvwr.push(temp_vvvvvwr);
	}
	else if (!isSet(permissiontype_vvvvvwr))
	{
		var permissiontype_vvvvvwr = [];
	}
	var permissiontype = permissiontype_vvvvvwr.some(permissiontype_vvvvvwr_SomeFunc);


	// set this function logic
	if (dropboxoptions && externalsources && permissiontype)
	{
		jQuery('#jform_sharedurl').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_sharedurl').closest('.control-group').hide();
	}
}

// the vvvvvwr Some function
function dropboxoptions_vvvvvwr_SomeFunc(dropboxoptions_vvvvvwr)
{
	// set the function logic
	if (dropboxoptions_vvvvvwr == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwr Some function
function externalsources_vvvvvwr_SomeFunc(externalsources_vvvvvwr)
{
	// set the function logic
	if (externalsources_vvvvvwr == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwr Some function
function permissiontype_vvvvvwr_SomeFunc(permissiontype_vvvvvwr)
{
	// set the function logic
	if (permissiontype_vvvvvwr == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvws function
function vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws)
{
	if (isSet(permissiontype_vvvvvws) && permissiontype_vvvvvws.constructor !== Array)
	{
		var temp_vvvvvws = permissiontype_vvvvvws;
		var permissiontype_vvvvvws = [];
		permissiontype_vvvvvws.push(temp_vvvvvws);
	}
	else if (!isSet(permissiontype_vvvvvws))
	{
		var permissiontype_vvvvvws = [];
	}
	var permissiontype = permissiontype_vvvvvws.some(permissiontype_vvvvvws_SomeFunc);

	if (isSet(externalsources_vvvvvws) && externalsources_vvvvvws.constructor !== Array)
	{
		var temp_vvvvvws = externalsources_vvvvvws;
		var externalsources_vvvvvws = [];
		externalsources_vvvvvws.push(temp_vvvvvws);
	}
	else if (!isSet(externalsources_vvvvvws))
	{
		var externalsources_vvvvvws = [];
	}
	var externalsources = externalsources_vvvvvws.some(externalsources_vvvvvws_SomeFunc);

	if (isSet(dropboxoptions_vvvvvws) && dropboxoptions_vvvvvws.constructor !== Array)
	{
		var temp_vvvvvws = dropboxoptions_vvvvvws;
		var dropboxoptions_vvvvvws = [];
		dropboxoptions_vvvvvws.push(temp_vvvvvws);
	}
	else if (!isSet(dropboxoptions_vvvvvws))
	{
		var dropboxoptions_vvvvvws = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvws.some(dropboxoptions_vvvvvws_SomeFunc);


	// set this function logic
	if (permissiontype && externalsources && dropboxoptions)
	{
		jQuery('#jform_sharedurl').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_sharedurl').closest('.control-group').hide();
	}
}

// the vvvvvws Some function
function permissiontype_vvvvvws_SomeFunc(permissiontype_vvvvvws)
{
	// set the function logic
	if (permissiontype_vvvvvws == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvws Some function
function externalsources_vvvvvws_SomeFunc(externalsources_vvvvvws)
{
	// set the function logic
	if (externalsources_vvvvvws == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvws Some function
function dropboxoptions_vvvvvws_SomeFunc(dropboxoptions_vvvvvws)
{
	// set the function logic
	if (dropboxoptions_vvvvvws == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwt function
function vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt)
{
	if (isSet(externalsources_vvvvvwt) && externalsources_vvvvvwt.constructor !== Array)
	{
		var temp_vvvvvwt = externalsources_vvvvvwt;
		var externalsources_vvvvvwt = [];
		externalsources_vvvvvwt.push(temp_vvvvvwt);
	}
	else if (!isSet(externalsources_vvvvvwt))
	{
		var externalsources_vvvvvwt = [];
	}
	var externalsources = externalsources_vvvvvwt.some(externalsources_vvvvvwt_SomeFunc);

	if (isSet(dropboxoptions_vvvvvwt) && dropboxoptions_vvvvvwt.constructor !== Array)
	{
		var temp_vvvvvwt = dropboxoptions_vvvvvwt;
		var dropboxoptions_vvvvvwt = [];
		dropboxoptions_vvvvvwt.push(temp_vvvvvwt);
	}
	else if (!isSet(dropboxoptions_vvvvvwt))
	{
		var dropboxoptions_vvvvvwt = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvwt.some(dropboxoptions_vvvvvwt_SomeFunc);

	if (isSet(permissiontype_vvvvvwt) && permissiontype_vvvvvwt.constructor !== Array)
	{
		var temp_vvvvvwt = permissiontype_vvvvvwt;
		var permissiontype_vvvvvwt = [];
		permissiontype_vvvvvwt.push(temp_vvvvvwt);
	}
	else if (!isSet(permissiontype_vvvvvwt))
	{
		var permissiontype_vvvvvwt = [];
	}
	var permissiontype = permissiontype_vvvvvwt.some(permissiontype_vvvvvwt_SomeFunc);


	// set this function logic
	if (externalsources && dropboxoptions && permissiontype)
	{
		jQuery('#jform_folder').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_folder').closest('.control-group').hide();
	}
}

// the vvvvvwt Some function
function externalsources_vvvvvwt_SomeFunc(externalsources_vvvvvwt)
{
	// set the function logic
	if (externalsources_vvvvvwt == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwt Some function
function dropboxoptions_vvvvvwt_SomeFunc(dropboxoptions_vvvvvwt)
{
	// set the function logic
	if (dropboxoptions_vvvvvwt == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwt Some function
function permissiontype_vvvvvwt_SomeFunc(permissiontype_vvvvvwt)
{
	// set the function logic
	if (permissiontype_vvvvvwt == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvwu function
function vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu)
{
	if (isSet(dropboxoptions_vvvvvwu) && dropboxoptions_vvvvvwu.constructor !== Array)
	{
		var temp_vvvvvwu = dropboxoptions_vvvvvwu;
		var dropboxoptions_vvvvvwu = [];
		dropboxoptions_vvvvvwu.push(temp_vvvvvwu);
	}
	else if (!isSet(dropboxoptions_vvvvvwu))
	{
		var dropboxoptions_vvvvvwu = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvwu.some(dropboxoptions_vvvvvwu_SomeFunc);

	if (isSet(externalsources_vvvvvwu) && externalsources_vvvvvwu.constructor !== Array)
	{
		var temp_vvvvvwu = externalsources_vvvvvwu;
		var externalsources_vvvvvwu = [];
		externalsources_vvvvvwu.push(temp_vvvvvwu);
	}
	else if (!isSet(externalsources_vvvvvwu))
	{
		var externalsources_vvvvvwu = [];
	}
	var externalsources = externalsources_vvvvvwu.some(externalsources_vvvvvwu_SomeFunc);

	if (isSet(permissiontype_vvvvvwu) && permissiontype_vvvvvwu.constructor !== Array)
	{
		var temp_vvvvvwu = permissiontype_vvvvvwu;
		var permissiontype_vvvvvwu = [];
		permissiontype_vvvvvwu.push(temp_vvvvvwu);
	}
	else if (!isSet(permissiontype_vvvvvwu))
	{
		var permissiontype_vvvvvwu = [];
	}
	var permissiontype = permissiontype_vvvvvwu.some(permissiontype_vvvvvwu_SomeFunc);


	// set this function logic
	if (dropboxoptions && externalsources && permissiontype)
	{
		jQuery('#jform_folder').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_folder').closest('.control-group').hide();
	}
}

// the vvvvvwu Some function
function dropboxoptions_vvvvvwu_SomeFunc(dropboxoptions_vvvvvwu)
{
	// set the function logic
	if (dropboxoptions_vvvvvwu == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvwu Some function
function externalsources_vvvvvwu_SomeFunc(externalsources_vvvvvwu)
{
	// set the function logic
	if (externalsources_vvvvvwu == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwu Some function
function permissiontype_vvvvvwu_SomeFunc(permissiontype_vvvvvwu)
{
	// set the function logic
	if (permissiontype_vvvvvwu == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvwv function
function vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv)
{
	if (isSet(permissiontype_vvvvvwv) && permissiontype_vvvvvwv.constructor !== Array)
	{
		var temp_vvvvvwv = permissiontype_vvvvvwv;
		var permissiontype_vvvvvwv = [];
		permissiontype_vvvvvwv.push(temp_vvvvvwv);
	}
	else if (!isSet(permissiontype_vvvvvwv))
	{
		var permissiontype_vvvvvwv = [];
	}
	var permissiontype = permissiontype_vvvvvwv.some(permissiontype_vvvvvwv_SomeFunc);

	if (isSet(externalsources_vvvvvwv) && externalsources_vvvvvwv.constructor !== Array)
	{
		var temp_vvvvvwv = externalsources_vvvvvwv;
		var externalsources_vvvvvwv = [];
		externalsources_vvvvvwv.push(temp_vvvvvwv);
	}
	else if (!isSet(externalsources_vvvvvwv))
	{
		var externalsources_vvvvvwv = [];
	}
	var externalsources = externalsources_vvvvvwv.some(externalsources_vvvvvwv_SomeFunc);

	if (isSet(dropboxoptions_vvvvvwv) && dropboxoptions_vvvvvwv.constructor !== Array)
	{
		var temp_vvvvvwv = dropboxoptions_vvvvvwv;
		var dropboxoptions_vvvvvwv = [];
		dropboxoptions_vvvvvwv.push(temp_vvvvvwv);
	}
	else if (!isSet(dropboxoptions_vvvvvwv))
	{
		var dropboxoptions_vvvvvwv = [];
	}
	var dropboxoptions = dropboxoptions_vvvvvwv.some(dropboxoptions_vvvvvwv_SomeFunc);


	// set this function logic
	if (permissiontype && externalsources && dropboxoptions)
	{
		jQuery('#jform_folder').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_folder').closest('.control-group').hide();
	}
}

// the vvvvvwv Some function
function permissiontype_vvvvvwv_SomeFunc(permissiontype_vvvvvwv)
{
	// set the function logic
	if (permissiontype_vvvvvwv == 'full')
	{
		return true;
	}
	return false;
}

// the vvvvvwv Some function
function externalsources_vvvvvwv_SomeFunc(externalsources_vvvvvwv)
{
	// set the function logic
	if (externalsources_vvvvvwv == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvwv Some function
function dropboxoptions_vvvvvwv_SomeFunc(dropboxoptions_vvvvvwv)
{
	// set the function logic
	if (dropboxoptions_vvvvvwv == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvww function
function vvvvvww(update_method_vvvvvww)
{
	// set the function logic
	if (update_method_vvvvvww == 2)
	{
		jQuery('.apicronjob_note').closest('.control-group').show();
	}
	else
	{
		jQuery('.apicronjob_note').closest('.control-group').hide();
	}
}

// update fields required
function updateFieldRequired(name, status) {
	// check if not_required exist
	if (document.getElementById('jform_not_required')) {
		var not_required = jQuery('#jform_not_required').val().split(",");

		if(status == 1)
		{
			not_required.push(name);
		}
		else
		{
			not_required = removeFieldFromNotRequired(not_required, name);
		}

		jQuery('#jform_not_required').val(fixNotRequiredArray(not_required).toString());
	}
}

// remove field from not_required
function removeFieldFromNotRequired(array, what) {
	return array.filter(function(element){
		return element !== what;
	});
}

// fix not required array
function fixNotRequiredArray(array) {
	var seen = {};
	return removeEmptyFromNotRequiredArray(array).filter(function(item) {
		return seen.hasOwnProperty(item) ? false : (seen[item] = true);
	});
}

// remove empty from not_required array
function removeEmptyFromNotRequiredArray(array) {
	return array.filter(function (el) {
		// remove ( 一_一) as well - lol
		return (el.length > 0 && '一_一' !== el);
	});
}

// the isSet function
function isSet(val)
{
	if ((val != undefined) && (val != null) && 0 !== val.length){
		return true;
	}
	return false;
}


jQuery(document).ready(function()
{
	var sharedurls = jQuery('#jform_sharedurl').val();
	if (sharedurls) {
		getBuildTable(sharedurls,'jform_sharedurl');
	}
	var folders = jQuery('#jform_folder').val();
	if (folders) {
		getBuildTable(folders,'jform_folder');
	}
	var source_id = jQuery('#jform_id').val();
	if (source_id) {
		getSourceStatus(source_id);
	}
	jQuery('.save-modal-data').text('Done');
});
function getSourceStatus_server(sourceID){
	var getUrl = "index.php?option=com_sermondistributor&task=ajax.getSourceStatus&format=json";
	if(token.length > 0 && sourceID > 0){
		var request = 'token='+token+'&id='+sourceID;
	}
	return jQuery.ajax({
		type: 'GET',
		url: getUrl,
		dataType: 'jsonp',
		data: request,
		jsonp: 'callback'
	});
}
function getSourceStatus(sourceID){
	getSourceStatus_server(sourceID).done(function(result) {
		if(result){
			setSourceStatus(result);
		} else {
			jQuery('#sourceStatus').remove();			
		}
	})
}
function setSourceStatus(result){
	jQuery('#sourceStatus').remove();
	jQuery('#jform_update_method').closest('.control-group').append('<div id="sourceStatus" class="controls"><br />'+result+'</div>');
}
function getBuildTable_server(string,idName){
	var getUrl = "index.php?option=com_sermondistributor&task=ajax.getBuildTable&format=json";
	if(token.length > 0 && string.length > 0 && idName.length > 0){
		var request = 'token='+token+'&idName='+idName+'&oject='+string;
	}
	return jQuery.ajax({
		type: 'GET',
		url: getUrl,
		dataType: 'jsonp',
		data: request,
		jsonp: 'callback'
	});
}
function getBuildTable(string,idName){
	getBuildTable_server(string,idName).done(function(result) {
		if(result){
			buildTable(result,idName);
		} else {
			jQuery('#table_'+idName).remove();			
		}
	})
}
function buildTable(result,idName){
	jQuery('#table_'+idName).remove();
	jQuery('#'+idName).closest('.control-group').append(result);
}

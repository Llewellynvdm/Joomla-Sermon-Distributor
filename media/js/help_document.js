/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_vvvvvwxvwf_required = false;
jform_vvvvvwyvwg_required = false;
jform_vvvvvwzvwh_required = false;
jform_vvvvvxavwi_required = false;
jform_vvvvvxcvwj_required = false;

// Initial Script
document.addEventListener('DOMContentLoaded', function()
{
	var location_vvvvvwx = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwx(location_vvvvvwx);

	var location_vvvvvwy = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwy(location_vvvvvwy);

	var type_vvvvvwz = jQuery("#jform_type").val();
	vvvvvwz(type_vvvvvwz);

	var type_vvvvvxa = jQuery("#jform_type").val();
	vvvvvxa(type_vvvvvxa);

	var type_vvvvvxb = jQuery("#jform_type").val();
	vvvvvxb(type_vvvvvxb);

	var target_vvvvvxc = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvxc(target_vvvvvxc);
});

// the vvvvvwx function
function vvvvvwx(location_vvvvvwx)
{
	// set the function logic
	if (location_vvvvvwx == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
		// add required attribute to admin_view field
		if (jform_vvvvvwxvwf_required)
		{
			updateFieldRequired('admin_view',0);
			jQuery('#jform_admin_view').prop('required','required');
			jQuery('#jform_admin_view').attr('aria-required',true);
			jQuery('#jform_admin_view').addClass('required');
			jform_vvvvvwxvwf_required = false;
		}
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
		// remove required attribute from admin_view field
		if (!jform_vvvvvwxvwf_required)
		{
			updateFieldRequired('admin_view',1);
			jQuery('#jform_admin_view').removeAttr('required');
			jQuery('#jform_admin_view').removeAttr('aria-required');
			jQuery('#jform_admin_view').removeClass('required');
			jform_vvvvvwxvwf_required = true;
		}
	}
}

// the vvvvvwy function
function vvvvvwy(location_vvvvvwy)
{
	// set the function logic
	if (location_vvvvvwy == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
		// add required attribute to site_view field
		if (jform_vvvvvwyvwg_required)
		{
			updateFieldRequired('site_view',0);
			jQuery('#jform_site_view').prop('required','required');
			jQuery('#jform_site_view').attr('aria-required',true);
			jQuery('#jform_site_view').addClass('required');
			jform_vvvvvwyvwg_required = false;
		}
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
		// remove required attribute from site_view field
		if (!jform_vvvvvwyvwg_required)
		{
			updateFieldRequired('site_view',1);
			jQuery('#jform_site_view').removeAttr('required');
			jQuery('#jform_site_view').removeAttr('aria-required');
			jQuery('#jform_site_view').removeClass('required');
			jform_vvvvvwyvwg_required = true;
		}
	}
}

// the vvvvvwz function
function vvvvvwz(type_vvvvvwz)
{
	if (isSet(type_vvvvvwz) && type_vvvvvwz.constructor !== Array)
	{
		var temp_vvvvvwz = type_vvvvvwz;
		var type_vvvvvwz = [];
		type_vvvvvwz.push(temp_vvvvvwz);
	}
	else if (!isSet(type_vvvvvwz))
	{
		var type_vvvvvwz = [];
	}
	var type = type_vvvvvwz.some(type_vvvvvwz_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		// add required attribute to url field
		if (jform_vvvvvwzvwh_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_vvvvvwzvwh_required = false;
		}
	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		// remove required attribute from url field
		if (!jform_vvvvvwzvwh_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_vvvvvwzvwh_required = true;
		}
	}
}

// the vvvvvwz Some function
function type_vvvvvwz_SomeFunc(type_vvvvvwz)
{
	// set the function logic
	if (type_vvvvvwz == 3)
	{
		return true;
	}
	return false;
}

// the vvvvvxa function
function vvvvvxa(type_vvvvvxa)
{
	if (isSet(type_vvvvvxa) && type_vvvvvxa.constructor !== Array)
	{
		var temp_vvvvvxa = type_vvvvvxa;
		var type_vvvvvxa = [];
		type_vvvvvxa.push(temp_vvvvvxa);
	}
	else if (!isSet(type_vvvvvxa))
	{
		var type_vvvvvxa = [];
	}
	var type = type_vvvvvxa.some(type_vvvvvxa_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		// add required attribute to article field
		if (jform_vvvvvxavwi_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vvvvvxavwi_required = false;
		}
	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		// remove required attribute from article field
		if (!jform_vvvvvxavwi_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vvvvvxavwi_required = true;
		}
	}
}

// the vvvvvxa Some function
function type_vvvvvxa_SomeFunc(type_vvvvvxa)
{
	// set the function logic
	if (type_vvvvvxa == 1)
	{
		return true;
	}
	return false;
}

// the vvvvvxb function
function vvvvvxb(type_vvvvvxb)
{
	if (isSet(type_vvvvvxb) && type_vvvvvxb.constructor !== Array)
	{
		var temp_vvvvvxb = type_vvvvvxb;
		var type_vvvvvxb = [];
		type_vvvvvxb.push(temp_vvvvvxb);
	}
	else if (!isSet(type_vvvvvxb))
	{
		var type_vvvvvxb = [];
	}
	var type = type_vvvvvxb.some(type_vvvvvxb_SomeFunc);


	// set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
	}
}

// the vvvvvxb Some function
function type_vvvvvxb_SomeFunc(type_vvvvvxb)
{
	// set the function logic
	if (type_vvvvvxb == 2)
	{
		return true;
	}
	return false;
}

// the vvvvvxc function
function vvvvvxc(target_vvvvvxc)
{
	// set the function logic
	if (target_vvvvvxc == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		// add required attribute to groups field
		if (jform_vvvvvxcvwj_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_vvvvvxcvwj_required = false;
		}
	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		// remove required attribute from groups field
		if (!jform_vvvvvxcvwj_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_vvvvvxcvwj_required = true;
		}
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

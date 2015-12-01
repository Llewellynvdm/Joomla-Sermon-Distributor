/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_oGAMfTbxuS_required = false;
jform_LTWmzwABJL_required = false;
jform_UCmYIbJcaV_required = false;
jform_Opviezbfev_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_bqdlaIg = jQuery("#jform_location input[type='radio']:checked").val();
	bqdlaIg(location_bqdlaIg);

	var location_YOOAZiI = jQuery("#jform_location input[type='radio']:checked").val();
	YOOAZiI(location_YOOAZiI);

	var type_oGAMfTb = jQuery("#jform_type").val();
	oGAMfTb(type_oGAMfTb);

	var type_LTWmzwA = jQuery("#jform_type").val();
	LTWmzwA(type_LTWmzwA);

	var type_UCmYIbJ = jQuery("#jform_type").val();
	UCmYIbJ(type_UCmYIbJ);

	var target_Opviezb = jQuery("#jform_target input[type='radio']:checked").val();
	Opviezb(target_Opviezb);
});

// the bqdlaIg function
function bqdlaIg(location_bqdlaIg)
{
	// [8001] set the function logic
	if (location_bqdlaIg == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the YOOAZiI function
function YOOAZiI(location_YOOAZiI)
{
	// [8001] set the function logic
	if (location_YOOAZiI == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the oGAMfTb function
function oGAMfTb(type_oGAMfTb)
{
	if (isSet(type_oGAMfTb) && type_oGAMfTb.constructor !== Array)
	{
		var temp_oGAMfTb = type_oGAMfTb;
		var type_oGAMfTb = [];
		type_oGAMfTb.push(temp_oGAMfTb);
	}
	else if (!isSet(type_oGAMfTb))
	{
		var type_oGAMfTb = [];
	}
	var type = type_oGAMfTb.some(type_oGAMfTb_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_oGAMfTbxuS_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_oGAMfTbxuS_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_oGAMfTbxuS_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_oGAMfTbxuS_required = true;
		}
	}
}

// the oGAMfTb Some function
function type_oGAMfTb_SomeFunc(type_oGAMfTb)
{
	// [7966] set the function logic
	if (type_oGAMfTb == 3)
	{
		return true;
	}
	return false;
}

// the LTWmzwA function
function LTWmzwA(type_LTWmzwA)
{
	if (isSet(type_LTWmzwA) && type_LTWmzwA.constructor !== Array)
	{
		var temp_LTWmzwA = type_LTWmzwA;
		var type_LTWmzwA = [];
		type_LTWmzwA.push(temp_LTWmzwA);
	}
	else if (!isSet(type_LTWmzwA))
	{
		var type_LTWmzwA = [];
	}
	var type = type_LTWmzwA.some(type_LTWmzwA_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_LTWmzwABJL_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_LTWmzwABJL_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_LTWmzwABJL_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_LTWmzwABJL_required = true;
		}
	}
}

// the LTWmzwA Some function
function type_LTWmzwA_SomeFunc(type_LTWmzwA)
{
	// [7966] set the function logic
	if (type_LTWmzwA == 1)
	{
		return true;
	}
	return false;
}

// the UCmYIbJ function
function UCmYIbJ(type_UCmYIbJ)
{
	if (isSet(type_UCmYIbJ) && type_UCmYIbJ.constructor !== Array)
	{
		var temp_UCmYIbJ = type_UCmYIbJ;
		var type_UCmYIbJ = [];
		type_UCmYIbJ.push(temp_UCmYIbJ);
	}
	else if (!isSet(type_UCmYIbJ))
	{
		var type_UCmYIbJ = [];
	}
	var type = type_UCmYIbJ.some(type_UCmYIbJ_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_UCmYIbJcaV_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_UCmYIbJcaV_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_UCmYIbJcaV_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_UCmYIbJcaV_required = true;
		}
	}
}

// the UCmYIbJ Some function
function type_UCmYIbJ_SomeFunc(type_UCmYIbJ)
{
	// [7966] set the function logic
	if (type_UCmYIbJ == 2)
	{
		return true;
	}
	return false;
}

// the Opviezb function
function Opviezb(target_Opviezb)
{
	// [8001] set the function logic
	if (target_Opviezb == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_Opviezbfev_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_Opviezbfev_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_Opviezbfev_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_Opviezbfev_required = true;
		}
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

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
jform_qgvZjvNaOJ_required = false;
jform_HkhDrQjmhr_required = false;
jform_llHjuZwvZl_required = false;
jform_clywlAZYIP_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_UohYgsV = jQuery("#jform_location input[type='radio']:checked").val();
	UohYgsV(location_UohYgsV);

	var location_dmkgZVG = jQuery("#jform_location input[type='radio']:checked").val();
	dmkgZVG(location_dmkgZVG);

	var type_qgvZjvN = jQuery("#jform_type").val();
	qgvZjvN(type_qgvZjvN);

	var type_HkhDrQj = jQuery("#jform_type").val();
	HkhDrQj(type_HkhDrQj);

	var type_llHjuZw = jQuery("#jform_type").val();
	llHjuZw(type_llHjuZw);

	var target_clywlAZ = jQuery("#jform_target input[type='radio']:checked").val();
	clywlAZ(target_clywlAZ);
});

// the UohYgsV function
function UohYgsV(location_UohYgsV)
{
	// [8002] set the function logic
	if (location_UohYgsV == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the dmkgZVG function
function dmkgZVG(location_dmkgZVG)
{
	// [8002] set the function logic
	if (location_dmkgZVG == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the qgvZjvN function
function qgvZjvN(type_qgvZjvN)
{
	if (isSet(type_qgvZjvN) && type_qgvZjvN.constructor !== Array)
	{
		var temp_qgvZjvN = type_qgvZjvN;
		var type_qgvZjvN = [];
		type_qgvZjvN.push(temp_qgvZjvN);
	}
	else if (!isSet(type_qgvZjvN))
	{
		var type_qgvZjvN = [];
	}
	var type = type_qgvZjvN.some(type_qgvZjvN_SomeFunc);


	// [7980] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_qgvZjvNaOJ_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_qgvZjvNaOJ_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_qgvZjvNaOJ_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_qgvZjvNaOJ_required = true;
		}
	}
}

// the qgvZjvN Some function
function type_qgvZjvN_SomeFunc(type_qgvZjvN)
{
	// [7967] set the function logic
	if (type_qgvZjvN == 3)
	{
		return true;
	}
	return false;
}

// the HkhDrQj function
function HkhDrQj(type_HkhDrQj)
{
	if (isSet(type_HkhDrQj) && type_HkhDrQj.constructor !== Array)
	{
		var temp_HkhDrQj = type_HkhDrQj;
		var type_HkhDrQj = [];
		type_HkhDrQj.push(temp_HkhDrQj);
	}
	else if (!isSet(type_HkhDrQj))
	{
		var type_HkhDrQj = [];
	}
	var type = type_HkhDrQj.some(type_HkhDrQj_SomeFunc);


	// [7980] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_HkhDrQjmhr_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_HkhDrQjmhr_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_HkhDrQjmhr_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_HkhDrQjmhr_required = true;
		}
	}
}

// the HkhDrQj Some function
function type_HkhDrQj_SomeFunc(type_HkhDrQj)
{
	// [7967] set the function logic
	if (type_HkhDrQj == 1)
	{
		return true;
	}
	return false;
}

// the llHjuZw function
function llHjuZw(type_llHjuZw)
{
	if (isSet(type_llHjuZw) && type_llHjuZw.constructor !== Array)
	{
		var temp_llHjuZw = type_llHjuZw;
		var type_llHjuZw = [];
		type_llHjuZw.push(temp_llHjuZw);
	}
	else if (!isSet(type_llHjuZw))
	{
		var type_llHjuZw = [];
	}
	var type = type_llHjuZw.some(type_llHjuZw_SomeFunc);


	// [7980] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_llHjuZwvZl_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_llHjuZwvZl_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_llHjuZwvZl_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_llHjuZwvZl_required = true;
		}
	}
}

// the llHjuZw Some function
function type_llHjuZw_SomeFunc(type_llHjuZw)
{
	// [7967] set the function logic
	if (type_llHjuZw == 2)
	{
		return true;
	}
	return false;
}

// the clywlAZ function
function clywlAZ(target_clywlAZ)
{
	// [8002] set the function logic
	if (target_clywlAZ == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_clywlAZYIP_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_clywlAZYIP_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_clywlAZYIP_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_clywlAZYIP_required = true;
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

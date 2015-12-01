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
jform_pfUDjYGVoL_required = false;
jform_cfyFlnvkzP_required = false;
jform_SSRqCogOIY_required = false;
jform_qdgfzreiiB_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_YqmLlcw = jQuery("#jform_location input[type='radio']:checked").val();
	YqmLlcw(location_YqmLlcw);

	var location_ErKnwQB = jQuery("#jform_location input[type='radio']:checked").val();
	ErKnwQB(location_ErKnwQB);

	var type_pfUDjYG = jQuery("#jform_type").val();
	pfUDjYG(type_pfUDjYG);

	var type_cfyFlnv = jQuery("#jform_type").val();
	cfyFlnv(type_cfyFlnv);

	var type_SSRqCog = jQuery("#jform_type").val();
	SSRqCog(type_SSRqCog);

	var target_qdgfzre = jQuery("#jform_target input[type='radio']:checked").val();
	qdgfzre(target_qdgfzre);
});

// the YqmLlcw function
function YqmLlcw(location_YqmLlcw)
{
	// [8001] set the function logic
	if (location_YqmLlcw == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the ErKnwQB function
function ErKnwQB(location_ErKnwQB)
{
	// [8001] set the function logic
	if (location_ErKnwQB == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the pfUDjYG function
function pfUDjYG(type_pfUDjYG)
{
	if (isSet(type_pfUDjYG) && type_pfUDjYG.constructor !== Array)
	{
		var temp_pfUDjYG = type_pfUDjYG;
		var type_pfUDjYG = [];
		type_pfUDjYG.push(temp_pfUDjYG);
	}
	else if (!isSet(type_pfUDjYG))
	{
		var type_pfUDjYG = [];
	}
	var type = type_pfUDjYG.some(type_pfUDjYG_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_pfUDjYGVoL_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_pfUDjYGVoL_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_pfUDjYGVoL_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_pfUDjYGVoL_required = true;
		}
	}
}

// the pfUDjYG Some function
function type_pfUDjYG_SomeFunc(type_pfUDjYG)
{
	// [7966] set the function logic
	if (type_pfUDjYG == 3)
	{
		return true;
	}
	return false;
}

// the cfyFlnv function
function cfyFlnv(type_cfyFlnv)
{
	if (isSet(type_cfyFlnv) && type_cfyFlnv.constructor !== Array)
	{
		var temp_cfyFlnv = type_cfyFlnv;
		var type_cfyFlnv = [];
		type_cfyFlnv.push(temp_cfyFlnv);
	}
	else if (!isSet(type_cfyFlnv))
	{
		var type_cfyFlnv = [];
	}
	var type = type_cfyFlnv.some(type_cfyFlnv_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_cfyFlnvkzP_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_cfyFlnvkzP_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_cfyFlnvkzP_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_cfyFlnvkzP_required = true;
		}
	}
}

// the cfyFlnv Some function
function type_cfyFlnv_SomeFunc(type_cfyFlnv)
{
	// [7966] set the function logic
	if (type_cfyFlnv == 1)
	{
		return true;
	}
	return false;
}

// the SSRqCog function
function SSRqCog(type_SSRqCog)
{
	if (isSet(type_SSRqCog) && type_SSRqCog.constructor !== Array)
	{
		var temp_SSRqCog = type_SSRqCog;
		var type_SSRqCog = [];
		type_SSRqCog.push(temp_SSRqCog);
	}
	else if (!isSet(type_SSRqCog))
	{
		var type_SSRqCog = [];
	}
	var type = type_SSRqCog.some(type_SSRqCog_SomeFunc);


	// [7979] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_SSRqCogOIY_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_SSRqCogOIY_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_SSRqCogOIY_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_SSRqCogOIY_required = true;
		}
	}
}

// the SSRqCog Some function
function type_SSRqCog_SomeFunc(type_SSRqCog)
{
	// [7966] set the function logic
	if (type_SSRqCog == 2)
	{
		return true;
	}
	return false;
}

// the qdgfzre function
function qdgfzre(target_qdgfzre)
{
	// [8001] set the function logic
	if (target_qdgfzre == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_qdgfzreiiB_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_qdgfzreiiB_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_qdgfzreiiB_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_qdgfzreiiB_required = true;
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

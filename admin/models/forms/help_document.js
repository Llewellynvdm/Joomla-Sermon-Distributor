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
jform_RCpdpBjqah_required = false;
jform_tzmdvkqrgG_required = false;
jform_tOoDUnWPZO_required = false;
jform_ZRloSvLdIL_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_TUtVLEk = jQuery("#jform_location input[type='radio']:checked").val();
	TUtVLEk(location_TUtVLEk);

	var location_gUAUNQj = jQuery("#jform_location input[type='radio']:checked").val();
	gUAUNQj(location_gUAUNQj);

	var type_RCpdpBj = jQuery("#jform_type").val();
	RCpdpBj(type_RCpdpBj);

	var type_tzmdvkq = jQuery("#jform_type").val();
	tzmdvkq(type_tzmdvkq);

	var type_tOoDUnW = jQuery("#jform_type").val();
	tOoDUnW(type_tOoDUnW);

	var target_ZRloSvL = jQuery("#jform_target input[type='radio']:checked").val();
	ZRloSvL(target_ZRloSvL);
});

// the TUtVLEk function
function TUtVLEk(location_TUtVLEk)
{
	// [8008] set the function logic
	if (location_TUtVLEk == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the gUAUNQj function
function gUAUNQj(location_gUAUNQj)
{
	// [8008] set the function logic
	if (location_gUAUNQj == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the RCpdpBj function
function RCpdpBj(type_RCpdpBj)
{
	if (isSet(type_RCpdpBj) && type_RCpdpBj.constructor !== Array)
	{
		var temp_RCpdpBj = type_RCpdpBj;
		var type_RCpdpBj = [];
		type_RCpdpBj.push(temp_RCpdpBj);
	}
	else if (!isSet(type_RCpdpBj))
	{
		var type_RCpdpBj = [];
	}
	var type = type_RCpdpBj.some(type_RCpdpBj_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_RCpdpBjqah_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_RCpdpBjqah_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_RCpdpBjqah_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_RCpdpBjqah_required = true;
		}
	}
}

// the RCpdpBj Some function
function type_RCpdpBj_SomeFunc(type_RCpdpBj)
{
	// [7973] set the function logic
	if (type_RCpdpBj == 3)
	{
		return true;
	}
	return false;
}

// the tzmdvkq function
function tzmdvkq(type_tzmdvkq)
{
	if (isSet(type_tzmdvkq) && type_tzmdvkq.constructor !== Array)
	{
		var temp_tzmdvkq = type_tzmdvkq;
		var type_tzmdvkq = [];
		type_tzmdvkq.push(temp_tzmdvkq);
	}
	else if (!isSet(type_tzmdvkq))
	{
		var type_tzmdvkq = [];
	}
	var type = type_tzmdvkq.some(type_tzmdvkq_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_tzmdvkqrgG_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_tzmdvkqrgG_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_tzmdvkqrgG_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_tzmdvkqrgG_required = true;
		}
	}
}

// the tzmdvkq Some function
function type_tzmdvkq_SomeFunc(type_tzmdvkq)
{
	// [7973] set the function logic
	if (type_tzmdvkq == 1)
	{
		return true;
	}
	return false;
}

// the tOoDUnW function
function tOoDUnW(type_tOoDUnW)
{
	if (isSet(type_tOoDUnW) && type_tOoDUnW.constructor !== Array)
	{
		var temp_tOoDUnW = type_tOoDUnW;
		var type_tOoDUnW = [];
		type_tOoDUnW.push(temp_tOoDUnW);
	}
	else if (!isSet(type_tOoDUnW))
	{
		var type_tOoDUnW = [];
	}
	var type = type_tOoDUnW.some(type_tOoDUnW_SomeFunc);


	// [7986] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_tOoDUnWPZO_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_tOoDUnWPZO_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_tOoDUnWPZO_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_tOoDUnWPZO_required = true;
		}
	}
}

// the tOoDUnW Some function
function type_tOoDUnW_SomeFunc(type_tOoDUnW)
{
	// [7973] set the function logic
	if (type_tOoDUnW == 2)
	{
		return true;
	}
	return false;
}

// the ZRloSvL function
function ZRloSvL(target_ZRloSvL)
{
	// [8008] set the function logic
	if (target_ZRloSvL == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_ZRloSvLdIL_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_ZRloSvLdIL_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_ZRloSvLdIL_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_ZRloSvLdIL_required = true;
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

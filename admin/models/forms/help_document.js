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
	@build			26th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_foAQwOmzhF_required = false;
jform_anTXLqQnMq_required = false;
jform_unEaDxhqRG_required = false;
jform_jWTPKsHcZM_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_JoAbvrL = jQuery("#jform_location input[type='radio']:checked").val();
	JoAbvrL(location_JoAbvrL);

	var location_rNfUvyQ = jQuery("#jform_location input[type='radio']:checked").val();
	rNfUvyQ(location_rNfUvyQ);

	var type_foAQwOm = jQuery("#jform_type").val();
	foAQwOm(type_foAQwOm);

	var type_anTXLqQ = jQuery("#jform_type").val();
	anTXLqQ(type_anTXLqQ);

	var type_unEaDxh = jQuery("#jform_type").val();
	unEaDxh(type_unEaDxh);

	var target_jWTPKsH = jQuery("#jform_target input[type='radio']:checked").val();
	jWTPKsH(target_jWTPKsH);
});

// the JoAbvrL function
function JoAbvrL(location_JoAbvrL)
{
	// [Interpretation 7326] set the function logic
	if (location_JoAbvrL == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the rNfUvyQ function
function rNfUvyQ(location_rNfUvyQ)
{
	// [Interpretation 7326] set the function logic
	if (location_rNfUvyQ == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the foAQwOm function
function foAQwOm(type_foAQwOm)
{
	if (isSet(type_foAQwOm) && type_foAQwOm.constructor !== Array)
	{
		var temp_foAQwOm = type_foAQwOm;
		var type_foAQwOm = [];
		type_foAQwOm.push(temp_foAQwOm);
	}
	else if (!isSet(type_foAQwOm))
	{
		var type_foAQwOm = [];
	}
	var type = type_foAQwOm.some(type_foAQwOm_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_foAQwOmzhF_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_foAQwOmzhF_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_foAQwOmzhF_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_foAQwOmzhF_required = true;
		}
	}
}

// the foAQwOm Some function
function type_foAQwOm_SomeFunc(type_foAQwOm)
{
	// [Interpretation 7291] set the function logic
	if (type_foAQwOm == 3)
	{
		return true;
	}
	return false;
}

// the anTXLqQ function
function anTXLqQ(type_anTXLqQ)
{
	if (isSet(type_anTXLqQ) && type_anTXLqQ.constructor !== Array)
	{
		var temp_anTXLqQ = type_anTXLqQ;
		var type_anTXLqQ = [];
		type_anTXLqQ.push(temp_anTXLqQ);
	}
	else if (!isSet(type_anTXLqQ))
	{
		var type_anTXLqQ = [];
	}
	var type = type_anTXLqQ.some(type_anTXLqQ_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_anTXLqQnMq_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_anTXLqQnMq_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_anTXLqQnMq_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_anTXLqQnMq_required = true;
		}
	}
}

// the anTXLqQ Some function
function type_anTXLqQ_SomeFunc(type_anTXLqQ)
{
	// [Interpretation 7291] set the function logic
	if (type_anTXLqQ == 1)
	{
		return true;
	}
	return false;
}

// the unEaDxh function
function unEaDxh(type_unEaDxh)
{
	if (isSet(type_unEaDxh) && type_unEaDxh.constructor !== Array)
	{
		var temp_unEaDxh = type_unEaDxh;
		var type_unEaDxh = [];
		type_unEaDxh.push(temp_unEaDxh);
	}
	else if (!isSet(type_unEaDxh))
	{
		var type_unEaDxh = [];
	}
	var type = type_unEaDxh.some(type_unEaDxh_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_unEaDxhqRG_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_unEaDxhqRG_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_unEaDxhqRG_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_unEaDxhqRG_required = true;
		}
	}
}

// the unEaDxh Some function
function type_unEaDxh_SomeFunc(type_unEaDxh)
{
	// [Interpretation 7291] set the function logic
	if (type_unEaDxh == 2)
	{
		return true;
	}
	return false;
}

// the jWTPKsH function
function jWTPKsH(target_jWTPKsH)
{
	// [Interpretation 7326] set the function logic
	if (target_jWTPKsH == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_jWTPKsHcZM_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_jWTPKsHcZM_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_jWTPKsHcZM_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_jWTPKsHcZM_required = true;
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

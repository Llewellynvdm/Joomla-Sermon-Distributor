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
	@build			23rd December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_PDcWyEnUDa_required = false;
jform_oNdbymxKWg_required = false;
jform_QQNYqXlxnF_required = false;
jform_xgjGfFertA_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_pLIjTGg = jQuery("#jform_location input[type='radio']:checked").val();
	pLIjTGg(location_pLIjTGg);

	var location_Ajshqyg = jQuery("#jform_location input[type='radio']:checked").val();
	Ajshqyg(location_Ajshqyg);

	var type_PDcWyEn = jQuery("#jform_type").val();
	PDcWyEn(type_PDcWyEn);

	var type_oNdbymx = jQuery("#jform_type").val();
	oNdbymx(type_oNdbymx);

	var type_QQNYqXl = jQuery("#jform_type").val();
	QQNYqXl(type_QQNYqXl);

	var target_xgjGfFe = jQuery("#jform_target input[type='radio']:checked").val();
	xgjGfFe(target_xgjGfFe);
});

// the pLIjTGg function
function pLIjTGg(location_pLIjTGg)
{
	// [8248] set the function logic
	if (location_pLIjTGg == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the Ajshqyg function
function Ajshqyg(location_Ajshqyg)
{
	// [8248] set the function logic
	if (location_Ajshqyg == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the PDcWyEn function
function PDcWyEn(type_PDcWyEn)
{
	if (isSet(type_PDcWyEn) && type_PDcWyEn.constructor !== Array)
	{
		var temp_PDcWyEn = type_PDcWyEn;
		var type_PDcWyEn = [];
		type_PDcWyEn.push(temp_PDcWyEn);
	}
	else if (!isSet(type_PDcWyEn))
	{
		var type_PDcWyEn = [];
	}
	var type = type_PDcWyEn.some(type_PDcWyEn_SomeFunc);


	// [8226] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_PDcWyEnUDa_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_PDcWyEnUDa_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_PDcWyEnUDa_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_PDcWyEnUDa_required = true;
		}
	}
}

// the PDcWyEn Some function
function type_PDcWyEn_SomeFunc(type_PDcWyEn)
{
	// [8213] set the function logic
	if (type_PDcWyEn == 3)
	{
		return true;
	}
	return false;
}

// the oNdbymx function
function oNdbymx(type_oNdbymx)
{
	if (isSet(type_oNdbymx) && type_oNdbymx.constructor !== Array)
	{
		var temp_oNdbymx = type_oNdbymx;
		var type_oNdbymx = [];
		type_oNdbymx.push(temp_oNdbymx);
	}
	else if (!isSet(type_oNdbymx))
	{
		var type_oNdbymx = [];
	}
	var type = type_oNdbymx.some(type_oNdbymx_SomeFunc);


	// [8226] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_oNdbymxKWg_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_oNdbymxKWg_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_oNdbymxKWg_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_oNdbymxKWg_required = true;
		}
	}
}

// the oNdbymx Some function
function type_oNdbymx_SomeFunc(type_oNdbymx)
{
	// [8213] set the function logic
	if (type_oNdbymx == 1)
	{
		return true;
	}
	return false;
}

// the QQNYqXl function
function QQNYqXl(type_QQNYqXl)
{
	if (isSet(type_QQNYqXl) && type_QQNYqXl.constructor !== Array)
	{
		var temp_QQNYqXl = type_QQNYqXl;
		var type_QQNYqXl = [];
		type_QQNYqXl.push(temp_QQNYqXl);
	}
	else if (!isSet(type_QQNYqXl))
	{
		var type_QQNYqXl = [];
	}
	var type = type_QQNYqXl.some(type_QQNYqXl_SomeFunc);


	// [8226] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_QQNYqXlxnF_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_QQNYqXlxnF_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_QQNYqXlxnF_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_QQNYqXlxnF_required = true;
		}
	}
}

// the QQNYqXl Some function
function type_QQNYqXl_SomeFunc(type_QQNYqXl)
{
	// [8213] set the function logic
	if (type_QQNYqXl == 2)
	{
		return true;
	}
	return false;
}

// the xgjGfFe function
function xgjGfFe(target_xgjGfFe)
{
	// [8248] set the function logic
	if (target_xgjGfFe == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_xgjGfFertA_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_xgjGfFertA_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_xgjGfFertA_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_xgjGfFertA_required = true;
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

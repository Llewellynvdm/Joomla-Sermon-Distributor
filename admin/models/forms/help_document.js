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
	@build			21st February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_jcTlLIzzmU_required = false;
jform_IQphLzkpQA_required = false;
jform_pWDgJoNcvN_required = false;
jform_hLRaUvJvdU_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_zkpehfO = jQuery("#jform_location input[type='radio']:checked").val();
	zkpehfO(location_zkpehfO);

	var location_RSFshae = jQuery("#jform_location input[type='radio']:checked").val();
	RSFshae(location_RSFshae);

	var type_jcTlLIz = jQuery("#jform_type").val();
	jcTlLIz(type_jcTlLIz);

	var type_IQphLzk = jQuery("#jform_type").val();
	IQphLzk(type_IQphLzk);

	var type_pWDgJoN = jQuery("#jform_type").val();
	pWDgJoN(type_pWDgJoN);

	var target_hLRaUvJ = jQuery("#jform_target input[type='radio']:checked").val();
	hLRaUvJ(target_hLRaUvJ);
});

// the zkpehfO function
function zkpehfO(location_zkpehfO)
{
	// [8696] set the function logic
	if (location_zkpehfO == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the RSFshae function
function RSFshae(location_RSFshae)
{
	// [8696] set the function logic
	if (location_RSFshae == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the jcTlLIz function
function jcTlLIz(type_jcTlLIz)
{
	if (isSet(type_jcTlLIz) && type_jcTlLIz.constructor !== Array)
	{
		var temp_jcTlLIz = type_jcTlLIz;
		var type_jcTlLIz = [];
		type_jcTlLIz.push(temp_jcTlLIz);
	}
	else if (!isSet(type_jcTlLIz))
	{
		var type_jcTlLIz = [];
	}
	var type = type_jcTlLIz.some(type_jcTlLIz_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_jcTlLIzzmU_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_jcTlLIzzmU_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_jcTlLIzzmU_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_jcTlLIzzmU_required = true;
		}
	}
}

// the jcTlLIz Some function
function type_jcTlLIz_SomeFunc(type_jcTlLIz)
{
	// [8661] set the function logic
	if (type_jcTlLIz == 3)
	{
		return true;
	}
	return false;
}

// the IQphLzk function
function IQphLzk(type_IQphLzk)
{
	if (isSet(type_IQphLzk) && type_IQphLzk.constructor !== Array)
	{
		var temp_IQphLzk = type_IQphLzk;
		var type_IQphLzk = [];
		type_IQphLzk.push(temp_IQphLzk);
	}
	else if (!isSet(type_IQphLzk))
	{
		var type_IQphLzk = [];
	}
	var type = type_IQphLzk.some(type_IQphLzk_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_IQphLzkpQA_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_IQphLzkpQA_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_IQphLzkpQA_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_IQphLzkpQA_required = true;
		}
	}
}

// the IQphLzk Some function
function type_IQphLzk_SomeFunc(type_IQphLzk)
{
	// [8661] set the function logic
	if (type_IQphLzk == 1)
	{
		return true;
	}
	return false;
}

// the pWDgJoN function
function pWDgJoN(type_pWDgJoN)
{
	if (isSet(type_pWDgJoN) && type_pWDgJoN.constructor !== Array)
	{
		var temp_pWDgJoN = type_pWDgJoN;
		var type_pWDgJoN = [];
		type_pWDgJoN.push(temp_pWDgJoN);
	}
	else if (!isSet(type_pWDgJoN))
	{
		var type_pWDgJoN = [];
	}
	var type = type_pWDgJoN.some(type_pWDgJoN_SomeFunc);


	// [8674] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_pWDgJoNcvN_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_pWDgJoNcvN_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_pWDgJoNcvN_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_pWDgJoNcvN_required = true;
		}
	}
}

// the pWDgJoN Some function
function type_pWDgJoN_SomeFunc(type_pWDgJoN)
{
	// [8661] set the function logic
	if (type_pWDgJoN == 2)
	{
		return true;
	}
	return false;
}

// the hLRaUvJ function
function hLRaUvJ(target_hLRaUvJ)
{
	// [8696] set the function logic
	if (target_hLRaUvJ == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_hLRaUvJvdU_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_hLRaUvJvdU_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_hLRaUvJvdU_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_hLRaUvJvdU_required = true;
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

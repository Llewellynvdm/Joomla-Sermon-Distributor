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
	@build			5th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.js
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// Some Global Values
jform_yDXLKGLYQj_required = false;
jform_vHHglafOKE_required = false;
jform_iTgyasQeff_required = false;
jform_txHrnuSUsE_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_wJBqozs = jQuery("#jform_location input[type='radio']:checked").val();
	wJBqozs(location_wJBqozs);

	var location_gkleSmj = jQuery("#jform_location input[type='radio']:checked").val();
	gkleSmj(location_gkleSmj);

	var type_yDXLKGL = jQuery("#jform_type").val();
	yDXLKGL(type_yDXLKGL);

	var type_vHHglaf = jQuery("#jform_type").val();
	vHHglaf(type_vHHglaf);

	var type_iTgyasQ = jQuery("#jform_type").val();
	iTgyasQ(type_iTgyasQ);

	var target_txHrnuS = jQuery("#jform_target input[type='radio']:checked").val();
	txHrnuS(target_txHrnuS);
});

// the wJBqozs function
function wJBqozs(location_wJBqozs)
{
	// [8269] set the function logic
	if (location_wJBqozs == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the gkleSmj function
function gkleSmj(location_gkleSmj)
{
	// [8269] set the function logic
	if (location_gkleSmj == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the yDXLKGL function
function yDXLKGL(type_yDXLKGL)
{
	if (isSet(type_yDXLKGL) && type_yDXLKGL.constructor !== Array)
	{
		var temp_yDXLKGL = type_yDXLKGL;
		var type_yDXLKGL = [];
		type_yDXLKGL.push(temp_yDXLKGL);
	}
	else if (!isSet(type_yDXLKGL))
	{
		var type_yDXLKGL = [];
	}
	var type = type_yDXLKGL.some(type_yDXLKGL_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_yDXLKGLYQj_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_yDXLKGLYQj_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_yDXLKGLYQj_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_yDXLKGLYQj_required = true;
		}
	}
}

// the yDXLKGL Some function
function type_yDXLKGL_SomeFunc(type_yDXLKGL)
{
	// [8234] set the function logic
	if (type_yDXLKGL == 3)
	{
		return true;
	}
	return false;
}

// the vHHglaf function
function vHHglaf(type_vHHglaf)
{
	if (isSet(type_vHHglaf) && type_vHHglaf.constructor !== Array)
	{
		var temp_vHHglaf = type_vHHglaf;
		var type_vHHglaf = [];
		type_vHHglaf.push(temp_vHHglaf);
	}
	else if (!isSet(type_vHHglaf))
	{
		var type_vHHglaf = [];
	}
	var type = type_vHHglaf.some(type_vHHglaf_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_vHHglafOKE_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_vHHglafOKE_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_vHHglafOKE_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_vHHglafOKE_required = true;
		}
	}
}

// the vHHglaf Some function
function type_vHHglaf_SomeFunc(type_vHHglaf)
{
	// [8234] set the function logic
	if (type_vHHglaf == 1)
	{
		return true;
	}
	return false;
}

// the iTgyasQ function
function iTgyasQ(type_iTgyasQ)
{
	if (isSet(type_iTgyasQ) && type_iTgyasQ.constructor !== Array)
	{
		var temp_iTgyasQ = type_iTgyasQ;
		var type_iTgyasQ = [];
		type_iTgyasQ.push(temp_iTgyasQ);
	}
	else if (!isSet(type_iTgyasQ))
	{
		var type_iTgyasQ = [];
	}
	var type = type_iTgyasQ.some(type_iTgyasQ_SomeFunc);


	// [8247] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_iTgyasQeff_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_iTgyasQeff_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_iTgyasQeff_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_iTgyasQeff_required = true;
		}
	}
}

// the iTgyasQ Some function
function type_iTgyasQ_SomeFunc(type_iTgyasQ)
{
	// [8234] set the function logic
	if (type_iTgyasQ == 2)
	{
		return true;
	}
	return false;
}

// the txHrnuS function
function txHrnuS(target_txHrnuS)
{
	// [8269] set the function logic
	if (target_txHrnuS == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_txHrnuSUsE_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_txHrnuSUsE_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_txHrnuSUsE_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_txHrnuSUsE_required = true;
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

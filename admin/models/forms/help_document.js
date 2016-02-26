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
jform_NCKgxlwFZY_required = false;
jform_XCxooMvuZd_required = false;
jform_pgDLIJxzwT_required = false;
jform_nhDdpbtHGn_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_hmAPgsz = jQuery("#jform_location input[type='radio']:checked").val();
	hmAPgsz(location_hmAPgsz);

	var location_BhHQJDK = jQuery("#jform_location input[type='radio']:checked").val();
	BhHQJDK(location_BhHQJDK);

	var type_NCKgxlw = jQuery("#jform_type").val();
	NCKgxlw(type_NCKgxlw);

	var type_XCxooMv = jQuery("#jform_type").val();
	XCxooMv(type_XCxooMv);

	var type_pgDLIJx = jQuery("#jform_type").val();
	pgDLIJx(type_pgDLIJx);

	var target_nhDdpbt = jQuery("#jform_target input[type='radio']:checked").val();
	nhDdpbt(target_nhDdpbt);
});

// the hmAPgsz function
function hmAPgsz(location_hmAPgsz)
{
	// [Interpretation 7326] set the function logic
	if (location_hmAPgsz == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the BhHQJDK function
function BhHQJDK(location_BhHQJDK)
{
	// [Interpretation 7326] set the function logic
	if (location_BhHQJDK == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the NCKgxlw function
function NCKgxlw(type_NCKgxlw)
{
	if (isSet(type_NCKgxlw) && type_NCKgxlw.constructor !== Array)
	{
		var temp_NCKgxlw = type_NCKgxlw;
		var type_NCKgxlw = [];
		type_NCKgxlw.push(temp_NCKgxlw);
	}
	else if (!isSet(type_NCKgxlw))
	{
		var type_NCKgxlw = [];
	}
	var type = type_NCKgxlw.some(type_NCKgxlw_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_NCKgxlwFZY_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_NCKgxlwFZY_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_NCKgxlwFZY_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_NCKgxlwFZY_required = true;
		}
	}
}

// the NCKgxlw Some function
function type_NCKgxlw_SomeFunc(type_NCKgxlw)
{
	// [Interpretation 7291] set the function logic
	if (type_NCKgxlw == 3)
	{
		return true;
	}
	return false;
}

// the XCxooMv function
function XCxooMv(type_XCxooMv)
{
	if (isSet(type_XCxooMv) && type_XCxooMv.constructor !== Array)
	{
		var temp_XCxooMv = type_XCxooMv;
		var type_XCxooMv = [];
		type_XCxooMv.push(temp_XCxooMv);
	}
	else if (!isSet(type_XCxooMv))
	{
		var type_XCxooMv = [];
	}
	var type = type_XCxooMv.some(type_XCxooMv_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_XCxooMvuZd_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_XCxooMvuZd_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_XCxooMvuZd_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_XCxooMvuZd_required = true;
		}
	}
}

// the XCxooMv Some function
function type_XCxooMv_SomeFunc(type_XCxooMv)
{
	// [Interpretation 7291] set the function logic
	if (type_XCxooMv == 1)
	{
		return true;
	}
	return false;
}

// the pgDLIJx function
function pgDLIJx(type_pgDLIJx)
{
	if (isSet(type_pgDLIJx) && type_pgDLIJx.constructor !== Array)
	{
		var temp_pgDLIJx = type_pgDLIJx;
		var type_pgDLIJx = [];
		type_pgDLIJx.push(temp_pgDLIJx);
	}
	else if (!isSet(type_pgDLIJx))
	{
		var type_pgDLIJx = [];
	}
	var type = type_pgDLIJx.some(type_pgDLIJx_SomeFunc);


	// [Interpretation 7304] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_pgDLIJxzwT_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_pgDLIJxzwT_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_pgDLIJxzwT_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_pgDLIJxzwT_required = true;
		}
	}
}

// the pgDLIJx Some function
function type_pgDLIJx_SomeFunc(type_pgDLIJx)
{
	// [Interpretation 7291] set the function logic
	if (type_pgDLIJx == 2)
	{
		return true;
	}
	return false;
}

// the nhDdpbt function
function nhDdpbt(target_nhDdpbt)
{
	// [Interpretation 7326] set the function logic
	if (target_nhDdpbt == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_nhDdpbtHGn_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_nhDdpbtHGn_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_nhDdpbtHGn_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_nhDdpbtHGn_required = true;
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

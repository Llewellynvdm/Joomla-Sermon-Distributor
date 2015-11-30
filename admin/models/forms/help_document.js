/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
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
jform_qBaiAonoCl_required = false;
jform_lRQxpwWmDS_required = false;
jform_tsDxjTicPJ_required = false;
jform_gengnJuwTQ_required = false;

// Initial Script
jQuery(document).ready(function()
{
	var location_XZUaOFk = jQuery("#jform_location input[type='radio']:checked").val();
	XZUaOFk(location_XZUaOFk);

	var location_VrJSUBc = jQuery("#jform_location input[type='radio']:checked").val();
	VrJSUBc(location_VrJSUBc);

	var type_qBaiAon = jQuery("#jform_type").val();
	qBaiAon(type_qBaiAon);

	var type_lRQxpwW = jQuery("#jform_type").val();
	lRQxpwW(type_lRQxpwW);

	var type_tsDxjTi = jQuery("#jform_type").val();
	tsDxjTi(type_tsDxjTi);

	var target_gengnJu = jQuery("#jform_target input[type='radio']:checked").val();
	gengnJu(target_gengnJu);
});

// the XZUaOFk function
function XZUaOFk(location_XZUaOFk)
{
	// [7974] set the function logic
	if (location_XZUaOFk == 1)
	{
		jQuery('#jform_admin_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_admin_view').closest('.control-group').hide();
	}
}

// the VrJSUBc function
function VrJSUBc(location_VrJSUBc)
{
	// [7974] set the function logic
	if (location_VrJSUBc == 2)
	{
		jQuery('#jform_site_view').closest('.control-group').show();
	}
	else
	{
		jQuery('#jform_site_view').closest('.control-group').hide();
	}
}

// the qBaiAon function
function qBaiAon(type_qBaiAon)
{
	if (isSet(type_qBaiAon) && type_qBaiAon.constructor !== Array)
	{
		var temp_qBaiAon = type_qBaiAon;
		var type_qBaiAon = [];
		type_qBaiAon.push(temp_qBaiAon);
	}
	else if (!isSet(type_qBaiAon))
	{
		var type_qBaiAon = [];
	}
	var type = type_qBaiAon.some(type_qBaiAon_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_url').closest('.control-group').show();
		if (jform_qBaiAonoCl_required)
		{
			updateFieldRequired('url',0);
			jQuery('#jform_url').prop('required','required');
			jQuery('#jform_url').attr('aria-required',true);
			jQuery('#jform_url').addClass('required');
			jform_qBaiAonoCl_required = false;
		}

	}
	else
	{
		jQuery('#jform_url').closest('.control-group').hide();
		if (!jform_qBaiAonoCl_required)
		{
			updateFieldRequired('url',1);
			jQuery('#jform_url').removeAttr('required');
			jQuery('#jform_url').removeAttr('aria-required');
			jQuery('#jform_url').removeClass('required');
			jform_qBaiAonoCl_required = true;
		}
	}
}

// the qBaiAon Some function
function type_qBaiAon_SomeFunc(type_qBaiAon)
{
	// [7939] set the function logic
	if (type_qBaiAon == 3)
	{
		return true;
	}
	return false;
}

// the lRQxpwW function
function lRQxpwW(type_lRQxpwW)
{
	if (isSet(type_lRQxpwW) && type_lRQxpwW.constructor !== Array)
	{
		var temp_lRQxpwW = type_lRQxpwW;
		var type_lRQxpwW = [];
		type_lRQxpwW.push(temp_lRQxpwW);
	}
	else if (!isSet(type_lRQxpwW))
	{
		var type_lRQxpwW = [];
	}
	var type = type_lRQxpwW.some(type_lRQxpwW_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_article').closest('.control-group').show();
		if (jform_lRQxpwWmDS_required)
		{
			updateFieldRequired('article',0);
			jQuery('#jform_article').prop('required','required');
			jQuery('#jform_article').attr('aria-required',true);
			jQuery('#jform_article').addClass('required');
			jform_lRQxpwWmDS_required = false;
		}

	}
	else
	{
		jQuery('#jform_article').closest('.control-group').hide();
		if (!jform_lRQxpwWmDS_required)
		{
			updateFieldRequired('article',1);
			jQuery('#jform_article').removeAttr('required');
			jQuery('#jform_article').removeAttr('aria-required');
			jQuery('#jform_article').removeClass('required');
			jform_lRQxpwWmDS_required = true;
		}
	}
}

// the lRQxpwW Some function
function type_lRQxpwW_SomeFunc(type_lRQxpwW)
{
	// [7939] set the function logic
	if (type_lRQxpwW == 1)
	{
		return true;
	}
	return false;
}

// the tsDxjTi function
function tsDxjTi(type_tsDxjTi)
{
	if (isSet(type_tsDxjTi) && type_tsDxjTi.constructor !== Array)
	{
		var temp_tsDxjTi = type_tsDxjTi;
		var type_tsDxjTi = [];
		type_tsDxjTi.push(temp_tsDxjTi);
	}
	else if (!isSet(type_tsDxjTi))
	{
		var type_tsDxjTi = [];
	}
	var type = type_tsDxjTi.some(type_tsDxjTi_SomeFunc);


	// [7952] set this function logic
	if (type)
	{
		jQuery('#jform_content-lbl').closest('.control-group').show();
		if (jform_tsDxjTicPJ_required)
		{
			updateFieldRequired('content',0);
			jQuery('#jform_content').prop('required','required');
			jQuery('#jform_content').attr('aria-required',true);
			jQuery('#jform_content').addClass('required');
			jform_tsDxjTicPJ_required = false;
		}

	}
	else
	{
		jQuery('#jform_content-lbl').closest('.control-group').hide();
		if (!jform_tsDxjTicPJ_required)
		{
			updateFieldRequired('content',1);
			jQuery('#jform_content').removeAttr('required');
			jQuery('#jform_content').removeAttr('aria-required');
			jQuery('#jform_content').removeClass('required');
			jform_tsDxjTicPJ_required = true;
		}
	}
}

// the tsDxjTi Some function
function type_tsDxjTi_SomeFunc(type_tsDxjTi)
{
	// [7939] set the function logic
	if (type_tsDxjTi == 2)
	{
		return true;
	}
	return false;
}

// the gengnJu function
function gengnJu(target_gengnJu)
{
	// [7974] set the function logic
	if (target_gengnJu == 1)
	{
		jQuery('#jform_groups').closest('.control-group').show();
		if (jform_gengnJuwTQ_required)
		{
			updateFieldRequired('groups',0);
			jQuery('#jform_groups').prop('required','required');
			jQuery('#jform_groups').attr('aria-required',true);
			jQuery('#jform_groups').addClass('required');
			jform_gengnJuwTQ_required = false;
		}

	}
	else
	{
		jQuery('#jform_groups').closest('.control-group').hide();
		if (!jform_gengnJuwTQ_required)
		{
			updateFieldRequired('groups',1);
			jQuery('#jform_groups').removeAttr('required');
			jQuery('#jform_groups').removeAttr('aria-required');
			jQuery('#jform_groups').removeClass('required');
			jform_gengnJuwTQ_required = true;
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

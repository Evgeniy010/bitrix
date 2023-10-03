<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

use \Bitrix\Crm;

if (\Bitrix\Main\Loader::includeModule('crm')) 
{ 
    $dbRes = CCrmLead::GetListEx( 
			$arOrder   = array('DATE_CREATE' => 'DESC'),  
			$arFilter  = array(),  
			$arGroupBy = false,  
			$arNavStartParams = false,  
			$arSelectFields = array('ID', 'TITLE', 'DATE_CREATE', 'SOURCE_ID', 'ASSIGNED_BY_LAST_NAME', 'ASSIGNED_BY_NAME', 'STATUS_ID', 'UF_CRM_LEAD_VERIFIED'),
			$arOptions = array('QUERY_OPTIONS' => ['LIMIT' => $arParam['LEAD_COUNT']]) );
	$arLeads  = [];
	$arSource = \CCrmStatus::GetStatus( 'SOURCE' );
	$arStatus = \CCrmStatus::GetStatus( 'STATUS' );

	while ($arRes = $dbRes->Fetch())
	{
		$arRes['SOURCE_NAME'] = $arSource[$arRes['SOURCE_ID']]['NAME'];
		$arRes['STATUS_NAME'] = $arStatus[$arRes['STATUS_ID']]['NAME'];
		$arLeads[] = $arRes;
	}
	$arResult['LEADS'] = $arLeads;
}

$arrHeaders = [
			['name' => GetMessage('COLUMN_NAME_TITLE')], 
		    ['name' => GetMessage('COLUMN_NAME_CREATED_DATE')],
		    ['name' => GetMessage('COLUMN_NAME_SOURCE')],
		    ['name' => GetMessage('COLUMN_NAME_RESPONSIBLE_NAME')],
		    ['name' => GetMessage('COLUMN_NAME_STATUS')],
		    ['name' => GetMessage('COLUMN_NAME_VERIFIED')],
		];

$arResult['HEADERS'] = $arrHeaders;
$this->IncludeComponentTemplate();

?>
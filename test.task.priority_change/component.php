<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}
use Bitrix\Main\Loader; 
Loader::includeModule("highloadblock"); 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

global $USER;

if ($USER->IsAuthorized())
{ 
	if (CModule::IncludeModule("tasks"))
	{
		$res = CTasks::GetList(
			Array('UF_PRIORITY' => 'ASC',
						 'sort' => 'asc'),
			Array('RESPONSIBLE_ID' => $USER->GetID()),
			Array('ID', 'TITLE', 'CREATED_DATE', 'RESPONSIBLE_LAST_NAME', 'RESPONSIBLE_NAME',
									'CREATED_BY_LAST_NAME', 'CREATED_BY_NAME', 'UF_PRIORITY'),
		);

		while ($arTask = $res->GetNext())
		{
			$arData[] = $arTask;
		}
	}
	$arResult['DATA']    = $arData;
	
	$arrHeaders = [
				['name' => GetMessage('COLUMN_NAME_TITLE')], 
			    ['name' => GetMessage('COLUMN_NAME_CREATED_DATE')],
			    ['name' => GetMessage('COLUMN_NAME_RESPONSIBLE_NAME')],
			    ['name' => GetMessage('COLUMN_NAME_CREATED_NAME')],
			    ['name' => GetMessage('COLUMN_NAME_PRIORITY')],
			];

	$arResult['HEADERS'] = $arrHeaders;

	$hlbl    = 2;
	$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
	$entity  = HL\HighloadBlockTable::compileEntity($hlblock); 
	$entity_data_class = $entity->getDataClass(); 

	$rsData  = $entity_data_class::getList(array(
		   "select" => array("*"),
		   "order"  => array("ID" => "DESC"),
		   "limit"  => 20,     // $arParam['PR_HISTORY_COUNT'],
	));

	while($arData = $rsData->Fetch()){
		$rsUser = CUser::GetByID($arData['UF_USER_ID']);
		$arUser = $rsUser->Fetch();
		
		$prHUserName   = $arUser["LAST_NAME"].' '.$arUser["NAME"];
		$prHistDate    = FormatDate("d.m.Y H:i", MakeTimeStamp($arData['UF_CHANGE_DATE']));
		$prHistUserURL = '/company/personal/user/'.$arData['UF_USER_ID'].'/';
		$prHistText    = sprintf(GetMessage('HISTORY_STRING_PATTERN'), ($arData['UF_PIORITY_UP'] ? GetMessage("PRIORITY_UP") : GetMessage("PRIORITY_DOWN")), $arData['UF_TASK_NAME']);
		
		$arPrHistoryStr[] = array(
			      'url' => $prHistUserURL,
			   'c_date' => $prHistDate,
			'user_name' => $prHUserName,
			     'text' => $prHistText,			
		);
	}
	$arResult['PRIORITY_HISTORY'] = $arPrHistoryStr;
		
}

$this->IncludeComponentTemplate();

?>
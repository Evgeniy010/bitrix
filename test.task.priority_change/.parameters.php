<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('crm'))
	return false;

$arComponentParameters = Array(
	'PARAMETERS' => array(	
		'PR_HISTORY_COUNT' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('CRM_PR_HISTORY_COUNT'),
			'TYPE' => 'INT',
			'DEFAULT' => 20
		)							
	)	
);
?>
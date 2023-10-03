<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	'NAME' => GetMessage('CRM_TASK_LIST_NAME'),
	'DESCRIPTION' => GetMessage('CRM_TASK_LIST_DESCRIPTION'),
	'SORT' => 20,
/*	'PATH' => array(
		'ID' => 'crm',
		'NAME' => GetMessage('CRM_NAME'),
		'CHILD' => array(
			'ID' => 'lead',
			'NAME' => GetMessage('CRM_TASK_NAME')
		)
	),   */
	'CACHE_PATH' => 'Y'
);
?>
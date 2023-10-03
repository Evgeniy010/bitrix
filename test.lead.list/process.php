<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

use \Bitrix\Crm;
global $USER_FIELD_MANAGER; 

if(isset($_POST['LEAD_ID'])) {

		$value = $USER_FIELD_MANAGER->GetUserFieldValue('CRM_LEAD', 'UF_CRM_LEAD_VERIFIED', $_POST['LEAD_ID']);
		if ($value) {
			$ret = $USER_FIELD_MANAGER->Update('CRM_LEAD', $_POST['LEAD_ID'], array("UF_CRM_LEAD_VERIFIED" => false)); 
		} else {
			$ret = $USER_FIELD_MANAGER->Update('CRM_LEAD', $_POST['LEAD_ID'], array("UF_CRM_LEAD_VERIFIED" => true)); 
		}
		
	header('Location: ' . $_SERVER['HTTP_REFERER']);

}  else {
	echo 'Error';
}
?>
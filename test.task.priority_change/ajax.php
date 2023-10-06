<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;
global $USER_FIELD_MANAGER; 
use Bitrix\Main\Loader; 
Loader::includeModule("highloadblock"); 
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;


if ( $_POST['ACTION'] ) {
	if (ISSET($_POST['TASK']) ) {
		switch($_POST['ACTION']){
			case 'pryority_up':
				$direction = -1;
				break;
			case 'pryority_down':
				$direction = 1;
				break;
		}
		
		$value = $USER_FIELD_MANAGER->GetUserFieldValue('TASKS_TASK', 'UF_PRIORITY', (int) $_POST['TASK']) + $direction; 
		if ($value < 1)	{
			echo 'fail';
			exit();
		}
		$ret = $USER_FIELD_MANAGER->Update('TASKS_TASK', $_POST['TASK'], array("UF_PRIORITY" => $value)); 
				
		add_HL_record($USER->GetID(), $USER->GetFullName(), $_POST['TASK_NAME'], $direction);
		
		echo 'success';
	}	
}

if ($_GET['action']=='get_history_arr') {
	if ($USER->IsAuthorized())
	{ 
		$hlbl    = 2;
		$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
		$entity  = HL\HighloadBlockTable::compileEntity($hlblock); 
		$entity_data_class = $entity->getDataClass(); 

		$rsData  = $entity_data_class::getList(array(
			   "select" => array("*"),
			   "order"  => array("ID" => "DESC"),
			   "limit"  => 20,
		));

		while($arData = $rsData->Fetch()){
			$rsUser = CUser::GetByID($arData['UF_USER_ID']);
			$arUser = $rsUser->Fetch();
			
			$prHUserName   = $arUser["LAST_NAME"].' '.$arUser["NAME"];
			$prHistDate    = FormatDate("d.m.Y H:i", MakeTimeStamp($arData['UF_CHANGE_DATE']));
			$prHistUserURL = '/company/personal/user/'.$arData['UF_USER_ID'].'/';
			$prHistText    = sprintf(' %s приоритет задачи %s', ($arData['UF_PIORITY_UP'] ? 'повысил(а)' : 'понизил(а)'), $arData['UF_TASK_NAME']);
			
			$arPrHistoryStr[] = array(
					  'url' => $prHistUserURL,
				   'c_date' => $prHistDate,
				'user_name' => $prHUserName,
					 'text' => $prHistText,			
			);
		}
		header('Content-Type: application/json');
		echo json_encode($arPrHistoryStr);
	}
}

function add_HL_record($user_id, $user_name, $task_name, $direction){
	$hlbl    = 2; // номер HL блока
	$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 
	$entity  = HL\HighloadBlockTable::compileEntity($hlblock); 
	$entity_data_class = $entity->getDataClass(); 
	$data = array(
		"UF_USER_ID"    => $user_id,
	  //  "UF_USERNAME"   => $user_name,
		"UF_TASK_NAME"  => $task_name,
		"UF_PIORITY_UP" => (($direction==1) ? $direction=0 : $direction=1)
	);

	$result = $entity_data_class::add($data);
}

?>
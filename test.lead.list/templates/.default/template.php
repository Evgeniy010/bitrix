<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<div class='lead_list_wraper'>
<div class='caption'><?=GetMessage('TITLE');?></div>

<TABLE class='table table-striped'>
<?foreach ($arResult['HEADERS'] as $item)
{
	echo '<th>'.$item['name'].'</th>';
}

foreach ($arResult['LEADS'] as $lead)
{?>	<TR>
	<TD><?=$lead['TITLE']?></TD>
	<TD><?=$lead['DATE_CREATE']?></TD>
	<TD><?=$lead['SOURCE_NAME']?></TD>
	<TD><?=$lead['ASSIGNED_BY_LAST_NAME'].' '.$lead['ASSIGNED_BY_NAME']?></TD>
	<TD><?=$lead['STATUS_NAME']?></TD>
	<TD>
	<form id='verified' action="/local/components/test.lead.list/process.php" method="post">
		<input type='hidden' name='LEAD_ID' value="<?=$lead['ID']?>" />
		<button class='btn' type="submit"><?=($lead['UF_CRM_LEAD_VERIFIED'] ? GetMessage('LEAD_CHECKED'): GetMessage('LEAD_UNCHECKED'))?></button>
	</form>
	</TD></TR>
<?}?>
</TABLE>
</div>


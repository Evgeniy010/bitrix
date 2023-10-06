<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
Bitrix\Main\UI\Extension::load( extNames: "ui.vue");
define('VUEJS_DEBUG', true);
CJSCore::Init(array('ajax'));
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<div class='list_wraper'>
<div class='caption'><?=GetMessage('PR_TITLE');?></div>
</div>

<div id="app">
</div>

<?
if ($arResult['DATA']) // Если пользователь не авторизовался массив не существует
{
?>
<script type="text/javascript">
var sortByPriority = function (d1, d2) { return (Number(d1.UF_PRIORITY) > Number(d2.UF_PRIORITY)) ? 1 : -1; };


	BX.Vue.create({
		el: '#app',
		data: {
			items:   <?=json_encode($arResult['DATA'])?>,
			headers: <?=json_encode($arResult['HEADERS'])?>,
			hist:    <?=json_encode($arResult['PRIORITY_HISTORY'])?>, 
		},
		
		methods: {
			pryority_up: function (item) {
				let task_id = Number(item.ID);
				task_name   = item.TITLE;
				if (Number(item.UF_PRIORITY)>1) {	
					BX.ajax({
						url: '/local/components/test.task.priority_change/ajax.php',
						data: {
							'ACTION'   : 'pryority_up',
							'TASK'     : task_id,
							'TASK_NAME': task_name,
						},
						method: 'POST',
						timeout: 10,
						onsuccess: function(data) {
							switch(data)
							{
								case 'success':
									item.UF_PRIORITY = Number(item.UF_PRIORITY) - 1;
									break;
								default:
									alert('Возникли ошибки!');
							}
						},
						onfailure: e => {
							alert(e);
						}
						
					});
				} else {
					alert('Наивысший приоритет!');
				}
					
			},
			pryority_down: function (item) {
				let task_id = Number(item.ID);
				task_name   = item.TITLE;
					
					BX.ajax({
						url: '/local/components/test.task.priority_change/ajax.php',
						data: {
							'ACTION'   : 'pryority_down',
							'TASK'     : task_id,
							'TASK_NAME': task_name,
						},
						method: 'POST',
						timeout: 10,
						onsuccess: function(data) {
							switch(data)
							{
								case 'success':
									item.UF_PRIORITY = Number(item.UF_PRIORITY) + 1;
									break;
								default:
									alert('Возникли ошибки!');
							}
						},
						onfailure: e => {
							alert(e);
						}
					});		
			},

			get_history() {
				fetch('/local/components/test.task.priority_change/ajax.php?action=get_history_arr')
				.then(response => response.json())
				.then(data => {
					this.hist = data;
				})
				.catch(error => {
					console.error('Ошибка:', error);
				});
			},
			
		},
		computed: {
			sortedTask () {
                return this.items.sort(sortByPriority);
             },
			 
		},
		mounted: function () {
			//this.get_history();
		},
		watch: {
			sortedTask: function () {
			console.log('upd');
			this.get_history(); 
			
		}},
		
		template: `
			<div>
			<table class="table table-striped" >
			<thead>
				<tr>
					<th scope="col" v-for="(item, index) in headers">
						<strong>{{ item.name }}</strong>
					</th>
				</tr>
			</thead> 
			<TransitionGroup name="list" tag="tbody">
				<tr v-for="(item, index) in sortedTask" :key="item.TITLE">
					<td>{{ item.TITLE }}</td>
					<td>{{ item.CREATED_DATE }}</td>
					<td>{{ item.RESPONSIBLE_LAST_NAME }} {{ item.RESPONSIBLE_NAME[0] }}.</td>
					<td>{{ item.CREATED_BY_LAST_NAME  }} {{ item.CREATED_BY_NAME[0]  }}.</td>
					<td>{{ item.UF_PRIORITY }}</td>
					<td class='btnUpDown'>
					<span class='btn' @click="pryority_up(item)">
						<i class="bi bi-caret-up"></i>
					</span>
					<span class='btn' @click="pryority_down(item)">
						<i class="bi bi-caret-down"></i>
					</span>
					</td>
				</tr>
			</TransitionGroup>
			</table>
			<div class='priority_history' v-for="(item, index) in hist">
				{{ item.c_date }} <a v-bind:href="item.url">{{ item.user_name }}</a> {{ item.text }}
			</div class='priority_history' v-model='hist'>
			</div>
	`
	});
</script>

<? } else { ?>
	<p>Информация доступна только авторизованным пользователям!</p>
	
<?}?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>


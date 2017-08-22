<?php
$list = $MODEL['list']; 
//vd($MODEL['error']);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>



<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
	
	
	.group{font-weight: bold !important; }
	.hint{font-weight: normal !important; }
	.group-status-<?=Status::ACTIVE?> .hint{display: none; }
	.group-status-<?=Status::INACTIVE?> {/*opacity: .6; */ color: #888; }
	.group-status-<?=Status::INACTIVE?> .hint{color: #888; font-style: italic; }
	
</style>


<?php 
if(count($list) )
{?>

	<table class="t">
		<tr>
			<th>#</th>
			<th>Дата</th>
			<th>Сущность</th>
			<th>Действие</th>
			<th>Obj</th>
			<th>Комментарий</th>
			<th>Админ</th>
		</tr>
		<?php 
		foreach($list as $key=>$item)
		{?>
			<tr >
				<td style="width:1px; font-size: 8px; text-align: center; "><?=(++$i)?>. </td>
				<td width="1"><?=$item->dateCreated?></td>

				<td >
                    <b><?=$item->objectType->name?></b>
                    <div style="font-size: .9em; ">[<?=$item->objectType->code?>]</div>
                </td>
				<td >
                    <b><?=$item->journalEntryType->name?></b>
                    <div style="font-size: .9em; ">[<?=$item->journalEntryType->code?>]</div>
                </td>
				<td ><?=$item->objectId?></td>
				<td ><?=$item->comment?></td>
                <td><?=$item->admin->name?></td>
			</tr>
		<?php 
		}?>
	</table>
	

	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>
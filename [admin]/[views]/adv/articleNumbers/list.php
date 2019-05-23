<?php
$list = $MODEL['list']; 
//vd($list);
//vd($MODEL['pageHelper']);
//$ph = $MODEL['pageHelper'];
//vd($ph);
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
	
</style>

<input id="add-btn" type="button" style="font-size: 1.2em; padding: 10px 15px; " onclick="edit();  " value="+ артикульный номер">
<p>




<div class="filters">

    <div class="section id">
        <h1>Искать:</h1>
        <input type="text" id="searchWord" value="<?=$MODEL['filters']['searchWord']?>" style="width: 140px;" />
        <button type="button" onclick="opts.searchWord=$('#searchWord').val(); list();  " >искать</button>
        <button type="button" onclick="opts.searchWord=''; list(); " >&times;</button>
    </div>


    <div class="clear"></div>
</div>





<?php 
if(count($list) )
{?>
Всего: <b><?=$MODEL['listCount']?></b> (<?=$MODEL['pageHelper']->infoStr()?>) <a href="javascript:void(0); " onclick="opts.p=1; opts.elPP=999999999; list(); ">показать все</a>


<div style="margin: 17px 0 7px 0; font-size: 10px; "><?=$MODEL['pageHelper']->html2(['onclick'=>'opts.p=###; list();', ])?></div>


<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/listSubmit" target="frame7" onsubmit="listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Название</th>
			<th>Картинка</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?php 
		foreach($list as $key=>$artNum)
		{?>
			<tr id="row-<?=$artNum->id?>" class="status-<?=$artNum->status ? $artNum->status->code : ''?> "  ondblclick="edit(<?=$artNum->id?>)">
				<td><?=$artNum->id?></td>
				<td width="1"  class="status-switcher" style="text-align: center; ">
					<a href="#" id="status-switcher-<?=$artNum->id?>" onclick="switchBrandStatus(<?=$artNum->id?>); return false; " ><?=$artNum->status->icon?></a>
				</td>
				<td><a href="#edit" onclick="edit(<?=$artNum->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$artNum->icon?> <?=$artNum->name?></td>
				
				<td><a class="highslide" href="/<?=UPLOAD_IMAGES_REL_DIR.$artNum->pic?>" onclick="return hs.expand(this)" title="Нажмите, чтобы увеличить"><img src="<?=Media::img($artNum->pic.'&height=30')?>" alt="" /></a></td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$artNum->id?>" name="idx[<?=$artNum->id?>]" value="<?=$artNum->idx?>" type="text"></td>
				<td>
					<a href="#delete" class="delete-btn status-<?=$item->status->code?>" onclick="if(confirm('Удалить?')){delete1(<?=$artNum->id?>);} return false;" style="font-size: 10px; color: red; ">&times; удалить</a>
				</td>
			</tr>
		<?php 
		}?>
	</table>

    <div style="margin: 7px 0 17px 0; font-size: 10px; "><?=$MODEL['pageHelper']->html2(['onclick'=>'opts.p=###; list();', ])?></div>


	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>
	
	
<?php
}
else
{?>
	Ничего нет.
<?php 	
} 
?>

<p><input id="add-btn" type="button" style="font-size: 1.2em; padding: 10px 15px; " onclick="/*Slonne.Adv.ArtNums.edit();*/ edit();  " value="+ артикульный номер">
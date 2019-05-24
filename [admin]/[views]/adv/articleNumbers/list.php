<?php
$list = $MODEL['list']; 
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<style>
	.status-active{}
	.status-inactive{opacity: .4;  }
	.delete-btn{display: none; }
	.status-inactive .delete-btn{display: block; }
</style>

<input id="add-btn" type="button" style="font-size: 1.2em; padding: 10px 15px; " onclick="edit();  " value="+ артикульный номер">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input id="add-btn" type="button" style="font-size: 1.2em; padding: 10px 15px; " onclick="multipleAddingForm();  " value="+ добавить толпой">
<p>



<div class="filters">

    <div class="section id">
        <h1>АртНомер:</h1>
        <input type="text" id="searchWord" value="<?=$MODEL['filters']['searchWord']?>" onblur="opts.searchWord=$(this).val(); list2();  " style="width: 140px;" />
        <button type="button" >искать</button>
        <button type="button" onclick="opts.searchWord=''; list2(); " >&times;</button>
    </div>

    <div class="section id">
        <h1>Бренд:</h1>
        <select type="text" id="brandId" value="<?=$MODEL['filters']['brandId']?>" onchange="opts.brandId=$(this).val(); list2();" style="width: 140px;" >
            <option value="">-выберите-</option>
            <?foreach($MODEL['dicts']['brands'] as $brand):?>
            <option value="<?=$brand->id?>" <?=$MODEL['filters']['brandId']==$brand->id ? 'selected' : ''?>><?=$brand->name?></option>
            <?endforeach;?>
        </select>
<!--        <button type="button" onclick="opts.searchWord=$('#searchWord').val(); list2();  " >искать</button>-->
        <button type="button" onclick="opts.brandId=''; list2(); " >&times;</button>
    </div>

    <div class="clear"></div>
</div>




<?if(count($list)):?>
Всего: <b><?=$MODEL['listCount']?></b> (<?=$MODEL['pageHelper']->infoStr()?>) <a href="javascript:void(0); " onclick="opts.p=1; opts.elPP=999999999; list2(); ">показать все</a>


<div style="margin: 17px 0 7px 0; font-size: 10px; "><?=$MODEL['pageHelper']->html2(['onclick'=>'opts.p=###; list2();', ])?></div>


<form id="list-form" method="post" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/listSubmit" target="frame7" onsubmit="listSubmitStart();" >
	<table class="t">
		<tr>
			<th>id</th>
			<th>Акт.</th>
			<th></th>
			<th>Название</th>
			<th>Картинка</th>
            <th>Бренд</th>
			<th>Сорт.</th>
			<th>Удалить</th>
		</tr>
		<?foreach($list as $key=>$artNum):?>
			<tr id="row-<?=$artNum->id?>" class="status-<?=$artNum->status ? $artNum->status->code : ''?> "  ondblclick="edit(<?=$artNum->id?>)">
				<td><?=$artNum->id?></td>
				<td width="1"  class="status-switcher" style="text-align: center; ">
					<a href="#" id="status-switcher-<?=$artNum->id?>" onclick="switchBrandStatus(<?=$artNum->id?>); return false; " ><?=$artNum->status->icon?></a>
				</td>
				<td><a href="#edit" onclick="edit(<?=$artNum->id?>); return false;">ред.</a></td>
				<td style="font-weight: bold; "><?=$artNum->icon?> <?=$artNum->name?></td>
				
				<td><a class="highslide" href="<?=$artNum->imgAbs()?>" onclick="return hs.expand(this)" title="Нажмите, чтобы увеличить"><img src="<?=Media::img($artNum->img().'&height=30')?>" alt="" /></a></td>

                <td>
                <?if($artNum->brand):?>
                    <?=$artNum->brand->name?>
                <?else:?>
                    -не указан (или не существует в базе - id:<?=$artNum->brandId?>)-
                <?endif?>
                </td>
				
				<td><input size="2" style="width: 25px; font-size: 9px;" id="idx-<?=$artNum->id?>" name="idx[<?=$artNum->id?>]" value="<?=$artNum->idx?>" type="text"></td>
				<td>
					<a href="#delete" class="delete-btn status-<?=$item->status->code?>" onclick="if(confirm('Удалить?')){delete1(<?=$artNum->id?>);} return false;" style="font-size: 10px; color: red; ">&times; удалить</a>
				</td>
			</tr>
		<?endforeach;?>
	</table>

    <div style="margin: 7px 0 17px 0; font-size: 10px; "><?=$MODEL['pageHelper']->html2(['onclick'=>'opts.p=###; list2();', ])?></div>


	<input type="submit" id="list-submit-btn" value="Сохранить изменения">
</form>
	
	
<?else:?>
	Ничего нет.
<?endif?>

<p><input id="add-btn" type="button" style="font-size: 1.2em; padding: 10px 15px; " onclick="/*Slonne.Adv.ArtNums.edit();*/ edit();  " value="+ артикульный номер">
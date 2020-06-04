<?php
$list = $MODEL['list'];
//vd($MODEL['statusClick']);
?>



<?if(count($list)):?>
    <? $counter = 0;  ?>
    <?foreach ($list as $item):?>
        <div class=" status-<?=$item->status->num?> item itemId-<?=$item->id?>">
            <div class="info">
                <div class="col counter"><?=++$counter?>.</div>
    <!--            <div class="col id">--><?//=$item->id?><!--</div>-->
                <div class="col status">
                    <a href="#" class="status-btn status-btn-2" onclick="<?=str_replace('_ID_', $item->id, $MODEL['statusClick'])?>; return false; "><i class="fa fa-toggle-off"></i></a>
                    <a href="#" class="status-btn status-btn-1" onclick="<?=str_replace('_ID_', $item->id, $MODEL['statusClick'])?>; return false; "><i class="fa fa-toggle-on"></i></a>
                </div>
                <div class="col title"><a href="#" onclick="<?=str_replace('_ID_', $item->id, $MODEL['click'])?>; return false; "><?=$item->name?> <sup style="font-size: 8px; ">id: <?=$item->id?></sup></a></div>
                <div class="col edit"><a href="#" onclick="<?=str_replace('_ID_', $item->id, $MODEL['edit'])?>; return false; " title="редактировать"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> </div>
            </div>
        </div>
    <?endforeach;?>
<?else:?>
 пусто.
<?endif;?>



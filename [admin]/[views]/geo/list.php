<?php
$countriesDict = $MODEL['countriesDict'];
$regionsDict = $MODEL['regionsDict'];
$citiesDict = $MODEL['citiesDict'];
//vd($countries);

?>

<style>
	/*.status-<?=Status::ACTIVE?>{}
	.status-<?=Status::INACTIVE?>{opacity: .4;  }
	.delete-btn{display: none; }
	.status-<?=Status::INACTIVE?> .delete-btn{display: block; }

	.name{font-weight: bold; font-size: 1.0em;  }
	.is-large .name{font-size: 1.3em; }

	.is-large-lbl{text-align: center; }
	.is-large .is-large-lbl{font-weight: bold; }	*/



    .country{ border: 0px solid red; margin: 0 0 20px 0; }
    .country > .info{}
    /*.country > .info > .num{width: 10px; text-align: right;  }*/
    .country > .info > .id{width: 20px; text-align: right; }
    .country > .info > .status{width: 30px; text-align: center;  }
    .country > .info > .title{font-size: 22px; }
    .country.status-2 > .info > .title{color: #6b6b6b; }


    .status-btn{display: none; }
    .country.status-2 > .info > .status > .status-btn-2{ display: inline-block;  }
    .country.status-1 > .info > .status > .status-btn-1{ display: inline-block;  }


    .regions{margin: 18px 0 50px 50px; }
    .regions>.region{border: 0px solid red; margin: 0 0 15px 0; }
    .regions>.region > .info{}
    /*.regions>.region > .info > .num{width: 10px; text-align: right;  }*/
    .regions>.region > .info > .id{width: 20px; text-align: right; font-size: 11px;  }
    .regions>.region > .info > .status{width: 30px; text-align: center;  }
    .regions>.region > .info > .title{font-size: 15px; font-weight: normal;   }
    .regions>.region > .info > .title>a{text-decoration: none; }
    .regions>.region.status-2 > .info > .title{color: #6b6b6b; }

    .regions>.region.status-2 > .info > .status > .status-btn-2{ display: inline-block;  }
    .regions>.region.status-1 > .info > .status > .status-btn-1{ display: inline-block;  }



    .cities{margin: 14px 0 20px 50px; }
    .cities>.city{border: 0px solid red; margin: 0 0 9px 0; }
    .cities>.city > .info{}
    /*.cities>.city > .info > .num{width: 10px; text-align: right;  }*/
    .cities>.city > .info > .id{width: 20px; text-align: right; font-size: 8px;  }
    .cities>.city > .info > .status{width: 30px; text-align: center;  }
    .cities>.city > .info > .title{font-size: 13px; font-weight: normal;   }
    .cities>.city > .info > .title>a{text-decoration: none; }
    .cities>.city.status-2 > .info > .title{color: #6b6b6b; }

    .cities>.city.status-2 > .info > .status > .status-btn-2{ display: inline-block;  }
    .cities>.city.status-1 > .info > .status > .status-btn-1{ display: inline-block;  }



    .col{display: inline-block;  vertical-align: middle;  border: 0px solid #000; }

</style>






<?
$countriesCounter = 0;
?>
<?foreach ($countriesDict as $country):?>
    <?
    $regions = $country->regions($regionsDict);
    $countriesCounter++;
    ?>
    <div class="country country-<?=$country->id?> status-<?=$country->status->num?>">
        <div class="info">
    <!--        <div class="col num" style="font-size: .8em; ">--><?//=$countriesCounter?><!--.</div>-->
            <div class="col id"><?=$country->id?></div>
            <div class="col status">
                <a href="#" class="status-btn status-btn-2" onclick="Country.switchStatus(<?=$country->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                <a href="#" class="status-btn status-btn-1" onclick="Country.switchStatus(<?=$country->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
            </div>
            <div class="col title"><a href="#" onclick="$('.country-<?=$country->id?> > .regions').slideToggle('fast'); return false; "><?=$country->name?></a></div>
    <!--        <div class="col date">--><?//=$country->createdAt?><!--</div>-->
        </div>
        <div class="regions" style="display: ; ">
        <?if(count($regions)):?>

            <!--REGIONS-->
            <?$regionsCounter=0; ?>
            <?foreach ($regions as $region):?>
                <?
                $regionsCounter++;
                $cities = $region->cities($citiesDict);
                ?>
                <div class="region region-<?=$region->id?> status-<?=$region->status->num?>">
                    <div class="info">
                        <!--        <div class="col num" style="font-size: .8em; ">--><?//=$regionsCounter?><!--.</div>-->
                        <div class="col id"><?=$region->id?></div>
                        <div class="col status">
                            <a href="#" class="status-btn status-btn-2" onclick="Region.switchStatus(<?=$region->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                            <a href="#" class="status-btn status-btn-1" onclick="Region.switchStatus(<?=$region->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
                        </div>
                        <div class="col title"><a href="#" onclick="$('.region-<?=$region->id?> > .cities').slideToggle('fast');; return false; "><?=$region->name?></a></div>
                        <!--        <div class="col date">--><?//=$country->createdAt?><!--</div>-->
                    </div>
                    <div class="cities" style="display: none; ">
                        <?if(count($cities)):?>

                            <!--CITIES-->
                            <?$citiesCounter=0; ?>
                            <?foreach ($cities as $city):?>
                                <?$citiesCounter++; ?>
                                <div class="city city-<?=$city->id?> status-<?=$city->status->num?>">
                                    <div class="info">
                                        <!--        <div class="col num" style="font-size: .8em; ">--><?//=$regionsCounter?><!--.</div>-->
                                        <div class="col id"><?=$city->id?></div>
                                        <div class="col status">
                                            <a href="#" class="status-btn status-btn-2" onclick="City.switchStatus(<?=$city->id?>); return false; "><i class="fa fa-toggle-off"></i></a>
                                            <a href="#" class="status-btn status-btn-1" onclick="City.switchStatus(<?=$city->id?>); return false; "><i class="fa fa-toggle-on"></i></a>
                                        </div>
                                        <div class="col title"><a href="#" onclick="; return false; "><?=$city->name?></a></div>
                                        <!--        <div class="col date">--><?//=$city->createdAt?><!--</div>-->
                                    </div>
                                </div>
                            <?endforeach;?>


                        <?else:?>
                            Городов пока нет.
                        <?endif?>
                    </div>
                </div>
            <?endforeach;?>


        <?else:?>
            Регионов пока нет.
        <?endif?>
        </div>
    </div>

<?endforeach;?>





<!--
<?php
if($list)
{?>
	<table class="t">
		<tr>
			<th>#</th>
			<th>id</th>
			<th></th>
			<th>Город</th>
			<th>Крупный?</th>
			<th>Создано</th>
		</tr>
		<?php
		$i=0;
		foreach($list as $key=>$item)
		{?>
			<tr id="row-<?=$item->id?>" class="status-<?=$item->status->code?> <?=$item->isLarge ? 'is-large':''?>">
				<td style="font-size: 8px; "><?=(++$i)?>. </td>
				<td><?=$item->id?></td>
				<td width="1"  class="status-switcher">
					<a href="#" id="status-switcher-<?=$item->id?>" onclick="switchStatus(<?=$item->id?>); return false; " ><?=$item->status->icon?></a>
				</td>
				<td><a href="#" onclick="edit(<?=$item->id?>); return false;" class="name" ><?=$item->name?></a></td>
				<td class="is-large-lbl"><?=$item->isLarge ? 'ДА':'нет'?></td>
				<td><?=Funx::mkDate($item->dateCreated, 'with_time')?></td>
			</tr>
		<?php
		}?>
	</table>
	<p>
	<input type="button" value="+ добавить город" onclick="edit()" />
<?php
}
else
{?>
	Ничего нет.
<?php
}
?>
-->
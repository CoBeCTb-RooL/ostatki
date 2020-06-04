<?php
$item = $MODEL['item'];
$countries = $MODEL['countries'];
$currentCountryId = $MODEL['currentCountryId'];
$error = $MODEL['error'];
$title = $item ? 'Редактирование:' : 'Создание:';
//vd($countries);


//vd($currentCountryId);
?>




<?if(!$MODEL['error']):?>
	<div class="view">
		<h1><?=$title?></h1>
		<form id="form" method="" action="/<?=ADMIN_URL_SIGN?>/geo/regionEditSubmit" onsubmit="Geo.Region.editSubmit(); /*return false;*/ " target="frame7">
			<input type="hidden" name="id" value="<?=$item->id?>" />
            <div class="field-wrapper">
                <span class="label">Название<span class="required">*</span>: </span>
                <span class="value"><input type="text" name="name" value="<?=htmlspecialchars($item->name)?>" style="width: 250px; "></span>
            </div>

            <div class="field-wrapper">
                <span class="label">Страна<span class="required">*</span>: </span>
                <span class="value">
                    <select name="countryId" >
                        <option value="">-выберите-</option>
                        <?foreach ($countries as $c):?>
                        <option value="<?=$c->id?>" <?=$c->id==$item->countryId ? 'selected="selected"' : ($currentCountryId == $c->id ? 'selected="selected"' : '')?>    ><?=$c->name?> <?=$c->status->code == Status::INACTIVE ? '(НЕАКТ)' : ''?></option>
                        <?endforeach;?>
                    </select>
                </span>
            </div>

			<!--<div class="field-wrapper">
				<span class="label">Крупный? </span>
				<span class="value"><input type="checkbox" name="isLarge" <?=$item->isLarge ? ' checked="checked" ' : ''?>></span>
			</div>-->
			<p>
			<input type="submit" value="сохранить" />
		</form>
	</div>
<?else:?>
	<?=$MODEL['error']?>
<?endif; ?>




<iframe name="frame7" style="display: none; width: 98%; border: 1px dashed #ccc; background: #ececec;  height: 400px;">111</iframe>

<?php
$item = $MODEL['item'];
?>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>


<?php
if($item || 1)
{?>
	<div class="view" >
		<form id="edit-form" method="post" enctype="multipart/form-data" action="/<?=ADMIN_URL_SIGN?>/adv/article_numbers/multipleAddingFormSubmit" target="frame77" onsubmit="multipleAddingFormSubmitStart();" >
            <input type="hidden" name="save" value="0">
            <h1>Добавление артикулов</h1>

            <div class="field-wrapper">
                <span class="label">Картинки: </span>
                <span class="value"><input type="file" name="pics[]" multiple="multiple" onchange="$('#edit-form input[name=save]').val('0'); $('#edit-form').submit(); " /></span>
                <button type="button" onclick="$('#edit-form input[name=save]').val('0'); $('#edit-form').submit();">обновить</button>
                <div class="clear"></div>
            </div>

<!--			<input type="submit" value="добавить">-->
				
			<div class="loading" style="display: none;">Секунду...</div>
			<div class="info"></div>
		</form>


        <div id="preview">

            <div class="item" v-for="file in files">
                <div style="display: table; width: 100%;  ">
                    <div class="cell" style="width: 50px; ">
                        <img :src="file.base64" >
                    </div>
                    <div class="cell" style="padding: 0 0 0 5px; ">
                        <b>{{ file.artName }}</b>
                        <div style="font-size: .8em; color: #777; ">{{ file.name }}</div>
                        <div v-if="file.error" style="font-size: .8em; color: red; font-weight: bold; ">{{file.error}}</div>
                    </div>
                </div>
            </div>

            <div v-if="files.length>0" >
                <hr>
                Файлов: <b>{{files.length}}</b>
                <p>
                <button type="button" style="font-size: 1.2em; " onclick="if(confirm('Сохранить?')){$('#edit-form input[name=save]').val('1'); $('#edit-form').submit(); }">Сохранить</button>
            </div>


        </div>

	</div>
	<iframe name="frame77" style="display:  ; ">asdasd</iframe>
<?php 	
}
else 
{
	echo 'Артикульный номер не найден! ['.$_REQUEST['id'].']';
}
?>


<style>
    #preview{}
    #preview .item{ display: inline-block; border: 0px solid red; width: 250px; height: 60px;  padding: 3px 3px 9px 3px; vertical-align: middle; }
    #preview .item img{ width: 50px; }
    #preview .item .cell{display: table-cell; border: 0px solid green; vertical-align: middle; }
</style>


<script>
    var app = new Vue({
        el: '#preview',
        data: {
            files: []
        }
    })
</script>


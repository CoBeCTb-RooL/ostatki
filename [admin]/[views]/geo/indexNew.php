<?php
?>

<style>
    .section{border-right: 1px solid #ccc; display: table-cell; padding: 0 40px ; vertical-align: top;  min-width: 240px;  }
    .section.countries{}
    .section.regions{ min-width: 330px; }
    .section h3{border: 0px solid red; text-align: center; padding-bottom: 20px;  }
    h3{margin: 10px 0 10px 0; padding: 0; font-size: 18px; }

    .status-active{}
    .status-inactive{opacity: .5; }


    .item.active .title{font-weight: bold; }

    .status-1{}
    .status-2{opacity: .4;}


    .item{ border: 0px solid red; margin: 0 0 13px 0; }
    .item > .info{}
    /*.item > .info > .num{width: 10px; text-align: right;  }*/
    .item > .info > .id{width: 20px; text-align: right; }
    .item > .info > .status{width: 26px; text-align: center;  }
    .item > .info > .title{  }
    .item > .info > .title a{ text-decoration: none; white-space: nowrap;   }
    .countries  .item .info .title{font-size: 17px; }
    .regions  .item .info  .title{font-size: 15px; }
    .item.status-2 > .info > .title{color: #6b6b6b; }


    .status-btn{display: none; }
    .item.status-2 > .info > .status > .status-btn-2{ display: inline-block;  }
    .item.status-1 > .info > .status > .status-btn-1{ display: inline-block;  }



    .col{display: inline-block;  vertical-align: middle;  border: 0px solid #000; }
    .col.counter{ font-size: .7em; }
    .col.id{ font-size: .8em; }
    .col.edit a{ width: 20px; color: #e27700; }


</style>




<script>
    var Geo = {
        Data: {
            Current: {
                country: null,
                region: null,
                city: null
            },
        },

        Country: {
            _w: null,
            $w: function(){
                return this._w ? this._w : $('.countries')
            },

            list: function (){
                // Geo.Data.Current.country = null
                // Geo.Data.Current.region = null
                // Geo.Data.Current.city = null

                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/countriesList/',
                    data: '',
                    beforeSend: function(){$.fancybox.showLoading(); },
                    success: function(data){
                        Geo.Country.$w().find('.inner').html(data)
                    },
                    error: function(){alert('Возникла ошибка...Попробуйте позже!')},
                    complete: function(){ $.fancybox.hideLoading(); }
                });
            },

            setCurrent: function(id){
                Geo.Data.Current.country = id
                this.$w().find('.item').removeClass('active')
                if(typeof id != 'undefined')
                    this.$w().find('.itemId-'+id).addClass('active')
            },

            click: function(id){
                this.setCurrent(id)
                Geo.Region.list(id)
                Geo.City.zaglushka()
            },

            switchStatus: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/countrySwitchStatus',
                    data: 'id='+id,
                    dataType: 'json',
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        if(data.error==''){
                            Geo.Country.$w().find('.itemId-'+id).removeClass('status-1').removeClass('status-2').addClass('status-'+data.status.num)
                        }
                        else
                            this.error(data.error)
                    },
                    error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },

            edit: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/countryEditForm',
                    data: {id: id},
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        $('#float').html(data)
                        $.fancybox('#float');
                    },
                    error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },


            editSubmit: function(){
                $.fancybox.showLoading()
                $('#form').submit()
            },
            editSubmitComplete: function(data){
                $.fancybox.hideLoading()
                if(!data.errors || data.errors.length == 0) {
                    $.fancybox.close()
                    notice('Сохранено!')
                    Geo.Country.list()
                    if(Geo.Data.Current.country > 0)
                        Geo.Country.setCurrent(Geo.Data.Current.country)
                }
                else
                    error(data.errors[0].error)
            },


        },



        Region: {
            _w: null,
            $w: function(){
                return this._w ? this._w : $('.regions')
            },
            list: function(countryId){
                // Geo.Data.Current.region = null
                // Geo.Data.Current.city = null

                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/regionsList/',
                    data: {countryId: countryId},
                    beforeSend: function(){$.fancybox.showLoading(); },
                    success: function(data){
                        Geo.Region.$w().find('.inner').html(data)
                    },
                    error: function(){alert('Возникла ошибка...Попробуйте позже!')},
                    complete: function(){ $.fancybox.hideLoading(); }
                });
            },
            switchStatus: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/regionSwitchStatus',
                    data: 'id='+id,
                    dataType: 'json',
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        if(data.error==''){
                            Geo.Region.$w().find('.itemId-'+id).removeClass('status-1').removeClass('status-2').addClass('status-'+data.status.num)
                        }
                        else
                            this.error(data.error)
                    },
                    error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },
            setCurrent: function(id){
                Geo.Data.Current.region = id
                this.$w().find('.item').removeClass('active')
                if(typeof id != 'undefined')
                    this.$w().find('.itemId-'+id).addClass('active')
            },
            click: function(id){
                this.setCurrent(id)
                Geo.City.list(id)
            },
            zaglushka: function(){
                this.$w().find('.inner').html('← Выберите страну сперва')
            },

            edit: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/regionEditForm',
                    data: {id: id, currentCountryId: Geo.Data.Current.country},
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        $('#float').html(data)
                        $.fancybox('#float');
                    },
                    error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },

            editSubmit: function(){
                $.fancybox.showLoading()
                // $('#form').submit()
            },
            editSubmitComplete: function(data){
                $.fancybox.hideLoading()
                if(!data.errors || data.errors.length == 0) {
                    $.fancybox.close()
                    notice('Сохранено!')
                    Geo.Region.list(Geo.Data.Current.country)
                    if(Geo.Data.Current.region > 0)
                        Geo.Region.setCurrent(Geo.Data.Current.region)
                }
                else
                    error(data.errors[0].error)
            },
        },



        City: {
            _w: null,
            $w: function(){
                return this._w ? this._w : $('.cities')
            },
            list: function(regionId){
                // Geo.Data.Current.city = null

                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/citiesList/',
                    data: {regionId: regionId},
                    beforeSend: function(){$.fancybox.showLoading(); },
                    success: function(data){
                        Geo.City.$w().find('.inner').html(data)
                    },
                    error: function(){alert('Возникла ошибка...Попробуйте позже!')},
                    complete: function(){ $.fancybox.hideLoading(); }
                });
            },
            switchStatus: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/citySwitchStatus',
                    data: 'id='+id,
                    dataType: 'json',
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        if(data.error==''){
                            Geo.City.$w().find('.itemId-'+id).removeClass('status-1').removeClass('status-2').addClass('status-'+data.status.num)
                        }
                        else
                            this.error(data.error)
                    },
                    error: function(e){alert(e=='' ? e : 'Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },
            setCurrent: function(id){
                Geo.Data.Current.city = id
                this.$w().find('.item').removeClass('active')
                if(typeof id != 'undefined')
                    this.$w().find('.itemId-'+id).addClass('active')
            },
            click: function(id){
                this.setCurrent(id)
            },
            zaglushka: function(){
                this.$w().find('.inner').html('← Выберите регион сперва')
            },

            edit: function(id){
                $.ajax({
                    url: '/<?=ADMIN_URL_SIGN?>/geo/cityEditForm',
                    data: {id: id, currentCountryId: Geo.Data.Current.country, currentRegionId: Geo.Data.Current.region},
                    beforeSend: function(){$.fancybox.showLoading()},
                    success: function(data){
                        $('#float').html(data)
                        $.fancybox('#float');
                    },
                    error: function(e){alert('Возникла ошибка на сервере... Попробуйте позже.')},
                    complete: function(){$.fancybox.hideLoading()}
                });
            },

            editSubmit: function(){
                $.fancybox.showLoading()
                // $('#form').submit()
            },
            editSubmitComplete: function(data){
                $.fancybox.hideLoading()
                if(!data.errors || data.errors.length == 0) {
                    $.fancybox.close()
                    notice('Сохранено!')
                    Geo.City.list(Geo.Data.Current.region)
                    if(Geo.Data.Current.region > 0)
                        Geo.City.setCurrent(Geo.Data.Current.city)
                }
                else
                    error(data.errors[0].error)
            },
        },

    }
</script>




<script>
    $(document).ready(function(){
        Geo.Country.list()
    })
</script>



<?php Core::renderPartial('adv/menu.php', $model);?>



<h1><i class="fa fa-globe"></i> Города и страны NEW</h1>


<?php if($MODEL['error']){ echo ''.$MODEL['error'].''; return; }?>




<!--<div>-->
<!--    <button type="button" onclick="Geo.Country.list(); return false; ">Country.list</button>-->
<!--</div>-->
<hr>


<div class="section countries">
    <h3>Страны</h3>
    <div class="inner"></div>
    <div class="loading" style="display: none;">заргузка...</div>
    <div style="margin: 20px 0; "><button type="button" onclick="Geo.Country.edit()">+ страна</button></div>
</div>


<div class="section regions">
    <h3>Регионы</h3>
    <div class="inner">&larr; Выберите страну сперва</div>
    <div class="loading" style="display: none;">заргузка...</div>
    <div style="margin: 20px 0; "><button type="button" onclick="Geo.Region.edit()">+ регион</button></div>
</div>



<div class="section cities">
    <h3>Города</h3>
    <div class="inner">&larr; Выберите регион сперва</div>
    <div class="loading" style="display: none;">заргузка...</div>
    <div style="margin: 20px 0; "><button type="button" onclick="Geo.City.edit()">+ город</button></div>
</div>





<iframe name="frame1" style="display: none; width: 98%; border: 1px dashed #ccc; background: #ececec;  height: 400px;">111</iframe>

<!--форма редактирования-->
<div id="float"  style="display:  ; min-width: 700px; max-width: 700px;  ">!!</div>










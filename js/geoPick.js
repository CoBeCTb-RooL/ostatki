var GeoPick = {

    Data: {},

    Countries: {
        initList: function(wrapper){
            if(typeof wrapper == 'undefined')
                wrapper = '#city-pick-new'
            $.ajax({
                url: '/index/geoPick_countriesJson',
                dataType: 'json',
                beforeSend: function(){},
                complete: function(){},
                success: function(data){
                    $(wrapper+' .country select').html('')
                    $(wrapper+' .country select').append('<option value="">-выберите-</option>')
                    $.each(data, function(i, country){
                        $(wrapper+' .country select').append('<option value="'+country.id+'">'+country.name+'</option>')
                    })
                },
                error: function(){},
            })
        },

    },

    Regions: {
        initList: function(countryId, wrapper){

            if(typeof wrapper == 'undefined')
                wrapper = '#city-pick-new'
            // alert(countryId)
            $(wrapper+' .city').slideUp('fast')

            if(countryId == ''){
                $(wrapper+' .region').slideUp('fast')
                return
            }
            // return
            $.ajax({
                url: '/index/geoPick_regionsJson',
                data: {countryId: countryId},
                dataType: 'json',
                beforeSend: function(){},
                complete: function(){},
                success: function(data){
                    $(wrapper+' .region').slideDown('fast')
                    $(wrapper+' .region select').html('')
                    $(wrapper+' .region select').append('<option value="">-выберите-</option>')
                    $.each(data, function(i, region){
                        $(wrapper+' .region select').append('<option value="'+region.id+'">'+region.name+'</option>')
                    })
                },
                error: function(){},
            })
        },

    },


    Cities: {
        initList: function(regionId, wrapper){
            if(typeof wrapper == 'undefined')
                wrapper = '#city-pick-new'
            // alert(regionId)
            if(regionId == ''){
                $(wrapper+' .city').slideUp('fast')
                return
            }
            // return
            $.ajax({
                url: '/index/geoPick_citiesJson',
                data: {regionId: regionId},
                dataType: 'json',
                beforeSend: function(){},
                complete: function(){},
                success: function(data){
                    $(wrapper+' .city').slideDown('fast')
                    $(wrapper+' .city select').html('')
                    $(wrapper+' .city select').append('<option value="">-выберите-</option>')
                    $.each(data, function(i, city){
                        $(wrapper+' .city select').append('<option value="'+city.id+'">'+city.name+'</option>')
                    })
                },
                error: function(){},
            })
        },

    },




}
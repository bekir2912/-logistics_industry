$(function() {
    // categories
    // view
    $(document).on('click', '.view_category', function() {
        var id = $(this).attr('data-value');

        $.ajax({
            url: '/admin/category/get-category',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'id':id},
            beforeSend: function() {
                $('#category-view-block').hide();
                $('#preloader').show();
            },
            success: function(data) {
                console.log(data);
                $('#category-view-icon').attr('src', data['category']['photo']);
                $('#category-icon-fa').html(data['category']['icon']);
                $('#category-name-ru').html(data['category']['name_ru']);
                $('#category-description-ru').html(data['category']['description_ru']);
                $('#category-name-uz').html(data['category']['name_uz']);
                $('#category-description-uz').html(data['category']['description_uz']);
                $('#category-name-en').html(data['category']['name_en']);
                $('#category-description-en').html(data['category']['description_en']);
                $('#preloader').hide();
                $('#category-view-block').fadeIn();
            }
        });

        return false;
    });

    // add / update subcategory
    $(document).on('click', '.update_category', function() {
        var id = $(this).attr('data-value');

        $.ajax({
            url: '/admin/category/get-category',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'id':id},
            beforeSend: function() {
                $('#category-update-block').hide();
                $('#preloader-update').show();
            },
            success: function(data) {
                console.log(data);
                if (data['category']['photo_id']) {
                    $('#remove-photo').attr('href', '/admin/default/remove-photo?id='+data['category']['photo_id']);
                } else {
                    $('#remove-photo').hide();
                }
                $('#category-update-id').val(data['category']['id']);
                $('#category-update-icon').attr('src', data['category']['photo']);
                $('#category-update-icon-fa').val(data['category']['icon']);
                $('#category-update-name-ru').val(data['category']['name_ru']);
                CKEDITOR.instances['category-update-description-ru'].setData(data['category']['description_ru']);
                $('#category-update-name-uz').val(data['category']['name_uz']);
                CKEDITOR.instances['category-update-description-uz'].setData(data['category']['description_uz']);
                $('#category-update-name-en').val(data['category']['name_en']);
                CKEDITOR.instances['category-update-description-en'].setData(data['category']['description_en']);
                $('#preloader-update').hide();
                $('#category-update-block').fadeIn();

                if (data['category']['main'] == 1) {
                    $('#check-main').attr('checked', 'checked');
                } else {
                    $('#check-main').removeAttr('checked');
                }
            }
        });

        return false;
    });

    // add subcategory
    $(document).on('click', '.add_category', function(){
        var id = $(this).attr('data-value');

        $.ajax({
            url: '/admin/category/get-category',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'id':id},
            beforeSend: function() {
                $('#category-add-block').hide();
                $('#preloader-add').show();
            },
            success: function(data) {
                if (data) {
                    $('#category-add-parent_id').val(data['category']['id']);
                    $('#preloader-add').hide();
                    $('#category-add-block').fadeIn();
                }
            }
        });

        return false;
    });

    // change language
    $(document).on('click', '.lang-button', function() {
        $('.lang-button').removeClass('btn-default').addClass('btn-primary');
        $(this).removeClass('btn-primary').addClass('btn-default');

        var val = $(this).find('img').attr('class').split('-');
        val = val[1];

        $('.lang-block').hide();
        $('.lang-block-'+val).show();

        return false;
    });

    $.datetimepicker.setLocale('ru');
    $('.datetimepicker').datetimepicker({
        timepicker:false,
        formatDate:'d/m/Y',
        format:'d/m/Y',
    });

    $.datetimepicker.setLocale('ru');
    $('.datetimepicker-news').datetimepicker({
        timepicker:false,
        formatDate:'Y-m-d',
        format:'Y-m-d',
    });

    // item list
	$('.dd').nestable({
        collapsedClass:'dd-collapsed',
     }).nestable('collapseAll');

    $(document).on('change', '.category-detail', function() {
        var element = $(this);
        $.ajax({
            url: '/admin/category/sub-category',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'id':element.val()},
            success: function(data) {
                $('#create-product-block').hide();
                element.parents('.form-group').nextAll('.form-group').remove();
                if (data['categories'] && (data['categories'].length > 0)) {
                    var new_element = element.parents('.form-group').clone();
                    new_element.find('select').html('');
                    new_element.find('select').append('<option value="0">Выберите подкатегорию</option>');
                    for (var i in data['categories']) {
                        new_element.find('select').append('<option value="'+data['categories'][i]['id']+'">'+data['categories'][i]['name']+'</option>');
                    }
                    $('#category-product-detail').append(new_element);
                } else {
                    if (element.val() != 0) {
                        $('#create-product-block').show();
                    }
                }
            }
        });
    });

    $("#save-category-sort").on('click', function(){
        var count = 0, top = [];
        $('.top').each(function(i){
            count++;
            var val = $(this).val();
            if (val) {
                top[i] = count+'-'+val;
            }
        });

        top = top.join(',');

        var count_second = 0, second = [];
        $('.second').each(function(i){
            count_second++;
            var val = $(this).val();
            if (val) {
                second[i] = count_second+'-'+val;
            }
        });

        second = second.join(',');

        var count_third = 0, third = [];
        $('.third').each(function(i){
            count_third++;
            var val = $(this).val();
            if (val) {
                third[i] = count_third+'-'+val;
            }
        });

        third = third.join(',');

        $.ajax({
            url: '/admin/category/save-sort',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'top':top, 'second':second, 'third':third},
            success: function(data) {
                $('.category-result').hide();
                if (data['save'] === true) {
                    $('#save-category-success').show();
                } else {
                    $('#save-category-error').show();
                }
            }
        });
        return false;
    });

    // approved remove
    $('.remove-object').on('click', function(event){
        event.stopPropagation();
        if(!confirm('Вы уверены, что хотите удалить?')){
            return false;
        }
    });

    // phone mask
    if ($("input").is(".sms-phone")) {
        $(".sms-phone").inputmask("998*********");
    }

    // change select page
    $(document).on('click', '#admin-type-page', function() {
        var val = $(this).val();
        (val == 1) ? $('#admin-section-page').hide() : $('#admin-section-page').show();
    });

    $('.add-variant-stock').on('click', function() {
        var block = $('#stock-form .stock-block').last().clone();

        block.find('input').val('');

        $('#stock-form').append(block);
    });

    $('.add-variant-quiz').on('click', function() {
        var block = $('#quiz-form .quiz-block').last().clone();

        block.find('input').val('');

        $('#quiz-form').append(block);
    });
    $('.add-block').on('click', function() {
        var block = $('#block-form .block-block').last().clone();

        block.find('input').val('');

        $('#block-form').append(block);
    });

    $('.add-color').on('click', function() {
        var color = $('#color-form .color-block').last().clone();

        color.find('input').val('');
        color.find('.remove-color').show();

        $('#color-form').append(color);

        var count = 0;
        var color_count = 0;
        $('.image-insert').each(function() {
            if (count == 7) {
                count = 0;
                color_count++;
            }

            $(this).prev().attr('name', 'Product[colors][image]['+color_count+']['+count+']');
            $(this).attr('name', 'Product[colors][image]['+color_count+']['+count+']');

            count++;
        });
    });

    $(document).on('click', '.remove-color', function() {
        $(this).parent().parent().parent().remove();
    });

    $(document).on('click', '.remove-block', function() {
        var block = $(this).parent().parent().parent();
        var cl = block.attr('class');

        if ($('.'+cl).length > 1) {
            block.remove();
        }
    });

    $('.product-tags').select2();
    $('.select-drop').select2();

    var value = $('#banner-type').val();
    $('.banner-alert').hide();
    $('#banner-'+value).show();

    $('#banner-type').on('change', function() {
        $('.banner-alert').hide();
        $('#banner-'+$(this).val()).show();
    });


    //plans
    $("#bplan-date_end").change(function() {
        let start = $('#bplan-date_start').val();
        let end = $('#bplan-date_end').val();

        if(start && end)
            // window.location.replace($(location).attr('href') + "?start="+start+"&end="+end );
            window.location.replace($(location).attr('pathname') +  "?start="+start+"&end="+end );

        // if (end) {
        //     $.ajax({
        //         url: '/admin/industry/b-plan/get-dates',
        //         type: 'post',
        //         data: {'start':start, 'end':end,},
        //         success: function(data) {
        //             if (data && data['data']) {
        //                 console.log(data);
        //
        //
        //
        //
        //                 // window.location.replace("http://texnopark-industry/admin/industry/b-plan/create");
        //
        //                 // block.append('<option value="">Выбрать марку</option>');
        //                 // for (var i in data['data']) {
        //                 //     block.append('<option value="'+data['data'][i]['id']+'">'+data['data'][i]['name']+'</option>');
        //                 // }
        //             }
        //         }
        //     });
        // }

    });

    //перезагрузка страницы при нажатии нахад в браузере
    if(!!window.performance && window.performance.navigation.type === 2)
    {
        window.location.reload();
    }


    $('#item-block td').click(function (e) {
        var url = $(this).closest('tr').attr('url');
        if(e.target == this)
            location.href = url;
    });


    $('.add-variant-product').on('click', function() {

        var block = $('#product-form .product-block').last().clone();

        block.find('.select2').remove();
        block.find('input').val('');
        block.find('textarea').val('');

        block.find('option').each(function(el) {
            $(this).removeAttr('selected');
        });

        if ($('.product-block').length < 2) {
            block.find('.box').prepend('<div class="box-header"><a href="javascript:;" class="btn btn-danger remove-block-product"><i class="fa fa-trash"></i> Удалить</a></div>');
        }

        $('#product-form').append(block);

        $('.select-drop').select2();

        block.find('.select2-container--default').attr('style', 'width:100%');
    });


    $('.add-variant-defect').on('click', function() {

         var block = $('#defect-form').clone().prependTo( ".append-block" );

        if ($('.append-block').length < 2) {
            block.parent().find('.button-add').html('<div class="box-header"><a href="javascript:;" class="btn btn-danger remove-block-defect"><i class="fa fa-trash"></i> Удалить</a></div>');
        }


        //
        // block.find('.select2').remove();
        // block.find('input').val('');
        // block.find('textarea').val('');
        //
        // block.find('option').each(function(el) {
        //     $(this).removeAttr('selected');
        // });
        //
        // if ($('.product-block').length < 2) {
        //     block.find('.box').prepend('<div class="box-header"><a href="javascript:;" class="btn btn-danger remove-block-product"><i class="fa fa-trash"></i> Удалить</a></div>');
        // }
        //
        // $('#product-form').append(block);
        //
        // $('.select-drop').select2();
        //
        // block.find('.select2-container--default').attr('style', 'width:100%');
    });

    $(document).on('click', '.remove-block-defect', function() {
        var block = $('.append-block > #defect-form');

        if ($(block.length > 1)) {
            $('.append-block').children().last().remove();
        }
    });





    $(document).on('click', '.remove-block-product', function() {
        var block = $(this).parent().parent().parent();

        if ($('.product-block').length > 1) {
            block.remove();
        }
    });

    $('.add-variant-phone').on('click', function() {
        var block = $('#phone-form .phone-block').last().clone();

        block.find('input').val('');

        $('#phone-form').append(block);
    });

    $('.add-variant-email').on('click', function() {
        var block = $('#email-form .email-block').last().clone();

        block.find('input').val('');

        $('#email-form').append(block);
    });

    $(document).on('click', '#action-links a', function() {
        var ids = [];
        var count = 0;

        var action = $(this).attr('data-value');
        var url = window.location.href.split('/');
        var page = url[4];

        $("#item-block input[type=checkbox]").each(function() {
            if ($(this).prop('checked')) {
                ids[count++] = $(this).val();
            }
        });

        if (ids.length > 0) {
            $.ajax({
                url: '/admin/default/change-all',
                type: 'post',
                data: {'ids':ids, 'action':action, 'page':page},
                success: function(data) {
                    console.log(data);
                }
            });
        }

        return false;
    });

    $(document).on('change', '#item-block input[type=checkbox]', function() {
        var check = false;

        $("#item-block input[type=checkbox]").each(function() {
            if ($(this).prop('checked')) {
                check = true;
            }
        });

        if (check === true) {
            $('#action-links').show();
        } else {
            $('#action-links').hide();
        }
    });

    // select drop
    $(document).on('change', '.select-drop-name', function() {
        $(this).parent().parent().next().find('select').val($(this).val()).trigger('change');
        return false;
    });

    $(document).on('change', '.select-drop-article', function() {
        $(this).parent().parent().prev().find('select').val($(this).val()).trigger('change');
        return false;
    });
});
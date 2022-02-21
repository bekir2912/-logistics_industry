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
                $('#category-count').html(data['category']['count']);
                $('#category-inn').html(data['category']['inn']);
                $('#category-mfo').html(data['category']['mfo']);
                $('#category-icon').attr('src', data['category']['photo']);
                $('#category-name-ru').html(data['category']['name_ru']);
                $('#category-description-ru').html(data['category']['description_ru']);
                $('#category-name-uz').html(data['category']['name_uz']);
                $('#category-description-uz').html(data['category']['description_uz']);
                $('#category-name-en').html(data['category']['name_en']);
                $('#category-description-en').html(data['category']['description_en']);
                $('#category-price').html(data['category']['price']);
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
                $('#category-update-count').val(data['category']['count']);
                $('#category-update-inn').val(data['category']['inn']);
                $('#category-update-mfo').val(data['category']['mfo']);
                $('#category-update-id').val(data['category']['id']);
                $('#category-update-icon').attr('src', data['category']['photo']);
                $('#category-update-name-ru').val(data['category']['name_ru']);
                CKEDITOR.instances['category-update-description-ru'].setData(data['category']['description_ru']);
                $('#category-update-name-uz').val(data['category']['name_uz']);
                CKEDITOR.instances['category-update-description-uz'].setData(data['category']['description_uz']);
                $('#category-update-name-en').val(data['category']['name_en']);
                CKEDITOR.instances['category-update-description-en'].setData(data['category']['description_en']);
                $('#category-update-price').val(data['category']['price']);
                $('#preloader-update').hide();
                $('#category-update-block').fadeIn();
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
        $('.lang-button').removeClass('btn-primary').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('btn-primary');

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


    // change select page
    $(document).on('click', '#admin-type-page', function() {
        var val = $(this).val();
        (val == 1) ? $('#admin-section-page').hide() : $('#admin-section-page').show();
    });
    
    $(document).on('change', '#select-region', function() {
        var id = $(this).val();
        $('#region-block').hide();
        $('#area-region').html('');
        $.ajax({
            url: '/main/get-sub',
            type: 'post',
            data: {csrf : '<?=Yii::$app->request->getCsrfToken()?>', 'id':id},
            success: function(data) {
                if (data && data['categories'] && (data['categories'].length > 0)) {
                    $('#area-region').append('<option value="">Выбрать</option>');
                    for (var i in data['categories']) {
                        $('#area-region').append('<option value="'+data['categories'][i]['id']+'">'+data['categories'][i]['name_ru']+'</option>');
                    }
                    $('#region-block').show();
                } else {
                    $('#area-region').html('');
                    $('#area-region').append('<option value="">Выберите регион</option>');
                }
            }
        });
        return false;
    });

    $(document).on('click', '.remove-block', function() {
        var block = $(this).parent().parent().parent();
        var cl = block.attr('class');

        if ($('.'+cl).length > 1) {
            block.remove();
        }
    });

    // checkbox
    $('#item-block input[type=checkbox]').on('change', function() {
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

    $('#action-links a').on('click', function() {
        var ids = [];
        var count = 0;

        var action = $(this).attr('data-value');
        var url = window.location.href.split('/');
        var page = url[url.length-1];
        page = page.split('?');
        page = page[0];

        $("#item-block input[type=checkbox]").each(function() {
            if ($(this).prop('checked')) {
                ids[count++] = $(this).val();
            }
        });

        $.ajax({
            url: '/admin/default/change-all',
            type: 'post',
            data: {'ids':ids, 'action':action, 'page':page},
            success: function(data) {}
        });

        return false;
    });

    $('#filter-type').on('change', function() {
        if ($(this).val() == 'input') {
            $('#filter-variable').hide();
        } else {
            $('#filter-variable').show();
        }
    });

    $(document).on('change', '#select-category', function() {
        $('.filter').hide();
        $('#extra-info').hide();

        var id = $(this).val();

        if (id) {
            $.ajax({
                url: '/admin/building/check-filter',
                type: 'post',
                data: {'id':id},
                success: function(data) {
                    if (data['result'] === true) {
                        $('.filter-'+id).show();
                        $('#extra-info').show();
                    }
                }
            });
        }
    });

    $('#shelfs-count').on('input', function() {
        var count = $(this).val();
        $('#shelf-blocks').html('');

        for(var i = 0; i < count; i++) {
            $('#shelf-blocks').append('<input type="text" class="form-control" name="Stack[shelfs][]" placeholder="Введите номер полки" aria-required="true"><br/>')
        }
    });

    $('#shop-shelfs-count').on('input', function() {
        var count = $(this).val();
        $('#shelf-blocks').html('');

        for(var i = 0; i < count; i++) {
            $('#shelf-blocks').append('<input type="text" class="form-control" name="ShopStack[shelfs][]" placeholder="Введите номер полки" aria-required="true"><br/>')
        }
    });

    $('.slider').slider();

    $(document).on('change', '.select-drop-name', function() {
        if ($(this).parent().parent().next().find('select').val() == '') {
            $(this).parent().parent().next().find('select').val($(this).val()).trigger('change');
        }

        return false;
    });

    $(document).on('change', '.select-drop-article', function() {
        if ($(this).parent().parent().prev().find('select').val() == '') {
            $(this).parent().parent().prev().find('select').val($(this).val()).trigger('change');
        }

        return false;
    });
});
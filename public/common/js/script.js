/**
 * option custom
 */
var optionCustomR = {
    size: {
        adminlte_sm: 767,
        custom_sm: 1195
    }
};

/**
 * select2 reload and trim text result
 */
function selectSearchReload(option) {
    optionDefault = {
        showSearch: false
    };
    option = jQuery.extend(optionDefault, option);
    if (option.showSearch) {
        jQuery(".select-search").select2();
    } else {
        jQuery(".select-search").select2({
            minimumResultsForSearch: Infinity
        });
    }
    
    jQuery('.select-search').each(function(i,k){
        text = jQuery(this).find('option:selected').text().trim();
        jQuery(this).siblings('.select2-container').find('.select2-selection__rendered').text(text);
    });
    jQuery('.select-search').on('select2:select', function (evt) {
        text = jQuery(this).find('option:selected').text().trim();
        jQuery(this).siblings('.select2-container').find('.select2-selection__rendered').text(text);
    });
}

jQuery(document).ready(function ($) {
//    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
//        event.preventDefault();
//        event.stopPropagation();
//        if ($(this).parent().hasClass('open')) {
//            $(this).parent().removeClass('open');
//            $(this).parent().find('li.dropdown-submenu').removeClass('open');
//        } else {
//            $(this).parent().addClass('open');
//        }
//    });
    
    //modal delete confirm
    $('.delete-confirm').removeAttr('disabled');
    var buttonClickShowModal;
    $('.delete-confirm').on('click', function (event) {
        if($(this).hasClass('process')) { //check flag processed
            return true;
        }
        event.preventDefault();
        buttonClickShowModal = $(this);
        $(this).addClass('process'); //set flag processing cofirm
        $('#modal-delete-confirm').modal('show');
    });
    $('#modal-delete-confirm').on('show.bs.modal', function (e) {
        var notification = buttonClickShowModal.data('noti');
        if (notification) {
            $(this).find('.modal-body .text-change').show().html(notification);
            $(this).find('.modal-body .text-default').hide().html(notification);
        } else {
            $(this).find('.modal-body .text-change').hide();
            $(this).find('.modal-body .text-default').show();
        }
    });
    $('#modal-delete-confirm').on('hide.bs.modal', function (e) {
        buttonClickShowModal.removeClass('process'); //remove flag processing cofirm
    });
    $('#modal-delete-confirm .modal-footer button').on('click', function (e) {
        if ($(this).hasClass('btn-ok')) {
            buttonClickShowModal.trigger('click');
            $('#modal-delete-confirm').modal('hide');
            return true;
        }
        $('#modal-delete-confirm').modal('hide');
        return false;
    });
    
    /**
     * model warning
     */
    var buttonClickShowModalWarning;
    $(document).on('click', '.warning-action', function (event) {
        buttonClickShowModalWarning = $(this);
        $('#modal-warning-notification').modal('show');
        return false;
    });
    $('#modal-warning-notification').on('show.bs.modal', function (e) {
        var notification = buttonClickShowModalWarning.data('noti');
        if (notification) {
            $(this).find('.modal-body .text-change').show().html(notification);
            $(this).find('.modal-body .text-default').hide().html(notification);
        } else {
            $(this).find('.modal-body .text-change').hide();
            $(this).find('.modal-body .text-default').show();
        }
    });
    
    /**
     * form input dropdown
     */
    $('.form-input-dropdown .input-menu a').click(function(event) {
        event.preventDefault();
        var textHtml = $(this).html();
        var dataValue = $(this).data('value');
        $(this).parents('.form-input-dropdown').find('.input-show-data span').html(textHtml);
        $(this).parents('.form-input-dropdown').find('.input').val(dataValue);
    });
});

jQuery(document).ready(function($) {
    /* filter-grid action */
    // get params from filter input
    function getSerializeFilter()
    {
        var valueFilter, nameFilter, params;
        params = '';
        $('.filter-grid').each(function(i,k) {
            valueFilter = $(this).val();
            nameFilter = $(this).attr('name');
            if (valueFilter && nameFilter) {
                params += nameFilter + '=' + valueFilter + '&';
            }
        });
        params += 'current_url=' + currentUrl;
        return params;
    }    
    //filter request with param filter
    function filterRequest()
    {
        data = getSerializeFilter();
        $('.btn-search-filter .fa').removeClass('hidden');
        $.ajax({
            url: baseUrl + 'grid/filter/request',
            type: 'GET',
            data: data,
            success: function() {
                window.location.href = currentUrl;
            }
        });
    }
    //filter pager with param filter
    function filterPager(pagerValue)
    {
        if (pagerValue == undefined) {
            pagerValue = $('.grid-pager form.form-pager input[name=page]').val();
        }
        data = {};
        data['limit'] = $('.grid-pager select[name=limit] option:selected').data('value');
        data['page'] = pagerValue;
        dataSubmit = {'filter_pager': data, 'current_url': currentUrl};
        $('.btn-search-filter .fa').removeClass('hidden');
        $.ajax({
            url: baseUrl + 'grid/filter/pager',
            type: 'GET',
            data: dataSubmit,
            success: function() {
                window.location.href = currentUrl;
            }
        });
    }
    
    //input filter grid key down - request filter action
    $(document).on('keydown','input.filter-grid',function(event) {
        if(event.which == 13) {
            filterRequest();
            return false;
        }
    });
    //reset filter
    $(document).on('click','.btn-reset-filter',function(event) {
        $('.btn-reset-filter .fa').removeClass('hidden');
        $.ajax({
            url: baseUrl + 'grid/filter/remove',
            type: 'GET',
            data: 'current_url=' + currentUrl,
            success: function() {
                window.location.href = currentUrl;
            }
        });
        return false;
    });
    
    //search filter button
    $(document).on('click','.btn-search-filter',function(event) {
        filterRequest();
        return false;
    });
    
    //pager 
    $('.grid-pager select[name=limit]').on('change', function(event) {
        event.preventDefault();
        filterPager(1);
    });
    $('.grid-pager .pagination a').on('click', function(event) {
        if ($(this).hasClass('disabled') || $(this).parent().hasClass('disabled')) {
            return false;
        }
        page = $(this).data('page');
        if (page) {
            event.preventDefault();
            filterPager(page);
        }
    });
    $('.grid-pager .pagination form.form-pager').on('submit', function(event) {
        event.preventDefault();
        page = $(this).find('input[name=page]').val();
        filterPager(page);
    });
    
    /* ---- endfilter-grid action */
    
    //menu mobile
    var domOpenChild = '<i class="fa fa-angle-left pull-right"></i>';
    menuMobileClone = $('#navbar-collapse .navbar-nav').clone();
    menuMobileClone.find('li:has(ul)',this).each(function() {
        $(this).children('a').append(domOpenChild);
        $(this).addClass('treeview');
        $(this).removeClass('dropdown');
        $(this).removeClass('dropdown-submenu');
        $(this).children('ul').removeClass('dropdown-menu');
        $(this).children('ul').addClass('treeview-menu');
    });
    $('.main-sidebar .sidebar .sidebar-menu').html(menuMobileClone.html());
    $.AdminLTE.layout.fix();
    
    $('.sidebar-toggle').on('click', function(event) {
        windowWidth = $(window).width();
        if (windowWidth > optionCustomR.size.adminlte_sm) {
            if ($("body").hasClass('sidebar-open')) {
                $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
            } else {
                $("body").addClass('sidebar-open').trigger('expanded.pushMenu');
            }
        }
    });
    $(document).mouseup(function (e){
        var container = $("aside.main-sidebar");
        var mMenuToggle = $('.sidebar-toggle');
        if (mMenuToggle.is(e.target)
            || mMenuToggle.has(e.target).length !== 0
        ){
            return false;
        }
        else if (!container.is(e.target)
            && container.has(e.target).length === 0
        ){
            $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $("body").removeClass('sidebar-open').removeClass('sidebar-collapse').trigger('collapsed.pushMenu');
        }
    });
    //------------------end menu mobile
});

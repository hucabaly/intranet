//collapse function
!function(e){function s(e,s){return void 0==e?s:e}e.fn.collapse=function(t){function l(e,s,l){s.siblings(o).slideUp(t.speed),t.parent?l.siblings().children(a).removeClass(c):e.siblings(a).removeClass(c),s.siblings(o).removeClass(r),t.changeText&&(e.siblings("."+h).html(t.more),t.parent&&l.siblings().children("."+h).html(t.more))}var n=e(this),i=n.selector,a=".collapse-title",o=".collapse-content",c="expand",r="open",h="change-text",d="collapse-close";t=s(t,{}),t.start=s(t.start,!0),t.click=s(t.click,!0),t.next=s(t.next,!0),t.speed=s(t.speed,500),t.only=s(t.only,!1),t.parent=s(t.parent,!1),t.clickOut=s(t.clickOut,!1),t.parent?t.sync=s(t.sync,!1):t.sync=!1,t.changeText=s(t.changeText,!1),t.changeText&&(t.more=s(t.more,"more"),t.less=s(t.less,"less")),t.close=s(t.close,!1),t.start&&e.each(n.find(a),function(){var s,l,n=e(this);t.parent&&(l=e(this).parent()),s=t.next?t.parent?l.next(o):n.next(o):t.parent?l.prev(o):n.prev(o),n.hasClass(c)?(s.show(),s.addClass(r)):(s.hide(),s.removeClass(r))}),t.click&&(t.clickOut&&e(document).on("click",function(s){domTitleOut=e(a),domContentOut=e(o),domTitleOut.is(s.target)||0!==domTitleOut.has(s.target).length||domContentOut.is(s.target)||0!==domContentOut.has(s.target).length||domTitleOut.each(function(s,l){domTitleOutThis=e(this),domTitleOutThis.hasClass("click-out")&&(domTitleOutThis.removeClass(c),domTitleOutThis.next(o).slideUp(t.speed),domTitleOutThis.next(o).removeClass(r))})}),e(document).on("click touchstart",i+" "+a,function(s){s.preventDefault();var n,i,a=e(this);t.parent&&(i=e(this).parent()),n=t.next?t.parent?i.next(o):a.next(o):t.parent?i.prev(o):a.prev(o),n.length&&(a.hasClass(c)?(t.only&&l(a,n,i),n.slideUp(t.speed),a.removeClass(c),t.sync&&a.siblings().removeClass(c),n.removeClass(r)):(t.only&&l(a,n,i),n.slideDown(t.speed),a.addClass(c),t.sync&&a.siblings().addClass(c),n.addClass(r)),t.changeText&&(a.hasClass(c)?a.hasClass(h)&&a.html(t.less):a.hasClass(h)&&a.html(t.more),a.siblings("."+h).hasClass(c)?a.siblings("."+h).html(t.less):a.siblings("."+h).html(t.more)))}),t.close&&e(document).on("click","."+d,function(s){s.preventDefault(),t.parent?domTitleClose=e(this).parent().prev().children(a):domTitleClose=e(this).parent().prev(a),domTitleClose.first().trigger("click")}))}}(jQuery);
//menu mobile
jQuery(document).ready(function($) {
    var domOpenChild = '<span class="collapse-title menu-open-child"><span>Open</span></span>';
    $("#mmenu-left").find('li:has(ul)',this).each(function() {
        $(this).children('ul').before(domOpenChild);
        $(this).children('ul').addClass('collapse-content');
        $(this).addClass('has-child');
        $(this).removeClass('dropdown');
        $(this).removeClass('dropdown-submenu');
        $(this).children('ul').removeClass('dropdown-menu');
    });
    $(document).on('click',".mmenu-toggle",function(e){
        e.preventDefault();
        $("#mmenu-left").toggleClass("show");
    });
    $(document).mouseup(function (e){
        var container = $("#mmenu-left");
        var mMenuToggle = $('.mmenu-toggle');
        if (mMenuToggle.is(e.target)
            || mMenuToggle.has(e.target).length !== 0
        ){
            return false;
        }
        else if (!container.is(e.target)
            && container.has(e.target).length === 0
        ){
            container.removeClass('show');
        }
    });
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $("#mmenu-left").removeClass('show');
        }
    });
});

jQuery(document).ready(function ($) {
    //menu
    $('.collapse-wrapper.collapse-mmenu').collapse();
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
    
    //modal delete confirm .find('[data-target="#modal-delete-confirm"][data-toggle="modal"]')
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
});

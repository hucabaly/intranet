jQuery(document).ready(function ($) {
    //menu
    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if ($(this).parent().hasClass('open')) {
            $(this).parent().removeClass('open');
            $(this).parent().find('li.dropdown-submenu').removeClass('open');
        } else {
            $(this).parent().addClass('open');
        }
    });
    
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
});

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
    
    //btn delete confirm
    $('.delete-confirm').on('click', function(event) {
        var dataNofitication = $(this).data('notification');
        if(!dataNofitication) {
            dataNofitication = 'Are you sure delete item';
        }
        if (!confirm(dataNofitication)) {
            event.preventDefault();
        }
    });
});

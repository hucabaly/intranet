jQuery(document).ready(function ($) {
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
});


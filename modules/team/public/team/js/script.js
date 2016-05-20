jQuery(document).ready(function ($) {
    if($('input#is-function').is(':checked')) {
            $('.team-group-function').show();
        } else {
            $('.team-group-function').hide();
        }
    $('input#is-function').on('change', function(event) {
        if($(this).is(':checked')) {
            $('.team-group-function').show();
        } else {
            $('.team-group-function').hide();
        }
    });
});


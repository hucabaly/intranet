jQuery(document).ready(function ($) {
    /**
     * update check/uncheck function unit
     * @param {type} dom
     * @returns {undefined}
     */
    function updateCheckFunction(dom)
    {
        var dataId = dom.data('id');
        if (dom.is(':checked')) {
            $('.team-group-function[data-id=' + dataId + ']').show();
        } else {
            $('.team-group-function[data-id=' + dataId + ']').hide();
        }
    }
    //update checkbox is-function
    $('input.input-is-function').each( function( i, v ) {
        updateCheckFunction($(this));
    });
    $('input.input-is-function').on('change', function (event) {
        updateCheckFunction($(this));
    });
});

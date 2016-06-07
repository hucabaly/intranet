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
    
    /**
     * add / remove team action
     */
    htmlAddTeamPositonOrigin = $('.group-team-position-orgin').html();
    $('.group-team-position-orgin').remove();
    dataIdLast = jQuery('.box-form-team-position').children('.group-team-position').length;
    if (!dataIdLast) {
        dataIdLast = 0;
    } else {
        dataIdLast = parseInt(dataIdLast);
    }
    $(document).on('click', '.input-team-position.input-add-new button', function(event) {
        dataIdLast++;
        htmlAddTeamPositon = $(htmlAddTeamPositonOrigin);
        htmlAddTeamPositon.find('.input-team-position.input-team select').attr('name', 'team[' + dataIdLast + '][team]');
        htmlAddTeamPositon.find('.input-team-position.input-position select').attr('name', 'team[' + dataIdLast + '][position]');
        $('.box-form-team-position').append(htmlAddTeamPositon);
    });
    $(document).on('click', '.input-team-position.input-remove button', function(event) {
        $(this).parents('.group-team-position').remove();
    });
    
    /**
     * update role label current
     */
    $('#employee-role-form').on('hide.bs.modal', function (e) {
        htmlRoleList = '';
        $(this).find('.checkbox input:checked').each(function (i,k) {
            htmlRoleList += '<li><span>';
            htmlRoleList += $(this).parent().text().trim();
            htmlRoleList += '</li></span>';
        });
        $('ul.employee-roles').html(htmlRoleList);
    });
});

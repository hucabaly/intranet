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
    
    /* filter-grid action */
    // get params from filter
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
        return params;
    }
    
    //filter request redirect with param
    function filterRequest()
    {
        var url = $('.btn-reset-filter').data('href'),
            params = getSerializeFilter();
        if (url && params) {
            window.location.href = url + '?' + params;
        }
    }
    
    //input key down - disable submit form - redirect action filter
    $(document).on('keydown','input.filter-grid',function(event) {
        if(event.which == 13) {
            filterRequest();
            return false;
        }
    });
    
    //reset filter
    $(document).on('click','.btn-reset-filter',function(event) {
        if ($(this).data('href')) {
            window.location.href = $(this).data('href');
        }
        return false;
    });
    
    //search filter button
    $(document).on('click','.btn-search-filter',function(event) {
        filterRequest();
        return false;
    });
});

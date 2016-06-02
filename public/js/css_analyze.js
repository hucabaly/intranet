$('input').iCheck({
    checkboxClass: 'icheckbox_minimal-blue',
    radioClass: 'iradio_minimal-blue'
});   

$("#dateRanger").dateRangeSlider({
    range: {min: new Date(2016, 0, 1)}, //use minimum range
    bounds: {
           min: new Date(2016, 0, 1),
           max: new Date(2017, 11, 31, 12, 59, 59)
            },
    defaultValues: {
           min: new Date(2016, 1, 10),
           max: new Date(2017, 4, 22)
            }

});

$(document).ready(function(){
    // Make "Item" checked if checkAll are checked
    $('#checkAll').on('ifChecked', function (event) {
        $('.checkItem').iCheck('check');
    });
    
    // Make "Item" unchecked if checkAll are unchecked
    $('#checkAll').on('ifUnchecked', function (event) {
        $('.checkItem').iCheck('uncheck');
    });
    
//    // Remove the checked state from "All" if any checkbox is unchecked
//    $('.checkItem').on('ifUnchecked', function (event) {
//        $('#checkAll').iCheck('uncheck');
//    });
//
//    // Make "All" checked if all checkboxes are checked
//    $('.checkItem').on('ifChecked', function (event) {
//        if ($('.checkItem').filter(':checked').length == $('.checkItem').length) {
//            $('#checkAll').iCheck('check');
//        }
//    });
    
    // Make "Item" checked if checkAll are checked
    $('#checkAllQuestion').on('ifChecked', function (event) {
        $('.checkItemQuestion').iCheck('check');
    });
    
    // Make "Item" unchecked if checkAll are unchecked
    $('#checkAllQuestion').on('ifUnchecked', function (event) {
        $('.checkItemQuestion').iCheck('uncheck');
    });
    
    $('.checkItemQuestion').on('ifChecked', function (event) {
        var parent_id = $(this).attr('data-id');
        $('.checkItemQuestion[parent-id='+parent_id+']').iCheck('check');
        
    });
    
    $('.checkItemQuestion').on('ifUnchecked', function (event) {
        var parent_id = $(this).attr('data-id');
        $('.checkItemQuestion[parent-id='+parent_id+']').iCheck('uncheck');
        
    });
});


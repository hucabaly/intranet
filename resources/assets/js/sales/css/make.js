$(document).ready(function(){
    fixPointContainer();
});

$(window).scroll(function(){
    fixPointContainer();
});

$(window).resize(function(){
    fixPointContainer();
});

/**
 * show or hide total point container at top right make css page
 */
function fixPointContainer(){
    var screen_width = $(window).width();
    var project_width = $('#make-header').width();
    var point_width = $(".total-point-container ").outerWidth();
    if($('.visible-check').visible()){
        $(".total-point-container ").css('position','inherit');
    } else {
        $(".total-point-container ").css('position','fixed');
        var fix_width = (screen_width - project_width)/2;
        if(fix_width <= point_width){
            $(".total-point-container ").css('right',fix_width);
        } else {
            var fix_width = fix_width - point_width;
            $(".total-point-container ").css('right',fix_width);
        }
    }
} 
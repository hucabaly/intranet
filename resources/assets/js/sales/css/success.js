//Fix footer bottom
    
setHeightByWidth();

if($('.success-body').height() < 300){
    $('.success-body .success-action').css('margin-top', '50px');
}else {
    $('.success-body .success-action').css('margin-top', '100px');
}
$(window).resize(function(){
    setHeightByWidth();

    if($('.success-body').height() < 300){
        $('.success-body .success-action').css('margin-top', '50px');
    }
});

function setHeightByWidth(){
    var widthScreen = $(window).width();
    if(widthScreen < 480){
       setHeightBody('.success-body', 90);
    } else if(widthScreen < 700) {
        setHeightBody('.success-body', 100);
    } else {
        setHeightBody('.success-body', 125);
    }
}
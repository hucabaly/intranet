//Fix footer bottom
setHeightByWidth();
$(window).resize(function(){
    setHeightByWidth();
});

function setHeightByWidth(){
    var widthScreen = $(window).width();
    if(widthScreen < 480){
       setHeightBody('.welcome-body', 95);
    } else if(widthScreen <= 768) {
        setHeightBody('.welcome-body', 120);
    }else if(widthScreen <= 1024) {
        setHeightBody('.welcome-body', 100);
    } else {
        setHeightBody('.welcome-body', 90);
    }
}
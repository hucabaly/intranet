//Fix footer bottom customer pages
function setHeightBody(elem, height){
    $(elem).css('min-height', $(window).height() - $('.welcome-footer').outerHeight() - height);
}

function goto_make(hrefMake) {
    var makeName = $('#make_name').val(); 
    if(makeName == ''){
        $('#modal-confirm-name').modal('show');
    }else{
        $.cookie("makeName", makeName, { expires: 7 }); 
        location.href = hrefMake;
    }
}

function hideModalConfirmMake(){
    $('#modal-confirm-make').hide();
}

function goToFinish(){
    location.href = "/css/cancel";
}

/**
 * Check point when press a comment
 */
function checkPoint(elem) {
    var value = $(elem).val();
    if(value === ''){
        var questionid = $(elem).attr('data-questionid');;
        var rateElem = $('.rateit[data-questionid='+questionid+']');
        var rateValue = rateElem.rateit('value');
        if(rateValue < 3 && rateValue > 0) {
            $(elem).css("border","1px solid red");
            $(elem).attr('placeholder','＃コメントがあればご記入ください。');
        }else if(rateValue >= 3){
            $(elem).css("border","1px solid #d2d6de");
            $(elem).removeAttr('placeholder');
        }
    }else {
        $(elem).css("border","1px solid #d2d6de");
        $(elem).removeAttr('placeholder');
    }
}

/**
 * Trang lam danh gia
 * Khi khach hang danh danh gia mot tieu chi
 * Tinh tong diem realtime
 * @returns void
 */
function totalMark(elem) { 
    var point = $(elem).rateit('value');  
    var dataQuestionid = $(elem).attr('data-questionid');
    var commentElem = $(".comment-question[data-questionid='"+dataQuestionid+"']");
    if(point < 3 && point > 0){
        var text = commentElem.val();
        if($.trim(text) === ''){
            commentElem.css("border","1px solid red");
            commentElem.attr('placeholder','＃コメントがあればご記入ください。');
        }
    }else if(point >= 3){
        commentElem.css("border","1px solid #d2d6de");
        commentElem.removeAttr('placeholder');
    }
    
    if(elem.id === 'tongquat'){
        $(elem).css("border","1px solid #d2d6de");
    }
    
    $(".total-point").html(getTotalPoint());
}

/**
 * Get total point
 * @returns {Number}
 */
function getTotalPoint(){
    var tongSoCauDanhGia = 0;
    var total = 0;
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        if($(this).attr("id") !== "tongquat"){
            if(danhGia > 0){
                tongSoCauDanhGia++;
                total += danhGia;
            }
        }
    });
    
    var diemTongQuat = parseInt($("#tongquat").rateit('value'));
    if(tongSoCauDanhGia === 0){
        total = diemTongQuat * 20;
    }else{
        total = diemTongQuat * 4 + total/(tongSoCauDanhGia * 5) * 80;
    }
    
    return total.toFixed(2);
}

/**
 * Function confirm CSS
 * @returns void
 */
function confirm(arrayValidate){
    var invalid = false;
    var strInvalid = "";
    $('#modal-confirm').modal('hide');
    $(".comment-question").css("border-color","#d2d6de");
    $("#tongquat").css("border","none");
    $("#comment-tongquat").css("border-color","#d2d6de");
    
    var arrayValidate = $.parseJSON(arrayValidate);
    
    var diemTongQuat = parseInt($("#tongquat").rateit('value'));
    if(diemTongQuat == 0){
        invalid = true;
        strInvalid += '<p>'+arrayValidate['totalMarkValidateRequired']+'</p>';
        $("#tongquat").css("border","1px solid red");
    }
    
    var arrValidate = [];
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
       
            if(danhGia > 0 && danhGia < 3){
                if($(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val() == ""){
                    arrValidate.push($(this).attr("data-questionid"));
                }
            }
        
    }); 
    
    if(arrValidate.length > 0) {
        for(var i=0; i<arrValidate.length; i++){
            $(".comment-question[data-questionid='"+arrValidate[i]+"']").css("border","1px solid red");
        }
        strInvalid += '<p>'+arrayValidate['questionCommentRequired']+'</p>';
        invalid = true;
    }
    
    if(invalid){
        $('#modal-alert .modal-body').html(strInvalid);
        $('#modal-alert').modal('show');
        return false;
    }
    
    $('#modal-confirm .modal-body').html("現在の点数は "+$(".total-point").html()+" 点です。アンケート結果を送信しますか。");
    $('#modal-confirm').modal('show');
}

/**
 * Validate then insert CSS result into database
 * @param string token
 * @param int cssId
 * @param json arrayValidate
 * @returns void
 */
function submit(token, cssId){ 
    var make_name = $(".make-name").text(); 
    var make_email = 'test';
    var totalMark = getTotalPoint();
    var proposed = $("#proposed").val();
    var arrayQuestion = [];
    $(".rateit").each(function(){
        var danhGia = parseInt($(this).rateit('value'));
        var questionId = $(this).attr("data-questionid");
        var diem = danhGia;
        var comment = $(".comment-question[data-questionid='"+$(this).attr("data-questionid")+"']").val();
        arrayQuestion.push([questionId,diem,comment]);
    });
    
    $(".apply-click-modal").show(); 
    $.ajax({
        url: baseUrl + '/css/saveResult',
        type: 'post',
        data: {
            _token: token, 
            arrayQuestion: arrayQuestion, 
            make_name: make_name, 
            make_email: make_email, 
            totalMark: totalMark,
            proposed: proposed,
            cssId: cssId
        },
    })
    .done(function (data) { 
        $('.comment-question').val('');
        $('.proposed').val('');
        location.href = baseUrl + "css/success";
    })
    .fail(function () {
        alert("Ajax failed to fetch data");
    })
}

$( "#frm_welcome" ).submit(function( event ) {
    var name = $.trim($('#make_name').val());
    if(name === ''){
        $('#modal-confirm-name').modal('show');
        event.preventDefault();
    }
     
});

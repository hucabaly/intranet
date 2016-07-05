function goto_make() {
    var makeName = $('#make_name').val(); 
    if(makeName == ''){
        $('#modal-confirm-name').modal('show');
    }else{
        $(".welcome-body").hide();
        $(".make-css").show();
    }
}

function hideModalConfirmMake(){
    $('#modal-confirm-make').hide();
}

function goToFinish(){
    location.href = "/css/cancel";
}
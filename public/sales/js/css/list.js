function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).attr('data-href')).select();
    document.execCommand("copy");
    $temp.remove();

    $("#modal-clipboard").modal('show');
}
//# sourceMappingURL=list.js.map

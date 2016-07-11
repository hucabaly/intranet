$(function () { $('#rateit_star').rateit({min: 1, max: 10, step: 2}); });

$("#link-make").click(function(){
    $("#link-make").selectText();
});

jQuery.fn.selectText = function(){
    var doc = document;
    var element = this[0];
    console.log(this, element);
    if (doc.body.createTextRange) {
        var range = document.body.createTextRange();
        range.moveToElementText(element);
        range.select();
    } else if (window.getSelection) {
        var selection = window.getSelection();        
        var range = document.createRange();
        range.selectNodeContents(element);
        selection.removeAllRanges();
        selection.addRange(range);
    }
 }
//# sourceMappingURL=preview.js.map

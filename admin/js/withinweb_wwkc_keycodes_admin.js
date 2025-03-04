jQuery(function ($) {
    'use strict';

    function SelectText(element) {
        var doc = document, text = doc.getElementById(element), range, selection;
        if (doc.body.createTextRange) {
            range = document.body.createTextRange();
            range.moveToElementText(text);
            range.select();
        } else if (window.getSelection) {
            selection = window.getSelection();
            range = document.createRange();
            range.selectNodeContents(text);
            selection.removeAllRanges();
            selection.addRange(range);
        }
    }

    $(function () {
        $('.prettyprint').click(function () {
            SelectText('prettyprint');
        });
    });
});
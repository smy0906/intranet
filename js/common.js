require(["jquery", "jquery.attrajax", "jquery-number", "chosen", "jquery.cookie", "jquery-form"], function ($) {
    $(function () {
        $('*[attrajax]').attrAjax();
        $('input[js_number_format]').number(true);
    });
    $('select[js_data_chosen]').chosen({search_contains: true});
    $('select[js_cookie_save],input[js_cookie_save]')
        .change(function () {
            var _this = $(this);
            var key = _this.tagName + '#' + _this.attr('name') + '#' + _this.attr('id');
            var val = _this.val();
            $.cookie(key, val);
        }).each(function () {
        var _this = $(this);
        var key = _this.tagName + '#' + _this.attr('name') + '#' + _this.attr('id');
        _this.val($.cookie(key)).trigger('change');
    });
});

function refresh() {
    document.location.reload();
}


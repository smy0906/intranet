$(function () {
    $('*[attrajax]').attrAjax();

    $('select[js_cookie_save],input[js_cookie_save]')
        .change(function () {
            var _this = $(this);
            var key = _this.tagName + '#' + _this.attr('name') + '#' + _this.attr('id');
            var val = _this.val();
            Cookies.set(key, val);
        }).each(function () {
            var _this = $(this);
            var key = _this.tagName + '#' + _this.attr('name') + '#' + _this.attr('id');
            _this.val(Cookies.get(key)).trigger('change');
        });
    $('select[js_data_chosen]').chosen({search_contains: true});
    $('input[js_number_format]').number(true);

});
function refresh() {
    document.location.reload();
}


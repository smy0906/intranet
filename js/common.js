require(["jquery", "jquery.attrajax", "jquery-number", "chosen", "jquery.cookie", "jquery-form"], function ($) {
  $(function () {
    $('*[attrajax]').attrAjax();
    $('input[js_number_format]').number(true);
  });
  $('select[js_data_chosen]').chosen({ search_contains: true });
  $('select[js_cookie_save],input[js_cookie_save]')
    .change(function () {
      var $this = $(this);
      var key = $this[0].tagName + '#' + $this.attr('name') + '#' + $this.attr('id');
      var val;
      if ($this.is('[type=checkbox]'))
        val = $this.is(":checked");
      else
        val = $this.val();
      $.cookie(key, val);
    })
    .each(function () {
      var $this = $(this);
      var key = $this[0].tagName + '#' + $this.attr('name') + '#' + $this.attr('id');

      var cookied_value = $.cookie(key);
      if (cookied_value !== undefined)
        if ($this.is('[type=checkbox]')) {
          if (cookied_value == 'true') {
            $this.attr("checked", cookied_value);
          }
        }
        else
          $this.val(cookied_value);
      $this.trigger('change').trigger('chosen:updated');
    });
});

function refresh() {
  document.location.reload();
}


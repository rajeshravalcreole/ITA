(function ($) {
    $(".table.posts #the-list, table.pages #the-list").sortable("destroy");
    if (custom_localize_object.lang_code == 'en') {
        $('table.posts #the-list, table.pages #the-list').sortable({
            'items': 'tr',
            'axis': 'y',
            'helper': fixHelper,
            'update': function (e, ui) {
                $.post(ajaxurl, {
                    action: 'update-custom-menu-order',
                    order: $('#the-list').sortable('serialize'),
                });
            }
        });

        var fixHelper = function (e, ui) {
            ui.children().children().each(function () {
                $(this).width($(this).width());
            });
            return ui;
        };
    }
})(jQuery)

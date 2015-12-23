+function ($) {
    'use strict';

    yii.confirm = function(message, ok, cancel) {

        if (!$.fn.popover) {
            if (confirm(message)) {
                !ok || ok();
            } else {
                !cancel || cancel();
            }
        } else {

            !cancel || cancel();

            var $this = $(this),
                method = $this.attr("data-method"),
                okButton, cancelButton;

            okButton = $("<a>", {class: "btn btn-sm green", href: $this.attr("href"), target: "_self"})
                .append("<i class='fa fa-check'></i> Да");

            if (method) {
                okButton.attr("data-method", method)
            }

            cancelButton = $("<a>", {class: "btn btn-sm red"})
                .append("<i class='fa fa-times'></i> Нет").click(function(e){e.preventDefault();});

            $this.popover({
                trigger: "focus",
                placement: "bottom",
                title: message,
                html: true,
                content: $("<div>", {class: "text-center"}).append(
                    $("<div class='btn-group'></div>").append(okButton).append(cancelButton))
            });

            $this.popover("show");
        }
    }

}(jQuery);


(function () {
    "use strict";

    // 	Allow Subscribe for news
    jQuery(document).ready(function () {
        var modalContainer = document.getElementById('subscribe-notification-container');
        if ('undefined' === typeof modalContainer || null === modalContainer) {
            return;
        }
        var statsInput = document.getElementById('modal-ffc-statistic');
        var subscribeInput = document.getElementById('modal-ffc-subscribe');


        modalContainer.addEventListener("click", function (e) {

            // Subscribe to newsletter event
            if (e.target.getAttribute("id") === "frtfl-modal__submit-btn") {
                e.preventDefault();

                var __submitBtn = e.target;
                var __notificationText = __submitBtn.parentElement;

                var data = {
                    action: "anaglyph_submit_modal",
                    type: "json",
                    data: {
                        'ffc_statistic' : statsInput.checked,
                        'ffc_subscribe' : subscribeInput.checked
                    }
                };

                jQuery.post(ajaxurl, data, function (response) {
                    console.log(response);

                    if (response.status === "success") {
                        __notificationText.innerHTML = response.message;
                    } else {
                        __notificationText.innerHTML = response.message;
                    }
                });
            }

            // Dismiss subscribe notification Event
            if (e.target.classList.contains("notice-dismiss")) {
                var data = {
                    action: "anaglyph_dismiss_subscribe_notification",
                    type: "json",
                };

                jQuery.post(ajaxurl, data, function (response) {
                    modalContainer.remove();
                });

            }

        });
    });
}());



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
        var userInfoContainer = document.getElementById("frtfl-modal__content_user-info");

        var modalForm = document.getElementById("frtfl-modal-form");
        var submitBtn = document.getElementById("frtfl-modal__submit-btn");
        var modalData = {};

        modalContainer.addEventListener("click", function (e) {

            //Subscribe checkbox event. If checked show additional inputs
            if (e.target === subscribeInput) {
                userInfoContainer.classList.toggle("hidden");
                if (userInfoContainer.classList.contains("hidden")) {
                    userInfoContainer.querySelector('input[type="email"]').setAttribute('disabled', 'true');
                    userInfoContainer.querySelector('input[type="text"]').setAttribute('disabled', 'true');
                } else {
                    userInfoContainer.querySelector('input[type="email"]').removeAttribute('disabled');
                    userInfoContainer.querySelector('input[type="text"]').removeAttribute('disabled');
                }

            }

            // Subscribe to newsletter click event - create modalData
            if (e.target === submitBtn) {
                modalData['ffc_statistic'] = +statsInput.checked;
                modalData['ffc_subscribe'] = +subscribeInput.checked;
                if (userInfoContainer.classList.contains("hidden")) {
                    modalData['ffc_subscribe_name'] = '';
                    modalData['ffc_subscribe_email'] = '';
                } else {
                    modalData['ffc_subscribe_name'] = userInfoContainer.querySelector('input[type="text"]').value;
                    modalData['ffc_subscribe_email'] = userInfoContainer.querySelector('input[type="email"]').value;
                }
            }

            // Dismiss subscribe notification Event
            if (e.target.classList.contains("notice-dismiss")) {
                var data = {
                    action: "anaglyph_dismiss_subscribe_notification",
                    type: "json",
                };

                jQuery.post(ajaxurl, data, function (response) {
                    modalContainer.remove();
                    location.reload();
                });
            }

        });

        modalForm.addEventListener('submit', function (e) {

            e.preventDefault();

            var __notificationText = modalForm.querySelector('.frtfl-modal__content');

            console.log(modalData);
            var data = {
                action: "anaglyph_submit_modal",
                type: "json",
                data: modalData
            };

            jQuery.post(ajaxurl, data, function (response) {

                var __title = "<h2>" + response.title + "</h2>";
                var __msg = "<p>" + response.message + "</p>";
                var __desc = "<p>" + response.description + "</p>";

                __notificationText.innerHTML = __title + __msg + __desc;

            });

        });

    });
}());



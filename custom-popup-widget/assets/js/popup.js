(function($) {
    'use strict';

    class PopupWidget {
        constructor() {
            this.popup = $('#cpw-popup');
            this.overlay = $('.cpw-popup-overlay');
            this.closeBtn = $('.cpw-close');
            this.settings = window.cpwSettings || {};
            this.init();
        }

        init() {
            this.bindEvents();
            this.checkFrequency();
        }

        bindEvents() {
            this.closeBtn.on('click', () => this.closePopup());
            this.overlay.on('click', () => this.closePopup());
            $(document).on('keyup', (e) => {
                if (e.key === 'Escape') {
                    this.closePopup();
                }
            });
        }

        checkFrequency() {
            const lastShown = localStorage.getItem('cpw_last_shown');
            const frequency = this.settings.popup_frequency;
            let shouldShow = false;

            switch (frequency) {
                case 'once_per_session':
                    shouldShow = !sessionStorage.getItem('cpw_shown');
                    break;
                case 'once_per_day':
                    shouldShow = !lastShown || (Date.now() - parseInt(lastShown)) > 86400000; // 24 hours
                    break;
                case 'once_per_two_weeks':
                    shouldShow = !lastShown || (Date.now() - parseInt(lastShown)) > 1209600000; // 14 days
                    break;
                case 'once_per_month':
                    shouldShow = !lastShown || (Date.now() - parseInt(lastShown)) > 2592000000; // 30 days
                    break;
                case 'every_time':
                    shouldShow = true;
                    break;
            }

            if (shouldShow) {
                this.showPopup();
            }
        }

        showPopup() {
            setTimeout(() => {
                this.setPosition();
                this.popup.fadeIn();
                this.updateStorage();
            }, this.settings.popup_delay * 1000);
        }

        closePopup() {
            this.popup.fadeOut();
        }

        setPosition() {
            const position = this.settings.popup_position;
            const content = this.popup.find('.cpw-popup-content');
            
            content.css({
                position: 'fixed',
                transform: 'none'
            });

            switch (position) {
                case 'center':
                    content.css({
                        top: '50%',
                        left: '50%',
                        transform: 'translate(-50%, -50%)'
                    });
                    break;
                case 'top-left':
                    content.css({
                        top: '20px',
                        left: '20px'
                    });
                    break;
                case 'top-right':
                    content.css({
                        top: '20px',
                        right: '20px'
                    });
                    break;
                case 'bottom-left':
                    content.css({
                        bottom: '20px',
                        left: '20px'
                    });
                    break;
                case 'bottom-right':
                    content.css({
                        bottom: '20px',
                        right: '20px'
                    });
                    break;
            }
        }

        updateStorage() {
            sessionStorage.setItem('cpw_shown', 'true');
            localStorage.setItem('cpw_last_shown', Date.now().toString());
        }
    }

    $(document).ready(() => {
        new PopupWidget();
    });

})(jQuery); 
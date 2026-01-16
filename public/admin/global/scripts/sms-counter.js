/**
 * SMS Counter - Calculate SMS count based on character length
 * 160 characters = 1 SMS
 * 161-320 characters = 2 SMS
 * and so on...
 */
var SmsCounter = {
    /**
     * Count SMS messages and remaining characters
     * @param {string} text - The text to count
     * @param {boolean} gsm - Whether to use GSM encoding (default: true)
     * @returns {object} Object containing messages count and remaining characters
     */
    count: function(text, gsm = true) {
        if (!text) {
            return {
                messages: 1,
                remaining: 160,
                characters: 0
            };
        }

        // Remove any HTML tags if present
        text = text.replace(/<[^>]*>/g, '');

        // Count actual characters
        var charCount = text.length;

        // Calculate SMS count (160 characters per SMS)
        var messages = Math.ceil(charCount / 160);

        // Calculate remaining characters in the current SMS
        var remaining = 160 - (charCount % 160);
        if (remaining === 160) {
            remaining = 0;
        }

        return {
            messages: messages,
            remaining: remaining,
            characters: charCount
        };
    },

    /**
     * Get detailed SMS information
     * @param {string} text - The text to analyze
     * @returns {object} Detailed SMS information
     */
    analyze: function(text) {
        if (!text) {
            return {
                messages: 1,
                remaining: 160,
                characters: 0,
                cost: 1,
                encoding: 'GSM'
            };
        }

        var count = this.count(text);

        return {
            messages: count.messages,
            remaining: count.remaining,
            characters: count.characters,
            cost: count.messages,
            encoding: 'GSM'
        };
    }
};

// jQuery plugin for easy integration
(function($) {
    $.fn.smsCounter = function(options) {
        var settings = $.extend({
            remainingElement: null,
            messagesElement: null,
            maxLength: 160,
            onUpdate: null
        }, options);

        return this.each(function() {
            var $textarea = $(this);
            var $remaining = settings.remainingElement ? $(settings.remainingElement) : null;
            var $messages = settings.messagesElement ? $(settings.messagesElement) : null;

            function updateCounter() {
                var text = $textarea.val();
                var count = SmsCounter.count(text);

                if ($remaining) {
                    $remaining.text(count.remaining + ' characters remaining');
                }

                if ($messages) {
                    $messages.text(count.messages + ' Message(s)');
                }

                if (settings.onUpdate && typeof settings.onUpdate === 'function') {
                    settings.onUpdate(count);
                }
            }

            // Bind events
            $textarea.on('input keyup paste', updateCounter);

            // Initial count
            updateCounter();
        });
    };
})(jQuery);

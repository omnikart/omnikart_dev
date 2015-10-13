(function($){

	// Number of seconds in every time division
	var days	= 24*60*60,
		hours	= 60*60,
		minutes	= 60;

	// Creating the plugin
	$.fn.countdown = function(prop){

		var options = $.extend({
			callback	: function(){},
			timestamp	: 0
		},prop);

		var left, d, h, m, s;

		(function tick(){

			// Time left
			left = Math.floor((options.timestamp - (new Date())) / 1000);

			if(left < 0){
				left = 0;
			}

			// Number of days left
			d = Math.floor(left / days);
			left -= d*days;

			// Number of hours left
			h = Math.floor(left / hours);
			left -= h*hours;

			// Number of minutes left
			m = Math.floor(left / minutes);
			left -= m*minutes;

			// Number of seconds left
			s = left;

			// Calling an optional user supplied callback
			if (options.callback(d, h, m, s)) {
				return;
			}

			// Scheduling another call of this function in 1s
			setTimeout(tick, 1000);
		})();

		return this;
	};
})(jQuery);
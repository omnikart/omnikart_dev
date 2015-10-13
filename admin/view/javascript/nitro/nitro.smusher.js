nitro.register('smusher', (function($) {
    var token = '';
    var method = 'local';
    var flagPause = false;
    var logArea = null;
    var refreshXHR = null;
    var isLoadingImagesList = false;
    var isSmushWaiting = false;
    var isSmushStartEventFired = false;
    var smushFinishedCallbacks = [];
    var smushPausedCallbacks = [];
    var smushResumedCallbacks = [];
    var smushStartedCallbacks = [];
    var smushUpdateCallbacks = [];
    var smushErrorCallbacks = [];

    var getImageList = function() {
        isLoadingImagesList = true;
        var smushTargetPath = '';
        if ($('#smushTargetPath').val().length > 0) {
            smushTargetPath = '&targetDir=' + $('#smushTargetPath').val().replace(/\//g, '%2F');
        }
        $.ajax({
            url: 'index.php?route=tool/nitro/smush_init' + smushTargetPath + '&token=' + token,
            dataType: 'json',
            cache: false,
            success: function(data) {
                isLoadingImagesList = false;
                switch (data.status) {
                    case 'success':
                        if (isSmushWaiting) {
                            smush('start');
                        }
                        break;
                    case 'fail':
                        fireCallbacks(smushErrorCallbacks, data);
                        break;
                }
            }
        });
    };

    var fireCallbacks = function(callbacksList, data) {
        data = data || null;
        if (callbacksList.length) {
            var callback = null;
            for (x = 0; x < callbacksList.length; x++) {
                callback = callbacksList[x];
                callback(data);
            }
        }
    };

    var refresh_progress = function() {
        if (refreshXHR && refreshXHR.readyState != 4) return;

        refreshXHR = $.ajax({
            url: 'index.php?route=tool/nitro/smush_get_progress&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                fireCallbacks(smushUpdateCallbacks, data);

                if (data.processed_images_count == data.total_images) {
                    fireCallbacks(smushFinishedCallbacks, data);
                    flagPause = true;
                } else if (!flagPause) {
                    setTimeout(refresh_progress, 1000);
                }
            },
            complete: function() {
                refreshXHR = null;
            }
        });
    };

    var smush = function(action) {
        if (flagPause) return;
        action = action || 'start';
        if (!isLoadingImagesList) {
            isSmushWaiting = false;
            if (!isSmushStartEventFired) {
                isSmushStartEventFired = true;
                fireCallbacks(smushStartedCallbacks);
            }

            $.ajax({
                url: 'index.php?route=tool/nitro/smush_' + action + '&token=' + token,
                type: 'GET',
                dataType: 'json',
                data: {
                    method: method
                },
                cache: false,
                complete: function() {
                    smush('resume');
                }
            });
            refresh_progress();
        } else {
            isSmushWaiting = true;
        }
    };

    var stopSmushing = function() {
        flagPause = true;
        $.ajax({
            url: 'index.php?route=tool/nitro/smush_pause&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                fireCallbacks(smushPausedCallbacks);
            }
        });
    };

    var restoreState = function() {
        $.ajax({
            url: 'index.php?route=tool/nitro/smush_get_progress&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                fireCallbacks(smushUpdateCallbacks, data);
            }
        });
    };

    return {
        init: function(logElement) {
            logArea = logElement;
            restoreState();
        },
        reset: function() {
            isSmushStartEventFired = false;
        },
        begin: function() {
            this.restart();
        },
        restart: function() {
            flagPause = false;
            isSmushStartEventFired = false;
            getImageList();
            smush('start');
        },
        resume: function() {
            flagPause = false;
            isSmushStartEventFired = false;
            smush('resume');
        },
        pause: function() {
            stopSmushing();
        },
        setToken: function(t) {
            token = t;
        },
        setMethod: function(m) {
            method = m;
        },
        addSmushFinishEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushFinishedCallbacks.push(callback);
            }
        },
        addSmushPauseEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushPausedCallbacks.push(callback);
            }
        },
        addSmushResumeEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushResumedCallbacks.push(callback);
            }
        },
        addSmushStartEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushStartedCallbacks.push(callback);
            }
        },
        addSmushStartedEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushStartedCallbacks.push(callback);
            }
        },
        addSmushUpdateEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushUpdateCallbacks.push(callback);
            }
        },
        addErrorEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushErrorCallbacks.push(callback);
            }
        }
    };
})(jQuery));
nitro.register('cdn', (function($){
  var config;
  var syncing;
  var cdn_xhr;

  var progress = function(type, message, percent) {
    if (0) {
      console.log('Type: ' + type);
      console.log('Message: ' + message);
      console.log('Percent: ' + percent);
    }

    if (type == 'success') {
      message = config.success_wrapper.replace('{MESSAGE}', message);
    }

    if (type == 'error') {
      message = config.error_wrapper.replace('{MESSAGE}', message);
    }

    $(config.message_selector).html(message);

    $(config.progressbar_selector).css('width', percent + '%');
  };

  var init = function(callback) {
    syncing = false;
    progress('', 'Preparing resources. Please wait...&nbsp;<i class="icon-spinner icon-spin"></i>', 0);
    if (cdn_xhr) {
      cdn_xhr.abort();
    }
    callback();
  };

  var sync = function(last_response) {
    if (typeof last_response == 'undefined') last_response = [];

    cdn_xhr = $.ajax({
      url : config.url,
      type : 'POST',
      data : {
        cdn : config.cdn,
        config : config.form_fields,
        last : last_response
      },
      dataType : 'json',
      beforeSend : function() {
        syncing = true;
      },
      success : function(data) {
        progress(data.response_type, data.message, data.percent);

        if (data.response_type != 'error') {
          if (data.done === false) {
            sync(data);
          } else {
            init(function() {
              progress('success', 'Task completed! Please clear your PageCache.', 100);
            });
          }
        } else {
          init(function() {
            progress(data.response_type, data.message, data.percent);
          });
        }
      },
      error : function(jqXHR) {
        init(function() {
          progress('error', 'Unexpected error. HTTP Response body:<br /><br />' + jqXHR.responseText, 0);
        });
      }
    });
  };

  var start = function() {
    init(function() {
      sync();
    });
  };
  
  var abort = function() {
    if (!syncing) return;

    init(function() {
      progress('error', 'Task aborted.', 0);
    });
  };
  
  return {
    setConfig: function(c) {
      config = c;
    },
    start: function() {
      start();
    },
    abort: function() {
      abort();
    }
  };
})(jQuery));
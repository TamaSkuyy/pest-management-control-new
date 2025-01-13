import jQuery from 'jquery/dist/jquery';

const $ = jQuery;
try {
  window.jQuery = window.$ = jQuery;

  $.ajaxSetup({
    statusCode: {
      401: function () {
        window.location.href = '/login';
      }
    }
  });
} catch (e) {}

export { jQuery, $ };

/*!
 * $web_package_name jQuery JavaScript Plugin v0.0.1
 * $web_package_url
 *
 * $web_package_description
 *
 * Copyright 2013, Aaron Klump
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: Sat, 23 Nov 2013 08:35:10 -0800
 */
;(function($, undefined) {
"use strict";

$.fn.verifyAge = function(options) {

  var $popup      = $(this);
  var $background = $(this).siblings('.verify-age.background');

  // Do nothing when nothing selected
  if ($popup.length === 0) {
    return;
  }

  function handleClick ($element) {
    var href = $element.attr('href');
    
    // remove r= so we get a json response
    href = href.replace(/&r=[^&]+/, '');
    
    // make the ajax call and process return
    $.getJSON(href, function (data) {
      if (data.replaceWith) {
        $background.remove();
        $popup.replaceWith(data.replaceWith);
      }
      if (data.redirect) {
        //window.location = data.redirect;
      }
    });
  }

  $popup.not('.verify-age-processed')
  .addClass('verify-age-processed')
  .find('a.verify-age')
  .click(function () {
    handleClick($(this));

    return false;
  });

  return this;
};

$.fn.verifyAge.version = function() { return '0.0.1'; };

})(jQuery);

(function($) {
  $('document').ready(function(){
    $('.verify-age.popup').verifyAge();
  });
})(jQuery);
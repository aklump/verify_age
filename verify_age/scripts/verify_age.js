/*!
 * Verify Age jQuery JavaScript Plugin v0.0.6
 * http://www.intheloftstudios.com/packages/php/verify_age
 *
 * A PHP/Javascript solution for SEO/Google-friendly website age-verification.
 *
 * Copyright 2013, Aaron Klump
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: Thu, 09 Jan 2014 17:51:49 -0800
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
        window.location = data.redirect;
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

$.fn.verifyAge.version = function() { return '0.0.6'; };

})(jQuery);

(function($) {
  $('document').ready(function(){
    $('.verify-age.popup').verifyAge();
  });
})(jQuery);
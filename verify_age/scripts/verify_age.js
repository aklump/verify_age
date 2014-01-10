/*!
 * Verify Age jQuery JavaScript Plugin v0.1.2
 * http://www.intheloftstudios.com/packages/php/verify_age
 *
 * A PHP/Javascript solution for SEO/Google-friendly website age-verification.
 *
 * Copyright 2013, Aaron Klump
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: Fri, 10 Jan 2014 10:58:08 -0800
 */
;(function($, undefined) {
"use strict";

$.fn.verifyAge = function() {

  var $background = $(this);

  // Do nothing when nothing selected
  if ($background.length === 0) {
    return;
  }

  function handleClick ($element) {
    var href = $element.attr('href');
    
    // remove r= so we get a json response
    href = href.replace(/&r=[^&]+/, '');
    
    // make the ajax call and process return
    $.getJSON(href, function (data) {
      if (data.replaceWith) {
        $background.replaceWith(data.replaceWith);
      }
      if (data.redirect) {
        window.location = data.redirect;
      }
    });
  }

  $background.not('.verify-age-processed')
  .addClass('verify-age-processed')
  .find('a.verify-age-enter, a.verify-age-exit')
  .click(function () {
    handleClick($(this));

    return false;
  });

  return this;
};

$.fn.verifyAge.version = function() { return '0.1.2'; };

})(jQuery);

(function($) {
  $('document').ready(function(){
    $('.verify-age').verifyAge();
  });
})(jQuery);
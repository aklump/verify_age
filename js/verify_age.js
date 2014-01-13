/*!
 * Verify Age jQuery JavaScript Plugin v
 * http://www.intheloftstudios.com/packages/php/verify_age
 *
 * A PHP/Javascript solution for SEO/Google-friendly website age-verification.
 *
 * Copyright 2013, Aaron Klump
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Date: Mon, 13 Jan 2014 12:13:42 -0800
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
    
    // add json=1 so we get a json response instead of a redirect or 403
    href = href.replace(/\?/, '?ajax=1&');

    var $links = $element.add($element.siblings('a'));
    $links.hide();
    $background.find('.verify-age-ajaxing').show();
    
    // make the ajax call and process return
    $.getJSON(href, function (data) {
      if (data.replaceWith) {
        $background.replaceWith(data.replaceWith);
        $('.verify-age').verifyAge();
      }
      if (data.redirect) {
        window.location = data.redirect;
      }
      $links.show();
    });
  }

  $(document).ajaxStop(function () {
    $('.verify-age-ajaxing').hide();
  });

  $background.not('.verify-age-processed')
  .addClass('verify-age-processed')
  .find('a.verify-age-enter, a.verify-age-exit')
  .click(function () {
    handleClick($(this));

    return false;
  });

  return this;
};

$.fn.verifyAge.version = function() { return ''; };

})(jQuery);

(function($) {
  $('document').ready(function(){
    $('.verify-age').verifyAge();
  });
})(jQuery);
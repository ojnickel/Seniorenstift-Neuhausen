function set_cookie(name, days) {
  var date, expires;
  date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  expires = " expires=" + date.toGMTString();
  document.cookie = name + "=true; path=/;" + expires;
}

// Cookie-Hinweis.
function cookiebar_open() {
  if (document.cookie.indexOf('cookiebar_closed=true') >= 0) {
    return false;
  }
  return true;
}

function set_video_optin_cookie() {
  set_cookie('video_optin', 30);
  $('.video-optin').prop( "checked", true );
  $(".v2oi").show();
}

function unset_video_optin_cookie() {
  document.cookie = "video_optin=; path=/; expires=Thu, 01 Jan 1970 00:00:00 UTC;";
  $('.video-optin').prop( "checked", false );
  $(".v2oi").hide();
}

$('.no-js').removeClass('no-js').addClass('js');

svg4everybody();

var template_path = $('html').data('path');

if (cookiebar_open()) {
  $('.cookies').show();
}

$('#cookie_info').click(function() {
  $('.cookies').hide();
  set_cookie('cookiebar_closed', 365);
});

$('#cookie_close').click(function(e) {
  e.preventDefault();
  $('.cookies').hide();
  set_cookie('cookiebar_closed', 365);
});

$(document).ready(function() {
  $(".video-optin").change(function() {
  if(this.checked) {
      set_video_optin_cookie();
  } else {
      unset_video_optin_cookie();
  }
});
});

///Navigation
// Menü ein-/ausblenden
$('.menu-button').click(function(e) {

  $(this).toggleClass('active');

  $('#' + $(this).data('for')).slideToggle();

  if ($(this).attr('aria-expanded') == 'true') {
    $(this).attr("aria-expanded", "false");
  } else {
    $(this).attr("aria-expanded", "true");
  }

});

$(document).click(function(e) {
  var menu = $('.sub.open');
  if ($(window).width() >= 768 && !menu.is(e.target) && menu.has(e.target).length === 0) {
    $('.sub.open').removeClass('open').children('ul').slideUp('fast');
  }
});

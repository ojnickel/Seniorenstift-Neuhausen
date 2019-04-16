///Navigation
// Menü ein-/ausblenden
$('.toggle').each(function() {

  var toggle_for = $('#' + $(this).data('for'));

  toggle_for.hide();

  $(this).click(function(e) {
    $(this).toggleClass('active');
    toggle_for.slideToggle();
  });
});

$(function() {
  $('#username').focus(function() {
    $('#user-icon').css('background','#a2d5e5');
  });
  $('#username').blur(function() {
    $('#user-icon').css('background','#69C6E0');
  });

  $('#password').focus(function() {
    $('#pass-icon').css('background','#a2d5e5');
  });
  $('#password').blur(function() {
    $('#pass-icon').css('background','#69C6E0');
  });
});

$(document).ready(function(){
  $('.circle1').click(function () {
    $(".circle1").addClass('circle-active');
    $(".circle2").removeClass('circle-active');
    $(".circle3").removeClass('circle-active');
    $(".text1").removeClass('hide-text');
    $(".text2").addClass('hide-text');
    $(".text3").addClass('hide-text');
  });
  $('.circle2').click(function () {
    $(".circle1").addClass('circle-active');
    $(".circle2").addClass('circle-active');
    $(".circle3").removeClass('circle-active');
    $(".text1").addClass('hide-text');
    $(".text2").removeClass('hide-text');
    $(".text3").addClass('hide-text');
  });
  $('.circle3').click(function () {
    $(".circle1").addClass('circle-active');
    $(".circle2").addClass('circle-active');
    $(".circle3").addClass('circle-active');
    $(".text1").addClass('hide-text');
    $(".text2").addClass('hide-text');
    $(".text3").removeClass('hide-text');
  });
});
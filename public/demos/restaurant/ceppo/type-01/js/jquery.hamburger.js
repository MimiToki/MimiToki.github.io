$(function(){
  $('.hamburger').on('click', function(){
    $(this).toggleClass('is-active');
    $('.hamburger').toggleClass('active');
    $('.navimavi').toggleClass('active');
  });
});
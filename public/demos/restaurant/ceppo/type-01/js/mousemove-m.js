
var $box = $('.wBg'),
  inter = 15,
  speed = 0;

$(window).on('mousemove', moveBox);

function moveBox(e) {
  TweenMax.killTweensOf();
  var xbg = (e.pageX * -1/10 ), ybg = (e.pageY * -1/10 );
  $box.each(function(index, val) {   
	  TweenMax.to($(this),0.8,{x:xbg+'px',y:ybg+'px',delay:0+(index/300)});
  });
}

$box.each(function(index, val) {
    index = index + 1;
    TweenMax.set(
      $(this),{
        autoAlpha: 0.6 - (0.02 * index),
        delay:0
      });
  });
  TweenMax.set($('.Bg:nth-child(1)'),{autoAlpha: 1});
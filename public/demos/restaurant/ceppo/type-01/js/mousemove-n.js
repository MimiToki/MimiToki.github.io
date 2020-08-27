
var $box = $('.wBg'),
  inter = 15,
  speed = 0;

$(window).on('mousemove', moveBox);

function moveBox(e) {
  TweenMax.killTweensOf();
  var xbg = (e.pageX * -0.2/10 ), ybg = (e.pageY * -0.2/10 );
  $box.each(function(index, val) {   
	  TweenMax.to($(this),0.8,{x:xbg+'px',y:ybg+'px',delay:0+(index/300)});
  });
}
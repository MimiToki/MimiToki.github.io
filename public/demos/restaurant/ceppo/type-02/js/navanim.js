$(document).ready(function () {
	$(window).scroll(function () {
		($(window).scrollTop() >= 1) ? (
			$('.door').addClass('scrolled'),
			$('.et-hero-tab').addClass('scrolled')
		) : (
			$('.door').removeClass('scrolled'),
            $('.et-hero-tab').removeClass('scrolled')
		);
	});
});

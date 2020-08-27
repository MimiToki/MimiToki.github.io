
$(function() {
	$("#datepicker").datepicker().holiday();
});

(function(){
	$("#datepicker").datepicker({
		dateFormat: 'M dd, yy',
		showOtherMonths: true,
		showButtonPanel: true,
		minDate: 0
	});
	
	$("#datepicker").datepicker().datepicker('setDate','today');
	
})();


	function input_check(){
	var result = true;

	$('#name').removeClass("inp_error");
	$('#email').removeClass("inp_error");
	$('#message').removeClass("inp_error");

	$("#name_error").empty();
	$("#email_error").empty();
	$("#message_error").empty();

	var name   = $("#name").val();
	var email  = $("#email").val();
	var message  = $("#message").val();

	// Name
	if(name == ""){
		$("#name").addClass("inp_error");
		result = false;
	}
	// Email
	if(email == ""){
		$("#email").addClass("inp_error");
		result = false;
	}else if(!mailaddress.match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/)){
		$("#email").addClass("inp_error");
		result = false;
	}else if(mailaddress.length > 255){
		$("#email").addClass("inp_error");
		result = false;
	}
	// message
	if(message == ""){
		$("#message").addClass("inp_error");
		result = false;
	}

	return result;
}
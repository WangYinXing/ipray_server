


function validate(selector, type, errorMsg) {
	var val = $(selector).val();

	if (val == null || val == "") {
		$(selector).addClass("input-error");
		alert(errorMsg);
		return false;
	}

	if (type == "email" && !validateEmail(val)) {
		$(selector).addClass("input-error");
		alert(errorMsg);
		return false;
	}
	
	$(selector).removeClass("input-error");
	return true;
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
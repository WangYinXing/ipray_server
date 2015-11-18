/*########################################################################################################################################################
	QB initialize function.........
########################################################################################################################################################*/

function initQB() {
	if (typeof(QB) == "undefined") {
		alert("Quickblox is not supported. QB won't be initialized.");
		return;
	}


	QB.init(qbConfig.appID, qbConfig.authKey, qbConfig.authSecret);
}

function createUser(login, password) {
    var params = { 'login': login, 'password': password};

    QB.users.create(params, function(err, user){
      if (user)
        return {user:user, success:true};
      else
        return {err:err, success:false};
    });
}
jQuery(document).ready(function () {
	var url = OF_PATH + 'web/adr/character';
	var user_url = OF_PATH + 'web/adr/user';

	// TODO: write helper functions ex. OF.Call(method, params, callback);
	function getvar(name) {
		return window[name];
	}
	function storevar(name, value) {
		window[name] = value;
	}
	// End helper functions

//START:: User creation
	asyncTest( "Registering user", function() {
		storevar('id', Math.floor(Math.random()* +new Date));

		var registrationInfo = {
			'username': 'of_tester' + getvar('id'),
			'password': 'of_tester' + getvar('id'),
			'email': 'of_tester@' + getvar('id') + 'openfantasy.org'
		};

		var xhr = jQuery.post(user_url + '/register', registrationInfo, function(data) {
			ok(data.status == 1, JSON.stringify(data));
			storevar('userID', data.userID);			
			start();
		});
	});

	asyncTest( "Logging in the user", function() {
		var loginInfo = {
			'email': 'of_tester@' + getvar('id') + 'openfantasy.org',
			'password': 'of_tester' + getvar('id')
		};

		var xhr = jQuery.post(user_url + '/login', loginInfo, function(data) {
			ok(data.status, JSON.stringify(data));
			start();
		});
	});

	asyncTest( "Getting User Info", function() {		
		var xhr = jQuery.get(user_url + '/get', {}, function(data) {
			ok(true, JSON.stringify(data));
			start();
		});

	});

	asyncTest( "Getting User Info by ID", function() {		
		var xhr = jQuery.get(user_url + '/get', {'userID': getvar(userID)}, function(data) {
			ok(true, JSON.stringify(data));
			start();
		});
	});
//END:: User creation




	asyncTest( "Create", function() {
		var info = {
			'race_id' : 1,
			'element_id' : 1,
			'alignment_id' : 1,
			'element_id' : 1,
			'alignment_id' : 1,
			'class_id' : 1,
			'character_might' : 1,
			'character_dexterity' : 1,
			'character_constitution' : 1,
			'character_intelligence' : 1,
			'character_wisdom' : 1,
			'character_charisma' : 1,
			'magic_attack' : 1,
			'magic_resistance' : 1,
			'character_name' : 'tester',
			'character_desc' : 'tester is bester'
		};

		var xhr = jQuery.post(url + '/create', info, function(data) {
			ok(data.status == 1, JSON.stringify(data));
			start();
		});
	});



	asyncTest( "Get Character", function() {
		var xhr = jQuery.get(url + '/', {}, function(data) {
			ok(data.status !== 0, JSON.stringify(data));
			start();
		});
	});

});
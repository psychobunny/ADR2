jQuery(document).ready(function () {
	var character_url = OF_PATH + 'web/adr/character';
	var user_url = OF_PATH + 'web/adr/user';
	var temple_url = OF_PATH + 'web/adr/temple'
	var battle_url = OF_PATH + 'web/adr/battle'

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
			'character_might' : 15,
			'character_dexterity' : 15,
			'character_constitution' : 15,
			'character_intelligence' : 15,
			'character_wisdom' : 15,
			'character_charisma' : 15,
			'magic_attack' : 15,
			'magic_resistance' : 15,
			'character_name' : 'tester',
			'character_desc' : 'tester is bester'
		};

		var xhr = jQuery.post(character_url + '/create', info, function(data) {
			ok(data.status == 1, JSON.stringify(data));
			storevar('character_id', data.character_id);
			start();
		});
	});



	asyncTest( "Get Character", function() {
		var xhr = jQuery.get(character_url + '/', {'character_id': getvar('character_id')}, function(data) {
			ok(data.status !== 0, JSON.stringify(data));			
			start();
		});
	});

	asyncTest( "Initializing random battle", function() {	
		var xhr = jQuery.post(battle_url + '/create', {character_id: getvar('character_id')}, function(data) {
			ok(data.status !== 0, JSON.stringify(data));
			start();
		});
		
	});

	for (var i=0; i < 10; i++) {
		asyncTest( "Attack", function() {	
			var xhr = jQuery.post(battle_url + '/attack', {character_id: getvar('character_id')}, function(data) {
				ok(true, JSON.stringify(data));
				start();
			});
		});
		asyncTest( "Opponent Turn", function() {	
			var xhr = jQuery.post(battle_url + '/opponent_turn', {character_id: getvar('character_id')}, function(data) {
				ok(true, JSON.stringify(data));
				start();
			});
		});
	}
	
	
	


	asyncTest( "Attempt to flee", function() {	
		var xhr = jQuery.post(battle_url + '/flee', {character_id: getvar('character_id')}, function(data) {
			ok(true, JSON.stringify(data));
			start();
		});
		
	});


});
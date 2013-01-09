jQuery(document).ready(function () {
	var url = OF_PATH + 'web/adr/classes';

	asyncTest( "Getting Classes", function() {
		var xhr = jQuery.get(url + '/', {}, function(data) {
			ok(data.status !== 0, JSON.stringify(data));
			start();
		});
	});

});
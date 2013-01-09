jQuery(document).ready(function () {
	var url = OF_PATH + 'web/adr/elements';

	asyncTest( "Getting Elements", function() {
		var xhr = jQuery.get(url + '/', {}, function(data) {
			ok(data.status !== 0, JSON.stringify(data));
			start();
		});
	});

});
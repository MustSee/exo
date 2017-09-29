$('.clickLastComment').click(function () {
	var lastCommentPosted = Routing.generate('tiny_url_main_last_comment');
	$('.lastComment').toggle().load(lastCommentPosted, function(data) {
		$(this).html(data);
	});
});

$('#lastAdded').click(function (e) {
	// TRES IMPORTANT
	// Si je ne mets pas preventDefault - la page "saute" et scroll to top lorsque je clique sur le lien
	e.preventDefault();
	var lastAddedLinks = Routing.generate('tiny_url_main_last_added');
	$('.ajaxTables').load(lastAddedLinks, function (data) {
		$(this).html(data);
		$('.active').removeClass('active');
		$('#lastAdded').addClass('active');
	});
});


$('#popularLinks').click(function (e) {
	e.preventDefault();
	var popularLinks = Routing.generate('tiny_url_main_popular_links');
	$('.ajaxTables').load(popularLinks, function (data) {
		$(this).html(data);
		$('.active').removeClass('active');
		$('#popularLinks').addClass('active');
	});
});
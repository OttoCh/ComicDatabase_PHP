$(document).ready(function() {
	$("#button-post").click(function() {
		// var comic_name = $("#comic-name-input").val();

		// $.post("/api/Comic/Add.php", $("#comic-name-input").serialize());
		console.log($("#comic-name-input").serialize());
		$.ajax({
			type: "POST",
			url: "/api/Comic/Add.php",
			data: $("#comic-name-input").serialize(),
			success: function(data) {
				$("span").html(data);
				$("span").toggleClass('alert-warning alert-success');
				$("span").css("visibility", "visible");
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert(errorThrown);
				alert(textStatus);
			}
		});
	})	


});
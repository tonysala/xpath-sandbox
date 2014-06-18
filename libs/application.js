$(document).on("ready",function(){
	
	prettyPrint();
	
	$("#query_field").on("keyup",function(){
		var query = $("#query_field").val();
		var xml = $("#xml_out").text();
		$.ajax({
			url: "index.php",
			type: "POST",
			data: {query: query,xml: xml},
			dataType: "json",
			//processData: false
		}).done(function(data){
			console.log(data);
			//data = $.parseJSON(data);
			// $("#xml_out").html(data.xml);
			
			if (data.error){
				$("#xml_status").html("Error, invalid syntax");
			} else {
				$("#xml_status").html(data.status+" node(s) matched");
			}
		});
	});
	
    $("#edit").on("click",function(){
        if ($("#xml_out").prop("contenteditable") != true){
            $("#xml_out").prop("contenteditable",true);
            $(this).text("Save XML!");
        } else {
            $("#xml_out").prop("contenteditable",false);
            $(this).text("Edit XML!");
        }
    });
    
	$("#zoomout").on("click",function(){
		console.log("out");
		var font_size = parseInt($("#xml_out").css("font-size"));
		font_size = font_size -1 + "px";
		$("#xml_out").css({'font-size':font_size});
	});
	
	$("#zoomin").on("click",function(){
		var font_size = parseInt($("#xml_out").css("font-size"));
		font_size = font_size + 1 + "px";
		$("#xml_out").css({'font-size':font_size});
	});
});

$(document).ready(function() {

	// Expand Panel
	$(".top_menu_button").click(function(){
		if ($(".top_menu_base").is(":hidden")){
              $(".top_menu_base").slideDown("slow");
		}
		else{
			$(".top_menu_base").slideUp("slow");
		}
	});	
/*	
	// Collapse Panel
	$("#close").click(function(){
		$("div#panel").slideUp("slow");	
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		
*/
});
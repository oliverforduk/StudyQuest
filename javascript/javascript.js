/*Animation for spinning/ expanding button*/
function buttonanim(){

	setTimeout(function(){
		$("#subbutton").removeClass("buttonspin");
		$("#subbutton").addClass("buttonsquare");
	}, 500);
	
	setTimeout(function(){
		$("#subbutton").removeClass("buttonsquare");
		$("#subbutton").addClass("buttonfull");
	}, 850);
}

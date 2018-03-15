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

/*Changes the image displayed on the character creation based on select option*/
function charDropDown(imgid, newimg){
			document.getElementById(imgid).src = "images/builds/" + newimg + "full.png";
		}

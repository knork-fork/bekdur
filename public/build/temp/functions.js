/* IE Provjera */
var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");

if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))
{
    alert('Koristite zastarjelu verziju internetskog pretraživača, molimo vas da nadogradite');
}

/* IE Provjera */

$(window).scroll(function(){

  var wScroll = $(this).scrollTop();//vrijednost koliko je crollano

  
  // Pocetna stranica, naslov, scrollanjem se stvara parallax efekt. Tekst se miče brže od ostatka stranice
  if(wScroll > 0){//ako je scrollano

    var offsetStart = wScroll;
	
    $('#headerDiv').css({'transform': 'translate(0px, '+ (offsetStart * 0.33777174) +'px)'});//postavlja se vrijednost
  }
  // Pocetna stranica, how it works

  if(wScroll > $('.howItWorks').offset().top - $(window).height()){//kada se scrolla nakon početnog Diva

    var offset1 = (Math.min(0, wScroll - $('.howItWorks').offset().top +$(window).height() - 600)).toFixed();//dobiva se vrijednost sa zakašnjenjem od 600px

    $('#post1').css({'transform': 'translate('+ offset1 +'px, '+ Math.abs(offset1 * 0.2) +'px)'});//uređuje se CSS tog ID-a tako da mu se postavi translate sa dobivenom vrijednosti

    $('#post2').css({'transform': 'translate('+ Math.abs(offset1) +'px, '+ (Math.abs(offset1 * 0.2)-130) +'px)'});

	
    var offset2 = (Math.min(0, wScroll - $('.howItWorks').offset().top +$(window).height() - 1050)).toFixed();

    $('#post3').css({'transform': 'translate('+ offset2 +'px, '+ (Math.abs(offset2 * 0.2)-130) +'px)'});

    $('#post4').css({'transform': 'translate('+ Math.abs(offset2) +'px, '+ Math.abs(offset2 * 0.2) +'px)'});

	
    var offset3 = (Math.min(0, wScroll - $('.howItWorks').offset().top +$(window).height() - 1500)).toFixed();

    $('#post5').css({'transform': 'translate('+ offset3 +'px, '+ Math.abs(offset3 * 0.2) +'px)'});

    $('#post6').css({'transform': 'translate('+ Math.abs(offset3) +'px, '+ (Math.abs(offset3 * 0.2)-130) +'px)'});

	
    var offset4 = (Math.min(0, wScroll - $('.howItWorks').offset().top +$(window).height() - 1950)).toFixed();

    $('#post7').css({'transform': 'translate('+ offset4 +'px, '+ (Math.abs(offset4 * 0.2)-130) +'px)'});

    $('#post8').css({'transform': 'translate('+ Math.abs(offset4) +'px, '+ Math.abs(offset4 * 0.2) +'px)'});	
	
	
  }
});


// login/register visina slike
//Tokom upisa u input polja, CSS ne reagira pa je potrebno koristiti JS kako bi se promijenila visina slike
$('.imgTxtDiv').css("height", ($('.logRegForm').height())+140);


// provjera ispravnosti imena, lozinke i emaila tokom registracije
function inputCheck(type)
{
	switch(type)
	{
		case "username":
			//ako korisničko ime ima više od 60 znakova, ne može se registrirat. Ispis greške
			if($('#usernameInput').val().length>60)
			{
				$('#usernameError').text('Najviše 60 znakova.');
				$('#registerButton123').attr('disabled', true);
			}
			else
				$('#usernameError').text('');
			$('.imgTxtDiv').css("height", ($('.logRegForm').height())+140);
			break;
			
			
		case "password":
			//Lozinka mora biti duga bar 6 znakova i imati barem jedno slovo. Ispis greške
			if($('#passwordInput').val().length < 6)
			{
				$('#passwordError').text('Minimalno 6 znakova');
				$('#registerButton123').attr('disabled', true);
			}
			else if(!($('#passwordInput').val().match(/\d+/g)))
			{
				$('#passwordError').text('Lozinka mora sadržavati barem 1 broj');
				$('#registerButton123').attr('disabled', true);
			}
			else
				$('#passwordError').text('');
						
			$('.imgTxtDiv').css("height", ($('.logRegForm').height())+140);
			break;
			
			
		case "passwordAgain":
			//potvrda lozinke
			if(($('#passwordAgainInput').val())==($('#passwordInput').val()))
			{
				$('#passwordAgainError').text('');
				$('#registerButton123').attr('disabled', true);
			}
			else
				$('#passwordAgainError').text('Potvrdite lozinku.');
			$('.imgTxtDiv').css("height", ($('.logRegForm').height())+140);
			break;
			
			
		case "email":
			//email treba biti u obliku a@a.a
			var check = $('#emailInput').val();
			var mailFormat = /\S+@\S+\.\S+/; 
			if(!mailFormat.test(check))
			{
				$('#emailError').text('Neispravna e-mail adresa');
				$('#registerButton123').attr('disabled', true);
			}
			else
				$('#emailError').text('');
			$('.imgTxtDiv').css("height", ($('.logRegForm').height())+140);
			break;
	}
	//ako postoji greška, ne može se registrirat
	if(($('#usernameError').text()=='')&&($('#passwordError').text()=='')&&($('#passwordAgainError').text()=='')&&($('#emailError').text())=='')
		$('#registerButton123').attr('disabled', false);
		
}



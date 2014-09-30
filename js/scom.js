// Menú desplegable para publicar URL
	//<![CDATA[ 
	$(window).load(function(){
	$("#publicar").on("click", function(event) {
	    event.preventDefault();
	    event.stopPropagation();
	
	    $(this).toggleClass("selected");
	    $("#datos").toggleClass("selected");
	    //$(this).removeClass("selected");
	});
	$(document).on("click", function(event) {
	    $("#publicar").removeClass("selected");
	    $("#datos").removeClass("selected");
	});
	$('#datos').click(function(e) {
	    e.stopPropagation();
	});
	});//]]>

// Modificación del tamaño del div de acuerdo a la pantalla
	function altura(){
		h=window.innerHeight;
		document.getElementById('objeto').style.minHeight = (h-72)+'px';
		document.getElementById('objetos').style.minHeight = (h-72)+'px';
		}		

// Validación de los datos ingresados para registro
	function validacion(){
		var u=document.forms["registro"]["url"].value;
		var e=document.forms["registro"]["email"].value;
		var c=document.forms["registro"]["recaptcha_response_field"].value;
		var regex=/^(ht|f)tps?:\/\/\w+([\.\-\w]+)?\.([a-z]{2,4}|travel)(:\d{2,5})?(\/.*)?$/i;
		if (u==null || u=="" || e==null || e=="" || c==null || c==""){
			alert("Por favor llene todos los campos");
			return false;
  		}
		else if (u.indexOf('30s.com.ec')>-1 || e.indexOf('30s.com.ec')>-1){
			alert("URL o Email no permitido, por favor ingrese otro valor");
			return false;
		}
		else if(!regex.test(u)){
			alert("URL incorrecta.");
			return false;
		}
		else if(!e.match(/^[^\s()<>@,;:\/]+@\w[\w\.-]+\.[a-z]{2,}$/i)){
			alert("Email incorrecto.");
			return false;
		}
	}

	
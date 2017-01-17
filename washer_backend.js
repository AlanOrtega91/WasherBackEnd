(function ($){
  jQuery("document").ready(function(){
    var SERVICES_URL = "/Washer/";
    var token = "";
    var id = "";
    var services;
    //Limpiar GUI
    $("#skip-link").remove();
    $("#main-wrapper").remove();
    $("#main-menu").html("");


    //Anadir formulario login
//    $("body").append("<div id='login-backend'><img src='../sites/all/modules/washer_backend/images/washer.png'></img>INICIAR SESI&Oacute;N <select><option>Selecciona el tipo de socio</option><option>Inversionista</option><option>Lavador</option></select><input type='text'>Alex</input><input></input><input></input><p>Olvidaste tu contrase&nacute;a</p><submit></div>");
    loginHtml= '  <div id="login-backend" style="display:none">	\
                    <img src="../sites/all/modules/washer_backend/images/washer.png"></img>	\
                    INICIAR SESI&Oacute;N	\
                    <p id="error"></p>	\
                    <select id="type">	\
                      <option value="">Selecciona el tipo de socio</option>	\
                      <option value="Inversionista">Inversionista</option>	\
                      <option value="Cleaner">Lavador</option>	\
                    </select>	\
                    <input type="text" id="email" value="Mail"></input>	\
                    <input type="password" id="password" value="Contrase&ntilde;a"></input>	\
                    <p>Olvidaste tu contrase&ntilde;a</p>	\
                    <input type="submit" id="enviarLogin" value="INICIAR SESI&Oacute;N"></input>	\
                  </div>';
    
    $("body").append(loginHtml);

    //Animar a LogIn
    $("body").prepend($("#header"));
    $("#header").slideUp("slow", function(){$("#page-wrapper").fadeOut();$("#login-backend").fadeIn();});

    $("#enviarLogin").on("click", function(){
      var type		= $("#type").val();
      var email		= $("#email").val();
      var password	= $("#password").val();
      if(type==""){
        $("#error").html("Selecciona el tipo de socio");
        return;
      }
      var address = SERVICES_URL+"API/"+type+"/LogIn/"; 
      $.post(address, {email: email, password: password, device: "web"}, function(data){
      	console.log(data);
        if(data.Status == "OK"){
          $("#error").html("");
          if (type == "Cleaner") {
        	  console.log("cleaner Ok");
        	  id = data["User Info"].idLavador;
        	  fillProducts();
          } else {
        	  console.log("Investor Ok");
        	  token = data["User Info"].Token;
        	  fillInvestor();
          }
          $("#login-backend").fadeOut();
          $("#header").slideDown("slow", function(){$("#page-wrapper").fadeIn();});
        }else{
          $("#error").html("Error al iniciar sesion");
        }
        console.log(data);
      }, "json");
    });
    //Pagina y funciones de Productos
    function fillProducts(){
    	refillProductsHtml = '  <div id="refill-backend" style="display:block">	\
    		<p id="productError"></p>\
	        <select id="product">	\
	          <option value="">Selecciona el producto a rellenar</option>	\
	          <option value="1">Ecologico</option>	\
	          <option value="2">Shampoo</option>	\
			  <option value="3">Vinil</option>	\
			  <option value="4">Rines</option>	\
			  <option value="5">Vidrios</option>	\
			  <option value="6">Vestiduras</option>	\
			  <option value="7">Aromatizante</option>	\
	        </select>	\
	    	<button id="enviarRellenar" type="button">Rellenar</button> \
	      </div>';
    	$("#page-wrapper").html(refillProductsHtml);
    }
    
    $(document).on("click","#enviarRellenar", function() {
        var product = $("#product").val();
        if(product == ""){
          $("#productError").html("Selecciona el producto");
          return;
        }
        var values = {idLavador: id, productId: product};
        console.log(values);
        $.post(SERVICES_URL+"API/Cleaner/Product/RefillProduct/", values, function(data){
          if(data.Status == "OK"){
        	  console.log("Rellenado");
            $("#productError").html("Rellenado");
          }else{
        	  console.log("No rellenado");
            $("#productError").html("Error al rellenar");
          }
          console.log(data);
        }, "json");
      });
    
    //Pagina y funciones de Inversionista
    function fillInvestor(){
    	investorHtml = '  <div id="refill-backend" style="display:block">	\
    		<p id="productError"></p>\
	        <select id="product">	\
	          <option value="">Selecciona el producto a rellenar</option>	\
	          <option value="1">Ecologico</option>	\
	          <option value="2">Shampoo</option>	\
			  <option value="3">Vinil</option>	\
			  <option value="4">Rines</option>	\
			  <option value="5">Vidrios</option>	\
			  <option value="6">Vestiduras</option>	\
			  <option value="7">Aromatizante</option>	\
	        </select>	\
	    	<button id="enviarBuscar" type="button">Rellenar</button> \
    		<div id="services" style="display:none> \
    		</div> \
	      </div> ';
    	$("#page-wrapper").html(refillProductsHtml);
    }
    $(document).on("click","#enviarBuscar", function(){
        var idLavador = $("#lavador").val();
        var fecha = $("#fecha").val();
        $.post(SERVICES_URL+"API/Inversionista/ReadServices/", {idLavador: id, token: token, fecha: fecha}, function(data){
          if(data.Status == "OK"){
        	  console.log("Se busco bien");
            services = data.Services;
            fillServices();
          }else{
        	  console.log("No se encontro");
            $("#errorInversionista").html("Error al buscar");
          }
          console.log(data);
        }, "json");
      });
	
    function fillServices(){
    	htmlServices = "";
    	services.each(function(service){
    		htmlServices.append(
        			'<div class="service-row> <p id="status>' + service.status + '</p> <p id="nombreLavador>' + service.nombreLavador + '</p> \
        			<p id="coche></p>' + service.coche + ' <p id="servicio></p>' + service.servicio + '<p id="precio>' + service.precio + '</p> \
        			<p id="descripcion></p>' + service.descripcion + '<p id="fechaEmpezado></p>' + service.fechaEmpezdo + '\
        			<p id="Calificacion>' + service.calificacion + '</p> <p id="nombreCliente></p>' + service.nombreCliente + '\
        			<p id="telCliente></p>' + service.telCliente + '<p id="Color>' + service.Color + '</p> <p id="Modelo>' + service.Modelo + '</p></div><br>  '
        			)
    	});
    	$("#services").html(htmlServices);
    }
  });

})(jQuery);
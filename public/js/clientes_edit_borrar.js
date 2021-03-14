function llenar_select_doc_tipo_js(ruta , tipo_actual){
   // Llena el select de tipos de documento a partir de la tabla
   // de tipos de documento.
   // Recibe 2 parámetros:
   //     la url del controlador que lee la b.d.
   //     es: ClienteController@llenar_select_doc_tipo
   //    el tipo actual que tiene grabado en la b.d.  el cliente
   //    que fue escogido.
  baseUrl = ruta;
  $.ajax({
    url:baseUrl,
    type: "GET",
    success: function(data){
      // llega un array con los nombres e id de cada tipo docu:
      combo = document.getElementById("selectDoc_tipo");
      for(var i = 0 ; i < data.length ;  i++){
        opcion = document.createElement("option");
        opcion.value = data[i]['id'];
        opcion.text = data[i]['nombre'];
        combo.appendChild(opcion);
     };
     // seleccionar el mismo tipo docu grabado en la b.d.
     document.getElementById("selectDoc_tipo").value = tipo_actual;


    },
    failure: function(msgFail){
      console.log(msgFail);
    }
  });
}  // fin de la función para llenar el combo de tipos de documentos

function llenar_juridica(valor_actual){
   document.getElementById("juridica").value = valor_actual;
}

function llenar_declarante(valor_actual){
   document.getElementById("declarante").value = valor_actual;
}

  // función para llenar el combo tipo documento
  function llenar_select_doc_tipo_js(ruta){
     // Llena el select de tipos de documento a partir de la tabla
     // de tipos de documento.
     // Recibe 1 parámetros:
     //     la url del controlador que lee la b.d. (ClienteController@llenar_select_doc_tipo)
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
        }
      },
      failure: function(msgFail){
        console.log(msgFail);
      }
    });
  }  // fin de la función para llenar el combo de tipos de documentos

  // función para llenar el combo departamentos:
  function llenar_select_dpto_js(ruta){
    baseUrl = ruta;
    $.ajax({
      url:baseUrl,
      type: "GET",
      success: function(data){
        // llega un array con los nombres e id@grupo de cada dir_ppal:
        combo = document.getElementById("selectDpto");

        // el placeholder del select:
        // <option value="" disabled selected style="display: none;"> placeholder</option>
        opcion = document.createElement("option");
        opcion.value = "";
        opcion.text = "SELECCIONE DEPARTAMENTO...";
        opcion.selected = true;
        opcion.disabled = true;
        opcion.style.display = "none";
        combo.appendChild(opcion);

        // llenar las opciones del select:
        for(var i = 0 ; i < data.length ;  i++){
          opcion = document.createElement("option");
          opcion.value = data[i]['dpto_cod'];
          opcion.text = data[i]['dpto_nom'];
          combo.appendChild(opcion);
        }
      },
      failure: function(msgFail){
        console.log(msgFail);
      }
    });
  }  // fin de la función para llenar el combo de dir_ppal

  function procesar_tipo_doc(objSelect){
    // llega acá cuando el usuario escoge un tipo de documento,
    // si escogio cedula (tipos 1 o 2):
    //      Activara los campos de nombres y apellidos
    //      Pondrá readonly el campo razon_social
    //      Borrará el contenido del campo razon_social
    //      Borrará el contenido del campo DV
    // en caso contrario:
    //      Activara razon_social
    //      Pondrá readonly los nombres y apellidos
    //      Borrará el contenido de los nombres y apellidos
    //      Calculará el DV en caso de que el campo docu:num esté lleno
    // Recibe 1 parámetros:
    //    el objeto Select, del cual se obtendrá el doc_tipo seleccionado
    var opcion = objSelect.value;
   if(opcion == 1 || opcion==2){
      document.getElementById("razon_social").value = "";
      document.getElementById("razon_social").readOnly = true;
      document.getElementById("nombre1").readOnly = false;
      document.getElementById("nombre2").readOnly = false;
      document.getElementById("apellido1").readOnly = false;
      document.getElementById("apellido2").readOnly = false;
      document.getElementById("doc_dv").value = "";
   }else{
      document.getElementById("nombre1").value = "";
      document.getElementById("nombre2").value = "";
      document.getElementById("apellido1").value = "";
      document.getElementById("apellido2").value = "";
      document.getElementById("nombre1").readOnly = true;
      document.getElementById("nombre2").readOnly = true;
      document.getElementById("apellido1").readOnly = true;
      document.getElementById("apellido2").readOnly = true;
      document.getElementById("razon_social").readOnly = false;
      doc_num = document.getElementById("doc_num").value;
      if ( !(doc_num == null || doc_num == undefined || doc_num == '')){
         determinar_dv(doc_num)
      }
   }
  }

  function procesar_dpto(objSelect , ruta){
    // llega acá cuando el usuario escoge un dpto, con lo cual se
    // podrá llenar el combo con las ciudades que correspondan
    // a ese departamento:
    // Recibe 2 parámetros:
    //    el objeto Select, del cual se obtendrá el dpto seleccionado
    //    la ruta que llega desde el create.blade.php, y que será
    //       pasada a la función llenar_select_ciudades() para que allí
    //       se llame el ajax correspondiente:
    var opcion = objSelect.value;
    llenar_select_ciudades( ruta , opcion);
  }

  // función para llenar el combo ciudades, de acuerdo
  // al departamento escogido:
  function llenar_select_ciudades(ruta, dpto_cod){
    // llamada por la función seleccionar_dpto(), esta
    // función se encarga de llenar el select de las
    // ciudades de acuerdo al dpto escogido, además llena
    // el input text de solo lectura cod_postal.
    // Recibe 2 parámetros:
    //      la ruta (que viene desde la vista create.blade.php,
    //      varias llamadas atrás)
    //      el dpto_cod escogido por el usuario
    baseUrl = ruta + "/" + dpto_cod;
    $.ajax({
      url:baseUrl,
      type: "GET",
      // 30oct2019: Para las url "amigables" de laravel, los
      // parámetros no se envian con data, por eso está
      // comentariada la siguiente instrucción, notar que
      // en la url va el parámetro separado con /.
      // data: {dpto_cod: dpto_cod},
      success: function(data){
        // llega un array 3 columnas:
        //     nombres de ciudades   (ciudad)
        //     cod divipolas        (divipol)
        //     cod postales         (cod_postal)
        // Primero debe borrar las opciones que puedan existir
        // en el select:
        combo = document.getElementById("selectCiudad");
        while (combo.length > 0) {
          combo.remove(0);
        }

        // llenar las opciones del select ciudades con las
        // correspondientes al departamento escogido:
        for(var i = 0 ; i < data.length ;  i++){
          opcion = document.createElement("option");
          opcion.value = data[i]['divipol'];
          opcion.text = data[i]['ciudad'];
          // para que seleccione la capital del departamento (en
          // la divipola la capital siempre es 001) y por ahi
          // derecho coloque el código postal de la misma:
          if(data[i]['divipol'].substr(2,3) == "001"){
            opcion.selected = true;
            document.getElementById('cod_postal').value = data[i]['cod_postal'];
          }
          combo.appendChild(opcion);
        }
      },
      failure: function(msgFail){
        console.log(msgFail);
      }
    });
  }  // fin de la función para llenar el combo de ciudades

  function procesar_ciudad(objSelect , ruta){
     // Llamada cuando se escoje una ciudad en el select
     // de ciudades.
     // Recibe 2 parámetros:
     //     el objeto select (con el cual se sabrá la divipol de la ciudad escogida)
     //     la ruta '/obtener_cod_postal' que se encargará de obtener el cod_postal que corresponde a la ciudad seleccionada
     //
      var divipol = objSelect.value;
      baseUrl = ruta + "/" + divipol;
      $.ajax({
        url:baseUrl,
        type: "GET",
        success: function(data){
           // recibió el cod_postal, lo coloca en el input text correspondiente:
           document.getElementById('cod_postal').value = data;
        },
       failure: function(msgFail){
         console.log(msgFail);
       }
     });
   }

  // función para llenar el combo dir ppal:
  function llenar_select_dir_ppal_js(ruta){
   // ruta es: ClienteController@llenar_select_dir_ppal
    baseUrl = ruta;
    $.ajax({
      url:baseUrl,
      type: "GET",
      success: function(data){
        // llega un array con los nombres e id@grupo de cada dir_ppal:
        combo = document.getElementById("selectDir_ppal");

        // el placeholder del select:
        // <option value="" disabled selected style="display: none;"> placeholder</option>
        opcion = document.createElement("option");
        opcion.value = "";
        opcion.text = "SELECCIONE DIRECCIÓN...";
        opcion.selected = true;
        opcion.disabled = true;
        opcion.style.display = "none";
        combo.appendChild(opcion);

        // llenar las opciones del select:
        for(var i = 0 ; i < data.length ;  i++){
          opcion = document.createElement("option");
          opcion.value = data[i]['idgrupo'];
          opcion.text = data[i]['nombre'];
          combo.appendChild(opcion);
        }
      },
      failure: function(msgFail){
        console.log(msgFail);
      }
    });
  }  // fin de la función para llenar el combo de dir_ppal

  function pedir_dir_completa(objSelect){
    // llega acá cuando el usuario escoge un dir_ppal (carrera, calle, etc..)
    // de acuerdo al grupo escogido pedirá el número de la casa o solamente
    // información adicional de la dirección:
    var opcion = objSelect.value;
    var texto = objSelect.selectedOptions[0].text;
    var pos_grupo = opcion.indexOf('@') + 1;
    var grupo = opcion.substr(pos_grupo);
    if(grupo == 1){
      document.getElementById('div_dir_num_ppal').style.display = "inline";
      document.getElementById('div_dir_num_casa').style.display = "inline";
      document.getElementById('div_dir_adic').style.display = "inline";
      document.getElementById('lbl_dir_num_ppal').innerHTML = "Número de la " + texto + ": ";
      document.getElementById('lbl_dir_num_casa').innerHTML = "Número de la casa, edificio, etc...: ";
      document.getElementById('lbl_dir_adic').innerHTML = "Notas adicionales a la dirección: ";
    }else{
      document.getElementById('div_dir_num_ppal').style.display = "none";
      document.getElementById('div_dir_num_casa').style.display = "none";
      document.getElementById('div_dir_adic').style.display = "inline";
      document.getElementById('lbl_dir_adic').innerHTML = "Nombre de " + texto + " y datos adicionales: ";
    }
  }

  function determinar_dv(entrada){
    // llega aca en el evento onblur() del input text
    // que pide el nro de documento. Esta función se
    // encarga de generar el DV en caso de necesitarse:
    var primos = [3, 7, 13, 17, 19, 23, 29, 37, 41, 43, 47, 53, 59, 67, 71];
    if (!/^([0-9])*$/.test(entrada)){
      alert("El número de documento que acaba de digitar (" + entrada + ") es erróneo.\r\n\r\nSolo puede escribir números. Por favor verifique.");
      var docu = document.getElementById('doc_num');
      docu.value = "";
    }else if(entrada !== ""){
      var cbo_tipo_docu = document.getElementById("selectDoc_tipo");
      var strOpcion = cbo_tipo_docu.options[cbo_tipo_docu.selectedIndex].text;
      if(strOpcion.substr(0,3) == "NIT"){
          entrada = entrada.trim();
          var cifra="";
          var suma=0;
          var indice_primos=0;
          for (i = entrada.length - 1; i >= 0; i--) {
            cifra = entrada.substr(i , 1);
            suma = suma + (cifra * primos[indice_primos]);
            indice_primos++;
          }
          var dv = suma % 11;
          if(dv > 1){
            dv = 11 - dv;
          };
          document.getElementById('doc_dv').value = dv;
      }
    }
  }

  function pedir_firma_digital(obj ){
     // si el cliente autorizó el tratamiento de sus datos personales,
     // entonces pide la firma (siempre y cuando ya se haya digitado el nit).
     // Esta función es llamada cuando se dá click al checkbox de autorización
     // del habeas data y recibe 1 parámetro:
     //     obj:  para saber si el usuario activó/desactivó el checkbox
     if(obj.checked){
        // se acaba de chequear (poner SI), el tratamiento de datos
        // personales, lo primero es verificar que si haya un nit/cc
        // digitados, pues con él se pondrá el nombre del .png mientras
        // se hace la grabación definitiva del cliente:

        obj.checked = false;   // se activará posteriormente, si el usuario GRABA la firma
        if(document.getElementById('doc_num').value){
           // puede proceder a pedir la firma:
          jQuery.noConflict();
          $('#myModal').modal();
        }else{
           alert('No se puede procesar la autorización hasta que no se digite una cédula / nit');
        }
     }
 }

function grabar_firma(ruta){
   // el usuario dió click en "Grabar firma"
   // 1) Mediante un llamado AJAX graba la firma en un archivo png
   // 2) Si el AJAX retorna correcto, pone true al checked de la
   //    casilla de verificación
   var token = $('meta[name="csrf-token"]').attr('content');
   var dataURL = signaturePad.toDataURL();
   var doc_num = document.getElementById('doc_num').value;
   baseUrl = ruta;
   $.ajax({
     url:baseUrl,
     type: "POST",
     data: {
        _token: token,
        dataUrl: dataURL,
        doc_num: doc_num,
     },
     success: function( response ) {
        document.getElementById('habeas_data').checked = true;
     },
     error:function (error) {
       console.log('entro a error...');
       console.log(error);
     },
     failure:function(msgfail){
       console.log('entro al failure');
       console.log(msgfail);
     }
   });    // fin del Ajax



}

function limpiar_firma(){
   // el usuario dió click en "Limpiar":
   signaturePad.clear();
}

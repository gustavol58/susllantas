   // *****************************************************************
   // Funciones utilizadas tanto por creación como por modificación
   // de clientes
   // *****************************************************************

   function llenar_select_doc_tipo_js(ruta , tipo_actual = null){
      // Llena el select de tipos de documento a partir de la tabla
      // de tipos de documento.
      // Recibe 2 parámetros:
      //     la url del controlador que lee la b.d.
      //     la ruta es: ClienteController@llenar_select_doc_tipo
      //    el tipo actual que tiene grabado en la b.d.  el cliente
      //    que fue escogido. NOTA: null significa que fué llamado
      //    desde creación y no desde modificación
     baseUrl = ruta;
     $.ajax({
       url:baseUrl,
       type: "GET",
       success: function(data){
         // llega un array con los nombres e id de cada tipo docu:
         combo = document.getElementById("selectDoc_tipo");

         opcion = document.createElement("option");
         opcion.value = "";
         opcion.text = "SELECCIONE EL TIPO DE DOCUMENTO...";
         opcion.selected = true;
         opcion.disabled = true;
         opcion.style.display = "none";
         combo.appendChild(opcion);

         for(var i = 0 ; i < data.length ;  i++){
           opcion = document.createElement("option");
           opcion.value = data[i]['id'];
           opcion.text = data[i]['nombre'];
           combo.appendChild(opcion);
        };
        if(tipo_actual !== null){
           // seleccionar el mismo tipo docu grabado en la b.d. y hacer
           // la activación de los campos nombres correspondientes:
           document.getElementById("selectDoc_tipo").value = tipo_actual;
           var objDocTipo = {value: tipo_actual};
           procesar_tipo_doc(objDocTipo);
        }
       },
       failure: function(msgFail){
         console.log(msgFail);
       }
     });
   }  // fin de la función para llenar el combo de tipos de documentos

   function procesar_tipo_doc(objSelect){
     // llega acá cuando el usuario escoge un tipo de documento, o
     // cuando se invoca desde la funcion javascript llenar_select_doc_tipollenar_select_dpto_y_ciudad_js()
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
      // el select cod_postal.
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
            combo_ciudades = document.getElementById("selectCiudad");
            while (combo_ciudades.length > 0) {
             combo_ciudades.remove(0);
            }

            // llenar las opciones del select ciudades con las
            // correspondientes al departamento escogido:
            for(var i = 0 ; i < data.length ;  i++){
               opcion_ciudades = document.createElement("option");
               opcion_ciudades.value = data[i]['divipol'];
               opcion_ciudades.text = data[i]['ciudad'];
               if(data[i]['divipol'].substr(2,3) == "001"){
                  // selecciona la capital de departamento:
                  opcion_ciudades.selected = true;
                  // llenar select de códigos postales:
                  var codigos_postales = data[i]['cod_postal'];
                  var arr_codigos_postales = codigos_postales.split(',')
                  llenar_select_cod_postales(arr_codigos_postales);
               }
               combo_ciudades.appendChild(opcion_ciudades);
            }
         },
         failure: function(msgFail){
            console.log(msgFail);
         }
      });  // fin del ajax
   }    // fin de la función para llenar el combo de ciudades

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
            // document.getElementById('cod_postal').value = data;

            // 24ene2020
            // llena el select de códigos postales:
            // llenar select de códigos postales:
            var codigos_postales = data;
            var arr_codigos_postales = codigos_postales.split(',')
            llenar_select_cod_postales(arr_codigos_postales);
         },
         failure: function(msgFail){
            console.log(msgFail);
         }
      });    // fin del ajax
   }  // fin de la función procesar ciudad

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

      function pedir_dir_completa(objSelect){
        // llega acá cuando el usuario escoge un dir_ppal (carrera, calle, etc..)
        // de acuerdo al grupo escogido pedirá el número de la casa o solamente
        // información adicional de la dirección:
        var opcion = objSelect.value;
        var texto = objSelect.selectedOptions[0].text;
        var pos_grupo = opcion.indexOf('@') + 1;
        var grupo = opcion.substr(pos_grupo);
        if(grupo == 1  || grupo == 3){
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



   // *****************************************************************
   // Funciones utilizadas solamente por CREACIÓN de clientes
   // *****************************************************************
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
  }  // fin de la función para llenar el combo de departamentos

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



   // *****************************************************************
   // Funciones utilizadas solamente por MODIFICACIÓN de clientes
   // *****************************************************************

   function llenar_juridica_js(valor_actual){
      document.getElementById("juridica").value = valor_actual;
   }

   function llenar_declarante_js(valor_actual){
      document.getElementById("declarante").value = valor_actual;
   }

   // funciones para llenar los combos departamentos y ciudades:
   function llenar_select_dpto_ciudad_y_codpostal_js(ruta_dpto , ruta_ciudad , dpto_actual , ciudad_actual , cod_postales , cod_postal_escogido){
     baseUrl = ruta_dpto;
     $.ajax({
       url:baseUrl,
       type: "GET",
       success: function(data){
         combo = document.getElementById("selectDpto");

         // llenar las opciones del select dpto:
         for(var i = 0 ; i < data.length ;  i++){
           opcion = document.createElement("option");
           opcion.value = data[i]['dpto_cod'];
           opcion.text = data[i]['dpto_nom'];
           combo.appendChild(opcion);
         }
         document.getElementById("selectDpto").value = dpto_actual;
         llenar_select_ciudades_codpostales_edit(ruta_ciudad, dpto_actual, ciudad_actual, cod_postales , cod_postal_escogido);
       },
       failure: function(msgFail){
         console.log(msgFail);
       }
     });
 }  // fin de la función para llenar el combo de departamentos y ciudades

   // función para llenar el combo ciudades, de acuerdo
   // al departamento escogido:
   function llenar_select_ciudades_codpostales_edit(ruta, dpto_actual, ciudad_actual, cod_postales , cod_postal_escogido){
      // llamada por la función llenar_select_dpto_ciudad_y_codpostal_js(), esta
      // función se encarga de llenar el select de las
      // ciudades de acuerdo al dpto escogido, muestra la ciudad
      // que está grabada en la b.d. para el cliente escogido.
      // Al terminar, llama la función que llena el select de codigos postales
      // Recibe 4 parámetros:
      //      la ruta para leer las ciudades (que viene desde la vista create.blade.php,
      //      varias llamadas atrás), la ruta es: ClienteController@llenar_select_ciudades
      //      el dpto_actual que estaba grabado en la b.d.
      //      la ciudad_actual grabado en la b.d.
      //      el código postal escogido para el cliente
      baseUrl = ruta + "/" + dpto_actual;
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
         // en el select de ciudades:
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
            combo.appendChild(opcion);
         };
         // seleccionar la ciudad grabada en la b.d.
         document.getElementById("selectCiudad").value = dpto_actual + ciudad_actual;
         // llenar el input de códigos postales:
         // document.getElementById('cod_postal').value = cod_postales;
         // llena el select de codigos postales:
         // llenar select de códigos postales:
         var codigos_postales = cod_postales;
         var arr_codigos_postales = codigos_postales.split(',')
         llenar_select_cod_postales_edit(arr_codigos_postales , cod_postal_escogido);
        },
        failure: function(msgFail){
         console.log(msgFail);
        }
      });
   }  // fin de la función para llenar el combo de ciudades en editar

   function llenar_select_cod_postales_edit(arr_postales , cod_postal_escogido){
      combo_postales = document.getElementById("selectCod_postal");
      // Borrar las opciones que puedan existir
      // en el select:
      while (combo_postales.length > 0) {
        combo_postales.remove(0);
      }
      // llenar las opciones del select cod postales con las
      // correspondientes al departamento-ciudad escogidos:
      for(var j = 0 ; j < arr_postales.length ;  j++){
         opcion_postales = document.createElement("option");
         opcion_postales.value = arr_postales[j].trim(); // el trim() es para
                                                         // poder asignar enseguida el value
         opcion_postales.text = arr_postales[j];
         combo_postales.appendChild(opcion_postales);
      }
      // seleccionar el cod postal grabado en la b.d.
      document.getElementById("selectCod_postal").value = cod_postal_escogido;
   }


   function llenar_direccion_js(ruta , dir_id_actual , dir_num_ppal_actual , dir_num_casa_actual , dir_adic_actual, idgrupo_actual, grupo_actual, nombre_actual){
      // ruta es: ClienteController@llenar_select_dir_ppal
// console.log(ruta);
// console.log(dir_id_actual);
// console.log(dir_num_ppal_actual);
// console.log(dir_num_casa_actual);
// console.log(dir_adic_actual);
// console.log(idgrupo_actual);
// console.log(grupo_actual);
// alert('revisar console.');
       baseUrl = ruta;
       $.ajax({
         url:baseUrl,
         type: "GET",
         success: function(data){
           // llega un array con los nombres y grupo (id@grupo) de cada dir_ppal:
           combo = document.getElementById("selectDir_ppal");

           // llenar las opciones del select:
           for(var i = 0 ; i < data.length ;  i++){
             opcion = document.createElement("option");
             opcion.value = data[i]['idgrupo'];
             opcion.text = data[i]['nombre'];
             combo.appendChild(opcion);
            };
            // seleccionar  la dir_ppal grabada en la b.d.:
            document.getElementById("selectDir_ppal").value = idgrupo_actual;
            // llenar los otros 3 campos de la dirección:
            if(grupo_actual == 1 || grupo_actual == 3){
               // debe pedir los 3 campos y mostrar los que están
               // grabado en la b.d.:
               document.getElementById('div_dir_num_ppal').style.display = "inline";
               document.getElementById('div_dir_num_casa').style.display = "inline";
               document.getElementById('div_dir_adic').style.display = "inline";
               document.getElementById('lbl_dir_num_ppal').innerHTML = "Número de la " + nombre_actual + ": ";
               document.getElementById('lbl_dir_num_casa').innerHTML = "Número de la casa, edificio, etc...: ";
               document.getElementById('lbl_dir_adic').innerHTML = "Notas adicionales a la dirección: ";
               document.getElementById('dir_num_ppal').value =  dir_num_ppal_actual;
               document.getElementById('dir_num_casa').value = dir_num_casa_actual
               document.getElementById('dir_adic').value = dir_adic_actual
            }else{
               // solo muestra y pide adicionales
               document.getElementById('div_dir_num_ppal').style.display = "none";
               document.getElementById('div_dir_num_casa').style.display = "none";
               document.getElementById('div_dir_adic').style.display = "inline";
               document.getElementById('lbl_dir_adic').innerHTML = "Nombre de " + nombre_actual + " y datos adicionales: ";
               document.getElementById('dir_adic').value = dir_adic_actual
            }
         },
         failure: function(msgFail){
           console.log(msgFail);
         }
       });
   }

   function seleccionar_cumple_mes_js(cumple_mes_actual){
      document.getElementById("selectMes_cumple").value = cumple_mes_actual;
   }

   function activar_habeas_data_js(habeas_actual){
      // 1 es true, 0 o null es false
      if(habeas_actual == 1){
         document.getElementById("habeas_data").checked = true;
      }else{
         document.getElementById("habeas_data").checked = false;
      }
   }

   // =============================================================================
   // funciones usadas para la firma de datos personales
   // =============================================================================
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

      // lo primero es verificar que sí se haya puesto una firma en el canvas:
      if(signaturePad.isEmpty()){
         alert('No se puede grabar una firma en blanco.\r\nSe asume que el cliente no dió su autorización.');
      }else{
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
   }

   function modificar_firma(ruta , cliente_id, nueva_firma_png){
      // el usuario dió click en "Grabar cambio de la firma" en la ventana de modificación
      // Recibe dos parámetros:
      //    a) la ruta es ClienteController@modificar_firma
      //    b) el cliente_id que se está modificando
      // 1) Mediante un llamado AJAX renombra el .png del cliente que se está
      //    modificando.
      // 2) Si el AJAX retorna correcto, pone true al checked de la
      //    casilla de verificación

      // lo primero es verificar que sí se haya puesto una firma en el canvas:
      if(signaturePad.isEmpty()){
         alert('No se puede grabar una firma en blanco.\r\nSe asume que el cliente no dió su autorización.');
      }else{
         var token = $('meta[name="csrf-token"]').attr('content');
         var dataURL = signaturePad.toDataURL();
         baseUrl = ruta;
         $.ajax({
           url:baseUrl,
           type: "POST",
           data: {
              _token: token,
              dataUrl: dataURL,
              cliente_id: cliente_id,
           },
           success: function( response ) {
              document.getElementById('habeas_data').checked = true;
              var timestamp = new Date().getTime();
              document.getElementById('img_firma_actual').src = nueva_firma_png + '?t=' + timestamp;
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
   }

   function limpiar_firma(){
      // el usuario dió click en "Limpiar":
      signaturePad.clear();
   }



   // =============================================================================
   // funciones para generar la orden de servicio
   // =============================================================================
   function llenar_select_placas_js(doc_num , ruta ){
      // Llena el select de placas del cliente que esté digitado en
      // el inputtext doc_num
      // Recibe 2 parámetros:
      //    el número de documento del cliente, para enviarlo con el AJAX
      //    la url del controlador que lee la b.d., la ruta
      //          es: OrdenController@llenar_select_placas_orden_servicio
      //
     baseUrl = ruta + '/' + doc_num;
     $.ajax({
       url:baseUrl,
       type: "GET",
       // data: {doc_num: doc_num},
       success: function(data){
         // llega un array con los id y la las placas de los
         // vehículos del cliente:
         // el placeholder del select:

         combo = document.getElementById("selectPlaca");
         opcion = document.createElement("option");
         opcion.value = "";
         opcion.text = "Seleccione el vehículo del cliente...";
         opcion.selected = true;
         opcion.disabled = true;
         opcion.style.display = "none";
         combo.appendChild(opcion);

         for(var i = 0 ; i < data.length ;  i++){
           opcion = document.createElement("option");
           opcion.value = data[i]['id'];
           opcion.text = data[i]['placa'];
           combo.appendChild(opcion);
        };

        opcion = document.createElement("option");
        opcion.value = "0";
        opcion.text = "===> CREAR NUEVO VEHÍCULO";
        // opcion.selected = true;
        // opcion.disabled = true;
        // opcion.style.display = "none";
        combo.appendChild(opcion);

        // if(tipo_actual !== null){
        //    // seleccionar el mismo tipo docu grabado en la b.d. y hacer
        //    // la activación de los campos nombres correspondientes:
        //    document.getElementById("selectDoc_tipo").value = tipo_actual;
        //    var objDocTipo = {value: tipo_actual};
        //    procesar_tipo_doc(objDocTipo);
        // }
       },
       failure: function(msgFail){
         console.log(msgFail);
       }
     });
   }  // fin de la función para llenar el combo de tipos de documentos

   function procesar_placa_orden_servicio(rutaverificar ,
               rutadatosvehi ,
               cliente_id,
               nombre_completo_cliente){
      // 26dic2019
      // llamada cuando cambia el combo de placas de un cliente
      // Recibe 4 parámetros:
      //       rutaverificar la ruta que se debe llamar (ajax) para verificar
      //          que el cliente + vehiculo digitados no tengan una orden de servicio
      //          abierta actualmente (OrdenController@verificar_orden_servicio_abierta)
      //       rutadatosvehi la ruta que se debe llamar (ajax) si se escogió una placa
      //          (en este caso será OrdenController@buscar_vehiculo_orden_servicio)
      //       cliente_id: para hacer la verificación de orden abierta.
      //       nombre_completo_cliente: nombre completo del  cliente
      // Esta función hace lo siguiente:
      // 0) Si la opción escogida es 0 (crear nuevo vehículo) irá al
      //    proceso de creación.
      // Si es otra opción (una placa):
      //    1) Verificar que el cliente+vehiculo escogido no tenga una orde
      //       de servicio abierta en la tabla servicios.
      //    2) Si no tiene orden abierta: mostrar la info de la
      //       placa escogida, a los correspondientes inputtext
      //       del formulario, esto se hace llamando
      //       un AJAX que leera los datos de la placa - cliente de los que se trate.
      //       El AJAX devolverá un JSON de 7 columnas:
      //       marca modelo gama fec_soat fec_tecno fec_extintor kilom kilom_aceite

      var objSelect = document.getElementById('selectPlaca');
      var selectedOption = objSelect.options[objSelect.selectedIndex];
      var vehiculo_id_escogido = selectedOption.value;
      var placa_escogida = selectedOption.text;

      if(vehiculo_id_escogido == 0){
         // el usuario escogió: CREAR NUEVA PLACA:
         document.getElementById("idgrupo_placa").style.visibility = "visible";
         document.getElementById("placa").focus();
         // poner vacios en la info del vehículo:
         document.getElementById('id_marca').value = '';
         document.getElementById('id_modelo').value = '';
         document.getElementById('id_gama').value = '';
         document.getElementById('id_fec_soat').value = '';
         document.getElementById('id_fec_tecno').value = '';
         document.getElementById('id_fec_extintor').value = '';
         document.getElementById('id_kilom').value = '';
         document.getElementById('id_kilom_aceite').value = '';


      }else{
         document.getElementById("idgrupo_placa").style.visibility = "hidden";
         // document.getElementById("idnueva_placa").style.display = "none";
         //    1) Verificar que el cliente+vehiculo escogido no tenga una orde
         //       de servicio abierta en la tabla servicios.
         baseUrl1 = rutaverificar;   // OrdenController@buscar_vehiculo_orden_servicio
         $.ajax({
           url:baseUrl1 + '/' + cliente_id + '/' + vehiculo_id_escogido,
           type: "GET",
           success: function(data1){
               if(data1.length >= 1){
                   // significa que halló una orden abierta para el
                   // cliente+vehículo escogido por el cliente:
                   // poner vacios en la info del vehículo:
                   document.getElementById('id_marca').value = '';
                   document.getElementById('id_modelo').value = '';
                   document.getElementById('id_gama').value = '';
                   document.getElementById('id_fec_soat').value = '';
                   document.getElementById('id_fec_tecno').value = '';
                   document.getElementById('id_fec_extintor').value = '';
                   document.getElementById('id_kilom').value = '';
                   document.getElementById('id_kilom_aceite').value = '';
                   // hacer que en el select no aparezca el vehículo que
                   // tiene orden abierta:
                   combo = document.getElementById("selectPlaca");
                   opcion = document.createElement("option");
                   opcion.value = "";
                   opcion.text = "Seleccione el vehículo del cliente...";
                   opcion.selected = true;
                   opcion.disabled = true;
                   opcion.style.display = "none";
                   combo.appendChild(opcion);

                   alert('El vehículo: ' + placa_escogida + ', del cliente: ' + nombre_completo_cliente + ', está asignado a la orden de servicio número: ' + data1[0]['id'] + ', la cual aún no ha sido cerrada.\r\n\r\nPor favor verificar.');
               }else{
                  // llamada AJAX para obtener los datos de la placa escogida:
                  baseUrl2 = rutadatosvehi;   // OrdenController@buscar_vehiculo_orden_servicio
                  $.ajax({
                    url:baseUrl2 + '/' + vehiculo_id_escogido,
                    type: "GET",
                    success: function(data){
      					   // llega un array con los datos del vehículo escogido, por
                        // lo tanto se deben llenar los datos del vehículo en el
                        // formulario, PERO por si de pronto en la pantalla
                        // aparecen errores del jquery validator, primero hay
                        // que ocultarlos con las siguientes 2 instrucciones:
                        $("label.error").hide();
                        $(".error").removeClass("error");
                        // colocar los datos del vehículo en los input text, y
                        // adicionalmente llevar la placa al campo hidden del formulario:
      					   document.getElementById('id_marca').value = data[0]['marca'];
      					   document.getElementById('id_modelo').value = data[0]['modelo'];
      					   document.getElementById('id_gama').value = data[0]['gama'];
      					   document.getElementById('id_fec_soat').value = data[0]['fec_soat'];
      					   document.getElementById('id_fec_tecno').value = data[0]['fec_tecno'];
      					   document.getElementById('id_fec_extintor').value = data[0]['fec_extintor'];
      					   document.getElementById('id_kilom').value = data[0]['kilom'];
      					   document.getElementById('id_kilom_aceite').value = data[0]['kilom_aceite'];
      					   document.getElementById('placa').value = placa_escogida;
                    },	// fin del sucess del ajax interno (lee datos del vehículo escogido)
                    failure: function(msgFail){
      					   console.log('leer datos del vehículo escogido. Llegó al failure...');
      					   console.log(msgFail);
                    }	// fin del failure del ajax interno (lee datos del vehículo escogido)
                  });   // fin del ajax interno (lee datos del vehículo escogido)
               }  // fin del if de verificar orden abierta (respuesta del ajax externo)
           },   // fin del success del ajax externo (verificar orden abierta)
           failure: function(msgFail){
             console.log('revisar orden abierta, llegó al failure...');
             console.log(msgFail);
           }   // fin del failure del ajax externo (verificar orden abierta)
         });   // fin del ajax externo (verificar orden abierta)
      }  // fin del if que verifica opcion 0 o número distinto
   }

   // =============================================================================
   // 24ene2020
   function llenar_select_cod_postales(arr_postales){
      combo_postales = document.getElementById("selectCod_postal");
      // Borrar las opciones que puedan existir
      // en el select:
      while (combo_postales.length > 0) {
        combo_postales.remove(0);
      }
      // llenar las opciones del select cod postales con las
      // correspondientes al departamento-ciudad escogidos:
      for(var j = 0 ; j < arr_postales.length ;  j++){
         opcion_postales = document.createElement("option");
         opcion_postales.value = arr_postales[j];
         opcion_postales.text = arr_postales[j];
         combo_postales.appendChild(opcion_postales);
      }
   }







   // =============================================================================
   // =============================================================================
   // =============================================================================

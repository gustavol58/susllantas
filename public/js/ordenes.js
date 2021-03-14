function agregar_a_tabla_html_js(ruta , helper_asset){
   // parámetros recibidos:
   //    ruta: contiene OrdenController@leer_datos_producto_escogido
   //          nota: esta ruta será llamada con dos parámetros adicionales.
   //    helper_asset: contiene '{{asset("/")}}', el cual será
   //                   usado para indicar la ubicación de las imágenes
   //                   que se vayan a utilizar (ej iconos para la tabla html)
   // llamada cuando el usuario digita un producto y su cantidad
   // para agregarlo a los cais, hace lo siguiente:
   // 1) verificar que el usuario haya suministrado producto,
   //    y cantidad
   // 1b) verificar que el idproducto+produc_adic escogidos/digitados no estén
   //     grabados en la tabla html
   // 2) determinar los datos del producto escogido (nombre, cai,
   //    descripción y valor unitario), mediante una llamada AJAX.
   // 3) agregar los datos a la   tabla hmtl: idtablacais.

   // 1)
   // 03feb2020
   // debido al cambio para hacer el AUTOCOMPLETED:
   // var objProducto = document.getElementById("selectProductos");
   // var productoSeleccionado = objProducto.options[objProducto.selectedIndex];
   // var producto_id_escogido = productoSeleccionado.value;
   var producto_id_escogido;
   var canti = document.getElementById("id_canti").value;
   var objOperario = document.getElementById("selectOperarios");
   var producto_adic = document.getElementById('idspan_adic').innerHTML;
   var operarioSeleccionado = objOperario.options[objOperario.selectedIndex];
   var operario_id_escogido = operarioSeleccionado.value;

   if(document.getElementById("idproducto_escogido_hidden").value == ""
            || canti.length == 0){
            // || operario_id_escogido == 0 ){
      alert('Debe escoger un producto y digitar la cantidad')
   }else{
      // 1b) verificar que el idproducto+produc_adic escogidos/digitados no estén
      //     grabados en la tabla html
      producto_id_escogido = document.getElementById("idproducto_escogido_hidden").value;

      if(buscar_en_tabla_html(producto_id_escogido , producto_adic)){
         alert('El producto ya fue asignado, no se puede repetir.')
      }else{
         document.getElementById('idspan_adic').innerHTML = null;
         // 2)
         $.ajax({
            url: ruta + '/' + producto_id_escogido + '/' + operario_id_escogido,
            type: "GET",
            success: function(data){
               // llega un array de una sola fila, con la info del
               // producto y operario escogidos:
               //    id
               //    cai
               //    nombre
               //    precio
               //    operario
               // 3)
               var cai = data[0]['cai'];
               var descripcion = data[0]['nombre'];
               var vlr_unitario = data[0]['precio'];
               var operario = data[0]['operario'];
               var vlr_total = vlr_unitario * canti;
               var vlr_unitario_f = number_format_round(vlr_unitario , 2);
               var vlr_total_f = number_format_round(vlr_total , 2);
               var tr = generar_fila_tabla_html(producto_id_escogido , operario_id_escogido , canti , cai , descripcion , producto_adic , operario , vlr_unitario_f , vlr_total_f , helper_asset);
               $("#idtablacais").append(tr);
               document.getElementById("idinput_producto").value = "";
               document.getElementById("idproducto_escogido_hidden").value = "";
               document.getElementById("id_canti").value = "";
               llenar_select_operarios();   // se encuentra en formu_pedir_blade.php
            },
            failure: function(msgFail){
               console.log(msgFail);
            }
         });
      }  // fin del if: producto escogido con anterioridad
   }  // fin del if "debe suministrar producto y cantidad"
}

function buscar_en_tabla_html(producto_id , producto_adic){
   // 17feb2020
   // llega aqui cuando el usuario escoge el + (agregar un cai a la
   // tabla html al crear/modificar órdenes)
   // Verifica que el producto escogido (en conjunto con su descripción
   // adicional en caso de que sea repuesto/servicio), no esté ya
   // grabado en la tabla html:
// if(producto_adic == null){
//    alert('es null ini_' + producto_adic + '_fin')
//    producto_adic = "";
//    alert('ahora no es null sino vacio ini_' + producto_adic + '_fin');
// }else{
//    alert('NO es null ini_' + producto_adic + '_fin')
// }
// console.log('el producto id que llegó ini_' + producto_id + '_fin');
// console.log('el producto adic que llegó ini_' + producto_adic + '_fin');
// console.log(producto_id.length);
// console.log(producto_adic.length);

   var resul = false;
   var tabla_cais = document.getElementById('idtablacais');
   var celdas_una_fila;
   for(var i = 1 ; i < tabla_cais.rows.length ; i++){
      celdas_una_fila = tabla_cais.rows[i].getElementsByTagName('td');
// console.log('el producto id de la fila nro: ' + i + ', ini_' + celdas_una_fila[0].innerHTML + '_fin');
// console.log('el producto adic de la fila nro: ' + i + ',ini_' + celdas_una_fila[5].innerHTML + '_fin');
// console.log(celdas_una_fila[0].innerHTML.length);
// console.log(celdas_una_fila[5].innerHTML.length);
      if(celdas_una_fila[0].innerText == producto_id.trim()
         && celdas_una_fila[5].innerText == producto_adic.trim()){
            resul = true;
            break;
      }
   }
   return resul;
}

function buscar_y_modificar_en_tabla_html(producto_id , producto_adic , nueva_canti , nuevo_operario_id , nuevo_operario_nombre , helper_asset){
   // 17feb2020
   // llega aqui cuando el usuario dá click al botón GRABAR en el formulario
   // modal que permite modificar un CAI ( vista formu_pedir_cais.blade.php)
   // Recorre la tabla html y cuando encuentre el producto_id y producto_adic
   // que fueron modificados, procede a modificar la cantidad, el operario_id
   // y el operario_nombre, de acuerdo a los nuevos valores dados por el
   // usuario y que llegan a esta función como parámetros.

   var tabla_cais = document.getElementById('idtablacais');
   var celdas_una_fila;
   for(var i = 1 ; i < tabla_cais.rows.length ; i++){
      celdas_una_fila = tabla_cais.rows[i].getElementsByTagName('td');
      if(celdas_una_fila[0].innerText == producto_id.trim()
         && celdas_una_fila[5].innerText == producto_adic.trim()){
            tabla_cais.rows[i].cells[2].innerHTML = nueva_canti;
            tabla_cais.rows[i].cells[1].innerHTML = nuevo_operario_id;
            tabla_cais.rows[i].cells[6].innerHTML = nuevo_operario_nombre;

            // para modificar el botón edit con los nuevos valores (la
            // función editar_cai() y todos sus parámetros):
            var cai = tabla_cais.rows[i].cells[3].innerText;
            var descripcion = tabla_cais.rows[i].cells[4].innerText;

            tabla_cais.rows[i].cells[9].innerHTML = "<td><button style='background-color: white; border: 0;' onclick='javascript:editar_cai("+nueva_canti+","+nuevo_operario_id+",\""+cai+"\",\""+producto_adic+"\",\""+descripcion+"\","+producto_id+");' type='button'> <img alt='editar' height='35' src = '" + helper_asset + "/img/iconos/ico_editar.png'/> </button></td>";
            break;
      }
   }
}

function generar_fila_tabla_html(producto_id , operario_id , canti , cai , descripcion , producto_adic , operario , vlr_unitario_f , vlr_total_f , helper_asset){
   // 17feb2020
   // llamada desde dos puntos distintos:
   // a) función agregar_a_tabla_html_js() cuando se va a ADICIONAR un cai
   // b) función llenar_tabla_html() cuando se va a MODIFICAR un cai
   var resul = '';
   resul = "<tr><td style='display:none;'>" + producto_id + "</td><td style='display:none;'>" + operario_id + "</td><td style='text-align:center;'>" + canti + "</td><td>" + cai + "</td><td>" + descripcion + "</td><td>" + producto_adic + "</td><td>" + operario + "</td><td style='text-align:right;'>" + vlr_unitario_f + "</td><td style='text-align:right;'>" + vlr_total_f + "</td><td><button style='background-color: white; border: 0;' ";
   resul = resul + "onclick='javascript:editar_cai("+canti+","+operario_id+",\""+cai+"\",\""+producto_adic+"\",\""+descripcion+"\","+producto_id+");'";
   resul = resul + " type='button'> <img alt='editar' height='35' src = '" + helper_asset + "/img/iconos/ico_editar.png'/> </button></td><td><button style='background-color: white; border: 0;' class='borrar' type='button'> <img id='ico_eliminar' alt='eliminar' height='35' src = '" + helper_asset + "/img/iconos/ico_eliminar.png'/> </button></td></tr>";
   return resul;
}

function generar_orden_js(ruta_grabar , ruta_generar_pdf , ruta_regresar , cliente_id , vehiculo_id){
   // 08ene2020
   // llamada cuando el usuario presiona el botón GENERAR ORDEN DE SERVICIO
   // en la vista formu_pedir_cais.blade
   // Recibe 4 parámetros:
   //    ruta_grabar  será usada por el ajax:  OrdenController@guardar_servicios
   //    ruta_generar_pdf:  Para generar el pdf: '{{ url('/generar_pdf_orden_servicio') }}'
   //    ruta_regresar:  Para regresar a pedir otra cédula de cliente '{{ url('/orden_cedula') }}'
   //    cliente_id   cliente escogido por el usuario
   //    vehiculo_id  vehículo escogido por el usuario
   // Esta función hace lo siguiente:
   // 0) verifica que al menos se haya agregado un producto a la lista de productos
   // 1) recorre la tabla html para obtener el arr_salida, donde será
   // almacenada la info que después será grabada en la
   // tabla servicios_detalles. El arr_salida tendrá tantas filas
   // como productos digitó el usuario, y 9 columnas:
   //       0 producto_id
   //       1 operario_id
   //       2 canti
   //       3  cai
   //       4  nombre del producto
   //       5  adicionales al nombre del producto
   //       6  nombre del operario
   //       7  valor unitario
   //       8  valor total
   // NOTA: Las columnas 0 a la 2 serán usadas por el primer AJAX (grabar
   //       en tablas), las demás serán usadas por el AJAX para generar pdf
   // 2) hace un llamado AJAX al controlador que grabará en las tablas
   //    servicios y servicios_detalles. Le envia a este AJAX los
   //    dos parámetros recibidos y el arr_salida
   // 3) Al regresar del AJAX, llamar un segundo AJAX (ajax interno) el
   //    cual generará y mostrará el pdf. Le envia a esta AJAX el
   //    consecutivo de servicio que se acaba de grabar en la tabla
   //    servicios, los dos parámetros recibidos, y el arr_salida
   // 4) Regresar a pedir otra cédula para generar orden de servicio.

   // 1)

// console.log(ruta_grabar);
// console.log(ruta_generar_pdf);
// console.log(ruta_regresar);
// console.log(cliente_id);
// console.log(vehiculo_id);
// alert('revise oconsle...');

   // 0) Si no hay productos, muestra el mensaje de error y suspende el proceso de creación:
   if(document.getElementById('idtablacais').rows.length <= 1){
      alert('No se puede crear la orden porque no tiene productos asignados.');
      return false;
   };

   // 1)
   var arr_salida = crear_arr_salida_tabla_html();

   // 2)
   var token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
      url: ruta_grabar,
      type: "POST",
      data: {
         _token: token,
         cliente_id: cliente_id,
         vehiculo_id: vehiculo_id,
         arr_productos: JSON.stringify(arr_salida),
      },
      success: function(data){
         // 3)
         // console.log('en el success del ajax');
         var servicio_id = data.servicio_id;
         // ajax para generar el pdf, al regreso recibirá el nombre del pdf:
         $.ajax({
            url: ruta_generar_pdf,
            type: "POST",
            data: {
               _token: token,
               origen: 'crear',
               servicio_id: servicio_id,
               cliente_id: cliente_id,
               vehiculo_id: vehiculo_id,
               arr_productos: JSON.stringify(arr_salida),
            },
            success: function(data2){
               // console.log(data2);
               // abre el pdf en una nueva ventana y regresa a pedir otro
               // cliente:
               var getUrl = window.location;
               var arr_path = getUrl.pathname.split('/');
               // el segundo elemento del arr_path puede ser:
               //    llamar_vista_pedir_cais: en un localhost
               //    colibri(u otro nombre): en un hosting compartido
               // esa es la razón de ser del siguiente if:
               if(arr_path[1]=='llamar_vista_pedir_cais'){
                  var baseUrl = getUrl.protocol + '//' + getUrl.host ;
               }else{
                  var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] ;
               }
               var pdf = baseUrl +  '/' + data2.pdf_creado;
// console.log(getUrl);
// console.log(pdf);
// alert('revisar console...');
               window.open( pdf , '_blank');

               location.href = ruta_regresar;
               // var arr_path = getUrl.pathname.split('/');
               // // el segundo elemento del arr_path puede ser:
               // //    generar_orden: en un localhost
               // //    colibri(u otro nombre): en un hosting compartido
               // // esa es la razón de ser del siguiente if:
               // if(arr_path[1]=='generar_orden'){
               //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + 'orden_cedula';
               // }else{
               //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] + '/' + 'orden_cedula';
               // }
               // location.href= baseUrl;

            },
            failure: function(msgFail2){
               console.log('failure en el ajax generar pdf...')
               console.log(msgFail2);
            }
         });
      },
      failure: function(msgFail){
         console.log('failure en el ajax grabar servicios....')
         console.log(msgFail);
      }
   });
}

function modificar_orden_js(ruta_modificar , ruta_generar_pdf , ruta_regresar , num_orden , cliente_id , vehiculo_id){
   // 08ene2020
   // llamada cuando el usuario presiona el botón MODIFICAR ORDEN DE SERVICIO
   // en la vista formu_pedir_cais.blade
   // Recibe 6 parámetros:
   //    ruta_modificar  será usada por el ajax:  OrdenController@modificar_servicios
   //    ruta_generar_pdf:  Para generar el pdf: '{{ url('/generar_pdf_orden_servicio') }}'
   //    ruta_regresar:  Para regresar al grid de órdenes abiertas '{{ url('/ordenes_abiertas_listar') }}'
   //    num_orden  número de la orden (servicio_id) que fue modificada (sus cais)
   //    cliente_id
   //    vehiculo_id
   // Esta función hace lo siguiente:
   // 0) verifica que al menos se haya agregado un producto a la lista de productos
   // 1) llama la función que devuelve el arr_salida, en el cual será
   // almacenada la info que después será grabada en la
   // tabla servicios_detalles (luego de hacer una copia en
   // la tabla servicios_detalles_historias).
   // El arr_salida tendrá tantas filas
   // como productos hayan en la tabla html, y 9 columnas:
   //       0 producto_id
   //       1 operario_id
   //       2 canti
   //       3  cai
   //       4  nombre del producto
   //       5  adicionales al nombre del producto
   //       6  nombre del operario
   //       7  valor unitario
   //       8  valor total
   // NOTA: Las columnas 0 a la 2 serán usadas por el primer AJAX (grabar
   //       xxxxxxxxxen tablas), las demás serán usadas por el AJAX para generar pdf
   // 2) hace un llamado AJAX al controlador que grabará en las tablas
   //    servicios_detalles_historias, borrará y creará en servicios_detalles. Le
   //    envia a este AJAX el parámetro num_orden recibido y el arr_salida
   // 3) Al regresar del AJAX, llamar un segundo AJAX (ajax interno) el
   //    cual generará y mostrará el pdf. Le envia a esta AJAX el
   //    número de la orden recibida en el parámetro,  y el arr_salida
   // 4) Regresar al grid de órdenes abiertas.

   // 0) Si no hay productos, muestra el mensaje de error y suspende el proceso de modificación:
   if(document.getElementById('idtablacais').rows.length <= 1){
      alert('No se pueden grabar los cambios porque la orden de servicio no tiene productos asignados.');
      return false;
   };

   // 1)
   var arr_salida = crear_arr_salida_tabla_html();
   // 2)
   //    ruta_modificar  es:  OrdenController@modificar_servicios
   var token = $('meta[name="csrf-token"]').attr('content');
   $.ajax({
      url: ruta_modificar,
      type: "POST",
      data: {
         _token: token,
         num_orden: num_orden,
         arr_productos: JSON.stringify(arr_salida),
      },
      success: function(data){
         // data recibe un array unidimensional de 3 elementos:
         //    msg
         //    status
         //    error
         // por ser el success todos recibirán valores constantes (de éxito, ok)
         // console.log('en el success del ajax');
         // ajax para generar el pdf, al regreso recibirá el nombre del pdf:
         $.ajax({
            url: ruta_generar_pdf,
            type: "POST",
            data: {
               _token: token,
               origen: 'modificar',
               servicio_id: num_orden,
               cliente_id: cliente_id,
               vehiculo_id: vehiculo_id,
               arr_productos: JSON.stringify(arr_salida),
            },
            success: function(data2){
               // console.log(data2);
               // abre el pdf en una nueva ventana y regresa a pedir otro
               // cliente:
               var getUrl = window.location;
               var arr_path = getUrl.pathname.split('/');
               // el segundo elemento del arr_path puede ser:
               //    llamar_vista_pedir_cais o modificar_orden_abierta: en un localhost
               //    colibri(u otro nombre): en un hosting compartido
               // esa es la razón de ser del siguiente if:
               if(arr_path[1]=='llamar_vista_pedir_cais'
                     || arr_path[1]=='modificar_orden_abierta'){
                  var baseUrl = getUrl.protocol + '//' + getUrl.host ;
               }else{
                  var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] ;
               }
               var pdf = baseUrl +  '/' + data2.pdf_creado;
// console.log(getUrl);
// console.log(pdf);
// alert('revisar console...');
               window.open( pdf , '_blank');

               location.href = ruta_regresar;
               // var arr_path = getUrl.pathname.split('/');
               // // el segundo elemento del arr_path puede ser:
               // //    generar_orden: en un localhost
               // //    colibri(u otro nombre): en un hosting compartido
               // // esa es la razón de ser del siguiente if:
               // if(arr_path[1]=='generar_orden'){
               //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + 'orden_cedula';
               // }else{
               //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] + '/' + 'orden_cedula';
               // }
               // location.href= baseUrl;

            },
            failure: function(msgFail2){
               console.log('failure en el ajax generar pdf...')
               console.log(msgFail2);
            }
         });
      },
      failure: function(msgFail){
         console.log('failure en el ajax grabar servicios....')
         console.log(msgFail);
      }
   });
}

function cerrar_orden_js(ruta_modificar , ruta_cerrar_bd , ruta_generar_pdf , ruta_regresar , num_orden , cliente_id , vehiculo_id){
   // 27feb2020
   // llamada cuando el usuario presiona el botón CERRAR ORDEN DE SERVICIO
   // en la vista formu_pedir_cais.blade
   // Recibe 7 parámetros:
   //    ruta_modificar  para llenar el arr_salida que se usará para modificar los
   //                    cais antes de cerrar la orden (por si de pronto el usuario
   //                    modificó los cais justo antes de cerrar):  OrdenController@modificar_servicios
   //    ruta_cerrar_bd     será usada por el ajax:  OrdenController@cerrar_orden_bd
   //    ruta_generar_pdf:  Para generar el pdf: '{{ url('/generar_pdf_orden_servicio') }}'
   //    ruta_regresar:  Para regresar al grid de órdenes abiertas '{{ url('/ordenes_cerrar_listar') }}'
   //    num_orden  número de la orden (servicio_id) que será cerrada
   //    cliente_id
   //    vehiculo_id
   // Esta función hace lo siguiente:
   //    0) verifica que al menos se haya agregado un producto a la lista de productos
   //    1) Verifica que todos los cais tengan operario asignado
   //    2) Hacer el proceso de modificación de cais (por si el usuario
   //       modificó alguno)
   //    3) tabla servicios: hacer su campo abierta = false (0)
   //    4) Hacer la salida de inventario, consiste en agregar un registro por
   //       cada cai a la tabla inv_movimientos (con tipo: salida)
   //    5) Genera el pdf con la hora de cierre

   // 0) Si no hay productos, muestra el mensaje de error y suspende el proceso de cierre:
   if(document.getElementById('idtablacais').rows.length <= 1){
      alert('No se puede cerrar la orden porque no tiene productos asignados.');
      return false;
   };

   // 1) Si faltan operarios, muestra el mensaje de error y suspende el proceso de cierre:
   for (var fil=1 ; fil < document.getElementById('idtablacais').rows.length; fil++){
// console.log(document.getElementById('idtablacais').rows[fil].cells[1].innerHTML);
// console.log(document.getElementById('idtablacais').rows[fil].cells[6].innerHTML);
      if(document.getElementById('idtablacais').rows[fil].cells[6].innerHTML == 'OPERARIO NO ASIGNADO'){
         alert('No se pudo cerrar la orden porque no están todos los operarios asignados. Por favor verificar.');
         return false;
      }
   }

   // 2) Proceso de modificación de cais (por si el usuario modificó alguno):
   // lo primero es crear el arr_salida:
   var arr_salida = crear_arr_salida_tabla_html();
   // luego llama el ajax que hará el proceso de modificación de cais (por si el
   // usuario modificó alguno):
   var token = $('meta[name="csrf-token"]').attr('content');
   // ajax que modifica cais:
   $.ajax({
      url: ruta_modificar,
      type: "POST",
      data: {
         _token: token,
         num_orden: num_orden,
         arr_productos: JSON.stringify(arr_salida),
      },
      success: function(data1){
         // el proceso de modificar CAIS se realizó correctamente.
         // data1 recibe un array unidimensional de 3 elementos:
         //    msg
         //    status
         //    error
         // por estar en el success, todos llegarán con valores constantes (de éxito, ok)

         // ajax que cierra la orden en la base de datos, este cierre consiste
         // en los puntos 3 y 4 documentados al principio de esta función:
         $.ajax({
            url: ruta_cerrar_bd,       // OrdenController@cerrar_orden_bd,
            type: "POST",
            data: {
               _token: token,
               num_orden: num_orden,
               arr_productos: JSON.stringify(arr_salida),
            },
            success: function(data2){
               // los procesos de los puntos 3 y 4 se hicieron correctamente.
               // data2 recibe un array unidimensional de 3 elementos:
               //    msg
               //    status
               //    error
               // ajax para generar el pdf, al regreso recibirá el nombre del pdf:
// alert('enseguida ejecutará el ajax para generar el pdf....');
// console.log(data2);
// alert('ver consoel....');
               $.ajax({
                  url: ruta_generar_pdf,     // {{ url('/generar_pdf_orden_servicio') }}'
                  type: "POST",
                  data: {
                     _token: token,
                     origen: 'cerrar',
                     servicio_id: num_orden,
                     cliente_id: cliente_id,
                     vehiculo_id: vehiculo_id,
                     arr_productos: JSON.stringify(arr_salida),
                  },
                  success: function(data3){
                     // abre el pdf en una nueva ventana y regresa al
                     // gridview CERRAR ÓRDENES:
                     var getUrl = window.location;
                     var arr_path = getUrl.pathname.split('/');
                     // el segundo elemento del arr_path puede ser:
                     //    cerrar_orden: en un localhost
                     //    colibri(u otro nombre): en un hosting compartido
                     // esa es la razón de ser del siguiente if:
                     if(arr_path[1]=='cerrar_orden'){
                        var baseUrl = getUrl.protocol + '//' + getUrl.host ;
                     }else{
                        var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] ;
                     }
                     var pdf = baseUrl +  '/' + data3.pdf_creado;
                     window.open( pdf , '_blank');

                     location.href = ruta_regresar;     // '{{ url('/ordenes_cerrar_listar') }}'
                  },
                  failure: function(msgFail3){
                     console.log('failure en el ajax generar pdf después de cerrar orden...')
                     console.log(msgFail3);
                  },
               });  // fin del ajax que genera el pdf


            },
            failure: function(msgFail2){
               console.log('failure en el ajax cerrar orden y salir inventarios...')
               console.log(msgFail2);
            },
         });  // fin del ajax que cierra la orden y sale inventarios
      },
      failure: function(msgFail1){
         console.log('failure en el ajax modificar cais....')
         console.log(msgFail1);
      },
   });  // fin del ajax que modifica cais



// *************************************************************************************
// , donde será
// almacenada la info que después será grabada en la
// tabla servicios_detalles (luego de hacer una copia en
// la tabla servicios_detalles_historias).
//
// 2) hace un llamado AJAX al controlador que grabará en las tablas
//    servicios_detalles_historias, borrará y creará en servicios_detalles. Le
//    envia a este AJAX el parámetro num_orden recibido y el arr_salida
// 3) Al regresar del AJAX, llamar un segundo AJAX (ajax interno) el
//    cual generará y mostrará el pdf. Le envia a esta AJAX el
//    número de la orden recibida en el parámetro,  y el arr_salida
// 4) Regresar al grid de órdenes abiertas.

//    // 2)
//    //    ruta_modificar  es:  OrdenController@modificar_servicios
//    var token = $('meta[name="csrf-token"]').attr('content');
//    $.ajax({
//       url: ruta_modificar,
//       type: "POST",
//       data: {
//          _token: token,
//          num_orden: num_orden,
//          arr_productos: JSON.stringify(arr_salida),
//       },
//       success: function(data){
//          // data recibe un array unidimensional de 3 elementos:
//          //    msg
//          //    status
//          //    error
//          // por ser el success todos recibirán valores constantes (de éxito, ok)
//          // console.log('en el success del ajax');
//          // ajax para generar el pdf, al regreso recibirá el nombre del pdf:
//          $.ajax({
//             url: ruta_generar_pdf,
//             type: "POST",
//             data: {
//                _token: token,
//                servicio_id: num_orden,
//                cliente_id: cliente_id,
//                vehiculo_id: vehiculo_id,
//                arr_productos: JSON.stringify(arr_salida),
//             },
//             success: function(data2){
//                // console.log(data2);
//                // abre el pdf en una nueva ventana y regresa a pedir otro
//                // cliente:
//                var getUrl = window.location;
//                var arr_path = getUrl.pathname.split('/');
//                // el segundo elemento del arr_path puede ser:
//                //    llamar_vista_pedir_cais o modificar_orden_abierta: en un localhost
//                //    colibri(u otro nombre): en un hosting compartido
//                // esa es la razón de ser del siguiente if:
//                if(arr_path[1]=='llamar_vista_pedir_cais'
//                      || arr_path[1]=='modificar_orden_abierta'){
//                   var baseUrl = getUrl.protocol + '//' + getUrl.host ;
//                }else{
//                   var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] ;
//                }
//                var pdf = baseUrl +  '/' + data2.pdf_creado;
// // console.log(getUrl);
// // console.log(pdf);
// // alert('revisar console...');
//                window.open( pdf , '_blank');
//
//                location.href = ruta_regresar;
//                // var arr_path = getUrl.pathname.split('/');
//                // // el segundo elemento del arr_path puede ser:
//                // //    generar_orden: en un localhost
//                // //    colibri(u otro nombre): en un hosting compartido
//                // // esa es la razón de ser del siguiente if:
//                // if(arr_path[1]=='generar_orden'){
//                //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + 'orden_cedula';
//                // }else{
//                //    var baseUrl = getUrl.protocol + '//' + getUrl.host + '/' + arr_path[1] + '/' + 'orden_cedula';
//                // }
//                // location.href= baseUrl;
//
//             },
//             failure: function(msgFail2){
//                console.log('failure en el ajax generar pdf...')
//                console.log(msgFail2);
//             }
//          });
//       },
//       failure: function(msgFail){
//          console.log('failure en el ajax grabar servicios....')
//          console.log(msgFail);
//       }
//    });
// *************************************************************************************
}

function crear_arr_salida_tabla_html(){
   // función llamada desde otras dos funciones de este mismo js:
   //    a) modificar_orden_js
   //    b) cerrar_orden_js
   // esta función recorre la tabla html y retorna el arr_salida
   // que tendrá tantas filascomo productos hayan en la tabla html,
   // y 9 columnas:
   //             0 producto_id
   //             1 operario_id
   //             2 canti
   //             3  cai
   //             4  nombre del producto
   //             5  adicionales al nombre del producto
   //             6  nombre del operario
   //             7  valor unitario
   //             8  valor total
   var arr_salida = [[]];
   var fila_salida = 0;
   for (var fil=1 ; fil < document.getElementById('idtablacais').rows.length; fil++){
      arr_salida[fila_salida] = [];
      arr_salida[fila_salida][0] = document.getElementById('idtablacais').rows[fil].cells[0].innerHTML;
      arr_salida[fila_salida][1] = document.getElementById('idtablacais').rows[fil].cells[1].innerHTML;
      arr_salida[fila_salida][2] = document.getElementById('idtablacais').rows[fil].cells[2].innerHTML;
      arr_salida[fila_salida][3] = document.getElementById('idtablacais').rows[fil].cells[3].innerHTML;
      arr_salida[fila_salida][4] = document.getElementById('idtablacais').rows[fil].cells[4].innerHTML;
      arr_salida[fila_salida][5] = document.getElementById('idtablacais').rows[fil].cells[5].innerHTML;
      arr_salida[fila_salida][6] = document.getElementById('idtablacais').rows[fil].cells[6].innerHTML;
      arr_salida[fila_salida][7] = document.getElementById('idtablacais').rows[fil].cells[7].innerHTML;
      arr_salida[fila_salida][8] = document.getElementById('idtablacais').rows[fil].cells[8].innerHTML;
      fila_salida++;
   };
   return arr_salida;
}

function number_format_round(amount, decimals) {
    // https://gist.github.com/jrobinsonc/5718959
    // amount puede ser un número o un string
    //        si es un string solo puede contener números y un punto
    //        si es un número puede ser positivo o negativo, o cero
    // decimals es el número de decimales a retornar, tener en
    //          cuenta que si hay menos de los que llegan en
    //          amount, la función redondeará (el redondeo
    //          normal, es decir: a 1 si es >=0.5)
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto

    decimals = decimals || 0; // por si la variable no fue fue pasada

    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0)
        return parseFloat(0).toFixed(decimals);

    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);

    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;

    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

    return amount_parts.join('.');
}

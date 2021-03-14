{{--
   genera el pdf, es llamado (como loadview, no como render) desde:
      a)  OrdenController@generar_pdf_orden_servicio()
   Recibe 27 parámetros
   el último de los cuales es un array bidimensional de 6 columnas:
      fec_entrada_dia
      fec_entrada_mes
      fec_entrada_an
      hor_entrada
      fec_cierre_dia
      fec_cierre_mes
      fec_cierre_an
      hor_cierre
      consecutivo
      cliente
      doc_num
      telefonos
      cumple
      direccion
      ciudad
      email
      marca
      gama
      modelo
      placa
      kilom
      kilom_aceite
      fec_soat
      fec_tecno
      fec_extintor
      asesor
      arr_productos  (array bidimensional de 6 columnas)
--}}

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Document</title>
        <style>
           body {
              font-size: 0.9em;
           }
           /* html {
           margin: 0;
           }
           body {
           font-family: "Times New Roman", serif;
           margin: 45mm 8mm 2mm 8mm;
           } */
           /* tr .sub_cabezote td{
              opacity: 0.5;
              text-align: center;
           } */
           /* tr .consecutivo td{
              font-size: 1.3em !important;
              text-align: center;
           } */
           .tabla_iniciales {
              /* border: 2px solid red; */
             border-collapse: collapse;
           }
           .tabla_iniciales  tr  th  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             background-color: #C0C0C0;
             text-align: center;
             font-size: 0.7em;
           }
           .tabla_iniciales  tr  td  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             font-size: 1.3em !important;
             text-align: center;
           }
           .tabla_cais {
             border-collapse: collapse;
             border-radius: 15px;
           }
           .tabla_cais  tr  th  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             background-color: #C0C0C0;
             text-align: center;
           }
           .tabla_cais  tr  td  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
           }
           .revi_vehicular{
              text-align: center;
              background-color: #C0C0C0;
           }
           .tabla_revision {
              /* border: 2px solid red; */
             border-collapse: collapse;
           }
           .tabla_revision  tr  th  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             background-color: #C0C0C0;
             text-align: center;
             /* font-size: 0.7em; */
             font-size: 0.7em;

           }
           .tabla_revision  tr  td  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             /* font-size: 1.3em !important;
             text-align: center; */
           }
           input[type="checkbox"]{
               vertical-align: top;
           }
           .tabla_pie {
              /* border: 2px solid red; */
             border-collapse: collapse;
           }
           .tabla_pie  tr  th  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
             background-color: #C0C0C0;
             text-align: center;
           }
           .tabla_pie  tr  td  {
              /* border: 2px solid yellow; */
             border: 1px solid black;
           }
           .pie_firmas{
               font-size: 1em;
               font-weight: bold;
               text-align: center;
           }
       </style>
    </head>
    <body>
      <table>
         <tr>
            <td colspan=12>
               {{-- 24ene2020
               aun esta pendiente decidir si hay que usar el width="718" o no,
               todo depende de lo que se decida con la impresión del cabezote  --}}
               <img src="{{asset('img/orden_servicio/cabezote.png')}}" width="718">
            </td>
         </tr>
         <tr>
            <td colspan=3>
               <table width=100% class="tabla_iniciales">
                  <tr>
                     <th colspan=3>
                        {{-- FECHA (D&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;A) --}}
                        ENTRADA (Día Mes Año)
                     </th>
                  </tr>
                  <tr>
                     <td>
                        {{$fec_entrada_dia}}
                     </td>
                     <td>
                        {{$fec_entrada_mes}}
                     </td>
                     <td>
                        {{$fec_entrada_an}}
                     </td>
                  </tr>
               </table>
            </td>
            <td colspan=2>
               <table width=100% class="tabla_iniciales">
                  <tr>
                     <th>
                        HORA DE ENTRADA
                     </th>
                  </tr>
                  <tr>
                     <td>
                        {{$hor_entrada}}
                     </td>
                  </tr>
               </table>
            </td>

            <td colspan=3>
               <table width=100% class="tabla_iniciales">
                  <tr>
                     <th colspan=3>
                        {{-- FECHA (D&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;A) --}}
                        SALIDA (Día Mes Año)
                     </th>
                  </tr>
                  <tr>
                     <td>
                        {{$fec_cierre_dia}}
                     </td>
                     <td>
                        {{$fec_cierre_mes}}
                     </td>
                     <td>
                        {{$fec_cierre_an}}
                     </td>
                  </tr>
               </table>
            </td>
            <td colspan=2>
               <table width=100% class="tabla_iniciales">
                  <tr>
                     <th>
                        HORA DE SALIDA
                     </th>
                  </tr>
                  <tr>
                     <td>
                        {{$hor_cierre}}
                     </td>
                  </tr>
               </table>
            </td>
            <td colspan=2>
               <table width=100% class="tabla_iniciales">
                  <tr>
                     <th>
                        ORDEN DE SERVICIO
                     </th>
                  </tr>
                  <tr>
                     <td>
                        {{$consecutivo}}
                     </td>
                  </tr>
               </table>
            </td>
         </tr>

         <tr>
            <td colspan=7>
               CLIENTE: <b>{{$cliente}}</b>
            </td>
            <td colspan=5>
               NIT o C.C.: <b>{{$doc_num}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=7>
               TELÉFONOS: <b>{{$telefonos}}</b>
            </td>
            <td colspan=5>
               CUMPLEAÑOS: <b>{{$cumple}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=7>
               DIRECCIÓN: <b>{{$direccion}}</b>
            </td>
            <td colspan=5>
               MUNICIPIO: <b>{{$ciudad}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=12>
               E-MAIL: <b>{{$email}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=3>
               MARCA: <b>{{$marca}}</b>
            </td>
            <td colspan=3>
               GAMA: <b>{{$gama}}</b>
            </td>
            <td colspan=3>
               MODELO: <b>{{$modelo}}</b>
            </td>
            <td colspan=3>
               PLACA: <b>{{$placa}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=5>
               KILOMETRAJE: <b>{{$kilom}}</b>
            </td>
            <td colspan=7>
               KMS PROX CAMBIO ACEITE: <b>{{$kilom_aceite}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=4>
               FECHA SOAT (D/M/A): <br><b>{{$fec_soat}}</b>
            </td>
            <td colspan=4>
               TECNO MECÁNICA (D/M/A): <br><b>{{$fec_tecno}}</b>
            </td>
            <td colspan=4>
               FECHA EXTINTOR (D/M/A): <br><b>{{$fec_extintor}}</b>
            </td>
         </tr>
         <tr>
            <td colspan=12>
               <table  width=100%  class="tabla_cais">
                  <tr>
                     <th width=5% colspan=1>CANT</th>
                     <th colspan=2>CAI</th>
                     {{-- <th colspan=2>CAI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.</th> --}}
                     <th colspan=3>DESCRIPCIÓN</th>
                     <th colspan=2>&nbsp;</th>
                     <th colspan=2>OPERARIO</th>
                     <th colspan=1>VR. UNIT.</th>
                     <th colspan=1>VR. TOTAL</th>
                  </tr>

                  @foreach ($arr_productos as $un_producto)
                     <tr>
                        <td width=5% colspan=1 style='text-align:center;'>{{ $un_producto[2] }}</td>
                        <td colspan=2>{{ $un_producto[3] }}</td>
                        <td colspan=3>{{ $un_producto[4] }}</td>
                        <td colspan=2>{{ $un_producto[5] }}</td>
                        <td colspan=2>{{ $un_producto[6] }}</td>
                        <td colspan=1 style='text-align:right;'>{{ $un_producto[7] }}</td>
                        <td colspan=1 style='text-align:right;'>{{ $un_producto[8] }}</td>
                     </tr>
                  @endforeach


                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=1>&nbsp;</td>
                     <td colspan=1>&nbsp;</td>
                  </tr>


                  {{-- <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr>
                  <tr>
                     <td width=5% colspan=1>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=3>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                     <td colspan=2>&nbsp;</td>
                  </tr> --}}
               </table>
            </td>
         </tr>
         <tr>
            <td class="revi_vehicular" colspan=12>
               REVISIÓN VEHICULAR
            </td>
         </tr>
         <tr>
            <td colspan=12>
               <table width=100% class="tabla_revision">
                  <tr>
                     <th>
                        1. REVISIÓN DE NIVELES
                     </th>
                     <th>
                        2. INSPECCIÓN LLANTAS
                     </th>
                     <th>
                        3. FILTRACIÓN
                     </th>
                     <th>
                        4. REVISIÓN ELÉCTRICA
                     </th>
                     <th>
                        5. INSPECCIÓN VISUAL
                     </th>
                  </tr>
                  <tr>
                     <td>
                        <input type="checkbox"> Aceite de motor
                     </td>
                     <td>
                        <input type="checkbox"> Presión
                     </td>
                     <td>
                        <input type="checkbox"> Filtro de aceite
                     </td>
                     <td>
                        <input type="checkbox"> Batería
                     </td>
                     <td>
                        <input type="checkbox"> Plumillas
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <input type="checkbox"> Refrigerante
                     </td>
                     <td>
                        <input type="checkbox"> Rotación
                     </td>
                     <td>
                        <input type="checkbox"> Filtro de aire
                     </td>
                     <td>
                        <input type="checkbox"> Luces
                     </td>
                     <td>
                        <input type="checkbox"> Sistema de suspensión
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <input type="checkbox"> Aceite de transmisión
                     </td>
                     <td>
                        <input type="checkbox"> Tapa válvulas
                     </td>
                     <td>
                        <input type="checkbox"> Filtro de combustible
                     </td>
                     <td>
                        <input type="checkbox"> Alternador
                     </td>
                     <td>
                        <input type="checkbox"> Líquido de frenos
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <input type="checkbox"> Aceite de caja
                     </td>
                     <td>
                        <input type="checkbox"> Repuesto
                     </td>
                     <td>
                        <input type="checkbox"> Filtro de aire acondicionado
                     </td>
                     <td>
                        <input type="checkbox"> Arranque
                     </td>
                     <td>
                        <input type="checkbox"> Fugas del sistema
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
         <tr>
            <td colspan=12>
               <table width=100% class="tabla_pie">
                  <tr>
                     <td style="vertical-align: top;" width=40%>Observaciones</td>
                     <td class="pie_firmas" width=20%><br><br> ________________<br>TÉCNICO</td>
                     <td class="pie_firmas" width=20%><br>{{$asesor}}<br> ________________<br>ASESOR</td>
                     <td class="pie_firmas" width=20%><br><br> ________________<br>FIRMA CLIENTE</td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>





    </body>
</html>

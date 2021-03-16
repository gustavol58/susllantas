<div>
    <div class="bg-white border rounded border-gray-300 m-4 p-5 text-gray-600">
        <div class="">
            {{-- <h1 class="font-black text-4xl uppercase">{{$titulo}}</h1> --}}
        </div>
        <div class="my-4">
            {{-- <a href="{{route('crear-recaudos')}}">
                <button type="button"  class="bg-blue-600 hover:bg-blue-700 focus:bg-blue-700 text-white rounded-lg px-3 py-3 font-semibold"><svg class="inline-block align-bottom  text-white fill-current w-6 h-6 " xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V8z" clip-rule="evenodd"/></svg> Nuevo recaudo</button>
            </a> --}}
        </div>

        {{-- Registros: --}}
        Recaudos que pueden ser modificados por el usuario:
        <div class="overflow-hidden overflow-x-scroll">
            {{ $clientes->links() }}
            <table class="table-fixed ">
                {{-- títulos de columna - botón ordenar en cada una --}}
                {{-- <thead class="justify-between">
                    <tr class="bg-green-500">
                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.id')">Nro</button>
                                </div>
                                {{-- @if($ordenar_campo == 'rec.id' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.id' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                  --}}
                            {{-- </div>
                        </th> --}}

                        {{-- <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.estado')">Estado</button>
                                </div>
                                {{-- @if($ordenar_campo == 'rec.estado' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.estado' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                  --}}



                        {{-- <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('cli.nom_cliente')">Cliente</button>
                                </div>
                                @if($ordenar_campo == 'cli.nom_cliente' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'cli.nom_cliente' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>   

                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1 ">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.valor')">Valor</button>
                                </div>
                                @if($ordenar_campo == 'rec.valor' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.valor' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif 
                            </div>
                        </th>

                        <th class="border border-l-1 sticky top-0">
                            <div class="flex">
                                <div class="flex-1">
                                    <span class="text-white font-bold bg-green-500 border-green-500 rounded">Foto</span>
                                </div>
                            </div>
                        </th>                        

                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.tipo')">Tipo</button>
                                </div>
                                @if($ordenar_campo == 'rec.tipo' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.tipo' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>
                        
                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.fec_pago')">Fec&nbsp;pago</button>
                                </div>
                                @if($ordenar_campo == 'rec.fec_pago' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.fec_pago' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                   
                            </div>
                        </th>                    

                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('rec.obs')">Observaciones</button>
                                </div>
                                @if($ordenar_campo == 'rec.obs' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'rec.obs' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                 
                            </div>
                        </th>

                        <th class="border border-l-1">
                            <div class="flex">
                                <div class="flex-1">
                                    <button class="text-white font-bold" type="button" wire:click="ordenar('usu.name')">Ingresado por</button>
                                </div>
                                @if($ordenar_campo == 'usu.name' && $ordenar_tipo == ' asc') 
                                    <div class="bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h5a1 1 0 000-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM13 16a1 1 0 102 0v-5.586l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 101.414 1.414L13 10.414V16z"></path></svg>
                                    </div>
                                @endif 
                                @if($ordenar_campo == 'usu.name' && $ordenar_tipo == ' desc') 
                                    <div class=" bg-blue-500 text-white ml-4">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3 3a1 1 0 000 2h11a1 1 0 100-2H3zM3 7a1 1 0 000 2h7a1 1 0 100-2H3zM3 11a1 1 0 100 2h4a1 1 0 100-2H3zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"></path></svg>
                                    </div>
                                @endif                                  
                            </div>
                        </th> --}}
            
                    </tr>
                </thead> --}}

                {{-- filtros y cuerpo de la tabla  --}}
                <tbody class="bg-gray-200">
                    {{-- filtros  --}}
                    {{-- <tr>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="nro">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="estado">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="categoria">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="cliente">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="valor">
                        </td>
                        <td>
                            &nbsp;
                        </td>                         
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="tipo">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="fec_pago">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="obs">
                        </td>
                        <td>
                            <input class="w-full mt-1 border rounded border-gray-300" type="text" wire:model="usu_ingreso">
                        </td>
                    </tr> --}}

                    {{-- cuerpo de la tabla  --}}
                    @foreach ($clientes as $cliente)
                        <tr class="bg-white border-4 border-gray-200">
                            <td class="border border-gray-300">
                                <span>{{$cliente->ciudad}}</span>
                            </td>

                            {{-- <td class="border border-gray-300">
                                <span>{{$recaudo->estado_texto}}</span>
                            </td>   

                            <td class="border border-gray-300">
                                <span>{{$recaudo->categoria_texto}}</span>
                            </td>                              

                            <td class="border border-gray-300">
                                <span>{{$recaudo->nom_cliente}}</span>
                            </td>   

                            <td class="border border-gray-300 text-right">
                                <span>{{number_format($recaudo->valor,0)}}</span>
                            </td>

                            <td class="border border-gray-300 text-center">
                                @if ($recaudo->foto_existe_nombre == '')
                                    <span>&nbsp;</span>
                                @else
                                    <button type="button" wire:click="mostrar_modal_foto('{{$recaudo->foto_existe_nombre_path}}')" >
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg> 
                                    </button>
                                @endif
                            </td>                              

                            <td class="border border-gray-300">
                                <span>{{$recaudo->tipo_texto}}</span>
                            </td>                            

                            <td class="border border-gray-300">
                                <span>{{$recaudo->fec_pago}}</span>
                            </td>

                            <td class="border border-gray-300">
                                <span>{{$recaudo->obs}}</span>
                            </td>


                            <td class="border border-gray-300">
                                <span>{{$recaudo->usu_ingreso}}</span>
                            </td>

                            <td class="border border-gray-300 px-4 text-yellow-500">
                                <a href="{{route('modificar-recaudo' , ['id' => $recaudo->nro])}}">
                                    <span><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path></svg></span>
                                </a>
                            </td>

                            @if(Auth::user()->hasRole('admin'))
                                <td class="border border-gray-300 px-4 text-red-500">
                                    <a href="" onclick="alert('proceso en construcción...');">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                    </a>
                                </td>
                            @endif --}}

                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>

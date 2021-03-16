@extends('layouts.appnolive')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- <div class="card"> -->
                <!-- <div class="card-header">Dashboard</div> -->

                <!-- <div class="card-body"> -->
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    {{-- <center> --}}
                      {{-- <img id="graficosini" alt="Pantalla inicial" width="800" height="600" --}}
                      <img id="graficosini" alt="Pantalla inicial" height="416"
                            src = "{{ asset('img/graficos_ini.png') }}"
                       />
                   {{-- </center> --}}
                <!-- </div> -->
            <!-- </div> -->
        </div>
    </div>
</div>
@endsection

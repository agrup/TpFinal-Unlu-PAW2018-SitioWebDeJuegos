@extends('layouts.app')


@section('content')
	<?php 
		setlocale(LC_TIME, 'spanish');
		Carbon\Carbon::setLocale('es');
		Carbon\Carbon::setUtf8(true);

		$fechaRegC = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$user->created_at);
		$fechaReg=$fechaRegC->formatLocalized('%d-%m-%Y');
	?>
    <div class="row">
        <div class="userAvatar">
            <img src="{{ asset($user->userAvatarPath()) }} " class="imgAvatar">
        </div>
        <div class="userData">
            @if(Auth::id() == $user->id)
                @if(!$user->isCreador())
                    <div class="devButton">
                        <button class="button" ><a href="{{ url('register/dev/'. $user->id) }}"><b>Registrarse como Desarrollador</b></a></button>
                    </div>
                @else
                    <div class="devButton">
                        <button class="button"><a href="{{ url('dev/'. $user->id) }}"><b>Perfil Desarrollador</b></a></button>
                    </div>
                @endif
                <div class="devButton">
                    <button class="button"><a href="{{ url('user/edit/'. $user->id) }}"><b>Editar Perfil</b></a></button>
                </div>


            @endif
            <h2>{{ $user->name}}</h2>
            <h3>Fecha de Registro: {{ $fechaReg }}</h3>
            @if (count($user->jugadas)>0)
                <h3>Última Actividad</h3>
                @php
                    $ultimoJuego = $user->ultimoJuegoJugado();
                    $fechaUltimaJugada = $user->fechaUltimaJugada();
                @endphp
                <h2><a href="{{url('game/'. $ultimoJuego->nombre_server)}}">{{$ultimoJuego->titulo}}</a> hace {{ $fechaUltimaJugada->diffForHumans() }}</h2>
            @endif

        </div>
    </div>
    <div class="row">
        <div class="columnActivity">
            <h2>Actividad Reciente</h2>
            <table>
                @foreach ($user->jugadas as $jugada)
                    <tr>
                        <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$jugada->created_at)->diffForHumans() }}</td>
                        <td><a href="{{url('game/'. $jugada->juego->nombre_server)}}">{{$jugada->juego->titulo}}</a>
                        </td>
                        <td>Puntaje: {{ $jugada->puntaje }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="columnFavoritos">
            <h2>Favoritos</h2>
            <ul>
                @foreach ($user->favoritos as $juegoFav)
                    @php
                        $rating = $user->getRating($juegoFav);
                        if($rating==\App\User::SIN_RATING){
                            $rating='Sin rating';
                        }
                    @endphp
                    <li>
                        <div class="profileFavorito">
                            <img src="{{ asset($juegoFav->getRutaAvatar()) }}">
                            <div class="datos-fav">
                                <h3><a href="{{url('game/'. $juegoFav->nombre_server)}}">{{ $juegoFav->titulo }}</a></h3>

                                <p>Rating global: {{ $juegoFav->valoracion_promedio }}</p>
                                <p>Rating de {{$user->name}}:{{ $rating }}</p>
                                <p>Máximo Puntaje: {{$user->getMaxPuntaje($juegoFav) }}</p>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

		</div>
	</div>
@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/profile.css') }}">
@endsection
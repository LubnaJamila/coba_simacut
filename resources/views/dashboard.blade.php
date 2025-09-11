@extends('layouts.app')

@section('content')
    <h2>Selamat datang, {{ $user->name }}!</h2>
    <p>Ini halaman dashboard, menu di sidebar menyesuaikan role user.</p>
@endsection

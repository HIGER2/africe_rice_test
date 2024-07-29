

@extends('layouts.app')

{{--
@section('head')
@endsection --}}

@section("content")

<section class="home">
@include('includes.header')
 <div class="container">
    {{-- {{$formData}} --}}
         <div id="app">
             <home-page
             :type="{{json_encode($type)}}"
             :employee="{{json_encode($employee)}}"
             :data="{{json_encode($formData)}}"
             />
        </div>
</div>
</section>

@endsection

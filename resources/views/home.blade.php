

@extends('layouts.app')

{{--
@section('head')
@endsection --}}

@section("content")

<section class="home">
@include('includes.header-home')
 <div class="container">
    {{-- {{$formData->children->count()}} --}}
         <div id="app">
             <home-page
             :currency="{{json_encode($currency)}}"
             :type="{{json_encode($type)}}"
             :employee="{{json_encode($employee)}}"
             :data="{{json_encode($formData)}}"
             />
        </div>
</div>
</section>

@endsection

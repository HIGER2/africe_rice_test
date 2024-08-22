@extends('layouts.app')
@section('content')
@include('includes.header-home')

<section class="config">
    <div class="container">
        {{-- {{$listeFilter}} --}}
        {{-- {{ htmlspecialchars($listeFilter)}} --}}
        {{-- {{$singleEmail}} --}}
         @if (session('success'))
                <div class="alert alert-success">
                    {{session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="headers">
                <h5>Email list</h5>
                <button data-bs-toggle="modal" data-bs-target="#exampleModal1"> add new email</button>
            </div>
        <div class="contentTable">

            @foreach ($listeFilter as $data )
            <div class="parentContent">
                <h6>{{$data->value }}</h6>
                <div class="content">
                   @foreach ($data->emails as $email )
                    <div class="elementContent">
                        <span>{{$email->email}}</span>
                        {{-- <a  href="/service-email/{{ $email->id }}">
                            <i class="uil uil-pen"></i>
                        </a> --}}
                        <form action="{{ route('service.email.destroy', $email->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this email?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="uil uil-trash"></i>
                            </button>
                        </form>

                    </div>
                   @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>


    <div class="modal" id="exampleModal1" aria-hidden="true">
        <div class="modal-content">
            <div class="card">
                <form class="form-email" action="{{ route('service.email') }}" method="post" >
                    @csrf
                    <input type="hidden" name="id" value="{{$singleEmail ? $singleEmail->id : ''}}" id="form_action" value="">
                       {{-- <h5>  {{isset($singleEmail ) ? "Update  email" : "Add new email"}} </h5> --}}
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Display error messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif
                        <div class="form-group">
                                <div class="form-group">
                                    <label for="stc">Select department</label>
                                    {{-- {{$services}} --}}
                                    <select name="service" id="" >
                                        <option value="">-- Select department --</option>
                                        @foreach ($services as $data )
                                            {{-- {{$data}} --}}
                                            <option value="{{ $data->id }}" @selected($singleEmail && $singleEmail->service == $data->id)>{{ $data->value }}</option>
                                            {{-- <option value="{{$data->id}}" @selected($singleEmail->service == $data->id ? true : false)>{{$data->value}}</option> --}}
                                        @endforeach
                                    </select>
                                    @error('service')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror

                                </div>

                                <div class="form-group">
                                    <label for="stc">department email</label>
                                    <input type="text" name="email" value="{{$singleEmail  ? $singleEmail->email : ''}}" placeholder="department email">
                                </div>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                        </div>

                        <button type="submit" class="login-button" >
                            {{$singleEmail ? "Update" : "Submit"}}
                        </button>
                </form>
            </div>
        </div>
    </div>
</section>

  <script>
        // function confirmSubmit() {
        //     // Afficher une alerte de confirmation
        //     return confirm('Are you sure you want to submit the form?');
        // }

        // document.getElementById('updateForm').addEventListener('submit', function() {
        //     // Afficher le loader lors de la soumission du formulaire
        //     document.getElementById('loader').style.display = 'block';
        // });
    </script>
@endsection

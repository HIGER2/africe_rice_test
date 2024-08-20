@extends('layouts.app')


@section('content')
@include('includes.header')

@section('content')

<section class="formstatus">
    <div class="container">
        <div class="card confirm">
            <h5>Confirm your action</h5>
            <p>Are you sure you want to {{ $action === 'approve' ? 'approve' : 'reject' }} this request?</p>
            <form action="{{ route('form.action', ['id' => $form->id, 'action' => $action]) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    Confirm
                </button>
            </form>
        </div>

    </div>
</section>

@endsection

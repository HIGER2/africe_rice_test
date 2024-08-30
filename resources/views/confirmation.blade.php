@extends('layouts.app')


@section('content')
@include('includes.header')

@section('content')

<section class="formstatus">
    <div class="container">
        <div class="card confirm">
       @if ($errors->has('message'))
            <div class="alert alert-danger">
                {{ $errors->first('message') }}
            </div>
        @endif
            <h5>Confirm your action</h5>
            <p>Are you sure you want to {{ $action === 'approve' ? 'approve' : 'reject' }} this request?</p>
                <form id="formsubmit" action="{{ route('form.action', ['id' => $form->id, 'action' => $action]) }}" method="POST" onsubmit="submitForm(event)">
                    @csrf
                    <button type="submit" id="submitButton" class="btn btn-primary">
                        Confirm
                    </button>
                </form>
        </div>

    </div>
</section>

<script>

    function submitForm(event) {
        // Prevent the form from submitting immediately
        event.preventDefault();

        // Change the button text to indicate processing
        const submitButton = document.getElementById('submitButton');
        submitButton.textContent = 'Processing please wait...';

        // Optionally disable the button to prevent multiple submissions
        submitButton.disabled = true;

        // Submit the form after updating the button text
        event.target.submit();
    }
</script>

@endsection

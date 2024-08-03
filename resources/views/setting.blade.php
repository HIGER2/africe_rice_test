@extends('layouts.app')
@section('content')
@include('includes.header-home')

<section class="setting">
    <div class="container">
        {{-- <form action="{{ route('setting') }}" method="post">
                <h2>Update amount Staff Category</h2>
                <div class="card">
                    <div class="form-group">
                        <label for="amount">Exchange rate</span></label>
                        <input type="number" id="amount"
                        value="{{$amount ? $amount: ''}}"
                        {{ empty($amount) ? 'disabled' : '' }}
                        name="amount" placeholder="Enter amount" required>
                    </div>
                </div>
            <div class="card">
                    <p>Sign into your staff account to get access to our platform.</p>
                    @if ($errors->has('message'))
                        <div class="alert alert-danger">
                            {{ $errors->first('message') }}
                        </div>
                    @endif
                     @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @csrf
                        <div class="form-group">
                            {{$type_allowence}}
                            <label for="tA">Select Type of Allowance</label>
                            <select name="type_allowance_id" id=""
                            value="{{$selectedTypeAllowenceId ? $selectedTypeAllowenceId : ''}}"
                            onchange="this.form.submit()">
                                <option value="">-- Select Type of Allowance --</option>
                                @foreach ($type_allowence as $data )
                                  <option value="{{$data->id}}">  {{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            {{$amount}}
                            {{$staffCategories ? $staffCategories : ''}}
                            {{$selectedTypeAllowenceId}}
                            <label for="stc">Select Staff Category</label>
                            <select name="staff_category_id" id=""  {{ empty($staffCategories) ? 'disabled' : '' }}
                            onchange="this.form.submit()"

                            value="{{$selectedStaffCategoriId ? $selectedStaffCategoriId :''}}"
                            >
                                <option value="">-- Select Staff Category --</option>
                                @foreach ($staffCategories as $data )
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</span></label>
                            <input type="number" id="amount"
                            value="{{$amount ? $amount: ''}}"
                            {{ empty($amount) ? 'disabled' : '' }}
                            name="amount" placeholder="Enter amount" required>
                        </div>


                        <div class="form-group">
                            <label for="currency">Currency</span></label>
                            <select name="currency"
                                {{ empty($currency) ? 'disabled' : '' }}
                                value="{{$currency ? $currency: ''}}"
                                id="">
                                <option value="">-- Select Currency --</option>
                                <option value="XOF">XOF</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                </div>
                <input type="hidden" name="form_action" id="form_action" value="">

                <button type="submit" class="login-button" onclick="document.getElementById('form_action').value='submit_final';">
                    update
                </button>
        </form> --}}

        <div id="app">
             <setting-page
             :type_allowence="{{json_encode($type_allowence)}}"
             :currency="{{json_encode($currency)}}"
             />
        </div>
    </div>
</section>
@endsection

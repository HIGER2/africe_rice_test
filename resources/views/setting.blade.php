@extends('layouts.app')
@section('content')
@include('includes.header-home')

<section class="setting">
    <div class="container">
        <form action="{{ route('setting') }}" method="post" onchange="this.form.submit()">
            @csrf
            <div class="card">
                @if (isset($succes))
                        <div class="alert alert-success">
                            {{ $succes }}
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
                        {{-- <div class="form-group">
                            <label for="number">Update exchange rate</label>
                            <input type="number" name="rate"
                            value="{{$rate->value}}"
                                placeholder="Update exchange rate" >
                        </div> --}}
                     <h2>Update amount Staff Category</h2>
                        <!-- Display success messages -->
                        <div class="form-group">
                            {{-- {{$type_allowence}} --}}
                            <label for="tA">Select Type of Allowance</label>
                            <select name="type_allowance_id" id=""
                            value="{{$selectedTypeAllowenceId ? $selectedTypeAllowenceId : ''}}"
                            onchange="this.form.submit()" >
                                <option value="">-- Select Type of Allowance --</option>
                                @foreach ($type_allowence as $data )
                                  <option value="{{$data->id}}">  {{$data->name}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group">
                            {{-- {{$amount}} --}}
                            {{-- {{$staffCategories ? $staffCategories : ''}} --}}
                            {{-- {{$selectedTypeAllowenceId}} --}}
                            <label for="stc">Select Staff Category</label>
                            <select name="staff_category_id" id=""  {{ $staffCategories->count() ==0 ? 'disabled' : '' }}
                            onchange="this.form.submit()"

                            value="{{$selectedStaffCategoriId ? $selectedStaffCategoriId :''}}"

                            >
                                <option value="">-- Select Staff Category --</option>
                                @foreach ($staffCategories as $data )
                                    <option value="{{$data->id}}">{{$data->name}}</option>
                                @endforeach
                            </select>
                            @error('staff_category_id')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</span></label>
                            <input type="number" id="amount"
                            value="{{$amount}}"
                            {{ $staffCategories->count() ==0 ? 'disabled' : '' }}
                            name="amount" placeholder="Enter amount" >
                            @error('amount')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="currency">Currency</span></label>
                            <select name="currency"
                               {{ $staffCategories->count() ==0 ? 'disabled' : '' }}
                                value="{{$currency ? $currency: ''}}"
                                id="" >
                                <option value="XOF">XOF</option>
                                <option value="USD">USD</option>
                            </select>
                             @error('currency')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                        </div>
                </div>
                <input type="hidden" name="form_action" id="form_action" value="">

                <button type="submit" class="login-button" onclick="document.getElementById('form_action').value='submit_final';">
                    update
                </button>
        </form>
        <div class="contentTable">
            {{-- <a href="/liste">voir la liste</a> --}}
            @foreach ($newTypeAllowance as $data )
            <div class="parentContent">
                <h6>{{$data->type }}</h6>
                <div class="content">
                   @foreach ($data->staff_categories as $category )
                    <div class="elementContent">
                        <span>{{$category->name}}</span>
                        <span>{{number_format($category->amount, 0, ',', ' ')." ".$category->currency}}</span>
                    </div>
                   @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

  <script>
        function confirmSubmit() {
            // Afficher une alerte de confirmation
            return confirm('Are you sure you want to submit the form?');
        }

        document.getElementById('updateForm').addEventListener('submit', function() {
            // Afficher le loader lors de la soumission du formulaire
            document.getElementById('loader').style.display = 'block';
        });
    </script>
@endsection

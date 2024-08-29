@extends('layouts.app')
@section('content')
@include('includes.header-home')

<section class="liste">
    <div class="container">
        <div class="card">
            <h5>All requests ({{$all}})</h5>
            <div class="headers">
                <div class="boxItem">
                {{-- <div class="box">
                    <div class="ico"></div>
                    <div>
                        All
                    </div>
                    <div>
                        ({{$all}})
                    </div>
                </div> --}}
                <div class="box">
                    <div class="ico"></div>
                    <div>
                        Pending
                    </div>
                    <div>
                        ({{$pending}})
                    </div>
                </div>
                <div class="box">
                    <div class="ico"></div>
                    <div>
                        Approved
                    </div>
                    <div>
                    ({{$approuve}})
                    </div>
                </div>
                <div class="box">
                    <div class="ico"></div>
                    <div>
                        Rejected
                    </div>
                    <div>
                        ({{$rejected}})
                    </div>
                </div>
            </div>
              <form action="{{ route('request.export') }}" method="GET">
                @csrf
                <button type="submit" class="btnexport">
                    Export
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="14" viewBox="0 0 24 24"><path fill="currentColor" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zm1.8 18H14l-2-3.4l-2 3.4H8.2l2.9-4.5L8.2 11H10l2 3.4l2-3.4h1.8l-2.9 4.5zM13 9V3.5L18.5 9z"/></svg>
                </button>
            </form>
            </div>
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('success') }}
                </div>
            @endif
        <div class="contentTable">

            {{-- {{$liste}} --}}
            {{-- <a href="/listchecked">voir la liste</a>
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
            @endforeach --}}

            <table>
                <thead>
                    <tr>
                        <th>n°</th>
                        <th>employee</th>
                        <th>Job title</th>
                        <th>departure date</th>
                        <th>Date of taking up office</th>
                        {{-- <th>Tatal amount</th> --}}
                        <th>Status Request</th>
                        <th>Status Payment</th>
                        <th>Payment Date</th>
                        <th>submission date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @if ($liste->count()> 0)
                     @foreach ($liste as $data )
                     <tr>
                        {{-- {{$data}} --}}
                            <td>{{$data->request_number}}</td>
                            <td class="user">
                                {{-- <div class="ico"></div> --}}
                                <div class="info">
                                    <span>{{$data->employees->firstName}}</span>
                                    <span>{{$data->employees->lastName}}</span>
                                </div>
                            </td>
                            <td>{{$data->employees->jobTitle}}</td>
                            <td>{{$data->depart_date}}</td>
                            <td>{{$data->taking_date}}</td>
                            {{-- <td>{{$data->total_amount}}</td> --}}
                            <td>
                               @if ($data->status == 'approved')
                                <div class="status approuve" >
                                approved
                                </div>
                               @elseif ($data->status == 'rejected')
                                <div class="status reject" >
                                rejected
                                </div>
                                @elseif  ($data->status == 'pending')
                                    <div class="status pennding">
                                    pending
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if (isset($data->payments->status_payment) )
                                    @if ($data->payments->status_payment == 'paid')
                                    <div class="status approuve" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2m0 14H4v-6h16zm0-10H4V6h16z"/></svg>
                                         paid
                                    </div>
                                @elseif ($data->payments->status_payment == 'failed')
                                    <div class="status reject" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2m0 14H4v-6h16zm0-10H4V6h16z"/></svg>
                                        failed
                                    </div>
                                    @elseif  ($data->payments->status_payment == 'pending')
                                        <div class="status pennding">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"><path fill="currentColor" d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2m0 14H4v-6h16zm0-10H4V6h16z"/></svg>
                                            pending
                                        </div>
                                    @endif
                                @else
                                    N/A
                                @endif
                            </td>
                            </td>
                            <td>{{ isset($data->payments->date_payment) ? $data->payments->date_payment : 'N/A' }}</td>
                            <td>{{$data->created_at}} </td>
                            <td>
                                 @if (($data->status == 'pending' || $data->payments->status_payment == 'pending'))
                                    <form action="{{route('reolad.request', ['id'=>$data->id])}}" method="get">
                                        @csrf
                                        <button type="submit" @disabled($data->status == 'pending' ? false : true) onclick="get_data_id({{$data->id}})" data-bs-toggle="modal" data-bs-target="#exampleModal"  type="button"class="btnpayement">
                                        <i class="uil uil-redo"></i>
                                        </button>
                                    </form>
                                @else
                                <span>...</span>
                                @endif
                            </td>
                        </tr>
                 @endforeach
                @endif
                </tbody>
            </table>
           @if ($liste->count() <=0)
                <div class="empty">
                Empty
            </div>
           @endif
        </div>
        </div>
    </div>
</section>

<script>
    function get_data_id(id) {
        document.querySelector('#data-id').value = id;
    }
</script>
@endsection

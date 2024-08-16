@extends('layouts.app')
@section('content')
@include('includes.header-home')

<section class="liste">
    <div class="container">
        <div class="card">
            <h5>All requests ({{$all}})</h5>
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
                        <th>employee</th>
                        <th>Job title</th>
                        <th>departure date</th>
                        <th>Date of taking up office</th>
                        <th>Tatal amount</th>
                        <th>Status</th>
                        <th>submission date</th>
                    </tr>
                </thead>
                <tbody>
                @if ($liste->count()> 0)
                     @foreach ($liste as $data )
                     <tr>
                        {{-- {{$data}} --}}
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
                            <td>{{$data->total_amount}}</td>
                            <td>
                               @if ($data->status == 'approved')
                                <div class="status approuve" >
                                approved
                                </div>
                               @elseif ($data->status == 'rejected')
                                <div class="status reject" >
                                rejected
                                </div>
                                @else
                                    <div class="status pennding">
                                    pending
                                    </div>
                                @endif
                            </td>
                            <td>{{$data->created_at}}</td>
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

    </script>
@endsection

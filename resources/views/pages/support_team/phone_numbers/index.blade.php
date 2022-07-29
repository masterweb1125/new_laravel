@extends('layouts.master')
@section('page_title', 'Manage phone numbers')
@section('content')

    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab" onclick="window.location.reload();">Manage Phone Numbers</a></li>
                <li class="nav-item"><a href="#add-class" class="nav-link" data-toggle="tab">Add New Phone Number</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all_phone_numbers as $key => $value) 
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>
                                        {{$value->name}}
                                    </td>
                                    <td>
                                        {{$value->phone}}
                                    </td>
                                    <td>
                                        @if($value->status == "s1")
                                            Message Sent
                                        @elseif($value->status == "s2")
                                            Client responded
                                        @elseif($value->status == "s3")
                                            Out for delievery 
                                        @elseif($value->status == "s4")
                                            Delievered 
                                        @elseif($value->status == "s5")
                                            Delievered “Digital Card”
                                        @endif                                        
                                    </td>
                                    <td>
                                        <a class="btn" type="button" onclick="deletePhoneNumber(`{{$value->id}}`, this);">
                                            <img src="/global_assets/images/icon/delete.png" width="20" height="20"/>
                                        </a>
                                        <a class="btn" type="button" onclick="editPhoneNumber(`{{$value->id}}`, this);">
                                            <img src="/global_assets/images/icon/edit.png" width="20" height="20"/>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="add-class">            
                    <form class="phone-number-store" method="post" action="{{ route('phone_numbers.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_name" class="col-lg-3 col-form-label font-weight-semibold">Name</label>
                                    <div class="col-lg-9">
                                        <input type="text" placeholder="Type name" class="form-control" name="client_name" id="client_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number" class="col-lg-3 col-form-label font-weight-semibold">Phone Number</label>
                                    <div class="col-lg-9">
                                        <input name="phone_number" id="phone_number"  type="text" class="form-control" placeholder="Type Phone Number" required >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button id="phone_number_submit" type="submit" class="btn btn-primary">Save <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}
    @include('partials.js.phone_number_index')
@endsection


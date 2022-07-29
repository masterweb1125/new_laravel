@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

    <div class="card">

        <div class="card-header header-elements-inline">
            <h1 class="card-title"></h1>
            <h1 class="card-title">Class List</h1>
            {!! Qs::getPanelOptions() !!}
        </div>
        <div class="card-body">

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ADMNO</th>
                            <th>NAME</th>
                            <th>STREAM</th>
                            <th>KCPE</th>
                            <th>CONTACTS</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($class_list as $val)
                                @foreach ($val->students as $k => $key)
                                    <tr>
                                        <td>{{$k + 1}}</td>
                                        <td><a href="{{route('students.show', $key->id )}}"> {{$key->adm_no}} </a></td>
                                        <td><a href="{{route('students.show', $key->id )}}"> {{$key->user->name}} </a></td>
                                        <td>{{$key->my_class->stream}}</td>
                                        <td>{{$key->kcpe}}</td>
                                        <td>not yet</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}
    @include('partials.js.class_index')
@endsection

@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')
    <style>
        .student_list a {
            color: black;
        }
    </style>
    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Classes</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ID</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $key => $val)
                                <tr class="student_list">
                                    <td><a href="{{route('students.show', $val->student_id )}}"> {{$key + 1}} </a></td>
                                    <td><a href="{{route('students.show', $val->student_id )}}"> {{$val->id}} </a></td>
                                    <td><a href="{{route('students.show', $val->student_id )}}"> {{$val->student->user->name}} </a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

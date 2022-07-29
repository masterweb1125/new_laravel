@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Classes</a></li>
                <li class="nav-item"><a href="#add-class" class="nav-link" data-toggle="tab">Add New Class</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>Form</th>
                            <th>Stream</th>
                            <th>Students</th>
                            <th>Class Teacher</th>
                            <th>Edit Name</th>
                            <th>Manage</th>
                            <th>Class List</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                            @foreach($class_list as $key => $c)
                            <tr>
                                <td data-id="{{ $c->id }}" style="display: none;">{{ $key }}</td>
                                <td>{{$c->form_id}}</td>
                                <td>{{$c->stream}}</td>
                                <td>{{count($c->students)}}</td>
                                
                                <td>   
                                    @if ($c->teacher_id != 0)
                                        <div class="d-flex align-items-center justify-content-between">
                                            <p style="margin: 0;">{{$c->teacher->user->name}}</p>
                                            <a class="btn" title="Delete this user" onclick="deleteClassTeacher({{ $c->id }}, this);">
                                                <img src="/global_assets/images/icon/delete.png" width="20" height="20"/>
                                            </a>
                                        </div>
                                    @else 
                                        <select required data-placeholder="Assign" class="form-control" onchange="assignClassTeacher({{ $c->id }}, this)" data-id="{{ $c->id }}">
                                            <option value="">Assign</option>
                                            @foreach($all_teachers as $value)
                                            <option value="{{$value->id}}">{{$value->user->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif   
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary edit_class_stream"  data-id="{{ $c->id }}">Edit</button>
                                </td>
                                <td>
                                    <a class="btn btn-info" href="{{ route('class_subject_manage', $c->id) }}" >Manage</a>
                                </td>
                                <td>
                                    <a class="btn btn-success"  href="{{ route('class_list2', $c->id) }}" >Class List</a>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger delete_class"  data-id="{{ $c->id }}">Delete</button>
                                </td>
                            </tr>
                            @endforeach
                        
                        </tbody>
                    </table>
                </div>
                

                <div class="tab-pane fade" id="add-class">
                    
            
                    <form class="ajax-store" method="post" action="{{ route('classes.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="form_id" class="col-lg-3 col-form-label font-weight-semibold">Form</label>
                                    <div class="col-lg-9">
                                        <select required data-placeholder="Select Form" class="form-control select" name="form_id" id="form_id">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-lg-3 col-form-label font-weight-semibold">Stream Name</label>
                                    <div class="col-lg-9">
                                        <input name="stream" id="stream" value="{{ old('stream') }}" required type="text" class="form-control" placeholder="Stream Name">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            
                            <?php  $subject_type = ''; $cnt = 0;?>
                            @foreach ($all_subjects as $subject) 
                            
                                @if($cnt == 0) <div class="col-md-4"> @endif

                                @if($subject_type != $subject->subject_type->id)<h3>{{$subject->subject_type->name}}</h3> <?php $subject_type = $subject->subject_type->id;?> @endif                            
                                <input type="checkbox" id="{{$subject->subject_type->name.$subject->title}}" name="{{$subject->subject_type->name.$subject->title}}" value="{{$subject->id}}">
                                <label for="{{$subject->subject_type->name.$subject->title}}">{{$subject->title}}</label><br>

                                @if($cnt == 13) </div> @endif
                                <?php $cnt++; if($cnt == 14) $cnt = 0; ?>
                            
                            @endforeach
                            
                        </div>
                        <div class="text-right">
                            <button id="ajax-btn" type="submit" class="btn btn-primary" style="float: right;" >Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{--Class List Ends--}}
    @include('partials.js.class_manage')
@endsection

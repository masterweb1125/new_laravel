@extends('layouts.master')
@section('page_title', 'Manage Teacher')
@section('content')

    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#new-user" class="nav-link active" data-toggle="tab">Edit Teacher</a></li>        
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="new-user">
                    <form method="post" action="{{ route('teachers.update', $teacher->id) }}">
                        @csrf @method('PUT')
                        <h6>Teacher Data</h6>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input value="{{ $teacher->user->name }}" required type="text" name="full_name" id="full_name" placeholder="Full Name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input value="{{ $teacher->user->email }}" required type="text" name="email" id="email" placeholder="Email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input value="{{ $teacher->user->phone }}" class="form-control" placeholder="07## ### ###" name="phone_number" id="phone_number" type="text" >
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tsc_no">TSC No.</label>
                                    <input value="{{ $teacher->user->tsc_no }}" type="text" name="tsc_no" id="tsc_no" placeholder="####" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="gender">Gender</label>
                                <select class="select form-control" id="gender" name="gender" data-fouc data-placeholder="Choose..">
                                    <option value="male" @if($teacher->user->gender == 'male') selected @endif >Male</option>
                                    <option value="female" @if($teacher->user->gender == 'female') selected @endif >Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="national_id_no">National ID No.</label>
                                    <input value="{{ $teacher->user->national_id_no }}" type="text" name="national_id_no" id="national_id_no" placeholder="####" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group">Group</label>
                                    <select class="select form-control" id="group" name="group" data-fouc data-placeholder="Choose..">
                                        @foreach ($group as $key => $g)
                                        <option value="{{$g->id}}"  @if($teacher->group_id == $g->id) selected @endif  >{{$g->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update <i class="icon-paperplane ml-2"></i></button>
                            <a class="btn btn-warning" href="{{ route('teachers.index') }}" >Back</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection

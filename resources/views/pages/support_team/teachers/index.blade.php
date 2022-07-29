@extends('layouts.master')
@section('page_title', 'Manage Classes')
@section('content')

    <div class="card">

        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all-classes" class="nav-link active" data-toggle="tab">Manage Teachers</a></li>
                <li class="nav-item"><a href="#new-user" class="nav-link" data-toggle="tab">Add New Teacher</a></li>     
                <li class="nav-item"><a href="#all-group" class="nav-link" data-toggle="tab">Manage Group</a></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all-classes">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Personal Email</th>
                            <th>Gender</th>
                            <th>TSC No.</th>
                            <th>National ID No.</th>
                            <th>Groups</th>
                            <th>Role</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_teachers as $key => $teacher)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$teacher->user->name}}</td>
                                    <td>{{$teacher->user->phone}}</td>
                                    <td>{{$teacher->user->email}}</td>
                                    <td>{{$teacher->user->gender}}</td>
                                    <td>{{$teacher->user->tsc_no}}</td>
                                    <td>{{$teacher->user->national_id_no}}</td>
                                    <td>@if($teacher->group_id != 0){{$teacher->group->name}}@endif</td>
                                    <td>{{$teacher->user->user_type->name}}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a href="#" class="list-icons-item" data-toggle="dropdown">
                                                    Action &nbsp;<i class="icon-menu9"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-left">
                                                    @if(Qs::userIsTeamSA())
                                                    <a href="{{ route('teachers.edit', $teacher->id) }}" class="dropdown-item"><i class="icon-pencil"></i> Edit</a>
                                                    @endif
                                                    @if(Qs::userIsSuperAdmin())
                                                    <a id="{{ $teacher->id }}" onclick="confirmDelete(this.id)" href="#" class="dropdown-item"><i class="icon-trash"></i> Delete</a>
                                                    <form method="post" id="item-delete-{{ $teacher->id }}" action="{{ route('teachers.destroy', $teacher->id) }}" class="hidden">@csrf @method('delete')</form>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new-user">
                    <form method="post" action="{{ route('teachers.store') }}">
                        @csrf
                        <h6>Teacher Data</h6>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">Full Name</label>
                                    <input value="{{ old('full_name') }}" required type="text" name="full_name" id="full_name" placeholder="Full Name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input value="{{ old('email') }}" required type="text" name="email" id="email" placeholder="Email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input value="{{ old('phone_number') }}" class="form-control" placeholder="07## ### ###" name="phone_number" id="phone_number" type="text" required>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tsc_no">TSC No.</label>
                                    <input value="{{ old('tsc_no') }}" required type="text" name="tsc_no" id="tsc_no" placeholder="####" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="gender">Gender</label>
                                <select class="select form-control" id="gender" name="gender" data-fouc data-placeholder="Choose..">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="national_id_no">National ID No.</label>
                                    <input value="{{ old('national_id_no') }}" required type="text" name="national_id_no" id="national_id_no" placeholder="####" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="group">Group</label>
                                    <select class="select form-control" id="group" name="group" data-fouc data-placeholder="Choose..">
                                        @foreach ($group as $key => $g)
                                        <option value="{{$g->id}}">{{$g->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Submit form <i class="icon-paperplane ml-2"></i></button>
                        </div>
                    </form>
                </div>

                
                <div class="tab-pane fade" id="all-group">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th class="d-flex justify-content-center">Delete</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $len = count($all_group); $i = 0; ?>
                            @foreach ($all_group as $key => $group)
                                <?php $i++ ?>
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>                                        
                                        <div class="d-flex align-items-center justify-content-start">                                            
                                            <p style="margin: 0;">{{$group->name}}</p>                                            
                                            <a class="btn" title="Delete this user" onclick="editingGroupName('{{$group->name}}', this);">
                                                <img src="/global_assets/images/icon/edit.png" width="20" height="20"/>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <div class="dropdown">
                                                <a class="list-icons-item" onclick="deleteGroup('{{$group->id}}', this);" style="cursor: pointer; ">
                                                    Delete &nbsp;<img src="/global_assets/images/icon/delete.png" width="20" height="20"/>
                                                </a>
                                            </div>
                                        </div>
                                    </td>                                    
                                    <td style="display: none; ">{{$group->id}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-primary" style="float: right" data-toggle="modal" data-target="#myModal" >Add Group</button>
                </div>
            </div>
        </div>
    </div>

    <!-- create new group modal starts -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="noticeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #5dd1bb; color: black;">
                    <h5>Creat New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('new_group')}}">
                    <div class="modal-body">
                        <div class="form-group d-flex flex-column align-items-center">
                            <label for="group_name">Group Name </label>
                            <input type="text" name="group_name" id="group_name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-theme">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- create new group modal ends -->  
    @include('partials.js.class_index')
    @include('partials.js.group_index')
@endsection

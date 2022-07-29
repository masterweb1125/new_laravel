@extends('layouts.master')
@section('page_title', 'Manage Exams')
@section('content')

<style>
    ul {
        list-style-type: none;
    }
    .forms_sitting_exam {
        margin: 1rem;
        padding: 0;
    }
    .one-sitting {
        border-top: 1px solid #00000042;
        border-bottom: 1px solid #00000042;
    }
    .one-sitting.odd {
        background: rgb(227 225 225 / 50%);
    }
</style>
    <div class="card">
        
        <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-highlight">
                <li class="nav-item"><a href="#all_exams_pane" class="nav-link active" data-toggle="tab" onclick="getInitExam()" >Manage Exams</a></li>
                <li class="nav-item"><a href="#my_classes_pane" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> My Classes</a></li>
                <li class="nav-item"><a href="#new_exam_pane" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Create Exam</a></li>
                <li class="nav-item"><a href="#grading_systems_pane" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Grading Systems</a></li>
                <li class="nav-item"><a href="#subject_paper_ratios" class="nav-link" data-toggle="tab"><i class="icon-plus2"></i> Subject Paper Ratios</a></li><li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">More</a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="#student-residences" class="dropdown-item" data-toggle="tab">Deleted Exams</a>
                    </div>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="all_exams_pane">
                    <table class="table datatable-button-html5-columns">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Class</th>
                            <th>Term</th>
                            <th>Year</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="exam_index_tbody">

                        </tbody>
                    </table>
                </div>

                <div class="tab-pane fade" id="new_exam_pane">
                    <div class="exam_type_pane">
                        <h6 id="search_title">Exam Type</h6>     
                        <form id="create_exam_form" method="post" action="{{ route('exams.store') }}">
                            @csrf               
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <input type="radio" name="exam_type" id="Ordinary_Exam" class="form-control" checked 
                                                    value="Ordinary_Exam" style="width: 20px; height: 20px;">
                                                <label class="ml-2" for="Ordinary_Exam">Ordinary Exam</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <input type="radio" name="exam_type" id="Consolidated_Exam" class="form-control"
                                                    value="Consolidated_Exam"  style="width: 20px; height: 20px;">
                                                <label class="ml-2" for="Consolidated_Exam">Consolidated Exam</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <input type="radio" name="exam_type" id="Year_Average" class="form-control"
                                                    value="Year_Average" style="width: 20px; height: 20px;">
                                                <label class="ml-2" for="Year_Average">Year Average</label>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="d-flex">
                                                <input type="radio" name="exam_type" id="KCSE" class="form-control"
                                                    value="KCSE" style="width: 20px; height: 20px;">
                                                <label class="ml-2" for="KCSE">KCSE</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mt-3">                                
                                    <div class="form-group">
                                        <label for="exam_name">Exam Name</label>
                                        <input class="form-control" type="text" id="exam_name" name="exam_name" placeholder="Mid Term">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 mt-3">                                
                                    <div class="form-group">
                                        <label for="exam_term">Term</label>
                                        <select class="select form-control" id="exam_term" name="exam_term" data-fouc data-placeholder="Select Term">
                                            <option value="">Select Term</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 mt-3">                                
                                    <div class="form-group">
                                        <label for="exam_year">Year</label>
                                        <select class="select form-control" id="exam_year" name="exam_year" data-fouc data-placeholder="Select Year">
                                            <option value="">Select Year</option>
                                            <option value="2022">2022</option>
                                            <option value="2021">2021</option>
                                            <option value="2020">2020</option>
                                            <option value="2019">2019</option>
                                            <option value="2018">2018</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">  
                                    <label for="exam_year">Forms sitting for the exam</label>
                                    <ul class="forms_sitting_exam">
                                        @foreach ($forms as $key => $val)
                                        <li class="row one-sitting @if($key % 2 == 0) odd @endif">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <input type="checkbox" class="exam_form my-2 mx-3" value="{{$val->id}}" style="width: 20px; height: 20px;">
                                                    <p class="my-2 mx-3">Form {{$val->name}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <input  class="my-2 mx-3" type="number" placeholder="Minimum Subject that can be taken" name="min_subject_cnt" id="min_subject_cnt{{$val->id}}" min="0" value="0" style="width: 80%;"/>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary" id="create-exam-btn">Create<i class="icon-paperplane ml-2"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.js.exam_js')
@endsection

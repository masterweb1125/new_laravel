<?php

namespace App\Http\Controllers\SupportTeam;

use Illuminate\Http\Request;
use App\Helpers\Qs;
use App\Http\Requests\Exam\ExamCreate;
use App\Http\Requests\Exam\ExamUpdate;
use App\Repositories\ExamRepo;
use App\Repositories\MyClassRepo;
use App\Http\Controllers\Controller;


use App\Models\Exam;

class ExamController extends Controller
{
    protected $exam;
    protected $my_class;
    public function __construct(ExamRepo $exam, MyClassRepo $my_class)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->exam = $exam;
        $this->my_class = $my_class;
    }

    public function index()
    {
        $d['exams'] = $this->exam->all();
        $d['forms'] = $this->my_class->getAllForms();
        return view('pages.support_team.exams.index', $d);
    }

    public function exam_index() {
        $exams = $this->exam->all();
        $forms = $this->my_class->getAllForms();
        $examforms = $this->exam->getAllExamForms();
        return json_encode(['exams' => $exams, 'forms' => $forms, 'examforms' => $examforms]);
    }

    public function store(Request $req)
    {
        $data['type'] = $req->exam_type;
        $data['name'] = $req->exam_name;
        $data['term'] = $req->exam_term;
        $data['year'] = $req->exam_year;

        if(!$exam = $this->exam->isExist($data)) {
            
            $exam = $this->exam->create($data);
            
            $exam_forms = json_decode($req->exam_forms);
            
            foreach($exam_forms as $value) {
                            
                $data2['exam_id'] = $exam->id;
                $data2['form_id'] = $value->form_id;
                $data2['min_subject_cnt'] = $value->min_subject_cnt;
                $examform = $this->exam->createExamForm($data2);
            }
            return json_encode(['ok' => true, 'msg' => "Created Successfully"]);
        }
        return json_encode(['ok' => true, 'msg' => "Already Exist"]);
    }

    public function exam_update(Request $req) {
        
        $data['name'] = $req->name;
        $data['type'] = $req->type;
        $data['term'] = $req->term;
        $data['year'] = $req->year;
        $res = $this->exam->update($req->id, $data);
        if($res) return json_encode(['ok' => true, 'msg' => "Updated Successfully"]);
        return json_encode(['ok' => true, 'msg' => "An error occured"]);
    }

    public function exam_delete(Request $req)
    {    
        // return json_encode(['data' => $req->id]);
        $res = $this->exam->delete($req->id);
        if($res) return json_encode(['ok' => true, 'msg' => "Deleted Successfully"]);
        return json_encode(['ok' => false, 'msg' => "An error occured"]);
    }
}

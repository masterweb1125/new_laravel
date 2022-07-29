<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Http\Requests\MyClass\ClassCreate;
use App\Http\Requests\MyClass\ClassUpdate;

use App\Repositories\MyClassRepo;
use App\Repositories\UserRepo;
use App\Repositories\TeacherRepo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MyClass;
use App\Models\ClassSubject;
use App\Models\Form;
use App\Models\Student;
use App\Models\StudentSubject;


class MyClassController extends Controller
{
    protected $my_class, $user, $teacher_repo;

    public function __construct(MyClassRepo $my_class, UserRepo $user, TeacherRepo $teacher_repo)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->my_class = $my_class;
        $this->user = $user;
        $this->teacher_repo = $teacher_repo;
    }
    
    public function index()
    {       
        $d['all_subjects'] = $this->my_class->getAllSubjects();
        $d['all_teachers'] = $this->teacher_repo->getAllTeachers();
        $d['all_forms'] = $this->my_class->getAllForms();
        return view('pages.support_team.classes.index', $d);
    }


    public function assign_supervisor(Request $request) {

        $this->my_class->assignSupervisor($request->formId, $request->teacher_id);
        $teacher = $this->teacher_repo->findTeacher($request->teacher_id);
        return json_encode(['ok' => true, 'msg' => "Assigned Successfully", 'teacher_name' => $teacher->user->name]);
    }

    public function delete_supervisor(Request $request) {

        $all_teachers = $this->teacher_repo->getAllTeachers1();
        $this->my_class->deleteSupervisor($request->formId);
        return json_encode(['ok' => true, 'msg' => "Deleted Successfully", 'all_teachers' => $all_teachers]);
    }

    public function class_manage($form_id)
    {     
        $d['all_subjects'] = $this->my_class->getAllSubjects();           
        $d['all_teachers'] = $this->teacher_repo->getAllTeachers();
        $d['class_list'] = $this->my_class->getClass($form_id);
        return view('pages.support_team.classes.class_manage', $d);
    }

    public function class_list($form_id)
    {  
        $d['class_list'] = $c = $this->my_class->getClassList($form_id);

        return view('pages.support_team.classes.class_list', $d);
    }

    public function class_list2($class_id)
    {
        $d['class_list'] = $c = $this->my_class->find($class_id);
        return view('pages.support_team.classes.class_list2', $d);
    }

    public function assign_class_teacher(Request $request) {
        
        $this->my_class->assignClassTeacher($request->classId, $request->teacher_id);
        $teacher = $this->my_class->findClassTeacher($request->classId);
        return json_encode(['ok' => true, 'msg' => "Assigned Successfully", 'teacher_name' => $teacher->user->name]);
    }
    
    public function delete_class_teacher(Request $request) {
        
        $this->my_class->deleteClassTeacher($request->classId);
        $all_teachers = $this->teacher_repo->getAllTeachers1();
        return json_encode(['ok' => true, 'msg' => "Deleted Successfully", 'all_teachers' => $all_teachers]);
    }   
    public function update_class_stream(Request $request) {
        
        MyClass::where('id', $request->classId)->update(['stream' => $request->updated_class_stream]);
        return Qs::jsonStoreOk();
    }

    public function delete_class(Request $request) {
        MyClass::where('id', $request->classId)->delete();
        // delete class subjects
        ClassSubject::where('my_class_id', $request->classId)->delete();
        // set property as null in Student
        Student::where('my_class_id', $request->classId)->update(['my_class_id' => null]);
        return Qs::jsonStoreOk();
    }

    public function class_subject_manage($class_id) {
        $d['all_subjects'] = $this->my_class->getAllSubjects();  
        $d['all_teachers'] = $this->teacher_repo->getAllTeachers();
        $d['class_subjects'] = $this->my_class->findSubjectByClass($class_id, 'id');   
        $d['class_id']  = $class_id;
        return view('pages.support_team.classes.subject_manage', $d);        
    }

    public function delete_subject_teacher(Request $request) {

        $this->my_class->deleteSubjectTeacher($request->classSubjectId);
        $all_teachers = $this->teacher_repo->getAllTeachers1();
        return json_encode(['ok' => true, 'msg' => "Deleted Successfully", 'all_teachers' => $all_teachers]);
    }

    public function assign_subject_teacher(Request $request) {
                
        $this->my_class->assignSubjectTeacher($request->classSubjectId, $request->teacher_id);
        $teacher = $this->teacher_repo->findClassSubjectTeacher($request->classSubjectId);
        return json_encode(['ok' => true, 'msg' => "Assigned Successfully", 'teacher_name' => $teacher->user->name]);
    }
    
    public function delete_subject(Request $request) {
        $this->my_class->deleteClassSubject($request->classSubjectId);
        // delete StudentSubject
        StudentSubject::where('class_subject_id', $request->classSubjectId)->delete();
        return Qs::jsonStoreOk();
    }

    public function students_taken_csubject($classSubjectId) {
        
        $d['students'] = ClassSubject::find($classSubjectId)->students_taken_this_subject;
        return view('pages.support_team.classes.students_taken_this_subject', $d);
    }



    public function store(Request $req)
    {
        //create my_class
        $myclass = new MyClass;
        $myclass['form_id'] = $req->form_id;
        $myclass['stream'] = $req->stream;
        $myclass->save();

        //create class_subject
        $last_id = MyClass::orderBy('id', 'DESC')->first();
        $subject_list = json_decode($req['subject_list']);                

        foreach($subject_list as $slist) {
            if($slist->check_status == true) {

                $classsubject = new ClassSubject;
                $classsubject['my_class_id'] = $last_id->id;
                $classsubject['subject_id'] = $slist->id;
                $classsubject->save();
            }
        }
        return Qs::jsonStoreOk();
    }

    public function edit($id)
    {
        $d['myclass'] = $c = $this->my_class->find($id);        
        $bbbb = $this->my_class->getMyClassSubjects($id);
        $myclasssubject = array();
        foreach($bbbb as $value) {
            array_push($myclasssubject, $value->subject_id);
        }
        $d['myclasssubject'] = $myclasssubject;
        $d['all_subjects'] = $this->my_class->getAllSubjects();
        $d['classId'] = $id;
        return is_null($c) ? Qs::goWithDanger('classes.index') : view('pages.support_team.classes.edit', $d);
    }

    public function update(Request $req, $id)
    {
        $data['form_id'] = $req->form_id;
        $data['stream'] = $req->stream;
        $this->my_class->update($id, $data);

        //create class_subject
        
        $subject_list = json_decode($req['subject_list']);                

        foreach($subject_list as $slist) {
            if($slist->check_status == true) {
                if(!ClassSubject::where('my_class_id', $id)->where('subject_id', $slist->id)->first()) {
                    $classsubject = new ClassSubject;
                    $classsubject['my_class_id'] = $id;
                    $classsubject['subject_id'] = $slist->id;
                    $classsubject->save();
                }
            } else {
                ClassSubject::where('my_class_id', $id)->where('subject_id', $slist->id)->delete();
            }
        }


        return Qs::jsonUpdateOk();
    }

    public function destroy($id)
    {
        $this->my_class->delete($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }

}


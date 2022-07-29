<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Repositories\TeacherRepo;
use App\Repositories\UserRepo;

use App\Models\Teacher;
use App\User;

class TeacherController extends Controller
{
    protected $teacher_repo, $user;

    public function __construct(TeacherRepo $teacher_repo, UserRepo $user)
    {
        $this->middleware('teamSA', ['except' => ['destroy',] ]);
        $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->teacher_repo = $teacher_repo;
        $this->user = $user;
    }

    public function index() {

        $d['all_teachers'] = $this->teacher_repo->getAllTeachers();
        $d['group'] = $this->teacher_repo->getAllGroup();
        $d['all_group'] = $this->teacher_repo->getAllGroup();
        return view('pages.support_team.teachers.index', $d);
    }
    
    public function store(Request $req) {

        $default_password = 'qwerQWER1234!@#$_teacher';
        $model = new User;
        $model['name'] = $req->full_name;
        $model['email'] = $req->email;
        $model['code'] = $this->user->generateRandomString();
        $model['user_type_id'] = 3;
        $model['phone'] = $req->phone_number;
        $model['tsc_no'] = $req->tsc_no;
        $model['gender'] = $req->gender;
        $model['national_id_no'] = $req->national_id_no;
        $model['photo'] = Qs::getDefaultUserImage();
        $model['password'] = Hash::make($default_password);
        $model->save();
        $user = User::latest()->first();

        $model1 = new Teacher;
        $model1['user_id'] = $user->id;
        $model1['group_id'] = $req->group;
        $model1->save();
        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function edit($id) {

        $d['teacher'] = $this->teacher_repo->findTeacher($id);
        $d['group'] = $this->teacher_repo->getAllGroup();
        return view('pages.support_team.teachers.edit', $d);
    }

    
    public function update(Request $req, $id)
    {
        $data['name'] = $req->full_name;
        $data['email'] = $req->email;
        $data['phone'] = $req->phone_number;
        $data['tsc_no'] = $req->tsc_no;
        $data['gender'] = $req->gender;
        $data['national_id_no'] = $req->national_id_no;

        $this->teacher_repo->updateTeacher($id, $data);
        $data['group'] = $req->group;

        $d['teacher'] = $this->teacher_repo->findTeacher($id);
        $d['group'] = $this->teacher_repo->getAllGroup();
        
        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function destroy($id)
    {        
        $this->teacher_repo->deleteTeacher($id);
        return back()->with('flash_success', __('msg.del_ok'));
    }

    // for group of teachers
    public function group_index(Request $request) {

        
        $d['all_group'] = $this->teacher_repo->getAllGroup();
        return view('pages.support_team.teachers.group_index', $d);
    }

    public function update_group_name(Request $request) {
        
        $this->teacher_repo->updateGroupName($request->classId, $request->updated_group_name);
        return json_encode(['ok' => true, 'msg' => "Updated Successfully"]);
    }
    public function delete_group(Request $request) {
        $this->teacher_repo->deleteGroup($request->groupId);
        return json_encode(['ok' => true, 'msg' => "Deleted Successfully"]);
    }

    public function new_group(Request $request) {
        
        $this->teacher_repo->addGroup($request->group_name);
        return back()->with('flash_success', __('msg.store_ok'));
    }
}

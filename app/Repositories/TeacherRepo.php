<?php

namespace App\Repositories;

use App\Models\Teacher;
use App\Models\Section;
use App\Models\ClassSubject;
use App\Models\Group;
use App\Models\Form;
use App\Models\SubjectTeacher;
use App\Models\MyClass;
use App\User;

class TeacherRepo
{
    /************* Teacher *******************/
        public function getAllTeachers() {
            return Teacher::orderBy('id', 'asc')->get();
        }
        public function getAllTeachers1()
        {
            $bbb = array();
            $aaa = Teacher::orderBy('id', 'asc')->get();
            foreach($aaa as $val) {
                array_push($bbb, array(
                    'id' => $val->id,
                    'name' => $val->user->name,
                    'user_id' => $val->user_id,
                ));
            }
            return $bbb;
        }
        public function findTeacher($id) {
            return Teacher::find($id);
        }

        public function findClassSubjectTeacher($classSubjectId) {
            $classsubject = ClassSubject::where('id', $classSubjectId)->first();
            return $this->findTeacher($classsubject->teacher_id);
        }

        public function updateTeacher($id, $data) {
            return Teacher::find($id)->user->update($data);
        }

    /************* Group *******************/
        public function getAllGroup() {
            return Group::get();
        }    
        
        public function updateGroupName($id, $data) {
            return Group::where('id', $id)->update(['name' => $data]);
        }

        public function deleteGroup($id) {
            Teacher::where('group_id', $id)->update(['group_id' => 0]);
            return Group::where('id', $id)->delete();
        }

        public function addGroup($name) {            
            return Group::create([
                'name' => $name,
            ]);
        }
    /************* Complex of several Models *******************/
    public function deleteTeacher($id) {
        
        ClassSubject::where('teacher_id', $id)->update(['teacher_id' => null]);
        Form::where('teacher_id', $id)->update(['teacher_id' => 0]);
        SubjectTeacher::where('teacher_id', $id)->delete();
        MyClass::where('teacher_id', $id)->update(['teacher_id' => null]);
        // MyClass::where('teacher_id', $id)->delete();
        return Teacher::where('id', $id)->delete();
    }
}
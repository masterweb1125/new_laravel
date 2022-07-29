<?php

namespace App\Repositories;

use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectType;
use App\Models\ClassSubject;
use App\Models\Teacher;
use App\Models\Form;



class MyClassRepo
{

    /************* MyClass *******************/
        public function all()
        {
            return MyClass::orderBy('form_id', 'asc')->get();
        }

        public function getClass($form_id)
        {
            return MyClass::where('form_id', $form_id)->get();
        }

        public function findClass($form_id, $stream) {
            return MyClass::where('form_id', $form_id)->where('stream', $stream)->first();
        }

    public function getMC($data)
    {
        return MyClass::where($data)->with('section');
    }

        public function find($id)
        {
            return MyClass::find($id);
        }

        public function create($data)
        {
            return MyClass::create($data);
        }

        public function update($id, $data)
        {
            return MyClass::find($id)->update($data);
        }

        public function delete($id)
        {
            return MyClass::destroy($id);
        }

    public function findType($class_type_id)
    {
        return ClassType::find($class_type_id);
    }

    public function findTypeByClass($class_id)
    {
        return ClassType::find($this->find($class_id)->class_type_id);
    }

        public function deleteClassTeacher($classId) {
            return MyClass::where('id', $classId)->update(['teacher_id' => null]);
        }

        public function assignClassTeacher($classId, $teacher_id) {
            return MyClass::where('id', $classId)->update(['teacher_id' => $teacher_id]);
        }
        public function findClassTeacher($classId) {
            $myclass = MyClass::where('id', $classId)->first();
            return Teacher::find($myclass->teacher_id);
        }
        public function searchMyClass($formId, $stream) {

            return MyClass::where([ ['form_id', '=', $formId], ['stream', '=', $stream] ])->first();
        }
        public function getClassList($form_id, $stream = null, $csubject = null) {
            if ($stream == null && $csubject == null) {
                
                $myclass = MyClass::where('form_id', $form_id)->get();
                return $myclass;
                // $i = 0;
                // foreach($myclass as $mc) {                
                //     foreach($mc->students as $value) {
                //         $i++;
                //         if($i == 14) {
                //             return $value;
                //         }
                //     }
                // }
            } else if($stream != null && $csubject == null) {
                

            } else if($stream == null && $csubject != null) {
                
            } else {
                
            }
        }
    /************* Section *******************/

    public function createSection($data)
    {
        return Section::create($data);
    }

    public function findSection($id)
    {
        return Section::find($id);
    }

    public function updateSection($id, $data)
    {
        return Section::find($id)->update($data);
    }

    public function deleteSection($id)
    {
        return Section::destroy($id);
    }

    public function isActiveSection($section_id)
    {
        return Section::where(['id' => $section_id, 'active' => 1])->exists();
    }

    public function getAllSections()
    {
        return Section::orderBy('name', 'asc')->with(['my_class', 'teacher'])->get();
    }

    public function getClassSections($class_id)
    {
        return Section::where(['my_class_id' => $class_id])->orderBy('name', 'asc')->get();
    }
        
    /************* ClassSubject *******************/
        public function findSubjectByClass($class_id, $order_by = 'name')
        {
            return $this->getSubject(['my_class_id'=> $class_id])->orderBy($order_by)->get();
        }
        public function getSubject($data)
        {
            return ClassSubject::where($data);
        }
        public function deleteSubjectTeacher($classSubjectId) {
            return ClassSubject::where('id', $classSubjectId)->update(['teacher_id' => null]);
        }
        
        public function assignSubjectTeacher($classSubjectId, $teacher_id) {
            return ClassSubject::where('id', $classSubjectId)->update(['teacher_id' => $teacher_id]);
        }

        public function deleteClassSubject($classSubjectId) {
            return ClassSubject::where('id', $classSubjectId)->delete();
        }
    //not used yet
    public function createSubject($data)
    {
        return Subject::create($data);
    }

    public function findSubject($id)
    {
        return Subject::find($id);
    }


    public function findSubjectByTeacher($teacher_id, $order_by = 'name')
    {
        return $this->getSubject(['teacher_id'=> $teacher_id])->orderBy($order_by)->get();
    }


    public function getSubjectsByIDs($ids)
    {
        return Subject::whereIn('id', $ids)->orderBy('name')->get();
    }

    public function updateSubject($id, $data)
    {
        return Subject::find($id)->update($data);
    }

    public function deleteSubject($id)
    {
        return Subject::destroy($id);
    }

        public function getAllSubjects()
        {
            return Subject::orderBy('subject_type_id', 'asc')->get();
        }

        public function getMyClassSubjects($classId) {// filter subject that is taken by this class
            
            //$myclassId = ClassSubject::find($classId)->my_class_id;
            
            return MyClass::find($classId)->class_subject;
        }
    /************* Form *******************/

        public function getAllForms()
        {
            return Form::orderBy('id', 'asc')->get();
        }

        public function assignSupervisor($formId, $teacher_id) {
            return Form::where('id', $formId)->update(['teacher_id' => $teacher_id]);        
        }
        public function deleteSupervisor($formId) {
            return Form::where('id', $formId)->update(['teacher_id' => 0]);
        }
        public function findForm($id) {
            return Form::find($id);
        }
    
    
}
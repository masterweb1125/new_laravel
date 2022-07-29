<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientPhone;

class PhoneNumberController extends Controller
{
    
    public function index()
    {     
        $all_phone_numbers = ClientPhone::get();        
        return view('pages.support_team.phone_numbers.index', compact('all_phone_numbers'));
    }
    public function store(Request $req)
    {
        $model = new ClientPhone;
        $model['name'] = $req->client_name;
        $model['phone'] = $req->phone_number;
        $model->save();
        return json_encode(['ok' => true, 'msg' => "Saved Successfully"]);
    }
    public function deletephonenumber(Request $req) {
        
        if(ClientPhone::where('id', $req->id)->delete())
        return json_encode(['ok' => true, 'msg' => "Deleted Successfully"]);
        return json_encode(['ok' => false, 'msg' => "An error occured"]);
    }
}

@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

    @if(Qs::userIsAdmin())
       <div class="row">
           <div class="col-sm-6 ">
               <div class="card card-body has-bg-image">
               <textarea class="form-control-lg" id="textAreaExample2" rows="4" placeholder="Input Automated Messsage!"></textarea>
               </div>
           </div>

           <div class="col-sm-6 ">
               <div class="card card-body">
                    <div class="row d-flex justify-content-center">
                        <h1 id="auto_message" class="text-primary" >Enter your Message!</h1>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 d-flex justify-content-center">
                        <input class="w-50 " placeholder="Input Phone Number" />
                        </div>
                        <div class="col-sm-6 d-flex justify-content-center">
                        <button class="w-25" onclick={}>Send</button>
                        </div>
                    </div>
               </div>
           </div>
       </div>
    @endif

    @endsection

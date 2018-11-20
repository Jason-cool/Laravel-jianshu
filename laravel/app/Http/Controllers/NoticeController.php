<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notice;

class NoticeController extends Controller
{
   public function index()
   {
       $user = \Auth::user();
       $notices = $user->notices;
       return view('notice/index',compact('notices'));
   }
}

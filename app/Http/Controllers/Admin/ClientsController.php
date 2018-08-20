<?php

namespace hispanicus\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use hispanicus\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.passport.clients');
    }

    public function personal()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.passport.personal');
    }

    public function accessCode(){
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.users.access-code');
    }

}
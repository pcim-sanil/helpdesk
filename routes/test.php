<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/test', function () {

    $users = DB::connection('helpdesk')->table('users')->get();
    return response()->json($users);
});

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;

class PersonUserController extends Controller
{
    //

    public function index()
    {
        $persons = Person::orderBy('created_at', 'desc')->paginate(10);
        return view('front.person-user.index', compact('persons'));
    }
    public function create()
    {
        return view('front.person-user.create');
    }
    public function store() {}
    public function destroy() {}
    public function update() {}
}

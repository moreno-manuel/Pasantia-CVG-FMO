<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRecoveries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/* controlador para crear 
y editar las preguntas de seguridad 
de un usuario */

class QuestionsSecurityController extends Controller
{

    public function showForm()
    {
        $questions = json_decode(file_get_contents(resource_path('js/data.json')), true)['questions']; // json con las marcas agregadas

        if (Auth::user()->questionsRecovery) {
            $user = User::find(Auth::user()->id);
            return view('front.perfil.editQuestion', compact('user', 'questions'));
        }

        return view('front.perfil.createQuestion', compact('questions'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question_1' => 'required',
            'question_2' => 'required',
            'question_3' => 'required',
            'answer_1' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'answer_2' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'answer_3' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ], [], ['answer_1' => 'Respuesta 1', 'answer_2' => 'Respuesta 2', 'answer_3' => 'Respuesta 3']);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);

        UserRecoveries::create([
            'user_id' => Auth::user()->id,
            'question_1' => $request->input('question_1'),
            'question_2' => $request->input('question_2'),
            'question_3' => $request->input('question_3'),
            'answer_1' => $request->input('answer_1'),
            'answer_2' => $request->input('answer_2'),
            'answer_3' => $request->input('answer_3'),
        ]);

        return redirect()->route('perfil.edit', ['user' => Auth::user()->userName])->with('success', 'Preguntas de seguridad creadas exitosamente.');
    }


    public function update(Request $request, $user)
    {

        $validator = Validator::make($request->all(), [
            'question_1' => 'required',
            'question_2' => 'required',
            'question_3' => 'required',
            'answer_1' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'answer_2' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
            'answer_3' => 'required|regex:/^[a-zA-Z0-9\s]+$/',
        ], [], ['answer_1' => 'Respuesta 1', 'answer_2' => 'Respuesta 2', 'answer_3' => 'Respuesta 3']);

        if ($validator->fails())
            return redirect()->back()->withInput()->withErrors($validator);

        $user = User::where('userName', $user)->first();

        $user->questionsRecovery->update([
            'question_1' => $request->input('question_1'),
            'question_2' => $request->input('question_2'),
            'question_3' => $request->input('question_3'),
            'answer_1' => $request->input('answer_1'),
            'answer_2' => $request->input('answer_2'),
            'answer_3' => $request->input('answer_3'),
        ]);

        return redirect()->route('perfil.edit', ['user' => Auth::user()->userName])->with('success', 'Preguntas de seguridad actualizadas exitosamente.');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//Validación creada por nosotros 
use App\Http\Requests\NewRegisterRequest;
use DB;
use App\Models\Register;

class FormsController extends Controller {

    //
    public function index() {
        $policies = DB::table('policies')->select(['id', 'policy'])
                ->where('active', true)
                ->get();
//        dump($policies);

        return view('forms', ['policies' => $policies]);
//        return 'cacota';
    }

    public function insert(NewRegisterRequest $request) {

        $inputRequest = $request->only(['name', 'surname']);
        $name = $inputRequest['name'];
        //Otra forma:
        $name = $request->get('name', 'NombreVacío');
        /**
         * Esto es una forma de inserción DIRECTA.
         */
        //DB::table('registers')->insert($request->only(['name', 'surname', 'age', 'policy_id', 'gender']));
        Register::create($request->only(['name', 'surname', 'age', 'policy_id', 'gender']));
        return redirect()->route('FormsThanks');
//        dd($request);
    }

    public function thanks() {
        return view('forms.thanks');
    }

}

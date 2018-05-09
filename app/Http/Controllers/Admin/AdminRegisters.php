<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Register;
use App\Models\Policy;
use App\Http\Requests\NewRegisterRequest;

class AdminRegisters extends Controller {

    //

    public function index(Request $request) {
        $registersQuery = Register::with('policy');
        if ($request->has('table_search')) {
            $registersQuery->where('name', 'like', '%' . $request->get('table_search') . '%')
                    ->orWhere('surname', 'like', '%' . $request->get('table_search') . '%')
                    ->orWhere('gender', 'like', '%' . $request->get('table_search') . '%')
                    ->orWhereHas('policy', function ($query) use ($request) {
                        $query->where('policy', 'like', '%' . $request->get('table_search') . '%');
                    });
        }
        $registers = $registersQuery->get();

        return view('admin.registersList', ['registers' => $registers, 'table_search' => $request->get('table_search')]);
    }

    public function editRegister(Register $register) {
//        dump($register);
        $policy = Policy::select(['id', 'policy'])->where('active', true)->get();
//        dump($policy);
        return view('admin.registersEdit', ['register' => $register, 'policy_list' => $policy]);
    }
    
    public function newRegister() {
        $policy = Policy::select(['id', 'policy'])->where('active', true)->get();
        return view ('admin.registersEdit',['policy_list' => $policy]);
    }

    /**
     * Función que actualizará un registro
     */
    public function updateRegister(NewRegisterRequest $request, Register $register) {

        $register->name = $request->get('name');
        $register->surname = $request->get('surname');
        $register->age = $request->get('age');
        $register->gender = $request->get('gender');

        $register->policy_id = $request->get('policy_id');

        $register->save();

        return redirect()->route('AdminRegistersList')->with('status', 'success')->with('msg', 'El registro #' . $register->id . ' ha sido actualizado');
    }

    
    public function insertRegister(NewRegisterRequest $request) {
        $register = new Register();
        $register->name = $request->get('name');
        $register->surname = $request->get('surname');
        $register->age = $request->get('age');
        $register->gender = $request->get('gender');

        $register->policy_id = $request->get('policy_id');

        $register->save();

        return redirect()->route('AdminRegistersList')->with('status', 'success')->with('msg', 'El registro #' . $register->id . ' ha sido insertado');
    }

}

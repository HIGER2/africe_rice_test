<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeChild;
use App\Models\EmployeeInformaton;
use App\Models\TypeAllowence;
use App\Notifications\EmployeeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    //

    public function index()
    {


        // dd("douma");

        if (Auth::guard('employees')->check()) {

            $employee = Auth::guard('employees')->user();
            $formData = EmployeeInformaton::with('children')
                ->where('employees_id', $employee->employeeId)->first();

            $type = TypeAllowence::with('staff_category')->get();

            // Session::put('type', $type);
            // Session::put('employee', $employee);
            // Session::put('formData', $infoGet);

            // return view('home', compact('type', 'employee', 'formData'));

            return view('home', compact('employee', 'type', 'formData'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function login(Request $request)
    {


        // dd($request->all());
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $employee = Employee::where('email', $request->email)->first();

        if (Hash::needsRehash($employee->password)) {
            $employee->update([
                "password" => Hash::make($request->password),
            ]);
        }

        $attemptWithNumero = Auth::guard('employees')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);



        // $request->session()->regenerate();

        // dd(Auth::guard('employees')->user());
        $employee = Auth::guard('employees')->user();

        // dd($employee->name);
        session::put('user', $employee);

        if (!$attemptWithNumero) {
            return back()->withErrors([
                'message' => 'Les informations d\'identification ne correspondent pas.',
            ]);
        }
        return redirect()->route('home');
    }

    public function save(Request $request)
    {

        try {
            DB::beginTransaction();
            if (Auth::guard('employees')->check()) {

                $employee = Auth::guard('employees')->user();
                $infoExist = EmployeeInformaton::where('employees_id', $employee->employeeId)->first();

                // return $employee;
                // return $employee->supervisor;

                // $supervisor =

                // return $employee->supervisor;

                if ($infoExist) {
                    return response()->json([
                        'message' => 'vous avez déja renseigné',
                        'data' => $infoExist
                    ], 401);
                }


                $information = $request->except('child');
                $information['status'] = true;
                $information['category'] = $employee->category;
                $information['employees_id'] = $employee->employeeId;
                $response = EmployeeInformaton::create($information);

                $children = [];

                // return $request->children;

                if ($request->children) {
                    foreach ($request->children as $data) {
                        $data['employee_informatons_id'] = $response->id;
                        $children[] = EmployeeChild::create($data);
                    }
                }
                $response->children = $children;
                Session::put('formData', $response);


                DB::commit();
                if ($employee->supervisor) {
                    // Envoyer une notification au supérieur
                    $employee->supervisor->notify(new EmployeeValidate($employee));
                }
                return response()->json(
                    [
                        'message' => 'information enregistré',
                        'data' => $response,
                        'error' => null
                    ],
                    200
                );
            } else {

                return redirect()->route('login');
            }

            return view('home', compact('employee', 'type'));
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'message' => "une erreur s'est produite",
                    'data' => null,
                    'error' => $th->getMessage()
                ],
                500
            );
        }
    }

    public function logout()
    {
        // Déconnecter l'utilisateur du guard 'employees'
        Auth::guard('employees')->logout();
        Session::forget('user');
        Session::flush();

        // session()->flush();
        // Rediriger l'utilisateur vers la page de connexion ou d'accueil
        return redirect()->route('login')->with('success', 'Déconnexion réussie');
    }
}
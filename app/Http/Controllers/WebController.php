<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeChild;
use App\Models\EmployeeInformaton;
use App\Models\TypeAllowence;
use App\Notifications\EmployeeValidate;
use Carbon\Carbon;
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

    public function destroy($id)
    {
        // Trouver l'employé et le supprimer
        $employee = EmployeeInformaton::findOrFail($id);
        $employee->delete();

        // Rediriger vers la route d'accueil après suppression
        return redirect()->route('home')->with('success', 'Employé supprimé avec succès');
    }

    public function handleAction($id, $action)
    {

        try {
            // Trouvez le formulaire avec l'ID donné
            $form = EmployeeInformaton::findOrFail($id);
            // Exécutez l'action appropriée en fonction du paramètre $action

            if (!$form) {
                // Génère une erreur 404 avec un message personnalisé
                return  abort(404, 'Le formulaire demandé n\'existe pas.');
            }

            if ($action === 'approve') {
                // Logique pour approuver le formulaire
                $form->status = 'approved';
                $form->save();
                session::flash('message', 'Formulaire approuvé');
            } elseif ($action === 'reject') {
                // Logique pour rejeter le formulaire
                $form->status = 'rejected';
                $form->save();
                session::flash('message', 'Formulaire rejeté');
            } else {
                session::flash('message', 'Action invalide');
            }

            // Rediriger vers une page d'affichage ou vers une autre vue
            return redirect()->route('form.status', ['action' => $action]);
            // ->with('message', $message);
        } catch (\Throwable $th) {
        }
    }

    public function showStatus($action)
    {
        // Cette méthode est appelée pour afficher le message
        return view('form_status', ['action' => $action]);
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
                // Carbon::setLocale('fr');
                // // Convertir et formater la date
                // $infoExist->depart_date = Carbon::parse($infoExist->depart_date)->translatedFormat('d F Y');

                // if ($employee->supervisor) {
                //     // Envoyer une notification au supérieur
                //     $employee->supervisor->notify(new EmployeeValidate($employee, $infoExist));
                // }
                // return $employee->supervisor;

                if ($infoExist) {
                    return response()->json([
                        'message' => 'vous avez déja renseigné',
                        'data' => $infoExist
                    ], 401);
                }


                $information = $request->except('child');
                $information['status_input'] = true;
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

                $response->depart_date = Carbon::parse($response->depart_date)->translatedFormat('d F Y');

                Carbon::setLocale('fr');
                // Convertir et formater la date

                if ($employee->supervisor) {
                    // Envoyer une notification au supérieur
                    $employee->supervisor->notify(new EmployeeValidate($employee, $response));
                }

                DB::commit();
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
            DB::rollBack();
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

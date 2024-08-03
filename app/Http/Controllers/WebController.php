<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Employee;
use App\Models\EmployeeChild;
use App\Models\EmployeeInformaton;
use App\Models\TypeAllowance;
use App\Notifications\EmployeeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

            $type = TypeAllowance::with('staff_categories')->get();
            $currency = Currency::first();

            // Session::put('type', $type);
            // Session::put('employee', $employee);
            // Session::put('formData', $infoGet);

            // return view('home', compact('type', 'employee', 'formData'));

            return view('home', compact('employee', 'type', 'formData', 'currency'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function setting()
    {

        if (!Auth::guard('employees')->check()) {
            return redirect()->route('login');
        }

        $type_allowence = TypeAllowance::with('staff_categories')->get();
        $currency = Currency::first();
        // $type_allowences = $type_allowence->staff_categories;
        $staffCategories = [];
        $selectedTypeAllowenceId = null;
        $selectedStaffCategoriId = null;
        $amount = null;

        // $dataSetting = (object)[
        //     // "currency" => $currency['value'],
        //     "type_allowence" => $type_allowence
        // ];

        return view('setting', compact('type_allowence', 'currency', 'staffCategories', 'selectedTypeAllowenceId', 'selectedStaffCategoriId', 'amount', 'currency'));
    }



    public function recursive($data)
    {

        if ($data) {
        }
    }
    public function settingIndex(Request $request)
    {
        try {

            DB::beginTransaction();


            // return $request->all();
            $data = $request->type_allowance;
            $currency = Currency::first();


            $find_allowence = "";

            // return $dat  a;
            $response = [];
            foreach ($data as $value) {
                // return $value['id'];
                $find_allowence = TypeAllowance::find($value['id']);
                // return $find_allowence->staff_categories;

                if ($find_allowence) {
                    foreach ($value['staff_categories'] as $value2) {
                        $staffCategory =  $find_allowence->staff_categories->find($value2['id']);
                        if ($staffCategory) {
                            $staffCategory->update($value2);
                            $response[] = $find_allowence;
                        }
                    }
                }
            }

            if ($request->currency != $currency->value) {
                $currency->update(['value' => $request->currency]);
            }


            // return view('setting', compact('type_allowence', 'staffCategories', 'selectedTypeAllowenceId', 'selectedStaffCategoriId', 'amount', 'currency'));


            DB::commit();
            return response()->json([
                'message' => "ok",
                "data" => $request->currency
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "ok",
                "error" => $th,
            ], 500);
            //throw $th;;
        }
        // if (!Auth::guard('employees')->check()) {
        //     return redirect()->route('login');
        // }
        // $selectedTypeAllowenceId = $request->input('type_allowance_id');
        // $selectedStaffCategoriId = $request->input('staff_category_id');
        // $form_action = $request->input('form_action');


        // $type_allowence = TypeAllowance::with('staff_categories')->get();
        // $find_allowence = TypeAllowance::find($selectedTypeAllowenceId);
        // $staffCategories = $find_allowence ? $find_allowence->staff_categories : [];

        // $amount = null;
        // $currency = null;
        // $findCategorie = null;


        // if ($selectedTypeAllowenceId && $selectedStaffCategoriId) {
        //     $findCategorie =  $find_allowence->staff_categories->find($selectedStaffCategoriId);
        //     if ($findCategorie) {
        //         $amount = $findCategorie->amount;
        //         $currency = $findCategorie->currency;
        //     }
        // }
        // // Vérifiez si des catégories de personnel existent
        // // $hasStaffCategories = $staffCategories->isNotEmpty();

        // if ($form_action == "submit_final" && $findCategorie) {
        //     try {
        //         $findCategorie->amount = $request->amount;
        //         $findCategorie->currency = $request->currency;
        //         $findCategorie->save();
        //         $amount = $findCategorie->amount;
        //         $currency = $findCategorie->currency;
        //         return view('setting', compact('type_allowence', 'staffCategories', 'selectedTypeAllowenceId', 'selectedStaffCategoriId', 'amount', 'currency'));

        //         return redirect()->back()->with('success', 'Information updated successfully.');
        //     } catch (\Exception $e) {
        //         return redirect()->back()->with('error', 'Failed to update information. Please try again.')
        //             ->with('errorDetails', $e->getMessage());
        //     }
        // }
        // Réinvoquez la vue avec les données nécessaires
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
                // session::flash('message', 'Formulaire approuvé');
            } elseif ($action === 'reject') {
                // Logique pour rejeter le formulaire
                $form->status = 'rejected';
                $form->save();
                // session::flash('message', 'Formulaire rejeté');
            } else {
                // session::flash('message', 'Action invalide');
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
                Log::info('Notification envoyée avec succès à ' . $employee->supervisor->email);
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
            Log::error('Erreur lors de l\'envoi de la notification : ' . $th->getMessage());
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
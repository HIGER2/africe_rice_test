<?php

namespace App\Http\Controllers;

use App\Mail\GroupEmail;
use Illuminate\Support\Collection;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\EmployeeChild;
use App\Models\EmployeeInformaton;
use App\Models\ExchangeRate;
use App\Models\StaffCategorie;
use App\Models\TypeAllowance;
use App\Notifications\EmployeeValidate;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class WebController extends Controller
{
    //

    public function newTypeAllowance($type_allowence)
    {
        $newTypeAllowance = collect();

        foreach ($type_allowence as $data) {
            $GSS1A5 = $data->staff_categories->slice(0, 5)->values();
            $GSS6A9 = $data->staff_categories->slice(5, 4)->values();
            $APS = $data->staff_categories->get(9);
            $PS = $data->staff_categories->get(10);

            // dd($GSS6A9);
            $newTypeAllowance->push((object)[
                "id" => $data->id,
                "type" => $data->name,
                "staff_categories" => [
                    (object)[
                        "id" => "GSS1-GSS5",
                        "name" => "GSS 1-5",
                        "amount" => $GSS1A5[0]->amount,
                        "currency" => $GSS1A5[0]->currency
                    ],
                    (object)[
                        "id" => "GSS6-GSS9",
                        "name" => "GSS 6-9",
                        "amount" => $GSS6A9[0]->amount,
                        "currency" => $GSS6A9[0]->currency
                    ],
                    (object)[
                        "id" => $APS->id,
                        "name" => "APS",
                        "amount" => $APS->amount,
                        "currency" => $APS->currency
                    ],

                    (object)[
                        "id" => $PS->id,
                        "name" => "PS",
                        "amount" => $PS->amount,
                        "currency" => $PS->currency
                    ],
                ]
            ]);
        }

        return  $newTypeAllowance;
    }
    public function index()
    {


        if (Auth::guard('employees')->check()) {

            $employee = Auth::guard('employees')->user();
            $formData = EmployeeInformaton::with('children')
                ->where('employees_id', $employee->employeeId)->first();
            $type = TypeAllowance::with('staff_categories')
                ->where('id', '!=', 5)
                ->get();
            $currency = ExchangeRate::first();

            return view('home', compact('employee', 'type', 'formData', 'currency'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function showInformations()
    {

        if (Auth::guard('employees')->check()) {

            $employee = Auth::guard('employees')->user();
            $liste = EmployeeInformaton::with('employees')->get();
            $all =  $liste->count();
            $pending =  $liste->where('status', null)->count();
            $approuve =  $liste->where('status', 'approved')->count();
            $rejected =  $liste->where('status', 'rejected')->count();
            return view('liste', compact('liste', 'pending', 'approuve', 'rejected', 'all'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function setting()
    {

        $type_allowence = TypeAllowance::where('id', '!=', 5)->get();
        $rate = ExchangeRate::first();

        // $type_allowences = $type_allowence->staff_categories;
        $staffCategories = collect();
        $selectedTypeAllowenceId = null;
        $selectedStaffCategoriId = null;
        $amount = null;
        $currency = null;
        $newTypeAllowance = $this->newTypeAllowance($type_allowence);
        // Créer une nouvelle collection


        return view('setting', compact(
            'type_allowence',
            'newTypeAllowance',
            'staffCategories',
            'selectedTypeAllowenceId',
            'selectedStaffCategoriId',
            'amount',
            'currency',
            'rate'
        ));
    }


    public function settingIndex(Request $request)
    {



        $type_allowence = TypeAllowance::get();
        $rate = ExchangeRate::first();

        $selectedTypeAllowenceId = $request->input('type_allowance_id');
        $selectedStaffCategoriId = $request->input('staff_category_id');
        $form_action = $request->input('form_action');
        $newTypeAllowance = $this->newTypeAllowance($type_allowence);

        // $find_allowence = TypeAllowance::find($selectedTypeAllowenceId);
        // $staffCategories = $find_allowence ? $find_allowence->staff_categories : [];

        $amount = null;
        $currency = null;
        $findCategorie = null;

        $staffCategories = collect();

        // selectionner les staff categorie d'un type
        if ($selectedTypeAllowenceId) {

            $found = $newTypeAllowance->firstWhere('id', $selectedTypeAllowenceId);
            $staffCategories = $staffCategories->collapse()->merge($found->staff_categories);
        }

        if ($selectedTypeAllowenceId &&  $selectedStaffCategoriId) {

            $found = $newTypeAllowance->firstWhere('id', $selectedTypeAllowenceId);

            $categorie  = collect($found->staff_categories)->firstWhere('id', $selectedStaffCategoriId);
            if ($categorie) {
                $currency = $categorie->currency;
                $amount = $categorie->amount;
            }

            // dd();
        }



        if ($form_action == "submit_final") {



            $validator = Validator::make($request->all(), [
                'currency' => 'required',
                'amount' => 'required|numeric',
                'staff_category_id' => 'required',
            ]);

            // $request->validate([
            //     'currency' => 'required',
            //     'amount' => 'required',
            //     'staff_category_id' => 'required',
            // ], [
            //     'currency.required' => 'The currency field is required.',
            //     'amount.required' => 'The amount field is required.',
            //     'staff_category_id.required'
            //     => 'The staff field is required.',
            // ]);
            try {

                if ($request->rate != "" && ($request->rate  != $rate->value)) {

                    $rate->update(['value' => $request->rate]);

                    $succes = 'Rate updated successfully.';
                    return view('setting', compact(
                        'newTypeAllowance',
                        'type_allowence',
                        'staffCategories',
                        'selectedTypeAllowenceId',
                        'selectedStaffCategoriId',
                        'amount',
                        'currency',
                        'rate',
                        'succes'

                    ));
                }

                if ($validator->validated()) {
                    $findNew = $newTypeAllowance->firstWhere('id', $selectedTypeAllowenceId);

                    $find_allowence = TypeAllowance::find($selectedTypeAllowenceId);
                    $staffCategory = "";

                    if ($selectedStaffCategoriId == "GSS6-GSS9") {
                        $GSS6A9 = $find_allowence->staff_categories->slice(5, 4)->values();
                        foreach ($GSS6A9 as $category) {
                            $staffCategory = StaffCategorie::where('id', $category->id)->first();
                            if ($staffCategory) {
                                $staffCategory->amount = $request->amount;
                                $staffCategory->currency = $request->currency;
                                $staffCategory->save(); // Enregistrer les modifications
                            }
                        }
                    } else if ($selectedStaffCategoriId == "GSS1-GSS5") {
                        $GSS1A5 = $find_allowence->staff_categories->slice(0, 5)->values();
                        foreach ($GSS1A5 as $category) {
                            $staffCategory = StaffCategorie::where('id', $category->id)->first();
                            if ($staffCategory) {
                                $staffCategory->amount = $request->amount;
                                $staffCategory->currency = $request->currency;
                                $staffCategory->save(); // Enregistrer les modifications

                            }
                        }
                    } else {
                        $staffCategory = StaffCategorie::where('id', $selectedStaffCategoriId)->first();
                        if ($staffCategory) {
                            $staffCategory->amount = $request->amount;
                            $staffCategory->currency = $request->currency;
                            $staffCategory->save(); // Enregistrer les modifications

                        }
                    }

                    $type_allowence = TypeAllowance::where('id', '!=', 5)->get();
                    $newTypeAllowance = $this->newTypeAllowance($type_allowence);

                    $currency = $request->currency;
                    $amount = $request->amount;

                    // session::flash('succes', 'Information updated successfully.');
                    $succes = 'Information updated successfully.';
                    return view('setting', compact(
                        'newTypeAllowance',
                        'type_allowence',
                        'staffCategories',
                        'selectedTypeAllowenceId',
                        'selectedStaffCategoriId',
                        'amount',
                        'currency',
                        'rate',
                        'succes'

                    ));

                    // return redirect()->back()->with('success', 'Information updated successfully.');
                }

                // return view('setting', compact('type_allowence', 'staffCategories', 'selectedTypeAllowenceId', 'selectedStaffCategoriId', 'amount', 'currency'));

            } catch (\Exception $e) {
                // return redirect()->back()->with('error', 'Failed to update information. Please try again.')
                //     ->with('errorDetails', $e->getMessage());
            }
        }
        // Réinvoquez la vue avec les données nécessaires

        return view('setting', compact('rate', 'newTypeAllowance', 'type_allowence', 'staffCategories', 'selectedTypeAllowenceId', 'selectedStaffCategoriId', 'amount', 'currency'));
    }



    public function recursive($data)
    {

        if ($data) {
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

    // methode pour gerer le statut de la demande
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
                // $form->status = 'approved';
                // $form->save();
                $employee = $form->employees;
                Carbon::setLocale('fr');
                $depart_date = Carbon::parse($form->depart_date)->translatedFormat('l d F Y');
                $taking_date = Carbon::parse($form->taking_date)->translatedFormat('l d F Y');

                $message = "la demande de l'utilisateur {$employee->firstName} {$employee->lastName} à été approuvée.Le montant à verser est de {$form->total_amount} CFA.\n La date de départ {$depart_date}\n.Prise de fonction {$taking_date}.
                    ";
                $recipients = [
                    (object)[
                        'email' => 'k.sams@cgiar.org',
                        'message' => $message,
                    ],
                    (object)[
                        'email' => 'user2@example.com',
                        'message' => $message,
                    ],
                    (object)[
                        'email' => 'user3@example.com',
                        'message' => $message,
                    ]
                ]; // Liste des e-mails
                // $data = ['message' => 'Ceci est un e-mail groupé envoyé à plusieurs utilisateurs.'];
                foreach ($recipients as $data) {
                    Mail::to($data->email)->send(new GroupEmail($data->message)); // Utiliser la file d'attente pour envoyer les e-mails
                }


                // if ($employee->supervisor) {
                //     // Envoyer une notification au supérieur
                //     $employee->supervisor->notify(new EmployeeValidate($employee, $form, 'form_validate'));
                // }
                // session::flash('message', 'Formulaire approuvé');
            } elseif ($action === 'reject') {
                // Logique pour rejeter le formulaire
                // $form->status = 'rejected';
                // $form->save();
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

    // afficher le statut de la demande
    public function showStatus($action)
    {
        // Cette méthode est appelée pour afficher le message
        return view('form_status', ['action' => $action]);
    }

    // methode de connexon
    public function login(Request $request)
    {


        // dd($request->all());
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);


        $employee = Employee::where('email', $request->email)->first();
        if (!$employee) {
            return back()->withErrors([
                'message' => 'invalid email.',
            ]);
        }
        if (Hash::needsRehash($employee->password)) {
            $url = "https://mycareer.africarice.org/api/auth/login";
            $options = [
                'json' => [ // Utiliser 'json' pour envoyer les données sous forme JSON
                    "email" => $request->email,
                    "password" => $request->password
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ];
            $apiResponse = $this->fetchApi('POST', $url, $options);
            if ($apiResponse->error) {
                if ($apiResponse->response_body && $apiResponse->response_body == "Unauthorized") {
                    return back()->withErrors([
                        'message' => 'Les informations d\'identification ne correspondent pas.',
                    ]);
                }
                // dd($apiResponse->response_body);
            } else {
                // auth by api and check if credentail is correcte
                $employee->update([
                    "password" => Hash::make($request->password),
                ]);
            }
        }

        $attemptWithNumero = Auth::guard('employees')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);

        if (!$attemptWithNumero) {
            return back()->withErrors([
                'message' => 'Les informations d\'identification ne correspondent pas.',
            ]);
        }

        $employee = Auth::guard('employees')->user();
        session::put('user', $employee);
        return redirect()->route('home');
    }

    // sauvegarder la demande de l'utilisateur
    // public function save(Request $request)
    // {

    //     try {
    //         DB::beginTransaction();
    //         if (Auth::guard('employees')->check()) {

    //             $employee = Auth::guard('employees')->user();
    //             $infoExist = EmployeeInformaton::where('employees_id', $employee->employeeId)->first();

    //             $dateMaxe = Carbon::now()->addDays(30)->toDateString();

    //             if ($dateMaxe != $request->taking_date) {
    //                 return response()->json([
    //                     'message' => 'la date de prise de fonction doit etre au dela de 30 jours ',
    //                     'data' => $infoExist
    //                 ], 400);
    //             }
    //             // dd();
    //             // if () {
    //             //     # code...
    //             // }
    //             // return $employee;
    //             // return $employee->supervisor;

    //             // $supervisor =
    //             // Carbon::setLocale('fr');
    //             // // Convertir et formater la date
    //             $infoExist->depart_date = Carbon::parse($infoExist->depart_date)->translatedFormat('d F Y');

    //             if ($employee->supervisor) {
    //                 // Envoyer une notification au supérieur
    //                 $employee->supervisor->notify(new EmployeeValidate($employee, $infoExist));
    //             }
    //             // return $employee->supervisor;

    //             if ($infoExist) {
    //                 return response()->json([
    //                     'message' => 'vous avez déja une demande en cours',
    //                     'data' => $infoExist
    //                 ], 401);
    //             }


    //             $information = $request->except('child');
    //             $information['status_input'] = true;
    //             $information['category'] = $employee->category;
    //             $information['employees_id'] = $employee->employeeId;
    //             $response = EmployeeInformaton::create($information);

    //             $children = [];

    //             // return $request->children;

    //             if ($request->children) {
    //                 foreach ($request->children as $data) {
    //                     $data['employee_informatons_id'] = $response->id;
    //                     $children[] = EmployeeChild::create($data);
    //                 }
    //             }
    //             $response->children = $children;
    //             Session::put('formData', $response);

    //             $response->depart_date = Carbon::parse($response->depart_date)->translatedFormat('d F Y');

    //             Carbon::setLocale('fr');
    //             // Convertir et formater la date

    //             if ($employee->supervisor) {
    //                 // Envoyer une notification au supérieur
    //                 $employee->supervisor->notify(new EmployeeValidate($employee, $response));
    //             }

    //             DB::commit();
    //             // Log::info('Notification envoyée avec succès à ' . $employee->supervisor->email);
    //             return response()->json(
    //                 [
    //                     'message' => 'information enregistré',
    //                     'data' => $response,
    //                     'error' => null
    //                 ],
    //                 200
    //             );
    //         } else {
    //             return redirect()->route('login');
    //         }

    //         return view('home', compact('employee', 'type'));
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         Log::error('Erreur lors de l\'envoi de la notification : ' . $th->getMessage());
    //         return response()->json(
    //             [
    //                 'message' => "une erreur s'est produite",
    //                 'data' => null,
    //                 'error' => $th->getMessage()
    //             ],
    //             500
    //         );
    //     }
    // }

    public function save(Request $request)
    {


        try {
            DB::beginTransaction();
            if (Auth::guard('employees')->check()) {

                $employee = Auth::guard('employees')->user();
                $infoExist = EmployeeInformaton::where('employees_id', $employee->employeeId)->first();

                $dateMaxe = Carbon::now()->addDays(30)->toDateString();

                if ($dateMaxe > $request->taking_date || $dateMaxe > $request->depart_date) {
                    return response()->json([
                        'message' => 'la date de prise de fonction doit etre au dela de 30 jours ',
                        'data' => $infoExist
                    ], 400);
                }
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
                    $employee->supervisor->notify(new EmployeeValidate($employee, $response, 'form_submission'));
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

    // deconexion de lutilisateur
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


    public static function fetchApi(string $method, string $url, array $options = [])
    {
        $client = new Client([
            'verify' => false, // Désactive la vérification SSL
        ]);
        try {
            // Effectuer la requête à l'API avec Guzzle
            $response = $client->request($method, $url, $options);
            // Récupérer les données de la réponse et les décoder
            // $data = json_decode($response->getBody(), true);
            $data = json_decode($response->getBody()->getContents());

            return (object)[
                "data" => $data,
                "error" => false
            ];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $body = $response ? $response->getBody()->getContents() : 'No response body';
            return (object)[
                "data" => null,
                "error" => true,
                "message" => $e->getMessage(),
                "response_body" => $body
            ];
        } catch (RequestException $e) {
            // Capturer les exceptions Guzzle
            return (object)[
                "data" => false,
                "error" => $e->getMessage()
            ];
            // throw new \Exception("Erreur lors de la requête à l'API : " . $e->getMessage());
        }
    }



    public function sendGroupEmails($data)
    {

        // foreach ($data as $value) {
        // }
        // return true;
    }
}
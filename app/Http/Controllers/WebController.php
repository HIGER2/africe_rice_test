<?php

namespace App\Http\Controllers;

use App\Mail\GroupEmail;
use Illuminate\Support\Collection;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\StaffChild;
use App\Models\StaffRequest;
use App\Models\ExchangeRate;
use App\Models\Payment;
use App\Models\ServiceEmail;
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
            $formData = StaffRequest::with('children')
                ->with('payments')
                ->where('employees_id', $employee->employeeId)
                ->latest()
                ->first();
            $type = TypeAllowance::with('staff_categories')
                ->where('id', '!=', 5)
                ->get();
            $currency = ExchangeRate::first();

            $authorize = $this->inAuthorize($employee->role, "admin", 'liste');

            if ($authorize) {
                return $authorize;
            }
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
            $liste = StaffRequest::with('employees')
                ->orderBy('created_at', 'desc')
                ->get();
            $all =  $liste->count();
            $pending =  $liste->where('status', 'pending')->count();
            $approuve =  $liste->where('status', 'approved')->count();
            $rejected =  $liste->where('status', 'rejected')->count();
            return view('liste', compact('liste', 'pending', 'approuve', 'rejected', 'all'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function myRequest()
    {

        if (Auth::guard('employees')->check()) {

            $employee = Auth::guard('employees')->user();

            // dd($employee);
            $liste = StaffRequest::with('employees')
                ->where('employees_id', $employee->employeeId)
                ->orderBy('created_at', 'desc')
                ->get();
            $all =  $liste->count();
            $pending =  $liste->where('status', 'pending')->count();
            $approuve =  $liste->where('status', 'approved')->count();
            $rejected =  $liste->where('status', 'rejected')->count();
            return view('my_request', compact('liste', 'pending', 'approuve', 'rejected', 'all'));
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    private function getRequestApprouvedData()
    {
        $liste = StaffRequest::with('employees')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get();

        $all = $liste->count();
        $pending = $liste->where('status', null)->count();
        $approuve = $liste->where('status', 'approved')->count();
        $rejected = $liste->where('status', 'rejected')->count();

        return compact('liste', 'pending', 'approuve', 'rejected', 'all');
    }

    public function requestApprouved()
    {
        if (Auth::guard('employees')->check()) {
            $employee = Auth::guard('employees')->user();
            $data = $this->getRequestApprouvedData();
            return view('request_approuve', $data);
        } else {
            return redirect()->route('login');
            // return redirect()->route('login', compact('type', 'employee', 'formData'));
        }
    }

    public function paymentConfirm(Request $request)
    {
        $request->validate([
            'request_id' => "required",
            'datepaiement' => "required",
        ], [
            "datepaiement.required" => "Please add an payement date."
        ]);

        // dd(Carbon::parse($request->datepaiement)->toDateString());
        $employee = Auth::guard('employees')->user();
        $staff_request = StaffRequest::find($request->request_id);
        // dd($staff_request);

        if (!isset($staff_request->payments)) {
            return abort(404, 'Formulaire non trouvé.');
        }

        if ($staff_request && ($staff_request->payments->status_payment == "pending")) {
            $staff_request->payments->status_payment = "paid";
            $staff_request->payments->finance_id  = $employee->employeeId;
            $staff_request->payments->date_payment = Carbon::parse($request->datepaiement)->toDateString();
            $staff_request->payments->save();
        }
        return redirect()->route('request.approve');
        // $data = $this->getRequestApprouvedData();
        // return view('request_approuve', $data);
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
        $type_allowence = TypeAllowance::where('id', '!=', 5)->get();
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

    public function confirmAction($id, $action)
    {
        try {

            // dd('dd');
            $form = StaffRequest::findOrFail($id);

            // Vérifie si l'action est valide
            if (!in_array($action, ['approve', 'reject'])) {
                return abort(400, 'Action non valide.');
            }

            // Afficher la page de confirmation
            return view('confirmation', compact('form', 'action'));
        } catch (\Exception $e) {
            return abort(404, 'Formulaire non trouvé.');
        }
    }


    public function destroy($id)
    {
        // Trouver l'employé et le

        $user =  Auth::guard('employees')->user();

        //  $user->employees
        // $user->information_employees->delete();
        if ($user->staff_requests) {
            $request = $user->staff_requests->where('id', $id)->first();
            $request->status_input = false;
            $request->save();

            // $user->staff_requests->each(function ($staff_request) {
            //     $staff_request->delete();
            //     $staff_request->save();
            // });
        }

        // Rediriger vers la route d'accueil après suppression
        return redirect()->route('home')->with('success', 'Employé supprimé avec succès');
    }

    // methode pour gerer le statut de la demande
    public function handleAction($id, $action)
    {
        try {

            DB::beginTransaction();
            $now = Carbon::now()->format('d-m-Y H:i:s');
            Log::channel('custom_controller_log')->info('*************');
            Log::channel('custom_controller_log')->info("demandeId:{$id} action:{$action} date:{$now}");
            Log::channel('custom_controller_log')->info('*************');

            // Trouvez le formulaire avec l'ID donné
            $form = StaffRequest::findOrFail($id);

            if (!$form) {
                // dd('jje');
                abort(404, 'Le formulaire demandé n\'existe pas.');
            }

            // if ($form->status != 'pending') {
            //     abort(404, 'demande déja validée.');
            // }

            $employee = $form->employees;
            Carbon::setLocale('fr');
            $depart_date = Carbon::parse($form->depart_date)->translatedFormat('d l F Y');
            $taking_date = Carbon::parse($form->taking_date)->translatedFormat('d l F Y');

            if ($action === 'approve') {
                $form->status = 'approved';
                $form->save();
                $form->taking_date = $taking_date;
                $form->depart_date = $taking_date;
                $payment = Payment::create([
                    'staff_requests_id' => $form->id,
                    'staff_id' => $form->employees->employeeId,
                    'amount' => $form->total_amount
                ]);

                $servieEmail = ServiceEmail::get();
                $recipiend = [
                    (object)[
                        'email' => 'k.sams@cgiar.org',
                        'message' => $form,
                        'view' => 'finance_validate'
                    ],
                    (object)[
                        'email' => $employee->supervisor->email,
                        'message' => "Hello,\n\nYou have approved the departure request of staff member **{$employee->firstName} {$employee->lastName}** for Bouaké.\nThe departure request is for **{$depart_date}**.\nThe taking up of office is scheduled for **{$taking_date}**.",
                        'view' => 'group'
                    ],
                    (object)[
                        'email' => 'k.sams@cgiar.org',
                        'message' => "Hello,\n\nThe departure date for Bouaké for staff member **{$employee->firstName} {$employee->lastName}** is scheduled for **{$depart_date}**.\n\nThe taking up of office is scheduled for **{$taking_date}**.\n\nPlease prepare all the necessary administrative documents.",
                        'view' => 'group'
                    ],
                    (object)[
                        'email' => $employee->email,
                        'message' => "Hello,\n\nYour departure request for Bouaké on **{$depart_date}** has been approved.",
                        'view' => 'group'
                    ]
                ];

                $messageService = [
                    (object)[
                        'data' => $form,
                        'view' => 'finance_validate',
                        'service' => 1
                    ],
                    (object)[
                        'data' => "Hello,\n\nThe departure date for Bouaké for staff member **{$employee->firstName} {$employee->lastName}** is scheduled for **{$depart_date}**.\n\nThe taking up of office is scheduled for **{$taking_date}**.\n\nPlease prepare all the necessary administrative documents.",
                        'view' => 'group',
                        'service' => 2
                    ],
                    (object)[
                        'data' => "Hello everyone,\n\n In the context of the departure for Bouaké, the staff member **{$employee->firstName} {$employee->lastName}** has had their departure request approved. Thus, the staff member will leave Abidjan on **{$depart_date}**.\n\n And will start their position on **{$taking_date}**.\n\n Please prepare the logistics for their departure as well as all the logistics related to their new position.",
                        'view' => 'group',
                        'service' => 3
                    ],
                ];

                $backMessage = [
                    (object)[
                        'data' => "Hello,\n\nYou have approved the departure request of staff member **{$employee->firstName} {$employee->lastName}** for Bouaké.\nThe departure request is for **{$depart_date}**.\nThe taking up of office is scheduled for **{$taking_date}**.",
                        'view' => 'group',
                        'email' => $employee->supervisor->email,
                    ],
                    (object)[
                        'data' => "Hello,\n\n Your departure request for Bouaké on **{$depart_date}** has been approved.",
                        'view' => 'group',
                        'email' => $employee->email,
                    ],
                ];

                $recipients = [];
                $emailData = "";
                if ($servieEmail->count() > 0) {
                    foreach ($servieEmail as $data) {
                        foreach ($messageService as $value) {
                            if ($data->service == $value->service) {
                                $recipients[] = (object)[
                                    "email" => $data->email,
                                    "data" => $value->data,
                                    "view" => $value->view
                                ];
                                break; // Ajouter un log ici peut être utile pour vérifier le processus.
                            }
                        }
                    }
                }

                if ($servieEmail->count() > 0) {
                    $emailData = array_merge($recipients, $backMessage);
                } else {
                    $emailData = $backMessage;
                }


                // $data = $emailData[5]; // Par exemple, accéder au 6ème email
                // Mail::to($data->email)->queue(new GroupEmail($data->data, $data->view));
                // foreach (array_chunk($emailData, $batchSize) as $batch) {
                //     foreach ($batch as $data) {
                //         Mail::to($data->email)->queue(new GroupEmail($data->data, $data->view));
                //     }
                //     sleep(10); // Pause de 10 secondes entre chaque lot
                // }
                // foreach ($emailData as $data) {
                //     Mail::to($data->email)->send(new GroupEmail($data->data, $data->view));
                // }

                foreach ($emailData as $data) {
                    Mail::to($data->email)->send(new GroupEmail($data->data, $data->view));
                }
                // dd($servieEmail);
            } elseif ($action == 'reject') {
                // Logique pour rejeter le formulaire
                $form->status = 'rejected';
                $form->status_input = false;
                $form->save();

                $recipients = [
                    (object)[
                        'email' => $employee->supervisor->email,
                        'message' =>
                        "Hello,\n\nYou have rejected the departure request of staff member **{$employee->firstName} {$employee->lastName}** for Bouaké.\nThe departure request is for **{$depart_date}**.\nThe taking up of office is scheduled for **{$taking_date}**.",
                        'view' => 'group'
                    ],
                    (object)[
                        'email' => $employee->email,
                        'message' => "Hello,\n\nYour departure request for Bouaké on **{$form->depart_date}** has been rejected.",
                        'view' => 'group'
                    ]

                ];

                foreach ($recipients as $data) {
                    Mail::to($data->email)->send(new GroupEmail($data->message, $data->view));
                }
            } else {
                // Action invalide
                return abort(400, 'Action invalide.');
            }

            // Rediriger vers une page d'affichage ou vers une autre vue

            DB::commit();
            return redirect()->route('form.status', ['action' => $action]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error("Erreur lors du traitement de la demande : " . $th->getMessage());
            return abort(500, 'Une erreur est survenue.');
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

        if ($employee->role == "admin") {
            return redirect()->route('liste');
        }

        return redirect()->route('home');
    }

    public function save(Request $request)
    {


        try {
            DB::beginTransaction();
            if (Auth::guard('employees')->check()) {

                $employee = Auth::guard('employees')->user();
                $infoExist = StaffRequest::where('employees_id', $employee->employeeId)->first();

                $dateMaxe = Carbon::now()->addDays(30)->toDateString();

                if ($dateMaxe > $request->taking_date || $dateMaxe > $request->depart_date) {
                    return response()->json([
                        'message' => 'la date de prise de fonction doit etre au dela de 30 jours ',
                        'data' => $infoExist
                    ], 400);
                }

                $information = $request->except('child');
                $information['status_input'] = true;
                $information['status'] = "pending";
                $information['category'] = $employee->category;
                $information['employees_id'] = $employee->employeeId;
                $response = StaffRequest::create($information);

                $children = [];


                if ($request->children) {
                    foreach ($request->children as $data) {
                        $data['staff_requests_id'] = $response->id;
                        $children[] = StaffChild::create($data);
                    }
                }
                $response->children = $children;
                Session::put('formData', $response);

                $response->depart_date = Carbon::parse($response->depart_date)->translatedFormat('d F Y');
                $response->taking_date = Carbon::parse($response->taking_date)->translatedFormat('d F Y');

                Carbon::setLocale('fr');

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


    public function serviceEmail($id = null)
    {
        $emails = ServiceEmail::get();

        $singleEmail = $emails->find($id);

        $services = collect([
            (object)[
                "id" => 1,
                "value" => "finance department",
                "emails" => [],
            ],
            (object)[
                "id" => 2,
                "value" => "human resources department",
                "emails" => [],
            ],
            (object)[
                "id" => 3,
                "value" => "operation department",
                "emails" => [],
            ],
        ]);

        $listeFilter = collect();
        foreach ($services as $service) {
            foreach ($emails as $email) {
                if ($service->id == $email->service) {
                    $service->emails[] = $email;
                }
            }
            $listeFilter->push($service);
        }
        // dd($singleEmail);
        return view('service_email', compact('singleEmail', 'services', 'listeFilter'));
    }

    public function serviceEmailSave(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'service' => 'required',
        ], [
            'email.required' => 'Please add an email address.',
            'email.email' => 'The email address must be a valid email format.',
            'service.required' => 'Please select a service.',
        ]);
        $singleEmail = null;
        $emailExiste = ServiceEmail::where('service', $request->service)
            ->where('email', $request->email)->first();
        $verif = ServiceEmail::where('service', $request->service)->count();


        if ($emailExiste) {
            return redirect()->back()->with('error', 'This email already exists for this department.');
        }

        $services = collect([
            (object)[
                "id" => 1,
                "value" => "finance department",
                "emails" => [],
            ],
            (object)[
                "id" => 2,
                "value" => "human resources department",
                "emails" => [],
            ],
            (object)[
                "id" => 3,
                "value" => "operation department",
                "emails" => [],
            ],
        ]);
        if (isset($request->id)) {
            $data = $request->except('_token');
            ServiceEmail::where('id', $request->id)->update($data);
        } else {
            if ($verif >= 2) {
                return redirect()->back()->with('error', 'The limit of two records has been reached. You cannot add more records.');
            }
            ServiceEmail::create($request->all());
        }
        $emails = ServiceEmail::get();

        $listeFilter = collect();
        foreach ($services as $service) {
            foreach ($emails as $email) {
                if ($service->id == $email->service) {
                    $service->emails[] = $email;
                }
            }
            $listeFilter->push($service);
        }
        // dd($singleEmail);
        return view('service_email', compact('singleEmail', 'services', 'listeFilter'));
    }

    public function destroyEmail($id)
    {
        // Rechercher l'email par son ID
        $email = ServiceEmail::findOrFail($id);

        // Supprimer l'email
        $email->delete();
        // Rediriger vers la route d'accueil avec un message de succès
        return redirect()->route('service.email.get')->with('success', 'Email supprimé avec succès');
    }

    public function redirectUser($email)
    {
        $existeEmail = ["k.olatifede@cgiar.org", "admintest@example.com"];
        if (in_array($email, $existeEmail)) {
            return redirect()->route('liste');
        }
    }

    public function inAuthorize($role, $roles, $route)
    {

        if ($role == $roles) {
            return redirect()->route($route);
        }
        // if (is_array($role, $roles)) {
        //     return redirect()->route($route);
        // }
        return false;
    }
}
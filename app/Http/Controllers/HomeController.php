<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Mail;
use App\Mail\SendMail;
use Twilio\Rest\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    // public function index()
    // {
    //     return view('home');
    // }


    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = User::latest()->get();
            return Datatables::of($data)

                ->addIndexColumn()

                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })

                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
                    $btn = $btn . ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('all_users');
    }
    public function store(Request $request)
    {
        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        dd($request->all());

        // $file = $request->file('image');
        // $fileName = time() . '.' . $file->getClientOriginalExtension();
        // $file->storeAs('public/images', $fileName);


        // $productId = $request->product_id;


        $details = ['name' => $request->name, 'email' => $request->email];

        if ($request->hasFile('image')) {
            if (Input::file('image')->isValid()) {
                $file = Input::file('image');
                $destination = 'public/images' . '/';
                $ext = $file->getClientOriginalExtension();
                $mainFilename = str_random(6) . date('h-i-s');
                $file->move($destination, $mainFilename . "." . $ext);
                // echo "uploaded successfully";
                $details['image'] = "$mainFilename";
            }
        }
        dd($details);
        $product   =   User::updateOrCreate($details);

        return response()->json(['success' => 'User saved successfully.']);
    }

    public function sendMail(Request $request)
    {

        $userEmail =  User::latest('id')->select('email')
            ->first();
        $userEmail = "mohammedaavesh9@gmail.com";
        $testMailData = [
            'title' => 'Test Email From AllPHPTricks.com',
            'body' => 'This is the body of test email.'
        ];

        Mail::to($userEmail)->send(new SendMail($testMailData));

        dd('Success! Email has been sent successfully.');
    }

    public function sendsms_view()
    {
        // dd('dfsa');
        return view('send_sms_view');
    }
    public function sendsms_form(Request $request)
    {
        // dd($request->all());
        try {
            $acc_sid = env('TWILIO_SID');
            $acc_token = env('TWILIO_TOKEN');
            $from_num = env('TWILIO_FROM');

            $client = new Client($acc_sid, $acc_token);
            $client->messages->create('+91' . $request->mobile, [
                'from' => $from_num,
                'body' => $request->message,
            ]);
            return "Message  Sent ....!!";
        } catch (Exception $e) {
            dd("Error: " . $e->getMessage());
        }
    }
}

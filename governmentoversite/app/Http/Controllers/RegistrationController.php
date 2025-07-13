<?php

namespace App\Http\Controllers;

use \App\Models\User;
use \App\Http\Controllers\Log;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class RegistrationController extends Controller
{
    public function __construct()
    { 
        $this->stripeSecretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    /**
     * Add a user
     */
    public function addSubscription(Request $request)
    {
        try {
            
            $user = User::findOrFail($request->user()->id);
            
            $options = [
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => 'price_1MscIQB0XpEFDjQQiHaaBENz',
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ];
            if($user->customer != ""){
                $options['customer'] = $user->customer;
            }else{
                $options['customer_email'] = $user->email;
            }

            $session = Session::create($options);           
            $user->checkout_session_id = $session->id;
            $user->save();
            
            return Redirect::to($session->url);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
            //Log::error($e->getMessage());
        }
    }   // end of addSubscription()

    /**
     * Add a user
     */
    public function add(Request $request)
    {
        try {

            //lookup if this user is taken.
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'email', 'unique:users'],
                // Other validation rules
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors)->withInput();
            }else{
                $name = $request->input('name');
                $email = $request->input('email');
                $phone = $request->input('phone');
                $password = $request->input('password');
    
                $session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [
                        [
                            'price' => 'price_1Mt1TAB0XpEFDjQQbauNl1EF',
                            'quantity' => 1,
                        ],
                    ],
                    'mode' => 'subscription',
                    'customer_email' => $email,
                    'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout.cancel') . '?session_id={CHECKOUT_SESSION_ID}',
                ]);           
    
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'password' => bcrypt($password),
                    'checkout_session_id' => $session->id,
                    'role' => 2
                ]);
                $user->save();
                Auth::login($user);
    
                return Redirect::to($session->url);
            }

        } catch (ApiErrorException $e) {
            //return response()->json(['error' => $e->getMessage()], 500);
            Log::error($e->getMessage());
        }
    }   // end of add()
}

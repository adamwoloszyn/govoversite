<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Subscription;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

use App\Models\UserSubscription;
use App\Models\User;


class StripeController extends Controller
{
    protected $stripeSecretKey;

    public function __construct()
    {
        $this->stripeSecretKey = config('services.stripe.secret');
        Stripe::setApiKey($this->stripeSecretKey);
    }

    public function createCheckoutSession(Request $request)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price' => 'price_1Mt1TAB0XpEFDjQQbauNl1EF',
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'subscription',
                'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel'),
            ]);

            // return response()->json([
            //     'session_id' => $session->id,
            // ]);

            return Redirect::to($session->url);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cancelSubscription(Request $request)
    {
        try {
            $subscription_id = $request->input('subscription_id');
            // Retrieve the subscription from Stripe
            $subscription = Subscription::retrieve($subscription_id);

            // Cancel the subscription
            $subscription->cancel();

            // Retrieve the updated subscription data after cancellation
            $updatedSubscription = Subscription::retrieve($subscription_id);

            //remove the subscription from the table
            $userSubscription = UserSubscription::where('subscription', $subscription_id)->first();
            if ($userSubscription) {
                // Delete the record
                $userSubscription->delete();
                //return Redirect::to(route('UserSettings'));
            }else{
                //return Redirect::to(route('UserSettings'));
            }

            return response()->json(['updatedSubscription' => $updatedSubscription]);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createBillingPortalSession(Request $request)
    {
        try {
            $customerId = $request->input('customer_id');
            $session = BillingPortalSession::create([
                //'customer' => $request->user()->stripe_customer_id,
                'customer' => $customerId,
                'return_url' => route('UserSettings'),
            ]);

            return response()->json([
                'session_url' => $session->url,
            ]);
            //return Redirect::to($session->url);

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        try {

            //check if there is already a subscription and don't recreate.
            $session = Session::retrieve($_GET['session_id']);

            $customerId= $session->customer;
            $invoiceId= $session->invoice;
            $subscriptionId= $session->subscription;
            
            if ($session->payment_status === 'paid') {
                $user = User::where('checkout_session_id', $_GET['session_id'])->first();
                $user->customer = $customerId; 
                $user->save();

                $userSubscription = new UserSubscription();
                $userSubscription->user_id = $user->id; 

                $userSubscription->invoice = $invoiceId; 
                $userSubscription->subscription = $subscriptionId; 

                $userSubscription->start_date = now()->subDay(); 
                $userSubscription->end_date = now()->addMonth(); 

                // Save the new user subscription to the database
                $userSubscription->save();

                // Payment was made successfully
                return Redirect::to(route('home'))->with('success','true');
            } else {
                // Payment was not successful
                return Redirect::to(route('UserSettings'));
            }

        } catch (ApiErrorException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

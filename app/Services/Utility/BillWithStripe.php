<?php 

namespace App\Services\Utility;

use Stripe;
use Stripe_Charge;
use Stripe_InvalidRequestError;
use Stripe_CardError;

class BillWithStripe {

	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
        Stripe::setApiKey(env('STRIPE_SECRET_LIVE'));
  	}


	/**
	 * Charge a user for a repo
	 *
	 * @return view
	 */
	public function makePurchase($user, $request, $item){
		
		try {

			Stripe_Charge::create([
			  'amount' => $item->amount,
			  'currency' => 'usd',
			  'customer' => $user->stripe_id,
			  'source' => $item['stripe-token'],
  			  'description' => $item->title,
			  'application_fee' => 100, // amount in cents to go to App owner. You'd probably want to set this up as it's own function to calculate
			  'destination' => $item->user->stripe_id // the remainder going to the author's account
			]);

        } 
        
        catch (Stripe_InvalidRequestError $e)
        {
	        // invalid request
        }
        
        catch (Stripe_CardError $e) 
        {
	        // card was declined
        }
		
	}

}
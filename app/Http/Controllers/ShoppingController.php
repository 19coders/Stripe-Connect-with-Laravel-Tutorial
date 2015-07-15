<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Utility\BillWithStripe;
use Laracasts\Flash\Flash;
use Response;
use Request;
use Auth;
use Input;
use Exception;

use App\User;

class ShoppingController extends Controller 
{
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct()
	{
        // Get the logged in User
        $this->user = User::whereLoggedIn()->first();
  	}

	/**
	 * Show a single purchase view page
	 *
	 * @return view
	 */
	public function purchasePage($slug)
	{
        $user = $this->user;
		return view("purchase", compact('user') );
	}


	/**
	 * Show a single site page
	 *
	 * @return view
	 */
	public function processPurchase(BillWithStripe $stripe)
	{
		// Get all the input, the user and set the resourceTitle
		$request = Input::all();
        $user = $this->user;

        // NOTE: THIS WILL NOT WORK AS IT IS WITHOUT PASSING $purchasedItem below as collection istead of Array
        // You can call this in as a collection, just make sure it has the following info
        $purchasedItem = [
        		'amount' => '100', // in cents
        		'user'	=> '', // get the user model for who this should be paying toward
        		'title' => '', // get the title of your purcahsed item
        	]; // Get the amount to be paid without passing it into the form

        try {

	        // Sent the purchase to stripe
	        $billed = $stripe->makePurchase($user, $request, $purchasedItem);
	        		
		} catch (Exception $e)
		{
			
	        // Send do download page
	        Flash::error('There was an issue with the payment being processed. You were not charged. Please try again');
	        return back();
			
		}
		
        // Send do download page
        Flash::success('You just made a purchase, yo! It should be listed below, with a link to download the repo contents!');
        // send them where they need to go

	}


}
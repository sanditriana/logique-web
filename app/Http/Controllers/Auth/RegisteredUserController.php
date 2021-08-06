<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MembershipType;
use App\Models\UserAddress;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

use DB;
use Session;
use Illuminate\Support\Carbon;
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $memberShipTypes = MembershipType::all();
        return view('auth.register',compact('memberShipTypes'));
    }

    public function checkCard(Request $request){
        return $this->validatecard($request->card);
    }

    public function validatecard($number)
    {
        $cardtype = array(
            "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
            "mastercard" => "/^5[1-5][0-9]{14}$/",
            "amex"       => "/^3[47][0-9]{13}$/",
            "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
        );

        if (preg_match($cardtype['visa'],$number))
        {
        $type= "visa";
            return 'visa';
        
        }
        else if (preg_match($cardtype['mastercard'],$number))
        {
        $type= "mastercard";
            return 'mastercard';
        }
        else if (preg_match($cardtype['amex'],$number))
        {
        $type= "amex";
            return 'amex';
        
        }
        else if (preg_match($cardtype['discover'],$number))
        {
        $type= "discover";
            return 'discover';
        }
        else
        {
            return false;
        } 
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'dob' => 'required',
            'membershipType' => 'required',
            'cc' => 'required',
            'ccExpireDate' => 'required',
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $card = $this->validatecard($request->cc);
        if(!$card){
            DB::rollback();
            return redirect()
            ->back()
            ->withErrors(
                [
                    'errors'=>'Credit Card not found'
                ]
            )->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'credit_card' => json_encode([$request->cc, $card, $request->ccExpireDate]),
                'membership_type' => $request->membershipType
            ]);
    
            $address = new UserAddress;
            $address->user_id = $user->id;
            $address->address = $request->address;
            $address->save();

            DB::commit();

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return redirect()
            ->back()
            ->withErrors(
                [
                    'errors'=>$th->getMessage()
                ]
            );
        }
        
        return redirect()->route('login')->with(
            [
                'status' => 'Success register. please login in down below'
            ]
        );
        
    }
}

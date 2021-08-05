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
            'email' => 'required|string|email:rfc,dns|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'credit_card' => $request->cc,
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

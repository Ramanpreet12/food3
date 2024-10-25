<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\DeliveryRequest;
use App\Models\DeliveryBoy;
use Illuminate\Support\Facades\Hash;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function deliveryRegistrationForm(){
        return view('admin.delivery.auth.deliveryRegister');
    }
    public function deliveryRegistration(DeliveryRequest $request)
    {

        if ($request->isMethod('post')) {
            $validated = $request->validated();

            // Handle image uploads
            try {
                $deliveryBoyImagePath = store_image($request->file('image'), 'delivery_boys/images');

                // for now not sure to get number or photo
                // $adharPath = $request->file('adhar')->store('delivery_boys/documents', 'public');
                // $licensePath = $request->file('drivingLicence')->store('delivery_boys/documents', 'public');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
            }


            // Create delivery boy
            $deliveryBoy = DeliveryBoy::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'altNumber' => $validated['altNumber'] ?? null,
                'gender' => $validated['gender'],
                'adhar' => $validated['adhar'],
                'image' => $deliveryBoyImagePath ?? null,
                'drivingLicence' =>  $validated['drivingLicence'],
                'deliveryBoyType' => $validated['deliveryBoyType'],
                'locationId' => $validated['locationId'] ?? null,
                'vehicle_type' => $validated['vehicle_type'] ?? null,
                'vehicle_number' => $validated['vehicle_number'] ?? null,
                'role' => 'delivery_boy'
            ]);

            if ($deliveryBoy) {
                return redirect()->route('delivery.login')
                    ->with('success', 'Registration successful! Please login to continue.');
            }

            return back()->with('error', 'Something went wrong! Please try again.');
        }


    }

    public function deliveryLogin(Request $request)
    {
       // If already logged in as delivery, redirect to dashboard
       if (Auth::guard('delivery')->check()) {
        return redirect()->route('delivery.dashboard');
    }
    if ($request->isMethod('post')) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        // Check if it's a delivery's email
        $deliveryBoy = DeliveryBoy::where('email', $request->email)
            ->where('role', 'delivery_boy')
            ->first();

        if (!$deliveryBoy) {
            return redirect()->back()
                ->with('error', 'No delivery boy account found with this email')
                ->withInput($request->except('password'));
        }

        // Attempt to authenticate
        if (Auth::guard('delivery')->attempt([
            'email' => $request->email,
            'password' => $request->password,
            'role' => 'delivery_boy'
        ], $request->filled('remember'))) {

            $request->session()->regenerate();

            // Handle remember me cookies
            if ($request->filled('remember')) {
                setcookie('delivery_email', $request->email, time() + 3600);
                setcookie('delivery_password', $request->password, time() + 3600);
            } else {
                setcookie('delivery_email', '', time() - 3600);
                setcookie('delivery_password', '', time() - 3600);
            }

            return redirect()->intended(route('delivery.dashboard'))
                ->with('success', 'Welcome back to your delivery dashboard');
        }

        return redirect()->back()
            ->with('error', 'Invalid email or password')
            ->withInput($request->except('password'));
    }

    return view('admin.delivery.auth.deliveryLogin');
    }



    public function dashboard()
    {
        return 'delivery';
    }
    public function profile()
    {
        return 'profile';
    }
}

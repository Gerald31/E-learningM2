<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Stripe\CreateCustomerDTO;
use App\Services\Stripe\PaymentDTO;
use App\Services\Stripe\PaymentMethodDTO;
use App\Services\Stripe\StripeServices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class TestStripeController extends Controller
{
    /**
     * show payment page
     *
     * @return Application|Factory|View
     */
    public function form()
    {
        return view('form');
    }

    /**
     * submit payment page
     *
     * @return void
     */
    public function submit(Request $request, StripeServices $stripeServices)
    {

        if (!empty($paymentMethod->id)) {
            ////////// Create Account PROF /////
            $user = new User();
            $user->firstname = $request->first_name;
            $user->lastname = $request->last_name;

            $customer = $stripeServices->createCustomer(new CreateCustomerDTO(
                'FR',
                $request->get('token-account'),
                $request->email,
                $request->get('token-bank')
            ));




        }
    }
}

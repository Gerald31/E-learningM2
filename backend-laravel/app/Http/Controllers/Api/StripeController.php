<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Http\Requests\StoreCustomerStripeRequest;
use App\Models\Tutorat;
use App\Services\Stripe\CreateCustomerDTO;
use App\Services\Stripe\PaymentDTO;
use App\Services\Stripe\PaymentIntentDTO;
use App\Services\Stripe\PaymentMethodDTO;
use App\Services\Stripe\StripeServices;
use App\Services\Tutorat\TutoratServices;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Response;

class StripeController extends Controller
{
    /**
     * @var StripeServices
     */
    private StripeServices $stripeServices;

    /**
     * StripeController constructor.
     * @param StripeServices $stripeServices
     */
    public function __construct(StripeServices $stripeServices)
    {
        $this->stripeServices = $stripeServices;
    }

    /**
     * @param StoreCustomerStripeRequest $request
     * @return array|Account
     */
    public function createCustomer(StoreCustomerStripeRequest $request) {
        return $this->stripeServices->createCustomer(new CreateCustomerDTO(
            'FR',
            $request->get('token_account'),
            $request->email,
            $request->get('token_bank')
        ));
    }

    /**
     * @param Tutorat $tutorat
     * @return Application|\Illuminate\Http\Response|ResponseFactory
     * @throws ApiErrorException
     */
    public function createPayment(Tutorat $tutorat)
    {
        if ($tutorat) {
            $paymentIntent = $this->stripeServices->createPaymentIntent(new PaymentIntentDTO(
                $tutorat->subject,
                $this->stripeServices->totalAmount($tutorat->price),
                $tutorat->price,
                $tutorat->tutor->stripe_customer
            ));
            return response($paymentIntent, Response::HTTP_OK);
        }
        return response(['success' => false], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return Application|\Illuminate\Http\Response|ResponseFactory
     */
    public function payIntent(Request $request) {
        return $this->stripeServices->confirmPaymentIntent($request->payment_intent_id, $request->return_url, $request->tutorat_id);
    }
}

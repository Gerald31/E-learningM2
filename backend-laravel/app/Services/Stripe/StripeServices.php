<?php

namespace App\Services\Stripe;

use App\Models\User;
use App\Services\Tutorat\StoreTutoratState;
use App\Services\Tutorat\TutoratServices;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\File;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Token;
use Symfony\Component\HttpFoundation\Response;

class StripeServices
{
    protected $stripe;

    /**
     * @var float
     */
    protected float $platformAmount;

    /**
     * @var TutoratServices
     */
    protected TutoratServices $tutoratServices;

    public function __construct(TutoratServices $tutoratServices)
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
        Stripe::setApiKey(config('stripe.api_keys.secret_key'));
        $this->platformAmount = config('stripe.platform.amount');
        $this->tutoratServices = $tutoratServices;
    }

    /**
     * @param float $amount
     * @return float
     */
    public function totalAmount(float $amount): float
    {
        return ($this->platformAmount + $amount);
    }

    /**
     * @param CreateCustomerDTO $customerDTO
     * @return Account
     */
    public function createCustomer(CreateCustomerDTO $customerDTO)
    {
        $account = null;
        try {
            $account = $this->stripe->accounts->create([
                'country' => $customerDTO->country,
                'type' => $customerDTO->type,
                'account_token' => $customerDTO->account_token,
                'email' => $customerDTO->emailUser,
                'capabilities' => [
                    'card_payments' => [
                        'requested' => true
                    ],
                    'transfers' => [
                        'requested' => true
                    ],
                ],
                'external_account' => $customerDTO->tokenBank,
                'business_profile' => [
                    'url' => 'https://www.web-school.com',
                    'mcc' => 8211 // 8211 => elementary_secondary_schools
                ]
            ]);
            if ($account) {
                User::where('email', $customerDTO->emailUser)->update(['stripe_customer' => $account->id ]);
            }
        } catch (\Exception $exception) {
            $account['error'] = $exception->getMessage();
        }
        return $account;
    }

    /**
     * @param PaymentIntentDTO $paymentDTO
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function createPaymentIntent (PaymentIntentDTO $paymentDTO): PaymentIntent
    {
        try {
            $payment = $this->stripe->paymentIntents->create([
                'amount' => $this->convertPriceStripe($paymentDTO->priceTotal),
                'currency' => $paymentDTO->currency,
                'description' => $paymentDTO->description,
                'setup_future_usage' => 'off_session',
                'on_behalf_of' => $paymentDTO->accountCustomerDest,
                'transfer_data' => [
                    'amount' => $this->convertPriceStripe($paymentDTO->amountTransfer),
                    'destination' => $paymentDTO->accountCustomerDest,
                ],
                'automatic_payment_methods' => ['enabled' => true],
            ]);
        } catch (CardException $e) {
            // Error code will be authentication_required if authentication is needed
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment = PaymentIntent::retrieve($payment_intent_id);
        }
        return $payment;
    }

    /**
     * @param string $paymentIntentId
     * @param string $returnUrl
     * @param int $tutoratId
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function confirmPaymentIntent(string $paymentIntentId, string $returnUrl, int $tutoratId)
    {
        try {
            $paymentIntent = $this->getPaymentIntent($paymentIntentId);

            if ($paymentIntent->status === 'requires_action') {
                $paymentIntent->confirm(['return_url' => $returnUrl]);
            }
            return $this->generateResponse($paymentIntent, $tutoratId);
        } catch (ApiErrorException $e) {
            # Display error on client
            return \response([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param PaymentIntent $paymentIntent
     * @param int $tutoratId
     * @return Application|ResponseFactory|\Illuminate\Http\Response
     */
    public function generateResponse(PaymentIntent $paymentIntent, int $tutoratId)
    {
        if ($paymentIntent->status === 'requires_action') {
            # Tell the client to handle the action
            return response([
                'requires_action' => true,
                'payment_intent_client_secret' => $paymentIntent->client_secret
            ]);
        } else if ($paymentIntent->status === 'succeeded') {
            # The payment didn’t need any additional actions and completed!
            # Handle post-payment fulfillment
            $invoice = $paymentIntent->charges->data && count($paymentIntent->charges->data) > 0 && current($paymentIntent->charges->data)->receipt_url ? current($paymentIntent->charges->data)->receipt_url : null;
            $user = Auth::guard('api')->user();
            $reference = 'Facture_' . $tutoratId .'_'. date('Y_m_d_h_i_s');
            $tutorat = $this->tutoratServices->getTutorat($tutoratId);
            $storeTutoratState = new StoreTutoratState($tutoratId, $this->totalAmount($tutorat->price), $user->id, $invoice, $reference, true);
            $tutoratState = $this->tutoratServices->createTutoratState($storeTutoratState);
            return response([
                "success" => true,
                'tutorat_state_user' => $tutoratState
            ]);
        } else {
            # Invalid status
            return response(['error' => 'Invalid PaymentIntent status'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param PaymentDTO $paymentDTO
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function paymentIntent (PaymentDTO $paymentDTO): PaymentIntent
    {
        try {
            // create customer vide
            $customer = Customer::create();

            $payment = $this->stripe->paymentIntents->create([
                'payment_method_types' => ['card'],
                'amount' => $this->convertPriceStripe($paymentDTO->priceTotal),
                'currency' => $paymentDTO->currency,
                'payment_method' => $paymentDTO->paymentMethodId,
                'customer' => $customer->id,
                'description' => $paymentDTO->description,
                'setup_future_usage' => 'off_session',
                'on_behalf_of' => $paymentDTO->accountCustomerDest,
                'transfer_data' => [
                    'amount' => $this->convertPriceStripe($paymentDTO->amountTransfer),
                    'destination' => $paymentDTO->accountCustomerDest,
                ],
                'confirmation_method' => "manual",
                'confirm' => true,
            ]);
        } catch (CardException $e) {
            // Error code will be authentication_required if authentication is needed
            $payment_intent_id = $e->getError()->payment_intent->id;
            $payment = $this->getPaymentIntent($payment_intent_id);
        }
        return $payment;
    }

    /**
     * @param float $price
     * @return float
     */
    protected function convertPriceStripe(float $price): float
    {
        return $price * 100;
    }

    /**
     * @param PaymentMethodDTO $paymentMethodDTO
     * @return PaymentMethod
     */
    public function createPaymentMethod(PaymentMethodDTO $paymentMethodDTO)
    {
        $paymentMethod = null;
        try {
            $paymentMethod = $this->stripe->paymentMethods->create([
                'type' => 'card',
                'card' => [
                    'number' => $paymentMethodDTO->numberCard,
                    'exp_month' => $paymentMethodDTO->expMonth,
                    'exp_year' => $paymentMethodDTO->expYear,
                    'cvc' => $paymentMethodDTO->cvc,
                ],
            ]);
        } catch (ApiErrorException $e) {
            $paymentMethod['error'] = $e->getMessage();
        }
        return $paymentMethod;
    }

    /**
     * @param string $paymentIntentId
     * @return PaymentIntent
     * @throws ApiErrorException
     */
    public function getPaymentIntent(string $paymentIntentId): PaymentIntent
    {
        return $this->stripe->paymentIntents->retrieve($paymentIntentId);
    }
}

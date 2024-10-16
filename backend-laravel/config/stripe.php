<?php

return [
    'api_keys' => [
        'secret_key' => env('STRIPE_SECRET', null)  // Secret KEY: https://dashboard.stripe.com/account/apikeys
    ],
    'platform' => [
        'amount' => env('PLATFORM_AMOUNT', 3.5)
    ],
];

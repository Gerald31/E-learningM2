<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Stripe form</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="mt-3">
                <h1>Stripe form</h1>
                <p class="lead">Amount: INR100</p>
                <form method="post" action="{{ route('stripeSubmit') }}" class="row my-form">
                    <input type="hidden" name="amount" value="100">
                    <input type="hidden" name="currency" value="INR">
                    <input type="hidden" name="token-account" id="token-account">
                    <input type="hidden" name="token-bank" id="token-bank">
                    @csrf
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="first_name" class="col-sm-2 col-form-label">First name</label>
                            <div class="col-sm-10">
                                <input type="text" id="first_name" class="form-control" name="first_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="last_name" class="col-sm-2 col-form-label">Last name</label>
                            <div class="col-sm-10">
                                <input type="text" id="last_name" class="form-control" name="last_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" id="email" class="form-control" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
                            <div class="col-sm-10">
                                <input type="text" id="phone" class="form-control" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="postal_code" class="col-sm-2 col-form-label">Postal code</label>
                            <div class="col-sm-10">
                                <input type="text" id="postal_code" class="form-control" name="postal_code">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="line1" class="col-sm-2 col-form-label">Address</label>
                            <div class="col-sm-10">
                                <input type="text" id="line1" class="form-control" name="line1">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" id="city" class="form-control" name="city">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="country" class="col-sm-2 col-form-label">Country</label>
                            <div class="col-sm-10">
                                <input type="text" id="country" class="form-control" name="country">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="card_no" class="col-sm-2 col-form-label">Card no</label>
                            <div class="col-sm-10">
                                <input type="text" id="card_no" class="form-control" name="card_no">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="exp_month" class="col-sm-2 col-form-label">Card Exp. month</label>
                            <div class="col-sm-10">
                                <input type="text" id="exp_month" class="form-control" name="exp_month">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="exp_year" class="col-sm-2 col-form-label">Card Exp. year</label>
                            <div class="col-sm-10">
                                <input type="text" id="exp_year" class="form-control" name="exp_year">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3 row">
                            <label for="cvc" class="col-sm-2 col-form-label">CVV</label>
                            <div class="col-sm-10">
                                <input type="text" id="cvc" class="form-control" name="cvc">
                            </div>
                        </div>
                    </div>
                    <input type="submit" name="Submit" class="btn btn-primary submit">
                </form>
                <div class="card mt-3">
                    <div class="card-body">
                        <p class="lead">Stripe docs links:</p>
                        <p>Test card available here: <a href="https://stripe.com/docs/testing#cards" target="_blank">Link</a></p>
                        <p>Payment method request docs: <a href="https://stripe.com/docs/api/payment_methods/create" target="_blank">Link</a></p>
                        <p>Payment intent request docs: <a href="https://stripe.com/docs/api/payment_intents/create" target="_blank">Link</a></p>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://js.stripe.com/v3/"></script>
        <script>
            var stripe = Stripe('pk_test_51LqBXPHAVy4heDsgKQJ17wIxk9x43yLWlrq0GmTiz2ltQuAfnGgy5GdhljqCDxdJd6sP4PmNotUFlVC0V8fW9rUY00TLM0gyMU');

            const myForm = document.querySelector('.my-form');
            myForm.addEventListener('submit', handleForm);

            async function handleForm(event) {
                event.preventDefault();

                const accountResult = await stripe.createToken('account', {
                    business_type: 'individual',
                    individual: {
                        first_name: document.querySelector('#first_name').value,
                        last_name: document.querySelector('#last_name').value,
                        email: document.querySelector('#email').value,
                        address: {
                            line1: document.querySelector('#line1').value,
                            city: document.querySelector('#city').value,
                            postal_code: document.querySelector('#postal_code').value,
                            // state: document.querySelector('#state').value,
                        },
                        phone: document.querySelector('#phone').value,
                        dob: {
                            day: '28',
                            month: '01',
                            year: '1995',
                        },
                    },
                    tos_shown_and_accepted: true,
                });
                console.log('accountResult =>', accountResult);

                const bankResult = await stripe
                    .createToken('bank_account', {
                        country: 'FR',
                        currency: 'EUR',
                        account_number: "FR1420041010050500013M02606",
                        account_holder_name: "Alvin Jean",
                        account_holder_type: 'individual',
                    });

                console.log('bankResult =>', bankResult);

                if (accountResult.token && bankResult.token) {
                    document.querySelector('#token-account').value = accountResult.token.id;
                    document.querySelector('#token-bank').value = bankResult.token.id;
                    myForm.submit();
                }
            }

            {{--async function uploadFiles(ids){--}}
            {{--    var url = '{{ path('admin_compte_valider_upload_files',{id: "text"}) }}'--}}
            {{--    url = url.replace('text', ids);--}}
            {{--    const fileResult = await fetch(url, {--}}
            {{--        method: 'POST',--}}
            {{--        body: {--}}
            {{--            id: ids,--}}
            {{--        },--}}
            {{--    });--}}
            {{--    return await fileResult.json();--}}
            {{--}--}}

        </script>
    </body>
</html>

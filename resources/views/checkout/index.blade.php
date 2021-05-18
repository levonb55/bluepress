@extends('layouts.main')

@section('title', config('app.name') . ' Checkout')

@section('content')
    <div class="container">
        <h4>Checkout</h4>
        @if(session()->has('payment-error'))
            <div class="alert alert-danger error">
                {{ session()->get('payment-error') }}
            </div>
        @endif
        <form action="{{ route('checkout') }}" method="POST" id="payment-form">
            @csrf
            <div class="col-md-4 col-offset-4">
                <div class="form-group">
                    <label for="name_on_card">Cardholder Name:</label>
                    <input type="text" class="form-control" name="name" id="name_on_card"
                           value="{{ old('name_on_card') }}">
                    @error('name')
                    <span class="text-danger error">
                                {{ $message }}
                            </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                    <span class="text-danger error">
                                {{ $message }}
                            </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="postal_code">Postal Code:</label>
                    <input type="text" class="form-control" name="postal_code" id="postal_code"
                           value="{{ old('postal_code') }}">
                    @error('postal_code')
                    <span class="text-danger error">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="card-element">
                        Credit or debit card
                    </label>
                    <div id="card-element">
                        <!-- A Stripe Element will be inserted here. -->
                    </div>

                    <!-- Used to display form errors. -->
                    <div id="card-errors" class="card-errors" role="alert"></div>
                </div>
            </div>
            <div class="back_continue experience_page">
                <button type="submit" class="btn btn-primary" id="payment-button">
                    Pay
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script>
        (function () {
            // Create a Stripe client.
            var stripe = Stripe(@json(config('services.stripe.key')));

            // Create an instance of Elements.
            var elements = stripe.elements();

            // Custom styling can be passed to options when creating an Element.
            // (Note that this demo uses a wider set of styles than the guide below.)
            var style = {
                base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                        color: '#aab7c4'
                    }
                },
                invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                }
            };

            // Create an instance of the card Element.
            var card = elements.create('card', {
                style: style,
                hidePostalCode: true
            });

            // Add an instance of the card Element into the `card-element` <div>.
            card.mount('#card-element');

            // Handle real-time validation errors from the card Element.
            card.addEventListener('change', function (event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Handle form submission.
            var form = document.getElementById('payment-form');
            var paymentBtn = document.getElementById('payment-button');
            form.addEventListener('submit', function (event) {
                event.preventDefault();

                // Disable the submit button to prevent repeated clicks
                paymentBtn.disabled = true;

                var options = {
                    name: document.getElementById('name_on_card').value,
                    address_line1: document.getElementById('address').value,
                    address_zip: document.getElementById('postal_code').value
                };

                stripe.createToken(card, options).then(function (result) {
                    if (result.error) {
                        // Inform the user if there was an error.
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;

                        // Enable the submit button
                        paymentBtn.disabled = false;
                    } else {
                        // Send the token to your server.
                        stripeTokenHandler(result.token);
                    }
                });
            });

            // Submit the form with the token ID.
            function stripeTokenHandler(token) {
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                form.appendChild(hiddenInput);

                // Submit the form
                form.submit();
            }

            //Scrolls into errors
            if (errorElement = document.querySelector('.error')) {
                errorElement.scrollIntoView({behavior: "smooth", block: "center", inline: "nearest"});
            }

        }());
    </script>
@endsection

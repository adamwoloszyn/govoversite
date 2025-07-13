@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Welcome Back!</div>
                    
                    <form method="POST" action="/billing/portal">
                        @csrf <!-- Include the CSRF token for Laravel forms -->
                        <input type="text" name="customer_id" value="cus_NfO2oubGNll1aU">
                        <button type="submit">Create Billing Portal Session</button>
                      </form>
                      
                      <form method="POST" action="/checkout/session">
                        @csrf <!-- Include the CSRF token for Laravel forms -->
                        <button type="submit">Checkout</button>
                      </form>
                      
                </div>
            </div>
        </div>        
    </div>        
@endsection

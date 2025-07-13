@extends('layouts.app')
@section('content')
<div class="container">
   <img src="{{ asset('images/home.png') }}" alt="" class="mb-4 rounded d-block w-100" />
   <div class="row mt-4 mb-4">
      <div class="col-md-6">
         <h2>GovernmentOversite.com</h2>
         <h4>PUBLICS RIGHT TO KNOW LAW:  NH CHAPTER: RSA-91-A:1 </h4>
         <h4>OPENNESS IN THE CONDUCT OF PUBLIC BUSINESS IS ESSENTIAL TO A DEMOCRATIC SOCIETY</h4>
         <p>
               The purpose of this chapter is to ensure both the greatest possible public access to the actions, discussions and records of all public bodies, and their accountability to the people. 
         </p>
         <p>
               Established 2011, By Hon. Ed Comeau: NH State Representative- 2016- 2020 alongside individual members who’s duty is to expose corruption, support honest public officials, and enforce the Constitution of these United States of America and the Constitution of the State of New Hampshire.
         </p>
         <h4>WHAT WE ARE:</h4>
         <p>
            A Specialized Investigative Media System (SIMS) that monitors, collects, and analyzes government activities using video and audio surveillance of public officials when it is lawfully permitted. 
         </p>
         <h4>MISSION STATEMENT:</h4>
         <p>
            Our mission is to provide the public a window into the local and regional political process by video taping various public meetings and officials during the course of their public duty. GovernmentOversite.com firmly believes this process affords the general public an easier and more convenient access to the proceedings of their local government. It is our intent to ensure all citizens have the ability to monitor government activities. We wish to facilitate the exercising of your inalienable first amendment rights that are protected by the US and NH Constitutions. We will earnestly strive to provide a mechanism for all concerned citizens, newspapers, television, and radio to assert themselves in a government by the people, for the people, and of the people.
         </p>
         <h4>PROTECTION:</h4>
         <p>
            First Amendment of the Constitution of these United States: 
         </p>
         <p>
            Congress shall make no law respecting an establishment of religion, or prohibiting the free exercise thereof; or abridging the freedom of speech, or of the press; or the right of the people peaceably to assemble, and to petition the Government for a redress of grievances.
         </p>
         <p>
            Article 8: New Hampshire Constitution: 
         </p>
         <p>
            Accountability of Magistrates and Officers; Public's Right to Know. All power residing originally in, and being derived from, the people, all the magistrates and officers of government are their substitutes and agents, and at all times accountable to them.  Government, therefore, should be open, accessible, accountable and responsive.  To that end, the public's right of access to governmental proceedings and records shall not be unreasonably restricted.
         </p>
         <h4>ENFORCEMENT: Right to know open meeting law Chapter RSA 91-A:</h4>
         <p>
            Become a member and DISCOVER the GoVo process:
         </p>
      </div>
      <div class="col-md-5 offset-md-1 ">
         <h2 class="text-center">{{ __('Log in to your account') }}</h2>
         <p class="text-center">Welcome back! Please enter your details</p>

         <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="row mb-3">
               <div class="col-md-8 offset-md-2">
                  <label for="email" class="">{{ __('Email') }}</label>
                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror w-100" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>

            <div class="row mb-3">
               <div class="col-md-8 offset-md-2">
                  <label for="password" class="">{{ __('Password') }}</label>
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
               </div>
            </div>
            <div class="row mb-3 align-baseline">
               <div class="col-md-8 offset-md-2">
                  <div class="row">
                     <div class="col-sm-6">
                        <div class="form-check">
                           <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                           <label class="form-check-label" for="remember">
                           {{ __('Remember Me') }}
                           </label>
                        </div>
                     </div>
                     <div class="col-sm-6 text-end">
                        @if (Route::has('password.request'))
                        <a class="text-right" href="{{ route('password.request') }}">
                        {{ __('Forgot password') }}
                        </a>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
            <div class="row mb-0">
               <div class="col-md-8 offset-md-2">
                  <button type="submit" class="btn btn-primary w-100 mb-3">
                  {{ __('Sign in') }}
                  </button>
                  <div class="text-center">
                     Don't have an account? <a href="/register">Sign up!</a>
                  </div>
               </div>
            </div>
         </form>
      </div>
      <!-- Ending Login form-->

      <!-- Starting Sign up form -->
      <div class="col-md-5 offset-md-1 d-none">
         <h2 class="text-center">{{ __('Register your account below.') }}</h2>
         <p class="text-center">Additional information.</p>
         
         <form method="POST" action="{{ route('registerUser') }}">
            @csrf
            <div class="row mb-3">
               <div class="col-lg-8 offset-lg-2">
                  <label for="name">Name</label>
                  <input id="name" type="text" name="name" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Name" value="adam woloszyn">
                  
                  <label class="mt-3" for="email">Email</label>
                  <input id="email" type="text" name="email" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="email@domain.com" value="adam@leandiscoverygroup.com">
                  
                  <label class="mt-3"  for="phone">Phone</label>
                  <input id="phone" type="text" name="phone" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Phone" value="8588378077">
                  
                  <label class="mt-3"  for="password">Create Password</label>
                  <input id="password" type="password" name="password" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Password" value="Testingthis1!">
                  
                  <label class="mt-3"  for="confirm">Confirm Password</label>
                  <input id="confirm" type="password" name="confirm" class="p-2 pe-3 ps-3 w-100 rounded-3 border border-1 d-block" placeholder="Confirm Password"> 
                  
                  <p class="mt-3">
                     <input class="form-check-input" type="checkbox" name="terms" id="terms" {{ old('remember') ? 'checked' : '' }}>
                     <label class="form-check-label" for="terms">{{ __('I agree to terms & conditions') }}</label>
                  </p>
                  @if($errors->any())
                  <div class="alert alert-danger">
                     <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                     </ul>
                  </div>
                  @endif
               </div>
            </div>
            <div class="row mb-0">
               <div class="col-md-8 offset-md-2">
                  <button type="submit" class="btn btn-primary w-100 mb-3">
                  {{ __('Sign up') }}
                  </button>
                  <div class="text-center">
                     Already have an account? <a href="">Sign in!</a>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection
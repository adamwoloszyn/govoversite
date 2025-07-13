@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Members</div>

                    <div class="card-body">
                        <small>
                            <p>Welcome to GovernmentOversite.com.</p>
                            
                            <p>
                            Are you a member? <a class="btn btn-primary" href="{{ route('login') }}">Log in here</a>
                            </p>
                        </small>
                    </div>
                </div>
                </div>
                <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Become a Member</div>

                    <div class="card-body">
                        <small>
                            <p>
                                Our members leverage the following features:
                                <ul>
                                    <li>You can elect to be notified of videos matching keywords that you chose</li>
                                    <li>Access to complete video library</li>
                                    <li>Stream videos</li>
                                    <li>Read meeting agenda/summary</li>
                                    <li>Read video transcriptions</li>
                                </ul>
                                <a class="btn btn-success" href="{{ route('register') }}">Become a member now</a>
                            </p>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 justify-content-center">
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">Live Stream</div>

                    <div class="card-body">
                    <div style='background-image: url("{{ asset("images/GOLogo.png") }}"); background-size: cover; background-repeat: no-repeat; background-position: 50% 50%;'>
                                <div style="height: 400px;">
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">Mission Statement</div>

                    <div class="card-body">
                        <small>
                            <p>
                                Our mission is to provide the public a window into the local and regional political process by video taping various public meetings and officials during the course of their public duty. 
                            </p>
                            <p>
                                GovernmentOversite.com firmly believes this process affords the general public an easier and more convenient access to the proceedings of their local government. It is our intent to ensure all citizens have the ability to monitor government activities. We wish to facilitate the exercising of your inalienable first amendment rights that are protected by the US and NH Constitutions. 
                            </p>
                            <p>
                                We will earnestly strive to provide a mechanism for all concerned citizens, newspapers, television, and radio to assert themselves in a government by the people, for the people, and of the people.
                            </p>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

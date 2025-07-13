@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
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
                    Our mission is to provide the public a window into the local and regional political process by video taping various public meetings and officials during the course of their public duty. GovernmentOversite.com firmly believes this process affords the general public an easier and more convenient access to the proceedings of their local government. It is our intent to ensure all citizens have the ability to monitor government activities. We wish to facilitate the exercising of your inalienable first amendment rights that are protected by the US and NH Constitutions. We will earnestly strive to provide a mechanism for all concerned citizens, newspapers, television, and radio to assert themselves in a government by the people, for the people, and of the people.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection




@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Welcome Back!</div>

                    <div class="card-body">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Keywords" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="$('.Results').toggle();">Search</button>
                            </div>
                        </div>
                        <hr/>
                        <small style="display:none;" class="Results">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Posted</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">3/1/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong1">Carroll County NH Commission</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 1]) }}">View</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3/12/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong2">New Hampshire Fish and Game Commission Meetings</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 10]) }}">View</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3/15/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong3">Lakes Region Porcupines Meetings</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 101]) }}">View</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Welcome Back!</div>

                    <div class="card-body">
                        <p>The latest 10 videos matching your subscriptions:</p>
                        <small>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Posted</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Keyword Filters</th>
                                        <th scope="col">View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">3/1/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong1">Carroll County NH Commission</a></td>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong4">View Filter</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 101]) }}">View</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3/12/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong2">New Hampshire Fish and Game Commission Meetings</a></td>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong4">View Filter</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 401]) }}">View</a></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3/15/2023</th>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong3">Lakes Region Porcupines Meetings</a></td>
                                        <td><a href="javascript:void()" data-toggle="modal" data-target="#exampleModalLong4">View Filter</a></td>
                                        <td><a href="{{ route('Video.view', ['id' => 601]) }}">View</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">System Messages</div>

                    <div class="card-body">
                        <small>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Posted</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">3/1/2023</th>
                                        <td>Videos Posted</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">3/12/2023</th>
                                        <td>Scheduled system maintenance for 3/30/2023 from 1:00 am PST to 3:00 am</td>
                                    </tr>
                                </tbody>
                            </table>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4 justify-content-center" style="display:none;">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalLong1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Carroll County NH Commission</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModalLong2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>New Hampshire Fish and Game Commission Meetings</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exampleModalLong3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Video Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Lakes Region Porcupines Meetings</h5>
                <p>
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nihil reiciendis optio cumque, ducimus quidem deserunt reprehenderit autem tempora quod molestiae sequi id doloribus? Nemo perferendis cumque nesciunt alias cum natus!
                </p>
                <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Optio saepe fugiat deserunt iure, animi enim veritatis aspernatur eius ratione cupiditate recusandae dolorum dolores voluptate quia quasi voluptatum illo possimus. Tenetur!</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ipsa quidem deleniti, eaque molestias aperiam nam magni corrupti sunt quos delectus autem, temporibus atque optio hic cumque quo magnam! Obcaecati, hic!

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="exampleModalLong4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Keyword Subscription</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Keywords</h5>
                <div>
                    <ul>
                        <li>Veterans tax credit</li>
                        <li>Veterans</li>
                        <li>Union Meadows Hike</li>
                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>        
@endsection

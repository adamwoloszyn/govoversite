@extends('layouts.app')

@section('title', 'Contact page')
@section('description', 'This is a contact page.')
@section('keywords', 'keyword1, keyword2, keyword3')
@section('canonical', 'https://example.com/page')
@section('author', 'John Doe')
@section('og_title', 'Welcome to My Website')
@section('og_description', 'This is the description of my website for social sharing.')
@section('og_image', 'https://example.com/images/og-image.jpg')
@section('twitter_title', 'Welcome to My Website')
@section('twitter_description', 'This is the description of my website for Twitter sharing.')
@section('twitter_image', 'https://example.com/images/twitter-image.jpg')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="">
                    <h2>Contact</h2>

                    <div class="">
                        <ul>
                            <li>EMail me at: <a target="_blank" href="mailto:governmentoversite@gmail.com">governmentoversite@gmail.com</a></li>
                            <li><a target="_blank" href="https://ballotpedia.org/Ed_Comeau" target="AboutMe">Learn about the Founder</a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/in/ed-comeau-76727336/" target="AboutMe"><img class="gologo" src="{{asset('images/LinkedInLogo.png')}}"></a></li>
                            <li><a target="_blank" href="https://www.youtube.com/@FreeFringeFighter/about" target="AboutMe">YouTube</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

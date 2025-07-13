<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\KeywordsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\AgendaItemTypeController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\OldVideoImportController;

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

//User Registration
Route::post('/register', [App\Http\Controllers\RegistrationController::class, 'add'])->name('registerUser');

//
// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

//
// Contact
Route::get('/contact', [App\Http\Controllers\HomeController::class, 'contact'])->name('contact');

//
// Donate
Route::get('/donate', [App\Http\Controllers\HomeController::class, 'donate'])->name('donate');

//
// User Settings
Route::get('/UserSettings', [App\Http\Controllers\UserSettingsController::class, 'index'])->name('UserSettings');


Route::get('/print/{videoslug}', [PrintController::class, 'index'])->name('print.index');
//
// Video Processing
Route::middleware(['auth', 'user-role:admin'])->group(
    function()
    {
        //Log::info('/VideoProcessing/uploadNewVideo');
        Route::post('/VideoProcessing/uploadNewVideo', [App\Http\Controllers\VideoProcessingController::class, 'uploadNewVideo'])->name('videoUpload');
        Route::get('/VideoProcessing/addVideo', [App\Http\Controllers\VideoProcessingController::class, 'addVideo'])->name('addVideo');
        Route::get('/VideoProcessing/dropZone', [App\Http\Controllers\VideoProcessingController::class, 'dropZone'])->name('dropZone');
        Route::post('/VideoProcessing/upload', [App\Http\Controllers\VideoProcessingController::class, 'upload'])->name('upload');


        Route::get( '/VideoProcessing/EditVideo/{id}',  [App\Http\Controllers\VideoProcessingController::class, 'EditVideo'])->name('EditVideo');
        Route::post('/VideoProcessing/EditVideoUpdate', [App\Http\Controllers\VideoProcessingController::class, 'EditVideoUpdate'])->name('EditVideoUpdate');

        Route::get('/Channels', [ChannelController::class, 'index'])->name('channels.index');
        Route::get('/channels/create', [ChannelController::class, 'create'])->name('channels.create');
        Route::post('/channels', [ChannelController::class, 'store'])->name('channels.store');
        Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
        Route::get('/channels/{id}/delete', [ChannelController::class, 'delete'])->name('channels.delete');
        Route::get('/channels/{id}/edit', [ChannelController::class, 'edit'])->name('channels.edit');
        Route::post('/channels/{id}/update', [ChannelController::class, 'update'])->name('channels.update');

        Route::get('/agendaItemTypes', [AgendaItemTypeController::class, 'index'])->name('agendaitemtypes.index');
        Route::get('/agendaitemtypes/create', [AgendaItemTypeController::class, 'create'])->name('agendaitemtypes.create');
        Route::post('/agendaitemtypes', [AgendaItemTypeController::class, 'store'])->name('agendaitemtypes.store');
        Route::get('/agendaitemtypes/{channel}', [AgendaItemTypeController::class, 'show'])->name('agendaitemtypes.show');
        Route::get('/agendaitemtypes/{id}/delete', [AgendaItemTypeController::class, 'delete'])->name('agendaitemtypes.delete');
        Route::get('/agendaitemtypes/{id}/edit', [AgendaItemTypeController::class, 'edit'])->name('agendaitemtypes.edit');
        Route::post('/agendaitemtypes/{id}/update', [AgendaItemTypeController::class, 'update'])->name('agendaitemtypes.update');
    }   // end of Video Processing
);

//
// Video
Route::resource('/video', VideoController::class);
Route::get('/Video', [App\Http\Controllers\VideoController::class, 'index'])->name('Videos');
Route::get('/Video/{slug}', [App\Http\Controllers\VideoController::class, 'view'])->name('Video.view');

Route::middleware(['auth', 'user-role:admin'])->group(
    function()
    {
        //
        // Keywords
        Route::resource('/Keywords', KeywordsController::class);
        Route::post('/Keywords/search/', [App\Http\Controllers\KeywordsController::class, 'search']);
        Route::post('/Keywords/add/', [App\Http\Controllers\KeywordsController::class, 'add']);
        Route::post('/Keywords/update/', [App\Http\Controllers\KeywordsController::class, 'update']);
        Route::post('/Keywords/delete/', [App\Http\Controllers\KeywordsController::class, 'delete']);
    }
);

Route::middleware(['auth', 'user-role:admin'])->group(
    function()
    {
        //
        // Users
        Route::get('/Users/', [App\Http\Controllers\UsersController::class, 'index']);
        Route::get('/Users/index', [App\Http\Controllers\UsersController::class, 'index']);
        Route::post('/Users/search/', [App\Http\Controllers\UsersController::class, 'search']);
        Route::post('/Users/delete/', [App\Http\Controllers\UsersController::class, 'delete']);
    }
);

Route::middleware(['auth', 'user-role:admin'])->group(
    function()
    {
        //
        // Video
        Route::post('/Video/search/', [App\Http\Controllers\VideoController::class, 'search']);
        Route::post('/Video/delete/', [App\Http\Controllers\VideoController::class, 'delete']);

        Route::get('/old-video-import', [OldVideoImportController::class, 'create'])->name('old-video-import.create');
        Route::post('/old-video-import', [OldVideoImportController::class, 'store'])->name('old-video-import.store');
    }
);

Route::middleware(['auth', 'user-role:subscriber'])->group(
    function()
    {
        //videos
        Route::get('/recommended', [App\Http\Controllers\HomeController::class, 'recommended'])->name('recommended');

        // Users
        Route::get('/Users/unsubscribe', [App\Http\Controllers\UsersController::class, 'unsubscribe'])->name('unsubscribe');
        Route::post('/Users/updateProfilePicture', [App\Http\Controllers\UsersController::class, 'updateProfilePicture'])->name('users.updateProfilePicture');
        Route::post('/Users/updateProfile', [App\Http\Controllers\UsersController::class, 'updateProfile'])->name('users.updateProfile');
        Route::post('/addSubscription', [App\Http\Controllers\RegistrationController::class, 'addSubscription'])->name('addSubscription');

        Route::get('/Livestream', [App\Http\Controllers\ChannelController::class, 'live_stream_index'])->name('livestream.index');
        Route::get('/Livestream/{slug}', [App\Http\Controllers\ChannelController::class, 'live_stream_channel'])->name('livestream.view');

    }
);


Route::post('/checkout/session', [StripeController::class, 'createCheckoutSession'])->name('checkout.session');
Route::post('/billing/portal', [StripeController::class, 'createBillingPortalSession'])->name('billing.portal');
Route::post('/subscription/cancel', [StripeController::class, 'cancelSubscription'])->name('subscription.cancel');
Route::post('/checkout/status', [StripeController::class, 'checkPaymentStatus'])->name('checkout.status');
Route::get('/checkout/success', [StripeController::class, 'checkPaymentStatus'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'checkPaymentStatus'])->name('checkout.cancel');


//
// A way to block access based on role
// User Route https://www.youtube.com/watch?v=vc4sXOdE4bQ
// Route::middleware(['auth', 'user-role:user'])->group(
//     function()
//     {
//         Route::get('/home', [App\Http\Controllers\HomeController::class, 'userHome'])->name('home')->name('welcome');
//     }
// );

// Route::middleware(['auth', 'user-role:subscriber'])->group(
//     function()
//     {
//         Route::get('/subscriber/home', [App\Http\Controllers\HomeController::class, 'subscriberHome'])->name('homesubscriber');
//     }
// );

// Route::middleware(['auth', 'user-role:admin'])->group(
//     function()
//     {
//         Route::get('/admin/home', [App\Http\Controllers\HomeController::class, 'adminHome'])->name('homeadmin');//->name('welcome.admin');
//     }
// );
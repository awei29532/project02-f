<?php

use App\Models\Game;
use App\Models\GameMaintenance;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'public'], function () {
    Route::post('login', 'SystemController@login');
    Route::post('register', 'SystemController@register');
    Route::post('logout', 'SystemController@logout');
    Route::get('site-menu', 'SystemController@siteMenu');
    Route::get('marquee', 'SystemController@marquee');
    Route::get('qrcode', 'SystemController@qrcode');
    Route::resource('promotion', 'PromotionController');
});

Route::group(['prefix' => 'game'], function () {
    Route::get('list', 'GameController@gameList');
    Route::get('launch', 'GameController@launch');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['prefix' => 'public'], function () {
        Route::get('auth', 'SystemController@auth');
    });
    
    Route::group(['prefix' => 'personal'], function () {
        Route::get('', 'PersonalController@show');
        Route::put('', 'PersonalController@update');
        Route::put('change-password', 'PersonalController@changePassword');
        Route::get('login-log', 'PersonalController@loginLog');
        Route::get('announcement/list', 'PersonalController@announcementList');
        Route::get('announcement/{id}', 'PersonalController@announcementDetail');
        Route::get('message/list', 'PersonalController@messageList');
        Route::get('message/{id}', 'PersonalController@messageDetail');
    });
    
    Route::group(['prefix' => 'finance', 'namespace' => 'Finance'], function () {
        Route::resource('member-bank', 'MemberBankController');
        Route::get('transaction-record', 'TransactionController@transactionRecord');
        Route::get('bet-record', 'TransactionController@betRecord');
        Route::get('company/list', 'TransactionController@companyList');
        Route::get('game-balance', 'TransferController@gameBalance');
        Route::post('transfer-back', 'TransferController@transferBack');

        # deposit
        Route::post('deposit', 'DepositController@deposit');
        Route::group(['prefix' => 'deposit'], function () {
            Route::get('payment-list', 'DepositController@paymentList');
            Route::get('payment-firm', 'DepositController@paymentFirm');
        });
    });
});

Route::get('add-game', function () {
    $games = Game::get();
    $games->each(function ($game, $key) {
        $m = new GameMaintenance();
        $m->site_id = 1;
        $m->game_id = $game->id;
        $m->company_id = $game->company_id;
        $m->game_type = $game->type;
        $m->status = $game->status;
        $m->saveOrFail();
    });
});

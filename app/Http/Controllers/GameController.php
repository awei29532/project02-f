<?php

namespace App\Http\Controllers;

use App\Exceptions\FailException;
use App\Models\Game;
use App\Models\GameCompany;
use App\Models\GameMaintenance;
use App\Models\MemberGameWallet;
use App\Service\Games\Factories\GameFactory;
use App\Service\GameService;
use App\Service\WalletService;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * showdoc
     * @catalog 遊戲功能
     * @title 遊戲列表
     * @description 遊戲列表
     * @method get
     * @url /api/game/list
     * @param type true string 遊戲類型
     * @param company_id false int 遊戲公司ID
     * @return {"data":[{"game_code":"123"}]}
     * @return_param game_code string 
     */
    public function gamelist(Request $request)
    {
        $query = GameMaintenance::where('status', '1')
            ->where('game_type', $request->type);

        $company_id = $request->company_id ?? '';
        if ($company_id) {
            $query->where('company_id', $request->company_id);
        }

        $res = $query->get();

        return $this->returnData($res->map(function ($row) {
            return [
                'game_code' => $row->game->game_code,
            ];
        }));
    }

    /**
     * showdoc
     * @catalog 遊戲功能
     * @title 啟動遊戲
     * @description 啟動遊戲
     * @method get
     * @url /api/game/launch
     * @param game_code true string 遊戲代碼
     * @return {"data":{"url":"123"}}
     * @return_param url string 遊戲網址
     */
    public function launch(Request $request)
    {
        # check game
        $game = Game::where('game_code', $request->game_code)->first();
        if (!$game) {
            throw new FailException('Game not found.');
        }
        $user = auth()->user();

        MemberGameWallet::firstOrCreate([
            'member_id' => $user->id,
            'company_id' => $game->company_id,
        ]);

        $wallet_service = new WalletService();
        # transfer back
        $wallet_service->transferBack();

        # transfer out
        $wallet_service->transferOut($game->company);

        $factory = (new GameFactory())->getInstance($request->game_code);
        $url = (new GameService($user))->launchGame($factory);

        return $this->returnData($url);
    }
}

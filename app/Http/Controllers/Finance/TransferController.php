<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use App\Service\WalletService;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    /**
     * showdoc
     * @catalog 財務中心
     * @title 取得遊戲餘額
     * @description 取得遊戲餘額
     * @method get
     * @url /api/finance/game-balance
     * @return {"data":[{"key":"sa","amount":"10.00"}]}
     * @return_param key string 遊戲商key
     * @return_param amount string 餘額
     */
    public function gameBalance()
    {
        $config = SiteConfig::where('site_id', 1)
            ->where('name', SiteConfig::site_menu)
            ->first();
        $wallets = (new WalletService())->getGameBalance();

        $companies = [];
        foreach(json_decode($config->value, true) as $value) {
            if (!$value['status']) {
                continue;
            }

            foreach($value['companies'] as $company) {
                if (!$company['status']) {
                    continue;
                }

                $companies[$company['key']] = [
                    'name' => $company['name'],
                ];
            }
        }

        $wallets->each(function ($item, $key) use (&$companies) {
            $companies[$item->company->key]['amount'] = $item->amount;
        });

        return $this->returnData($companies);
    }

    /**
     * showdoc
     * @catalog 財務中心
     * @title 轉回點數
     * @description 轉回點數
     * @method post
     * @url /api/finance/transfer-back
     */
    public function transferBack()
    {
        (new WalletService())->transferBack();
    }
}

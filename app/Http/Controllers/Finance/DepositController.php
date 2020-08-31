<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\MemberDeposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DepositController extends Controller
{
    protected $url = 'http://gm98pa.ateam99.net/api/v1';

    /**
     * showdoc
     * @catalog 財務中心/儲值
     * @title 儲值
     * @description 儲值
     * @method post
     * @url /api/finance/deposit
     * @param payment_id true int 支付方式ID
     * @param firm_id true int 金流商ID
     * @param amount true int 金額
     * @param type true string 儲值方式
     */
    public function deposit(Request $request)
    {
        $response = Http::post($this->url . '/post', [
            'site' => 1,
            'feature' => $request->payment_id,
            'firm' => $request->firm_id,
            'amount' => $request->amount,
        ]);

        if ($response->status() != 200) {
            return response('deposit error', 422);
        }

        $res = json_decode($response->body());
        $user = auth()->user();

        $member_deposit = new MemberDeposit();
        $member_deposit->member_id = $user->id;
        $member_deposit->order_no = $res->uuid;
        $member_deposit->type = '';
        $member_deposit->cash_id = $request->firm_id;
        $member_deposit->amount = $request->amount;
        $member_deposit->bank_card_id = '';
        $member_deposit->username = '';
        $member_deposit->deposit_type = '';
        $member_deposit->member_bank = '';
        $member_deposit->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 財務中心/儲值
     * @title 支付方式
     * @description 支付方式
     * @method get
     * @url /api/finance/deposit/payment-list
     * @return {"data":[{"id":"1","name":"123"}]}
     * @return_param id int 支付方式ID
     * @return_param name string 支付方式名稱
     */
    public function paymentList(Request $request)
    {
        $response = Http::get($this->url . '/feature', [
            'site' => 1,
        ]);

        $res = collect(json_decode($response->body())->data);

        return $this->returnData(
            $res->filter(function ($row) {
                return $row->status;
            })->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            })
        );
    }

    /**
     * showdoc
     * @catalog 財務中心/儲值
     * @title 支付廠商
     * @description 支付廠商
     * @method get
     * @url /api/finance/deposit/payment-firm
     * @param payment_id true int 支付方式ID
     * @return {"data":[{"id":"1","name":"123"}]}
     * @return_param id int ID
     * @return_param name string 廠商名稱
     */
    public function paymentFirm(Request $request)
    {
        $response = Http::get($this->url . '/firm', [
            'site' => 1,
            'feature' => $request->payment_id,
        ]);

        $res = collect(json_decode($response->body())->data);

        return $this->returnData(
            $res->filter(function ($row) {
                return $row->status;
            })->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                ];
            })
        );
    }
}

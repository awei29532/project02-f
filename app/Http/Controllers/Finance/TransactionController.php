<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\GameCompany;
use App\Models\GameOrderRecord;
use App\Models\MemberTransactionRecord;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * showdoc
     * @catalog 財務中心
     * @title 交易紀錄
     * @description 交易紀錄
     * @method get
     * @url /api/finance/transaction-record
     * @param start_at false string 起始日期
     * @param end_at false string 結束日期
     * @param type false int 交易類型
     * @return {"data":[{}],}
     */
    public function transactionRecord(Request $request)
    {
        $user = auth()->user();

        $query = MemberTransactionRecord::orWhere('from_mid', $user->id)
            ->orWhere('to_mid', $user->id);

        $start_at = $request->start_at ?? '';
        if ($start_at) {
            $query->where('created_at', '>=', $start_at);
        }

        $end_at = $request->end_at ?? '';
        if ($end_at) {
            $query->where('created_at', '<=', $end_at);
        }

        $type = $request->type ?? '';
        if ($type) {
            $query->where('tran_type', $type);
        }

        $res = $query->paginate($request->per_page);

        return $this->returnPaginate($res->map(function ($row) {
            return $row;
        }), $res);
    }

    /**
     * showdoc
     * @catalog 財務中心
     * @title 投注紀錄
     * @description 投注紀錄
     * @method get
     * @url /api/finance/bet-record
     * @param company_id int false 遊戲公司ID
     * @param start_at string false 起始時間
     * @param end_at string false 結束時間
     * @return {"data":[{"game":"123","company":"123","amount":"10","win":"1","order_at":"2020-10-10"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param game string 遊戲
     * @return_param company string 廠商
     * @return_param amount string 下注額度
     * @return_param win string 輸贏
     * @return_param order_at string 下注時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function betRecord(Request $request)
    {
        $user = auth()->user();

        $selects = implode(', ', [
            'game_id',
            'company_id',
            'SUM(amount) as amount',
            'SUM(win) as win',
            'DATE_FORMAT(order_at, "%Y-%m-%d") as order_at',
        ]);

        $start_at = $request->start_at ?? date('Y-m-d');
        $end_at = ($request->end_at ?? date('Y-m-d')) . ' 23:59:59';

        # res
        $query = GameOrderRecord::selectRaw($selects)
            ->where('member_id', $user->id)
            ->groupByRaw('game_id, order_at, company_id')
            ->whereBetween('order_at', [$start_at, $end_at]);

        # total amount
        $total_query = GameOrderRecord::selectRaw('SUM(amount) as total_amount, SUM(win) as total_win')
            ->where('member_id', $user->id)
            ->whereBetween('order_at', [$start_at, $end_at]);

        $company_id = $request->comapny_id ?? '';
        if ($company_id) {
            $query->where('company_id', $company_id);
            $total_query->where('company_id', $company_id);
        }

        $res = $query->paginate($request->per_page);
        $total_res = $total_query->first();

        return response([
            'data' => $res->map(function ($row) {
                return [
                    'game' => $row->game->game_code,
                    'company' => $row->company->name,
                    'amount' => $row->amount,
                    'win' => $row->win,
                    'order_at' => $row->order_at,
                ];
            }),
            'total_amount' => $total_res->total_amount ?? 0,
            'total_win' => $total_res->total_win ?? 0,
            'total' => $res->total(),
            'page' => $res->currentPage(),
            'per_page' => $res->perPage(),
            'last_page' => $res->lastPage(),
        ]);
    }

    /**
     * showdoc
     * @catalog 財務中心
     * @title 遊戲公司列表
     * @description 遊戲公司列表
     * @method get
     * @url /api/finance/company/list
     * @return {"data":[{"id":"1","key":"sa","name":"SA"}]}
     * @return_param id int ID
     * @return_param key string KEY
     * @return_param name string 遊戲公司名稱
     */
    public function companyList(Request $request)
    {
        $res = GameCompany::where('status', '1')->get();

        return $this->returnData($res);
    }
}

<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\MemberBank;
use Illuminate\Http\Request;

class MemberBankController extends Controller
{
    /**
     * showdoc
     * @catalog 財務中心/銀行卡
     * @title 銀行卡列表
     * @description 銀行卡列表
     * @method get
     * @url api/finance/member-bank
     * @return {"data":[{"id","123","bank_name":"123","account":"123"}]}
     * @return_param id string ID
     * @return_param bank_name string 銀行名稱
     * @return_param account string 帳號
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $res = MemberBank::where('member_id', $user->id)
            ->where('type', 1)
            ->get();

        return $this->returnData($res->map(function ($row) {
            return [
                'id' => $row->uuid,
                'bank_name' => $row->bank_name,
                'account' => $row->account,
            ];
        }));
    }

    /**
     * showdoc
     * @catalog 財務中心/銀行卡
     * @title 銀行卡資訊
     * @description 銀行卡資訊
     * @method get
     * @url api/finance/member-bank/{id}
     * @return {"data":{"bank_name":"123","branch_name":"123","account":"123","name":"123"}}
     * @return_param bank_name string 銀行名稱
     * @return_param branch_name string 分行名稱
     * @return_param name string 戶名
     * @return_param account string 帳號
     */
    public function show(Request $request, $id)
    {
        $res = MemberBank::where('sn', $id)->first();

        return $this->returnData([
            'bank_name' => $res->bank_name,
            'branch_name' => $res->branch_name,
            'account' => $res->account,
            'name' => $res->name,
        ]);
    }

    /**
     * showdoc
     * @catalog 財務中心/銀行卡
     * @title 新增銀行卡
     * @description 新增銀行卡
     * @method put
     * @url api/finance/member-bank
     * @param name true string 戶名
     * @param id_number true string 身分證字號
     * @param line true string LINE ID
     * @param bank_name true string 銀行名稱
     * @param branch_name true string 分行名稱
     * @param account true string 帳號
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'id_number' => 'required',
            'line' => 'required',
            'bank_name' => 'required',
            'branch_name' => 'required',
            'account' => 'required',
        ]);
    }
}

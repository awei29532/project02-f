<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Announcement;
use App\Models\Marquee;
use App\Models\Member;
use App\Models\MemberLoginLog;
use App\Models\MemberWallet;
use App\Models\SiteConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use hisorange\BrowserDetect\Parser as Browser;
use Illuminate\Support\Facades\Storage;

class SystemController extends Controller
{
    /**
     * showdoc
     * @catalog 系統功能
     * @title 登入
     * @description 登入
     * @method post
     * @url /api/public/login
     * @param username true string 帳號
     * @param password true string 密碼
     * @return {"data":{"id":"1","username":"member001","amount":"0.00","basic_information_complete":"1","member_bank":"1","security_level":"high"}}
     * @return_param id integer ID
     * @return_param username string 帳號
     * @return_param amount double 錢包餘額
     * @return_param basic_information_complete int 個人資料完善
     * @return_param member_bank int 銀行卡綁定
     * @return_param security_level string 安全等級
     * @remark username:member01，password:123456
     */
    public function login(Request $request)
    {
        $token = auth()->attempt([
            'username' => $request->username,
            'password' => $request->password,
        ]);

        if (!$token) {
            throw new UnauthorizedException('username or password error.');
        }

        /** @var \App\User $user */
        $user = auth()->user();

        # insert login log
        $log = new MemberLoginLog();
        $log->member_id = $user->id;
        $log->device = Browser::browserName();
        $log->browser = Browser::platformName();
        $log->ip = $request->ip();
        $log->saveOrFail();

        return response([
            'data' => [
                'id' => $user->id,
                'username' => $user->username,
                'wallet' => number_format($user->wallet->amount, 2),
                'basic_information_complete' => $user->basicInformationComplete(),
                'member_bank' => count($user->memberBank) ? 1 : 0,
                'security_level' => $user->securityLevel(),
                'cell_phone' => $user->cell_phone,
                'invitation_code' => $user->invitation_code,
            ],
        ], 200, [
            'content-type' => 'application/json',
            'token' => 'Bearer ' . $token,
        ]);
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 登出
     * @description 登出
     * @method post
     * @url /api/public/logout
     */
    public function logout(Request $request)
    {
        auth()->logout();
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 註冊
     * @description 註冊
     * @method post
     * @url /api/public/register
     * @param username true string 帳號
     * @param password true string 密碼
     * @param nickname false string 暱稱
     */
    public function register(Request $request)
    {
        request()->validate([
            'username' => 'required|min:6|unique:member,username|regex:/[a-z]/',
            'password' => 'required|min:8|max:12|regex:/[a-z]/',
        ]);

        $member = new Member();
        $member->username = $request->username;
        $member->password = Hash::make($request->password);
        $member->register_ip = $request->ip();
        $member->saveOrFail();

        $wallet = new MemberWallet();
        $wallet->member_id = $member->id;
        $wallet->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 取得user資訊
     * @description 取得user資訊
     * @method get
     * @url /api/public/auth
     * @return {"data":{"id":"1","username":"member001","nickname":"member001","amount":"0.00","basic_information_complete":"1","member_bank":"1","security_level":"high"}}
     * @return_param id integer ID
     * @return_param username string 帳號
     * @return_param nickname string 暱稱
     * @return_param amount double 錢包餘額
     * @return_param basic_information_complete int 個人資料完善
     * @return_param member_bank int 銀行卡綁定
     * @return_param security_level string 安全等級
     */
    public function auth()
    {
        /** @var \App\User $user */
        $user = auth()->user();

        return $this->returnData([
            'id' => $user->id,
            'username' => $user->username,
            'wallet' => number_format($user->wallet->amount, 2),
            'basic_information_complete' => $user->basicInformationComplete(),
            'member_bank' => count($user->memberBank) ? 1 : 0,
            'security_level' => $user->securityLevel(),
            'cell_phone' => $user->cell_phone,
            'invitation_code' => $user->invitation_code,
        ]);
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 遊戲菜單
     * @description 遊戲菜單
     * @method get
     * @url /api/public/site-menu
     * @return {"data":{"name":"123","value":"123","info":"123"}}
     * @return_param name string 名稱
     * @return_param value json 設定值
     * @return_param info 說明
     */
    public function siteMenu(Request $request)
    {
        $res = SiteConfig::where('site_id', 1)
            ->where('name', 'site_menu')
            ->first();

        return $this->returnData([
            'name' => $res->name,
            'value' =>  json_decode($res->value),
            'info' => $res->info,
        ]);
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 跑馬燈
     * @description 跑馬燈
     * @method get
     * @url /api/public/marquee
     * @return {"data":[{"id":"1","type":"system","content":"sdgfdsg"}]}
     * @return_param id int ID
     * @return_param content string 內容
     */
    public function Marquee(Request $request)
    {
        $res = Marquee::where('status', '1')->get();

        return $this->returnData($res->map(function ($row) {
            return [
                'id' => $row->id,
                'content' => $row->content,
            ];
        }));
    }

    /**
     * showdoc
     * @catalog 系統功能
     * @title 客服QRCODE
     * @description 客服QRCODE
     * @method get
     * @url /api/public/qrcode
     * @return {"data":{"line":"123","telegram":"123"}}
     */
    public function qrcode(Request $request)
    {
        return $this->returnData([
            'line' => '',
            'telegram' => '',                                         
        ]);
    }
}

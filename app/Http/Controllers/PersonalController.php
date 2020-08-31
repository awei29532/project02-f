<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\MemberLoginLog;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PersonalController extends Controller
{
    /**
     * showdoc
     * @catalog 個人功能
     * @title 個人資訊
     * @description 個人資訊
     * @method get
     * @url /api/personal
     * @return {"data":{"name":"123","gender":"male","birthday":"2020-10-10"}}
     * @return_param name string 會員姓名
     * @return_param gender string 性別
     * @return_param birthday string 生日
     */
    public function show(Request $request)
    {
        $user = auth()->user();

        return $this->returnData([
            'name' => $user->name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
        ]);
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 更新個人資訊
     * @description 更新個人資訊
     * @method put
     * @url /api/personal
     * @param name true string 姓名
     * @param gender true string 性別:male、female
     * @param bithday true string 生日
     * @return {"data":{"name":"123","gender":"male","birthday":"2020-10-10"}}
     * @return_param name string 會員姓名
     * @return_param gender string 性別
     * @return_param birthday string 生日
     */
    public function update(Request $request)
    {
        request()->validate([
            'name' => 'required|max:10',
            'gender' => 'required|in:male,female',
            'birthday' => 'required|date_format:Y-m-d',
        ]);

        /** @var \App\User $user */
        $user = auth()->user();
        $user->name = $request->name;
        $user->gender = $request->gender;
        $user->birthday = $request->birthday;
        $user->saveOrFail();

        return $this->returnData([
            'name' => $user->name,
            'gender' => $user->gender,
            'birthday' => $user->birthday,
        ]);
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 變更密碼
     * @description 變更密碼
     * @method put
     * @url /api/personal/change-password
     * @param old_password true string 舊密碼
     * @param new_password true string 新密碼
     */
    public function changePassword(Request $request)
    {
        request()->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|max:12|regex:/[a-z]/',
        ]);

        /** @var \App\User $user */
        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            throw ValidationException::withMessages(['old_password' => 'old password error.']);
        }

        $user->password = Hash::make($request->password);
        $user->saveOrFail();
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 登入記錄
     * @description 登入記錄
     * @method get
     * @url /api/personal/login-log
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":[{"id":"1","device":"pc","browser":"chrome","ip":"127.0.0.1","created_at":"2020-10-10 10:10:10"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param id int ID
     * @return_param device string 裝置
     * @return_param browser string 瀏覽器
     * @return_param ip string IP
     * @return_param created_at string 登入時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function loginLog(Request $request)
    {
        $user = auth()->user();
        $res = MemberLoginLog::where('member_id', $user->id)->paginate($request->per_page);

        return $this->returnPaginate($res->map(function ($row) {
            return [
                'id' => $row->id,
                'device' => $row->device,
                'browser' => $row->browser,
                'ip' => $row->ip,
                'created_at' => $row->created_at,
            ];
        }), $res);
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 公告列表
     * @description 公告列表
     * @method get
     * @url /api/personal/announcement/list
     * @param type false string 類型，system=系統公告，activity=活動公告
     * @return {"data":[{"id":"1","title":"123","content":"123"}],"page":"1","per_page":"15","last_page":"5","total":"150"}
     * @return_param id int ID
     * @return_param title string 標題
     * @return_param content string 內容
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function announcementList(Request $request)
    {
        $query = Announcement::where('lang', 'zh-tw')
            ->orderBy('id', 'desc');

        $type = $request->type ?? '';
        if ($type) {
            $query->where('type', $type);
        }

        $res = $query->paginate($request->per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'title' => $row->title,
                    'content' => $row->content,
                    'created_at' => $row->created_at,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 公告列表
     * @description 公告列表
     * @method get
     * @url /api/personal/announcement/{id}
     * @param id true int 公告ID
     * @return {"data":{"id":"1","title":"qwe456","content":"65sdf1a32"}}
     * @return_param id int ID
     * @return_param title string 標題
     * @return_param content string 內文
     */
    public function announcementDetail($request, $id)
    {
        $res = Announcement::findOrFail($id);

        return $this->returnData([
            'title' => $res->title,
            'content' => $res->content,
            'type' => $res->type,
            'created_at' => $res->created_at,
        ]);
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 站內信列表
     * @description 站內信列表
     * @method get
     * @url /api/personal/message/list
     * @param page false int 頁碼
     * @param per_page false int 每頁幾筆資料
     * @return {"data":{"content":[{"id":"1","title":"123","send_at":"2005-05-07 09:52:16"}],"page":"1","per_page":"15","last_page":"5","total":"150"}}
     * @return_param id int ID
     * @return_param title string 標題
     * @return_param send_at string 傳送時間
     * @return_param total int 資料總筆數
     * @return_param page int 頁碼
     * @return_param per_page int 每頁幾筆資料
     * @return_param last_page int 最後一頁
     */
    public function messageList(Request $request)
    {
        $user = auth()->user();

        $res = Message::where('member_ids', 'like', '%"' . $user->id . '"%')
            ->where('status', '1')
            ->paginate($request->per_page);

        return $this->returnPaginate(
            $res->map(function ($row) {
                return [
                    'id' => $row->id,
                    'title' => $row->title,
                    'send_at' => $row->send_at,
                ];
            }),
            $res
        );
    }

    /**
     * showdoc
     * @catalog 個人功能
     * @title 站內信列表
     * @description 站內信列表
     * @method get
     * @url /api/personal/message/{id}
     * @param id true int 站內信ID
     * @return {"data":{"id":"1","title":"qwe456","content":"65sdf1a32","send_at":"2005-05-07 09:52:16"}}
     * @return_param id int ID
     * @return_param title string 標題
     * @return_param content string 內文
     * @return_param send_at string 傳送時間
     */
    public function messageDetail(Request $request, $id)
    {
        $user = auth()->user();

        $res = Message::where('id', $id)
            ->where('member_ids', 'like', '%"' . $user->id . '"%')
            ->first();

        return $this->returnData([
            'id' => $res->id,
            'title' => $res->title,
            'content' => $res->content,
            'send_at' => $res->send_at,
        ]);
    }
}

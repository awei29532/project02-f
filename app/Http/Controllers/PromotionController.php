<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\PromotionContent;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * showdoc
     * @catalog 系統功能
     * @title
     * @descreption
     * @method get
     * @url /api/public/promotion
     * @return {"data":[{"id":"123","image":"123","content":"123","updated_at":"2020-10-10 10:10:10","created_at":"2020-10-10 10:10:10"}]}
     * @return_param id string ID
     * @return_param image string 圖片路徑
     * @return_param content string 內文
     * @return_param updated_at string 更新時間
     * @return_param created_at string 建立時間
     */
    public function index(Request $request)
    {
        $res = PromotionContent::where('promotion_id', function ($query) {
            $query->select('id')->from((new Promotion())->getTable())->where('status', '1');
        })->get();

        return $this->returnData(
            $res->map(function ($row) {
                return [
                    'id' => $row->promotion_id,
                    'image' => $row->image,
                    'content' => $row->content,
                    'updated_at' => $row->updated_at,
                    'created_at' => $row->created_at,
                ];
            })
        );
    }
}

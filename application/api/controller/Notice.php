<?php
// +----------------------------------------------------------------------
// | JSHOP [ 小程序商城 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://jihainet.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: tianyu <tianyu@jihainet.com>
// +----------------------------------------------------------------------
namespace app\api\controller;
use app\common\model\Notice as NoticeModel;
use app\common\controller\Api;

/**
 * 公告
 * Class Notice
 * @package app\api\controller
 */
class Notice extends Api
{
    /**
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function noticeList()
    {
        $result = [
            'status' => true,
            'msg' => error_code(10024,true),
            'data' => []
        ];

        $noticeModel = new NoticeModel;

        //获取排序方法
        $order = input('param.order','id');
        //获取排序方式
        $orderType = input('param.orderType','desc');
        //每页显示多少，默认5条
        $pageSize = input('param.pageSize',5);
        //获取当前页
        $page = input('param.page',1);
        //获取公告类型
        $type   = input('param.type',1);
        $data = $noticeModel->getNoticeList($type, $order, $orderType, $page, $pageSize);

        if($data) {
            $result['data'] = $data;
        }
        return $result;
    }


    /**
     *
     *  获取公告详情
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function noticeInfo()
    {
        $result = [
            'status' => false,
            'msg' => error_code(10025,true),
            'data' => []
        ];
        $noticeModel = new NoticeModel;
        $data = $noticeModel->getNoticeInfo(input('param.id/d'));

        if ($data) {
            $result['status'] = true;
            $result['msg'] = error_code(10024,true);
            $result['data'] = $data;
        }

        return $result;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: tian yu
 * Date: 2018/1/8 0008
 * Time: 16:08
 */
namespace app\common\model;
use think\Validate;
use think\model\concern\SoftDelete;

class Brand extends Common
{
    use SoftDelete;
    protected $deleteTime = 'isdel';
    protected $autoWriteTimestamp = true;
    protected $updateTime = 'utime';

    protected $rule = [
        'name'          => 'require|max:50',
        'logo'          => 'require',
        'sort'          => 'number',
    ];
    protected $msg = [
        'name.require'  => '请输入品牌名',
        'name.max'      => '品牌名长度最大50位',
        'logo.require'  => '请选择要上传的品牌图片',
        'sort'          => '排序必须为数字'
    ];


    /**
     * @param $post
     *
     * @return mixed
     * @throws \think\exception\DbException
     */
    public function tableData($post)
    {
        if(isset($post['limit'])){
            $limit = $post['limit'];
        }else{
            $limit = config('paginate.list_rows');
        }
        $tableWhere = $this->tableWhere($post);
        $list = $this->with('sellerInfo')->field($tableWhere['field'])->where($tableWhere['where'])->order($tableWhere['order'])->paginate($limit);
        $data = $this->tableFormat($list->getCollection());         //返回的数据格式化，并渲染成table所需要的最终的显示数据类型
        $re['code'] = 0;
        $re['msg'] = '';
        $re['count'] = $list->total();
        $re['data'] = $data;

        return $re;
    }



    /**
     *  添加品牌
     * User:tianyu
     * @param $data
     * @return array
     */
    public function addData($data)
    {
        $result = ['status'=>true,'msg'=>'保存成功','data'=>''];
        $validate = new Validate($this->rule,$this->msg);
        if (!$validate->check($data)) {
            $result['status'] = false;
            $result['msg'] = $validate->getError();
        } else {
            if (!$this->allowField(true)->save($data))
            {
                $result['status'] = false;
                $result['msg'] = '保存失败';
            }
        }
        return $result;
    }


    /**
     *  修改品牌
     * User:tianyu
     * @param $data
     * @return array
     */
    public function saveData($data)
    {
        $result = ['status'=>true,'msg'=>'保存成功','data'=>''];
        $validate = new Validate($this->rule,$this->msg);
        if (!$validate->check($data)) {
            $result['status'] = false;
            $result['msg'] = $validate->getError();
        } else {
            if (!$this->allowField(true)->save($data,['id'=>$data['id']]))
            {
                $result['status'] = false;
                $result['msg'] = '保存失败';
            }
        }
        return $result;
    }


    //where搜索条件
    protected function tableWhere($post)
    {
        $where = [];
        if(isset($post['name']) && $post['name'] != ""){
            $where[] = ['name', 'like', '%'.$post['name'].'%'];
        }
        if(isset($post['utime']) && $post['utime'] != ""){
            $stime = strtotime($post['utime'].'00:00:00',time());
            $etime = strtotime($post['utime'].'23:59:59',time());
            $where[] = ['utime', ['EGT',$stime],['ELT',$etime],'and'];
        }
        if(isset($post['seller_id']) && $post['seller_id'] != ""){
            $where[] = ['seller_id','eq',$post['seller_id']];
        }
        $result['where'] = $where;
        $result['field'] = "*";
        $result['order'] = ['sort ASC'];
        return $result;
    }


    /**
     * @param $list
     *
     * @return mixed
     */
    protected function tableFormat($list)
    {
        foreach($list as $key => $val){
            $list[$key]['logo'] = _sImage($val['logo']);
            $list[$key]['utime'] = date('Y-m-d H:i:s',$val['utime']);
        }
        return $list;
    }


    /**
     * 获取全部品牌
     * @return array|\PDOStatement|string|\think\Collection
     * User: wjima
     * Email:1457529125@qq.com
     * Date: 2018-01-12 17:32
     */
    public function getAllBrand($seller_id = 0)
    {
        $filter = [];
        if ($seller_id) {
            $filter['seller_id'] = $seller_id;
        }
        $data = $this->field('id,name')
            ->where($filter)
            ->order('sort asc')
            ->select();

        return $data;
    }

    //列表
    public function brandList($data){
        return $this->field('name,logo,sort,utime')
            ->where('seller_id',$data['sellerId'])
            ->order($data['order'],$data['orderType'])
            ->page($data['page'],$data['pageSize'])
            ->select();
    }

    /**
     * 根据名称获取品牌信息
     * @param string $name
     * @param $seller_id
     * @param bool $isForce 没有名称时，是否添加
     * @return int
     */
    public function getInfoByName($name = '', $seller_id, $isForce = false)
    {
        if (!$name || !$seller_id) {
            return false;
        }
        $brand_id = 0;
        $brand = $this->field('id')->where([['name', 'like', '%' . $name . '%'], ['seller_id', 'eq', $seller_id]])->find();
        if (!$brand && $isForce) {
            $this->save([
                'name' => $name,
                'seller_id' => $seller_id
            ]);
            $brand_id = $this->getLastInsID();
        } elseif ($brand) {
            $brand_id = $brand['id'];
        }
        return $brand_id;
    }



    public function sellerInfo()
    {
        return $this->hasOne('Seller','id','seller_id')->bind(['seller_name']);
    }
}
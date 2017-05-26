<?php
namespace app\admin\controller;

class Reader extends Base{
	
	public function _initialize(){
		parent::_initialize();
		$m1 = db('model')->field('id,title,name')->where(['title'=>$this->controller])->find();
		$m = db('model')->field('id,title,name')->where(['title'=>$this->action,'fid'=>$m1['id']])->find();
		$m = $m?$m:$m1;
		$this->m=$m;
	}
	/**
	 * 显示读者
	 */
	public function index(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		if(empty($param)){
			$param = request()->param();
		}
		if(!empty($param['s_keywords'])){
			$where['read_no|read_name']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['read_status']=$param['s_status'];
		}
		if(!empty($param['s_date'])){
			$date = explode('-',$param['s_date']);
			$where['cate_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:''
		]);
		$list = db('reader')->where($where)->order('read_time desc')->paginate(10);
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('reader')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}

	public function administrator(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		if(empty($param)){
			$param = request()->param();
		}
		if(!empty($param['s_keywords'])){
			$where['read_no|read_name']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['read_status']=$param['s_status'];
		}
		if(!empty($param['s_date'])){
			$date = explode('-',$param['s_date']);
			$where['cate_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:''
		]);
		$list = db('reader')->where($where)->order('read_time desc')->paginate(10);
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('reader')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}

	/**
	 * 添加/修改 
	 */
	public function add($id=0){
		$model = ['name'=>$this->m['name']];
		$type = 0;
		if($id){
        	$vo = db('reader')->field('dates',true)->find($id);
        	$this->assign('info',$vo);
		}
		$this->assign('type',$type);
		$this->assign('model',$model);
		return view();
	}
	/**
	 * 生成卡号
	 */
	public function creat_cards($t=0,$id=0){
		if($t==2){
			$id = $id+time();
		}else{
			$id = db('reader')->max('id');
			$id = $id+time();
		}
		$code= new \service\Code();
		$card_no = $code->encodeID($id,5);
		$card_pre = '755'; 
		$card_vc = substr(md5($card_pre.$card_no),0,2); 
		$card_vc = strtoupper($card_vc); 
		return [
			'status'=>1,
			'no'=>$card_pre.$card_no.$card_vc
		];
	}
	
	/**
	 * [add_handler 修改/添加控制器]
	 * @param integer $id [description]
	 */
	public function add_handler($id=0){
		$param = request()->param();
		if($param['read_status']==2 && $param['old_read_no']!=$param['read_no']){
			$param['read_status']=0;
		}
		unset($param['old_read_no']);
		if($id){
			$param['dates']=time();
			if(!db('reader')->update($param)){
				return ['status'=>0,'msg'=>'修改失败请重试'];
			}
			return ['status'=>1,'msg'=>'修改成功','redirect'=>Url('index')];
		}else{
			$param['read_time']=time();
			if(!db('reader')->insert($param)){
				return ['status'=>0,'msg'=>'添加失败请重试'];
			}
			return ['status'=>1,'msg'=>'添加成功','redirect'=>Url('index')];
		}
	}

	/**
	 * [status 状态操作]
	 * @param  [type] $id [修改id]
	 * @param  [type] $type  [操作类型]
	 * @return [type]     [description]
	 */
	public function status($id,$type){
		$type = ($type=="delete-all")?"delete":$type;
		$_result = $this->_status($id,'reader',$type,'','','read_');
		return $_result;
	}
}

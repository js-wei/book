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
			$where['username']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['status']=$param['s_status'];
		}
		if(!empty($param['s_date'])){
			$date = explode('-',$param['s_date']);
			$where['last_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:''
		]);
		$list = db('admin')->where($where)->order('date desc')->paginate(10);
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('admin')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}
	
	public function check_group($id=0){
		$ids = db('admin')->field('gid')->find($id);
		$cate = db('model')->field('id,name')->where(['id'=>['in',$ids['gid']]])->select();
		$this->assign('list',$cate);
		return view();
	}

	public function add_admin($id=0){
		$model = ['name'=>$this->m['name']];
		$type = 0;
		$controller = db('model')->field('id,fid,name')->where('id!=1 and fid=0')->select();
		
		if($id){
        	$vo = db('admin')->field('dates',true)->find($id);
        	$this->assign('info',$vo);
			$ids = explode(',', $vo['gid']);
			foreach($controller as $k => $v){
				if(in_array($v['id'], $ids)){
					$controller[$k]['checked']=1;
				}else{
					
					$controller[$k]['checked']=0;
				}
			}
		}else{
			foreach($controller as $k => $v){
				$controller[$k]['checked']=0;
			}
		}
		
		$this->assign('type',$type);
		$this->assign('action',$controller);
		$this->assign('model',$model);
		return view();
	}
	
	
	public function reset_password($id=0){
		if(!$id){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		$password = substr(md5('123456'),10,15);
		if(!db('admin')->update([
			'id'=>$id,
			'password'=>$password,
			'dates'=>time()
		])){
			return ['status'=>0,'msg'=>'密码重置失败'];
		}
		return ['status'=>1,'msg'=>'密码重置成功'];
	}
	
	public function add_admin_handler($id=0){
		$param = request()->param();
		$param['gid']=implode(',', $param['gid']);
		$param['password'] = substr(input('password','','MD5'),10,15);
		unset($param['confirmPassword']);
		if($id){
			$param['dates']=time();
			if(!db('admin')->update($param)){
				return ['status'=>0,'msg'=>'修改失败请重试'];
			}
			return ['status'=>1,'msg'=>'修改成功','redirect'=>Url('administrator')];
		}else{
			$param['date']=time();
			if(!db('admin')->insert($param)){
				return ['status'=>0,'msg'=>'添加失败请重试'];
			}
			return ['status'=>1,'msg'=>'添加成功','redirect'=>Url('administrator')];
		}
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
	
	public function admin_status($id,$type){
		$type = ($type=="delete-all")?"delete":$type;
		$_result = $this->_status($id,'admin',$type,'','administrator','');
		return $_result;
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

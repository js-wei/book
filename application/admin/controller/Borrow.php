<?php
namespace app\admin\controller;

class Borrow extends Base{
	public function _initialize(){
		parent::_initialize();
		$m1 = db('model')->field('id,title,name')->where(['title'=>$this->controller])->find();
		$m = db('model')->field('id,title,name')->where(['title'=>$this->action,'fid'=>$m1['id']])->find();
		$m = $m?$m:$m1;
		$this->m=$m;
		$this->assign('option',1);
	}
	/**
	 * 显示读者
	 */
	public function index(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		$bk='';
		$nk='';
		$param['s_status']=-1;
		$param = request()->param();
		
		if(!empty($param)){
			if(!empty($param['s_keywords'])){
				$ti['book_name']=['like',"%".$param['s_keywords']."%"];
				$title = db('book')->field('id')->where($ti)->select();
				foreach($title as $v){
					$bk.=','.$v['id'];
				}
				$bk = substr($bk,1);
				
				$ni['read_name']=['like',"%".$param['s_keywords']."%"];
				$read = db('reader')->field('id')->where($ni)->select();
				foreach($read as $v){
					$nk.=','.$v['id'];
				}
				$nk = substr($nk,1);
				
				$where['bor_bid']=['in',$bk];
			}
			if( $param['s_status']!=-1){
				$where['bor_status']=$param['s_status'];
			}
			if(!empty($param['s_date'])){
				$date = explode('-',$param['s_date']);
				$where['bor_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
			}
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:-1
		]);
		
		$list = db('borrow')->where($where)
		->alias('a')
		->join('think_book b','a.bor_bid = b.id','LEFT')
		->join('think_reader r','a.bor_rid = r.id','LEFT')
		->field('a.id,a.bor_rid,a.bor_bid,a.bor_count,a.bor_date,a.bor_gdate,a.bor_status,a.bor_notify,b.book_name,b.book_no,r.read_no,r.read_name')
		->order('bor_date desc')->paginate(10);
	
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('borrow')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}
	
	public function expire(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		$bk='';
		$nk='';
		$param['s_status']=-1;
		$param = request()->param();
		
		if(!empty($param)){
			if(!empty($param['s_keywords'])){
				$ti['book_name']=['like',"%".$param['s_keywords']."%"];
				$title = db('book')->field('id')->where($ti)->select();
				foreach($title as $v){
					$bk.=','.$v['id'];
				}
				$bk = substr($bk,1);
				
				$ni['read_name']=['like',"%".$param['s_keywords']."%"];
				$read = db('reader')->field('id')->where($ni)->select();
				foreach($read as $v){
					$nk.=','.$v['id'];
				}
				$nk = substr($nk,1);
				
				$where['bor_bid']=['in',$bk];
			}
			if( $param['s_status']!=-1){
				$where['bor_status']=$param['s_status'];
			}
			if(!empty($param['s_date'])){
				$date = explode('-',$param['s_date']);
				$where['bor_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
			}
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:-1
		]);
		
		$where['bor_gdate']=['between time',[time(),strtotime("+10 day + 24 hour")]];
		$list = db('borrow')->where($where)
		->alias('a')
		->join('think_book b','a.bor_bid = b.id','LEFT')
		->join('think_reader r','a.bor_rid = r.id','LEFT')
		->field('a.id,a.bor_rid,a.bor_bid,a.bor_count,a.bor_date,a.bor_gdate,a.bor_status,a.bor_notify,b.book_name,b.book_no,r.read_no,r.read_name')
		->order('bor_date desc')->paginate(10);
	
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('borrow')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('option',1);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}
	
	public function exceed(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		$bk='';
		$nk='';
		$param['s_status']=-1;
		$param = request()->param();
		
		if(!empty($param)){
			if(!empty($param['s_keywords'])){
				$ti['book_name']=['like',"%".$param['s_keywords']."%"];
				$title = db('book')->field('id')->where($ti)->select();
				foreach($title as $v){
					$bk.=','.$v['id'];
				}
				$bk = substr($bk,1);
				
				$ni['read_name']=['like',"%".$param['s_keywords']."%"];
				$read = db('reader')->field('id')->where($ni)->select();
				foreach($read as $v){
					$nk.=','.$v['id'];
				}
				$nk = substr($nk,1);
				
				$where['bor_bid']=['in',$bk];
			}
			if( $param['s_status']!=-1){
				$where['bor_status']=$param['s_status'];
			}
			if(!empty($param['s_date'])){
				$date = explode('-',$param['s_date']);
				$where['bor_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
			}
		}
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:-1
		]);
		
		$where['bor_gdate']=['lt',time()];
		$list = db('borrow')->where($where)
		->alias('a')
		->join('think_book b','a.bor_bid = b.id','LEFT')
		->join('think_reader r','a.bor_rid = r.id','LEFT')
		->field('a.id,a.bor_rid,a.bor_bid,a.bor_count,a.bor_date,a.bor_gdate,a.bor_status,a.bor_notify,b.book_name,b.book_no,r.read_no,r.read_name')
		->order('bor_date desc')->paginate(10);
	
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('borrow')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('option',1);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}

	public function add_borrow($id=0){
		$model = ['name'=>$this->m['name']];
		$type = 0;
		if($id){
        	$vo = db('book')->field('dates',true)->find($id);
        	$this->assign('info',$vo);
		}
		$cate = db('category')->where(['cate_status'=>0])->select();
		$this->assign('cate',$cate);
		$this->assign('type',$type);
		$this->assign('model',$model);
		return view();
	}
	
	/**
	 * 添加/修改 
	 */
	public function add_index($id=0){
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
	
	public function add_book_handler($id=0){
		$param = request()->param();
		if($id){
			$param['dates']=time();
			if(!db('book')->update($param)){
				return ['status'=>0,'msg'=>'修改失败请重试'];
			}
			return ['status'=>1,'msg'=>'修改成功','redirect'=>Url('books')];
		}else{
			$param['book_date']=time();
			if(!db('book')->insert($param)){
				return ['status'=>0,'msg'=>'添加失败请重试'];
			}
			return ['status'=>1,'msg'=>'添加成功','redirect'=>Url('books')];
		}
	}
	
	/**
	 * 添加分类
	 */
	public function add_cate_handler($id=0){
		$param = request()->param();
		if($id){
			$param['dates']=time();
			if(!db('category')->update($param)){
				return ['status'=>0,'msg'=>'修改失败请重试'];
			}
			return ['status'=>1,'msg'=>'修改成功','redirect'=>Url('index')];
		}else{
			$param['cate_date']=time();
			if(!db('category')->insert($param)){
				return ['status'=>0,'msg'=>'添加失败请重试'];
			}
			return ['status'=>1,'msg'=>'添加成功','redirect'=>Url('index')];
		}
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
	public function bor_notify($id,$type){
		
		if(empty($id)){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		
		if(!db('borrow')->update([
			'id'=>$id,
			'bor_notify'=>1,
			'dates'=>time()
		])){
			return ['status'=>0,'msg'=>'提醒失败']; 
		}
		return ['status'=>1,'msg'=>'成功提醒','redirect'=>Url('expire')]; 
	}
	
	public function bor_status($id,$type){
		$type = ($type=="delete-all")?"delete":$type;
		$_result = $this->_status($id,'borrow',$type,'','','bor_');
		return $_result;
	}
	public function cate_status($id,$type){
		$type = ($type=="delete-all")?"delete":$type;
		$_result = $this->_status($id,'category',$type,'','','cate_');
		return $_result;
	}
	
	public function book_status($id,$type){
		$type = ($type=="delete-all")?"delete":$type;
		$_result = $this->_status($id,'book',$type,'','book','book_');
		return $_result;
	}
	
}

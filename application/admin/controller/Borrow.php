<?php
namespace app\admin\controller;

class Borrow extends Base{
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
			$where['cate_name']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['cate_status']=$param['s_status'];
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
		$list = db('category')->where($where)->order('cate_date desc')->paginate(10);
		//p(db('category')->where($where)->fetchSql(true)->select());die;
		// 查询状态为1的用户数据 并且每页显示10条数据
		$count = db('category')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}

	public function books(){
		$model = ['name'=>$this->m['name']];
		$where=[];
		if(empty($param)){
			$param = request()->param();
		}
		if(!empty($param['s_keywords'])){
			$where['book_name']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['book_status']=$param['s_status'];
		}
		if(!empty($param['s_date'])){
			$date = explode('-',$param['s_date']);
			$where['book_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
		}
		
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:''
		]);
		
		$list = db('book')->alias('a')->where($where)->join('think_category c','a.book_cate = c.id','LEFT')
		->field('a.id,a.book_name,a.book_desc,a.book_no,a.book_author,a.book_publish,a.book_cate,a.book_price,a.book_mark,a.book_totals,a.book_given,a.book_left,a.book_status,a.book_date,c.cate_name')->order('book_date desc')->paginate(10);
		
		$count = db('book')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}


	public function borrow($id=0){
		$model = ['name'=>$this->m['name']];
		$where=[];
		if(empty($param)){
			$param = request()->param();
		}
		if(!empty($param['s_keywords'])){
			$where['book_name']=['like',"%".$param['s_keywords']."%"];
		}
		if(!empty($param['s_status']) && $param['s_status']!=-1){
			$where['book_status']=$param['s_status'];
		}
		if(!empty($param['s_date'])){
			$date = explode('-',$param['s_date']);
			$where['book_date']=['between time',[strtotime($date[0]),strtotime("$date[1] + 24 hour")]];
		}
		
		$this->assign('search',[
			's_keywords'=>!empty($param['s_keywords'])?$param['s_keywords']:'',
			's_date'=>!empty($param['s_date'])?$param['s_date']:'',
			's_status'=>!empty($param['s_status'])?$param['s_status']:''
		]);
		
		$list = db('book')->alias('a')->where($where)->join('think_category c','a.book_cate = c.id','LEFT')
		->field('a.id,a.book_name,a.book_desc,a.book_no,a.book_author,a.book_publish,a.book_cate,a.book_price,a.book_mark,a.book_totals,a.book_given,a.book_left,a.book_status,a.book_date,c.cate_name')->order('book_date desc')->paginate(10);
		
		$count = db('book')->where($where)->count('*');
		$this->assign('count',$count);
		$this->assign('model',$model);
		$this->assign('list',$list);
		return view();
	}


	
	public function add_book($id=0){
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

<?php
namespace app\index\controller;
use think\controller;

class Book extends controller{
	protected function _initialize(){
		header('Content-type:text/html;charset=utf-8;');
		set_time_limit(0);
        //获取网站配置
        $this->site = db('Config')->order('id desc')->find();
	}
	public function borrow(){
		return view();
	}
	
	public function query($q='',$p=1){
		$where=[];
		$list=[];
		if($q){
			$where['book_name|book_publish']=['like',"%{$q}%"];
			$list = db('book')->field('dates',true)->where($where)->order('book_date desc')->paginate(10);
			foreach($list as $k=>$v){
				$v['name'] = $v['book_name'];
				$v['book_name'] = heigLine($q,$v['book_name']);
				$v['book_publish'] = heigLine($q,$v['book_publish']);
				$list[$k]=$v;
			}
		}
		$this->assign('q',$q);
		$this->assign('p',$p);
		$this->assign('list',$list);
		return view();
	}
	
	public function add_session($id=0){
		$no = session('_no');
		if(!$no){
			return ['status'=>-1,'msg'=>'请先输入借书号'];
		}
		if(!$id){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		$book = db('book')->field('dates',true)->find($id);
		if(empty($book)){
			return ['status'=>0,'msg'=>'参数错误']; 
		}
		if($book['book_totals']<=0){
			return ['status'=>0,'msg'=>'图书已借完,请选择其他书目']; 
		}
		$reader = db('borrow')->where(['bor_rid'=>$no])->sum('bor_count');
		if($reader>=10){
			return ['status'=>0,'msg'=>'您已经达到借书上线,请先还书在进行借书']; 
		}
		return ['status'=>1,'data'=>$book];
	}
	
	public function check_no(){
		return view();
	}
	
	public function my(){
		return view();
	}
	
	public function add_borrow(){
		$num =  request()->param()['num'];
		$no = session('_no');
		if(!$no){
			return ['status'=>-1,'msg'=>'请先输入借书号'];
		}
		$_result=[];
		
		foreach($num as $k=>$v){
			$_result[]=[
				'bor_rid'=>$no,
				'bor_bid'=>$k,
				'bor_count'=>$v,
				'bor_date'=>time(),
				'bor_gdate'=>strtotime("+{$this->site['time']} day"),
			];
		}
		if(!db('borrow')->insertAll($_result)){
			return ['status'=>0,'msg'=>'借书失败,请稍后重试'];
		}
		return ['status'=>1,'msg'=>'借书成功,是否转到我的借书','redirect'=>Url('my')];
	}
	
	public function add_account($no='',$tel=''){
		if(!$no){
			return ['status'=>0,'msg'=>'请输入卡号'];
		}
		if(!$tel){
			return ['status'=>0,'msg'=>'请输入手机号'];
		}
		$reader = db('reader')->field('dates',true)->where(['read_no'=>$no])->find();
		if(empty($reader)){
			return ['status'=>0,'msg'=>'输入卡号有误'];
		}
		if($reader['read_contac']!=$tel){
			return ['status'=>0,'msg'=>'输入手机有误'];
		}
		session('_no',$reader['id']);
		return ['status'=>1,'msg'=>'验证成功您可以尽情的借书了'];
	}
}

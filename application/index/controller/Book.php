<?php
namespace app\index\controller;
use think\controller;

class Book extends controller{
	
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
		if(!session('_no')){
			return ['status'=>-1,'msg'=>'请先输入借书号'];
		}
		if(!$id){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		$book = db('book')->find($id);
	}
}

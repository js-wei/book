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
		$reader = db('borrow')->where(['bor_bid'=>$id,'bor_rid'=>$no,'bor_status'=>0])->sum('bor_count');
		if($reader>0){
			return ['status'=>0,'msg'=>'您已经借过此书了,不可以再次借本书']; 
		}
		if(empty($book)){
			return ['status'=>0,'msg'=>'参数错误']; 
		}
		if($book['book_totals']<=0){
			return ['status'=>0,'msg'=>'图书已借完,请选择其他书目']; 
		}
		$reader = db('borrow')->where(['bor_rid'=>$no,'bor_status'=>0])->sum('bor_count');
		if($reader>=10){
			return ['status'=>0,'msg'=>'您已经达到借书上线,请先还书在进行借书']; 
		}
		return ['status'=>1,'data'=>$book];
	}
	
	public function given(){
		return view();
	}
	
	public function check_no(){
		return view();
	}
	
	public function my($q='',$p=''){
		$where=[];
		$list=[];
		if($q){
			$r = db('reader')->field('dates',true)->where(['read_no'=>$q])->find();
			if(!$r){
				$this->error('没有此卡号');
			}
			$list = db('borrow')
			->alias('a')
			->field('a.id,a.bor_rid,a.bor_date,a.bor_gdate,a.bor_bid,a.bor_count,a.bor_date,a.bor_gdate,a.bor_status,a.bor_notify,b.book_name,b.book_author,b.book_publish')
			->where($where)
			->join('think_book b','a.bor_bid = b.id','LEFT')
			->order('book_date desc')->paginate(18);
			
		}
		
		$this->assign('q',$q);
		$this->assign('p',$p);
		$this->assign('list',$list);
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
		foreach($num as $k=>$v){
			$b = db('book')->where(['id'=>$k])->find();
			db('book')->update([
				'id'=>$b['id'],
				'book_given'=>$v,
				'book_left'=>$b['book_totals'] - $v,
			]);
		}
		return ['status'=>1,'msg'=>'借书成功,是否转到我的借书','redirect'=>Url('my')];
	}
	
	public function give_books($no,$rid){
		if(!$no){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		if(!$rid){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		$book = db('book')->field('dates',true)->where(['book_name'=>$no])->find();
		$read = db('read')->field('dates',true)->where(['read_name'=>$rid])->find();
		if(!$book){
			return ['status'=>0,'msg'=>'没有书籍'];
		}
		if(!$read){
			return ['status'=>0,'msg'=>'没有书籍'];
		}
		if(!db('book')->update([
			'id'=>$book['id'],
			'book_given'=>$book['book_given']-1,
			'book_left'=>$book['book_left']+1
		])){
			return ['status'=>0,'msg'=>'操作失败'];
		}
		$borrow = db('borrow')->where(['bid'=>$book['id'],'rid'=>$read['id']])->find();
		if(!$borrow){
			return ['status'=>0,'msg'=>'操作失败'];
		}
		$msg='';
		if($borrow['bor_gdate']<time()){
			$diff = time_diff($borrow['bor_gdate']);
			$m = $diff['day']*$this->site['price']*10;
			$msg = "您已经超过规定还书期限{$diff['day']}天，需要额外支付：{$m}元。";
		}
		db('borrow')->uppdate([
			'id'=>$borrow['id'],
			'bor_status'=>1
		]);
		return ['status'=>1,'msg'=>'操作成功'.$msg];
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

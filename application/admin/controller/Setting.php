<?php
namespace app\admin\controller;

class Setting extends Base{
	protected function  _initialize(){
		parent::_initialize();
		$m1 = db('model')->field('id,title,name')->where(['title'=>$this->controller])->find();
		$m = db('model')->field('id,title,name')->where(['title'=>$this->action,'fid'=>$m1['id']])->find();
		$m = $m?$m:$m1;
		$this->m=$m;
	}
	
	public function punish(){
		$model = ['name'=>$this->m['name']];
		$info = db('config')->find(1);
		$this->assign('info',$info);
		$this->assign('model',$model);
		return view();
	}
	
	public function add_handler($id=0,$price=0){
		if(empty($id) || empty($price)){
			return ['status'=>0,'msg'=>'参数错误'];
		}
		if(!db('config')->update([
			'id'=>$id,
			'price'=>$price,
			'dates'=>time()
		])){
			return ['status'=>0,'msg'=>'修改失败'];
		}
		return ['status'=>1,'msg'=>'修改成功','redirect'=>Url('punish')];
	}
}

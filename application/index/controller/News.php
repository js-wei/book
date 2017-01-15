<?php
namespace app\index\controller;
use app\index\model\Article;

class News extends Base{
   /**
    * 获取栏目数据列表
    * @param int $id 栏目id
    * @param int $p  当前页数
    */
   public function getlisting($id=0,$p=0){
   		$map['status']=0;
  		if($id){
  			$map['column_id']=$id;
  		}
     	$list = db('article')->field('id,title,author,keywords,description,content')->where($map)->paginate();
  		$total = db('article')->where($map)->count('id');
  		
  		$list = \Service\Object2Array::parse1($list);
  		
  		if(empty($list['data'])){
  			 return jsonp(['status'=>0,'msg'=>'小编很懒没有留下任何的脚印(:']);
  		}
  		
  		foreach($list['data'] as $k => $v){
  			$content = htmlspecialchars_decode($v['content']);
  			$image = get_image($content);
  			if(!empty($image)){
  				$list['data'][$k]['image']=$image[0];
  			}
  			unset($list['data'][$k]['content']);
  		}
  		$list['page_count']=ceil($total/Config('paginate.list_rows'));
  		ksort($list);
		
   		return  jsonp($list);
   }
   /**
    * [details 获取详细信息]
    * @param  integer $id [文档id]
    * @return [type]      [description]
    */
   public function getdetails($id=0){
   		$m = new Article($id);
	    return jsonp($m->getAllData());
   }
}

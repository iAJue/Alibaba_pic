<?php
/**
 * 阿里图床上传
 * @author: 阿珏 (QQ群：712473912)
 * @link: http://www.52ecy.cn
 * @version: 1.1
 */

$file = $_FILES['file'];
if (is_uploaded_file($file['tmp_name'])){
	$arr = pathinfo($file['name']);
	$ext_suffix = $arr['extension'];
	$allow_suffix = array('jpg','gif','jpeg','png');
	if(!in_array($ext_suffix, $allow_suffix)){
		msg(['code'=> 1,'msg'=> '上传格式不支持']);
	}
	$new_filename = time().rand(100,1000).'.'.$ext_suffix;
	if (move_uploaded_file($file['tmp_name'], $new_filename)){
		$data = upload('https://kfupload.alibaba.com/mupload',$new_filename);
		$pattern = '/"url":"(.*?)"/';
		preg_match($pattern, $data, $match);
		@unlink($new_filename);
		if($match && $match[1]!=''){
			msg(['code'=> 0,'msg'=> $match[1]]);
		}else{
			msg(['code'=> 1,'msg'=> '上传失败']);
		}
	}else{
		msg(['code'=> 1,'msg'=> '上传数据有误']);
	}

}else{
	msg(['code'=> 1,'msg'=> '上传数据有误']);
}



function upload($url,$file) {
	return get_url($url,[
		'scene' => 'aeMessageCenterV2ImageRule',
		'name' =>$file,
		'file' => new \CURLFile(realpath($file))
	]);
}


function get_url($url,$post){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
	}
	if(curl_exec($ch) === false){
	  echo 'Curl error: ' . curl_error($ch);
	}
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

function msg($data){
	exit(json_encode($data));
}
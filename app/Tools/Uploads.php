<?php

namespace App\Tools;

use App\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use App\Models\Config;
use Illuminate\Http\Request;

class Uploads extends Controller{

    private $ossClient = null;
    private $req = null;
    private $data = [];

    private $resp = [
        'status'        =>  false,
        'path'          =>  '', 
        // 'thumb_path'    =>  '', 
    ];

    private $allow = ['jpeg','png','gif','jpg'];
    private $water = ''; // 水印图片地址
    private $dir = '/shop/';

    private $width = 800;
    private $height = 800;

    /**
     * 
     * @author hg
     * $allow  允许上传类型
     * $water  水印地址 不为空则加水印
     * $width $height 默认图片的宽高
     * $is_oss 是否oss上传
     * $filepath 自定义目录存储 空则系统自定义 要带尾部 /
     * 
     */

    
    // 上传文件方法
    public function uploads($data = []){

        $this->data = $data;

        // 默认name
        $name = 'file';

        // 如果没有name默认file
        if(isset($data['name'])){
            $name = $data['name'];
        }

        $this->data['name'] = $name;

        // 如果没有文件则返回false
        if(!request()->hasFile($name)){
            $this->resp['msg'] = '无图片上传！';
            return $this->resp;
        }

        $config_model = new Config;
        $config_list = $config_model->get();
        $config_list_format = get_format_config($config_list);
        $this->data['config_list_format'] = $config_list_format;

        $ali_oss_config = $config_list_format['alioss'];
        $config_alioss_format = json_decode($ali_oss_config,true);
        $this->data['config_alioss_format'] = $config_alioss_format;
        
        
        // 判断是本地上传还是OSS
        if(isset($config_alioss_format['status']) && !empty($config_alioss_format['status'])){
            
            $this->OssClient = new \OSS\OssClient($config_alioss_format['access_key'], $config_alioss_format['secret_access'], $config_alioss_format['endpoint']);

            $returnPath = '/Uploads';
            $filepath = public_path($returnPath);
            

            $this->data['oss_real_path'] = $this->dir.date('Y_m_d');
            $this->data['real_path'] = $filepath;

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $this->data['real_path'] = $filepath;
                $this->data['oss_real_path'] = $this->dir.$data['filepath'].date('Y_m_d');
            }

        }else{
            $returnPath = '/Uploads/'.date('Y_m_d');

            // 文件存储路径
            $filepath = public_path($returnPath);

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $returnPath = '/Uploads/'.$data['filepath'].date('Y_m_d');
                $filepath = public_path($returnPath);
            }

            $this->data['oss_real_path'] = '';
            $this->data['real_path'] = $filepath;
            $this->data['return_path'] = $returnPath;

            
        }

        if (!file_exists($this->data['real_path'])) {
            mkdir($this->data['real_path'],  0777, true);
        }

        // 判断是多文件上传还是单文件
        if(isset($data['is_many'])){
            return $this->upload_many();
        }else{
            return $this->upload_one();
        }

    }

    // 富文本编辑器上传
    public function editor_upload($data = []){
        $this->data = $data;

        // 默认name
        $name = 'file';

        // 如果没有name默认file
        if(isset($data['name'])){
            $name = $data['name'];
        }

        $this->data['name'] = $name;

        // 如果没有文件则返回false
        if(!request()->hasFile($name)){
            $this->resp['msg'] = '无图片上传！';
            return $this->resp;
        }

        $config_model = new Config;
        $config_list = $config_model->get();
        $config_list_format = get_format_config($config_list);
        $this->data['config_list_format'] = $config_list_format;

        $ali_oss_config = $config_list_format['alioss'];
        $config_alioss_format = json_decode($ali_oss_config,true);
        $this->data['config_alioss_format'] = $config_alioss_format;
        
        
        // 判断是本地上传还是OSS
        if(isset($config_alioss_format['status']) && !empty($config_alioss_format['status'])){
            
            $this->OssClient = new \OSS\OssClient($config_alioss_format['access_key'], $config_alioss_format['secret_access'], $config_alioss_format['endpoint']);

            $returnPath = '/Uploads';
            $filepath = public_path($returnPath);
            

            $this->data['oss_real_path'] = $this->dir.date('Y_m_d');
            $this->data['real_path'] = $filepath;

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $this->data['real_path'] = $filepath;
                $this->data['oss_real_path'] = $this->dir.$data['filepath'].date('Y_m_d');
            }

        }else{
            $returnPath = '/Uploads/'.date('Y_m_d');

            // 文件存储路径
            $filepath = public_path($returnPath);

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $returnPath = '/Uploads/'.$data['filepath'].date('Y_m_d');
                $filepath = public_path($returnPath);
            }

            $this->data['oss_real_path'] = '';
            $this->data['real_path'] = $filepath;
            $this->data['return_path'] = $returnPath;

            
        }

        if (!file_exists($this->data['real_path'])) {
            mkdir($this->data['real_path'],  0777, true);
        }

        // 获取上传文件
        $files = request()->file($this->data['name']);//获取上传文件
        
        $this->resp['path'] = [];
        $files = request()->file($this->data['name']);
        
        foreach($files as $v){
            $ext = strtolower($v->getClientOriginalExtension()); // 获取后缀
            if(!in_array($ext, $this->allow)){
                $this->resp['msg'] = $ext.' - 不允许上传';
                return $this->resp;
            }

            // 文件名字
            $filename = time().mt_rand(1000,9999);

            // 判断是否又自定义名字
            if(isset($this->data['filename'])){
                $filename = $this->data['filename'];
            }

            // 保存文件全路径
            $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;

            $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;

            // 实例化扩展 使用gd
            $manager = new ImageManager(['driver' => 'gd']);

            $obj = $manager->make($v);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }
            $obj->save($real_path); // 保存文件

            if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                try {
                    $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                    unlink($real_path);
                    $this->resp['path'][] = $ossInfo['info']['url'];
                    $this->resp['status'] = true;
                } catch (OssException $e) {
                    $this->resp['msg'] = $e->getMessage();
                }
            }else{
                $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                $this->resp['status'] = true;
            }

        }

        return $this->resp;

    }

    // 广告上传
    public function adv_upload($data = []){
        $this->data = $data;

        // 默认name
        $name = 'file';

        // 如果没有name默认file
        if(isset($data['name'])){
            $name = $data['name'];
        }

        $this->data['name'] = $name;

        // 如果没有文件则返回false
        if(!request()->hasFile($name)){
            $this->resp['msg'] = '无图片上传！';
            return $this->resp;
        }

        $config_model = new Config;
        $config_list = $config_model->get();
        $config_list_format = get_format_config($config_list);
        $this->data['config_list_format'] = $config_list_format;

        $ali_oss_config = $config_list_format['alioss'];
        $config_alioss_format = json_decode($ali_oss_config,true);
        $this->data['config_alioss_format'] = $config_alioss_format;
        
        
        // 判断是本地上传还是OSS
        if(isset($config_alioss_format['status']) && !empty($config_alioss_format['status'])){
            
            $this->OssClient = new \OSS\OssClient($config_alioss_format['access_key'], $config_alioss_format['secret_access'], $config_alioss_format['endpoint']);

            $returnPath = '/Uploads';
            $filepath = public_path($returnPath);
            

            $this->data['oss_real_path'] = $this->dir.date('Y_m_d');
            $this->data['real_path'] = $filepath;

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $this->data['real_path'] = $filepath;
                $this->data['oss_real_path'] = $this->dir.$data['filepath'].date('Y_m_d');
            }

        }else{
            $returnPath = '/Uploads/'.date('Y_m_d');

            // 文件存储路径
            $filepath = public_path($returnPath);

            // 判断是否又自定义路径
            if(isset($this->data['filepath'])){
                $returnPath = '/Uploads/'.$data['filepath'].date('Y_m_d');
                $filepath = public_path($returnPath);
            }

            $this->data['oss_real_path'] = '';
            $this->data['real_path'] = $filepath;
            $this->data['return_path'] = $returnPath;

            
        }

        if (!file_exists($this->data['real_path'])) {
            mkdir($this->data['real_path'],  0777, true);
        }

        // 获取上传文件
        $files = request()->file($this->data['name']);//获取上传文件
        
        $this->resp['path'] = [];
        $files = request()->file($this->data['name']);

        // 判断是多文件上传还是单文件
        if(isset($data['is_many'])){
            foreach($files as $v){
                $ext = strtolower($v->getClientOriginalExtension()); // 获取后缀
                if(!in_array($ext, $this->allow)){
                    $this->resp['msg'] = $ext.' - 不允许上传';
                    return $this->resp;
                }
    
                // 文件名字
                $filename = time().mt_rand(1000,9999);
    
                // 判断是否又自定义名字
                if(isset($this->data['filename'])){
                    $filename = $this->data['filename'];
                }
    
                // 保存文件全路径
                $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;
    
                $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;
    
                // 实例化扩展 使用gd
                $manager = new ImageManager(['driver' => 'gd']);
    
                $obj = $manager->make($v);
    
                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }
                $obj->save($real_path); // 保存文件
    
                if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                    try {
                        $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                        unlink($real_path);
                        $this->resp['path'][] = $ossInfo['info']['url'];
                        $this->resp['status'] = true;
                    } catch (OssException $e) {
                        $this->resp['msg'] = $e->getMessage();
                    }
                }else{
                    $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                    $this->resp['status'] = true;
                }
    
            }
        }else{
            $ext = strtolower($files->getClientOriginalExtension()); // 获取后缀
            if(!in_array($ext, $this->allow)){
                $this->resp['msg'] = $ext.' - 不允许上传';
                return $this->resp;
            }

            // 文件名字
            $filename = time().mt_rand(1000,9999);

            // 判断是否又自定义名字
            if(isset($this->data['filename'])){
                $filename = $this->data['filename'];
            }

            // 保存文件全路径
            $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;

            $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;

            // 实例化扩展 使用gd
            $manager = new ImageManager(['driver' => 'gd']);

            $obj = $manager->make($files);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }
            $obj->save($real_path); // 保存文件

            if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                try {
                    $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                    unlink($real_path);
                    $this->resp['path'][] = $ossInfo['info']['url'];
                    $this->resp['status'] = true;
                } catch (OssException $e) {
                    $this->resp['msg'] = $e->getMessage();
                }
            }else{
                $this->resp['path'] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                $this->resp['status'] = true;
            }
        }
        
        

        return $this->resp;

    }

    // 单文件上传
    private function upload_one(){
        
        // 获取上传文件
        $files = request()->file($this->data['name']);//获取上传文件
        $ext = strtolower($files->getClientOriginalExtension()); // 获取后缀
        
        if(!in_array(strtolower($files->getClientOriginalExtension()), $this->allow)){
            $this->resp['msg'] = $files->getClientOriginalExtension().' - 不允许上传';
            return $this->resp;
        }

        // 文件名字
        $filename = time().mt_rand(1000,9999);

        // 判断是否又自定义名字
        if(isset($this->data['filename'])){
            $filename = $this->data['filename'];
        }

        // 保存文件全路径
        $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;
        $real_path_400 = $this->data['real_path'].'/'.$filename.'_400.'.$ext;
        $real_path_200 = $this->data['real_path'].'/'.$filename.'_200.'.$ext;

        $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;
        $oss_real_path_400 = $this->data['oss_real_path'].'/'.$filename.'_400.'.$ext;
        $oss_real_path_200 = $this->data['oss_real_path'].'/'.$filename.'_200.'.$ext;

        // 实例化扩展 使用gd
        $manager = new ImageManager(['driver' => 'gd']);

        $obj = $manager->make($files);

        //  判断是否缩略图
        if(isset($this->data['is_thumb'])){
        
            // 判断是否有自定义宽高
            if(isset($this->data['width']) && isset($this->data['height'])){
                $this->width = $this->data['width'];
                $this->height = $this->data['height'];
            }

            $width = $manager->make($files)->width();
            $height = $manager->make($files)->height();

            if($width > $this->width || $height > $this->height){
                $width = $this->width;
                $height = $this->height;
            }

            $obj = $obj->resize($width,$height,function($v){
                $v->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas($width,$height);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }

            $obj->save($real_path); // 保存文件

            if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                try {
                    $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                    unlink($real_path); // 删除本地图片
                    $this->resp['path'] = $ossInfo['info']['url'];
                    $this->resp['status'] = true;
                } catch (OssException $e) {
                    $this->resp['msg'] = $e->getMessage();
                }
            }else{
                $this->resp['path'] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;
                $this->resp['status'] = true;
            }

        }else{
            $width = $manager->make($files)->width();
            $height = $manager->make($files)->height();

            if($width > $this->width || $height > $this->height){
                $width = $this->width;
                $height = $this->height;
            }

            $obj = $obj->resize($width,$height,function($v){
                $v->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas($width,$height);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }

            $obj->save($real_path); // 保存文件

            // 存储400px 图片
            $obj = $obj->resize(400,400,function($v){
                $v->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas(400,400);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }

            $obj->save($real_path_400); // 保存文件

            // 存储200px 图片
            $obj = $obj->resize(200,200,function($v){
                $v->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas(200,200);

            // 是否加水印
            if(isset($this->data['is_water'])){
                $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
            }

            $obj->save($real_path_200); // 保存文件

            if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                try {
                    $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                    $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_400,'/'),$real_path_400);
                    $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_200,'/'),$real_path_200);
                    unlink($real_path);unlink($real_path_400);unlink($real_path_200); // 删除本地图片
                    $this->resp['path'] = $ossInfo['info']['url'];
                    $this->resp['status'] = true;
                } catch (OssException $e) {
                    $this->resp['msg'] = $e->getMessage();
                }
            }else{
                $this->resp['path'] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                $this->resp['status'] = true;
            }

        }
        return $this->resp;

    }

    // 多文件上传
    private function upload_many(){
        $this->resp['path'] = [];
        $files = request()->file($this->data['name']);
        
        foreach($files as $v){
            $ext = strtolower($v->getClientOriginalExtension()); // 获取后缀
            if(!in_array($ext, $this->allow)){
                $this->resp['msg'] = $ext.' - 不允许上传';
                return $this->resp;
            }

            // 文件名字
            $filename = time().mt_rand(1000,9999);

            // 判断是否又自定义名字
            if(isset($this->data['filename'])){
                $filename = $this->data['filename'];
            }

            // 保存文件全路径
            $real_path = $this->data['real_path'].'/'.$filename.'.'.$ext;
            $real_path_400 = $this->data['real_path'].'/'.$filename.'_400.'.$ext;
            $real_path_200 = $this->data['real_path'].'/'.$filename.'_200.'.$ext;

            $oss_real_path = $this->data['oss_real_path'].'/'.$filename.'.'.$ext;
            $oss_real_path_400 = $this->data['oss_real_path'].'/'.$filename.'_400.'.$ext;
            $oss_real_path_200 = $this->data['oss_real_path'].'/'.$filename.'_200.'.$ext;

            // 实例化扩展 使用gd
            $manager = new ImageManager(['driver' => 'gd']);

            $obj = $manager->make($v);

            //  判断是否缩略图
            if(isset($this->data['is_thumb'])){
            
                // 判断是否有自定义宽高
                if(isset($this->data['width']) && isset($this->data['height'])){
                    $this->width = $this->data['width'];
                    $this->height = $this->data['height'];
                }

                $width = $manager->make($v)->width();
                $height = $manager->make($v)->height();

                if($width > $this->width || $height > $this->height){
                    $width = $this->width;
                    $height = $this->height;
                }

                $obj = $obj->resize($width,$height,function($v){
                    $v->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas($width,$height);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path); // 保存文件

                if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                    try {
                        $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                        unlink($real_path);
                        $this->resp['path'][] = $ossInfo['info']['url'];
                        $this->resp['status'] = true;
                    } catch (OssException $e) {
                        $this->resp['msg'] = $e->getMessage();
                    }
                }else{
                    $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                    $this->resp['status'] = true;
                }

            }else{
                $width = $manager->make($v)->width();
                $height = $manager->make($v)->height();

                if($width > $this->width || $height > $this->height){
                    $width = $this->width;
                    $height = $this->height;
                }

                $obj = $obj->resize($width,$height,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas($width,$height);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path); // 保存文件

                // 存储400px 图片
                $obj = $obj->resize(400,400,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas(400,400);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path_400); // 保存文件

                // 存储200px 图片
                $obj = $obj->resize(200,200,function($vo){
                    $vo->aspectRatio();  // 这个应该是同比例缩减
                })->resizeCanvas(200,200);

                // 是否加水印
                if(isset($this->data['is_water'])){
                    $obj = $obj->insert($this->data['is_water'],'bottom-right', 10, 10);
                }

                $obj->save($real_path_200); // 保存文件

                if(isset($this->data['config_alioss_format']['status']) && !empty($this->data['config_alioss_format']['status'])){
                    try {
                        $ossInfo = $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path,'/'),$real_path);
                        $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_400,'/'),$real_path_400);
                        $this->OssClient->uploadFile($this->data['config_alioss_format']['bucket'],ltrim($oss_real_path_200,'/'),$real_path_200);
                        unlink($real_path);unlink($real_path_400);unlink($real_path_200); // 删除本地图片
                        $this->resp['path'][] = $ossInfo['info']['url'];
                        $this->resp['status'] = true;
                    } catch (OssException $e) {
                        $this->resp['msg'] = $e->getMessage();
                    }
                }else{
                    $this->resp['path'][] = $this->data['config_list_format']['web_url'].$this->data['return_path'].'/'.$filename.'.'.$ext;;
                    $this->resp['status'] = true;
                }

            }

        }
        return $this->resp;
    }

    // // 上传文件
    // public function uploads2($data = []){

    //     // 默认name
    //     $name = 'file';

    //     // 如果没有name默认file
    //     if(isset($data['name'])){
    //         $name = $data['name'];
    //     }

    //     // 如果没有文件则返回false
    //     if(!Input::hasFile($name)){
    //         $this->resp['msg'] = '无图片上传！';
    //         return $this->resp;
    //     }

    //     // 获取文件后缀
    //     $ext = Input::file($name)->getClientOriginalExtension();//获取后缀

    //     // 文件名字
    //     $filename = time().mt_rand(1000,9999);

    //     // 判断是否又自定义名字
    //     if(isset($data['filename'])){
    //         $filename = $data['filename'];
    //     }

    //     $returnPath = '/Uploads/'.date('Y_m_d');

    //     // 文件存储路径
    //     $filepath = public_path($returnPath);

    //     // 判断是否又自定义路径
    //     if(isset($data['filepath'])){
    //         $returnPath = '/Uploads/'.$data['filepath'];
    //         $filepath = public_path($returnPath);
    //     }

    //     if (!file_exists($filepath)) {
    //         mkdir($filepath,  0777, true);
    //     }

    //     // 保存文件全路径
    //     $file = $filepath.'/'.$filename.'.'.$ext;

    //     // 文件资源
    //     $fileres = Input::file($name);

    //     // 实例化扩展 使用gd
    //     $manager = new ImageManager(['driver' => 'gd']);

    //     // 获取他的mine类型
    //     $mime = $manager->make($fileres)->mime();

    //     // 自定义限制
    //     if(isset($data['allow'])){
    //         $this->allow = $data['allow'];
    //     }

    //     if(!in_array($mime,$this->allow)){
    //         $this->resp['msg'] = $mime.'，图片类型非法！';
    //         return $this->resp;
    //     }

    //     $obj = $manager->make($fileres);

    //     //  判断是否缩略图
    //     if(isset($data['is_thumb'])){
            
    //         // 判断是否有自定义宽高
    //         if(isset($data['width']) && isset($data['height'])){
    //             $this->width = $data['width'];
    //             $this->height = $data['height'];
    //         }

    //         $width = $manager->make($fileres)->width();
    //         $height = $manager->make($fileres)->height();

    //         if($width > $this->width || $height > $this->height){
    //             $width = $this->width;
    //             $height = $this->height;
    //         }

    //         $obj = $obj->resize($width,$height,function($v){
    //             $v->aspectRatio();  // 这个应该是同比例缩减
    //         })->resizeCanvas($width,$height);

    //         $is_thumb = true;
            
    //     }

    //     // 是否加水印
    //     if(isset($data['is_water'])){
    //         $obj = $obj->insert($this->water,'bottom-right', 10, 10);
    //     }

    //     // 是否留存原图片
    //     if(isset($data['is_source']) && isset($data['is_thumb'])){
    //         unset($data['is_thumb'],$data['is_source']);
    //         $source = $this->uploads($data);
    //          $this->data['path'] = $source['path'];
    //     }

    //     $obj->save($file);

    //     $realfile = $returnPath.'/'.$filename.'.'.$ext;

    //     // 判断是否使用OSS
    //     $setting_model = new Setting;
    //     $upload_setting = $setting_model->where(['name'=>'upload'])->first();
    //     if($upload_setting['val'] == 'local'){
    //         $appUrl = env("APP_URL");
    //     }else{
    //         $config_model = new Config;
    //         $config_alioss = $config_model->where(['is_type'=>'alioss'])->get();
    //         $config_alioss_format = $this->get_config_name($config_alioss);

    //         // Alioss   阿里云OSS上传
    //         $is_endpoint = empty($config_alioss_format['city'])?false:true;
    //         require_once base_path('vendor/aliyuncs/oss-sdk-php/autoload.php');
    //         $OssClient = new \OSS\OssClient($config_alioss_format['app_id'], $config_alioss_format['app_secret'], $config_alioss_format['alioss_endpoint'], $is_endpoint);
    //         try {
    //             $ossInfo = $OssClient->uploadFile($config_alioss_format['alioss_bucket'],ltrim($realfile,'/'),$file);
    //         } catch (OssException $e) {
    //             return $e->getMessage();
    //         }

    //         $appUrl = '';
    //         $realfile = $ossInfo['info']['url'];
            
    //     }

    //     if(isset($is_thumb)){
    //         $this->data['thumb_path'] = $appUrl.$realfile;
    //     }else{
    //          $this->data['path'] = $appUrl.$realfile;
    //     }

    //     $this->data['status'] = true;
    //     $this->data['msg'] = '上传成功！';

    //     return $this->data;

    // }


    // 头像
    public function avatar($data = []){
        $data['is_thumb'] = true;
        $data['width'] = 80;
        $data['height'] = 80;
        $uploadInfo = $this->uploads($data);
        
        return $uploadInfo['thumb_path'];
    }

 

}

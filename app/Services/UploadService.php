<?php
namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UploadService extends BaseService{

    protected $path = 'myfiles'; // 文件上传目录，默认地址
    protected $fileName = 'file'; // 上传的字段
    protected $fileAllow = ['ico','xls','xlsx']; // 文件允许上传
    protected $photoAllow = ['jpg','png','gif','jpeg']; // 图片允许上传

    // 非图片文件上传处理
    public function uploadFile($path='',$filename=''){
        
        if(!request()->hasFile($this->fileName)){
            return $this->format_error(__('upload.file_not_found'));
        }

        // 获取上传来的文件
        $file = request()->file($this->fileName);

        if(!$file->isValid()){ //无效文件
            return $this->format_error(__('upload.invalid_file'));
        }

        $ext = strtolower($file->getClientOriginalExtension()); // 后缀

        if(!in_array($ext,$this->fileAllow)){
            return $this->format_error(__('upload.not_allow').'['.implode(',',$this->fileAllow).']');
        }

        if(!empty($path)){
            $this->path = $path;
        }

        $configService = new ConfigService;
        try{
            $config = $configService->getFormatConfig('alioss');
        }catch(\Exception $e){
            return $this->format_error(__('upload.error_config_oss'));
        }

        $disk = 'public'; // 默认是本地
        
        if(!empty($config['status'])){
            $disk = 'alioss';
        }

        $this->path .= '/'.date('Y-m-d'); // 加上日期

        if(empty($filename)){
            $rs = Storage::disk($disk)->putFile($this->path, $file);
        }else{
            $rs = Storage::disk($disk)->putFileAs($this->path, $file ,$filename.'.'.$ext);
        }

        $rs = Storage::disk($disk)->url($rs);

        return $this->format($rs,__('upload.upload_success'));

    }


    // 图片上传
    public function uploadPhoto($path='',$opt=[]){

        if(!request()->hasFile($this->fileName)){
            return $this->format_error(__('upload.file_not_found'));
        }

        // 获取上传来的文件
        $file = request()->file($this->fileName);

        // 判断是单文件上传还是多文件上传
        if(!isset($opt['many'])){ // many 存在则是多文件，这里if下是单文件
            // 判断是否传错了 单文件上传选择多文件
            if(is_array($file)){
                return $this->format_error(__('upload.upload_type'));
            }
            try{
                $rs = $this->photoHandle($file,$path,$opt);
            }catch(Exception $e){
                return $this->format_error($e->getMessage());
            }
            
            return $this->format($rs,__('upload.upload_success'));
         
        }

        $fileList = []; // 多文件地址
        if(!is_array($file)){
            return $this->format_error(__('upload.upload_type'));
        }
        try{
            foreach($file as $v){
                $fileList[] = $this->photoHandle($v,$path,$opt);
            }
        }catch(Exception $e){
            return $this->format_error($e->getMessage());
        }
        return $this->format($fileList,__('upload.upload_success'));

    }

    /**
     * 编辑器图片上传
     *
     * @param integer $type 类型 
     * @author hg <www.qingwuit.com>
     */
    public function editer($type=0){

        // 单图片上传
        $path = 'editers';
        if($type==0){
            return $this->uploadPhoto($path);
        }

        // 多图片上传
        if($type==1){
            return $this->uploadPhoto($path,['many'=>1]);
        }

        // 文件上传
        if($type==1){
            return $this->uploadFile($path);
        }

    }

    /**
     * 用户头像上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function avatar($id=0){
        $path = 'avatars';
        $opt = [
            'width'=>140,
            'height'=>140,
        ]; // 配置文件
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 商家Logo上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function store_logo($id=0){
        $path = 'store_logo';
        $opt = [
            'width'=>140,
            'height'=>140,
        ]; // 配置文件
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }
    /**
     * 商家幻灯片门面上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function store_slide($id=0){
        $path = 'store_slide';
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path);
    }

    /**
     * 配置中心图片上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function config_logo($id=0){
        $path = 'configs';
        $opt = [
            'width'=>600,
            'height'=>600,
        ]; // 配置文件
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 配置中心icon上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function config_icon($id=0){
        $path = 'configs';
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadFile($path);
    }

    /**
     * 商品分类缩略图上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function goods_class($id=0){
        $path = 'goods_class';
        $opt = [
            'width'=>200,
            'height'=>200,
        ]; // 配置文件
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 商品品牌缩略图上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function goods_brand($id=0){
        $path = 'goods_brand';
        $opt = [
            'width'=>200,
            'height'=>200,
        ]; // 配置文件
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 广告图上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function adv($id=0){
        $path = 'adv';
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path);
    }

    /**
     * 在线聊天图片商城
     *
     * @param integer $store_id  商家ID
     * @author hg <www.qingwuit.com>
     */
    public function chat($store_id=0){
        $path = 'chat';
        $opt = [
            'width'=>800,
            'height'=>800,
            'thumb'=>[
                [150,150],
            ],
        ]; // 配置文件
        if(!empty($store_id)){
            $path = $path.'/'.$store_id;
        }
        
        return $this->uploadPhoto($path,$opt);
    }
    
    /**
     * 商品图片上传
     *
     * @param integer $store_id  商家ID
     * @author hg <www.qingwuit.com>
     */
    public function goods($store_id=0){
        $path = 'goods';
        $opt = [
            'width'=>800,
            'height'=>800,
            'thumb'=>[
                [400,400],
                [300,300],
                [150,150],
            ],
        ]; // 配置文件
        if(!empty($store_id)){
            $path = $path.'/'.$store_id;
        }
        
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 积分产品图片上传
     *
     * @param integer $store_id  商家ID
     * @author hg <www.qingwuit.com>
     */
    public function integral($store_id=0){
        $path = 'integrals';
        $opt = [
            'width'=>800,
            'height'=>800,
            'thumb'=>[
                [400,400],
                [300,300],
                [150,150],
            ],
        ]; // 配置文件
        if(empty($store_id)){
            $path = $path.'/'.$store_id;
        }
        return $this->uploadPhoto($path,$opt);
    }

    /**
     * 商家入驻 图片上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function store_join($id=0){
        $path = 'store_join';
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path);
    }

    /**
     * 用户认证 图片上传
     *
     * @param integer $id 用户ID
     * @author hg <www.qingwuit.com>
     */
    public function user_check($id=0){
        $path = 'user_check';
        if(!empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path);
    }

    /**
     * 评论图片
     *
     * @param integer $id  用户ID
     * @author hg <www.qingwuit.com>
     */
    public function comment($id=0){
        $path = 'comments';
        $opt = [
            'width'=>800,
            'height'=>800,
            'thumb'=>[
                [150,150],
            ],
        ]; // 配置文件
        if(empty($id)){
            $path = $path.'/'.$id;
        }
        return $this->uploadPhoto($path,$opt);
    }


    // 图片处理
    protected function photoHandle($file,$path,$opt){

        if(!$file->isValid()){ //无效文件
            throw new Exception(__('upload.invalid_file'));
        }
        
        $ext = strtolower($file->getClientOriginalExtension()); // 后缀

        if(!in_array($ext,$this->photoAllow)){
            throw new Exception(__('upload.not_allow').'['.implode(',',$this->photoAllow).']');
        }

        if(!empty($path)){
            $this->path = $path;
        }

        $configService = new ConfigService;
        try{
            $config = $configService->getFormatConfig('alioss');
        }catch(\Exception $e){
            throw new Exception(__('upload.error_config_oss'));
        }

        $disk = 'public'; // 默认是本地
        
        if(!empty($config['status'])){
            $disk = 'alioss';
        }

        $this->path .= '/'.date('Y-m-d'); // 加上日期

        // 实例化扩展 使用gd  imagick
        $manager = new ImageManager(['driver' => 'gd']);
        $obj = $manager->make($file);

        // 如果有配置宽高
        if(isset($opt['width']) && isset($opt['height'])){
            $width = $obj->width();
            $height = $obj->height();
            if($width > $opt['width'] || $height > $opt['height']){ // 如果图片大于则进行裁剪
                $width = $opt['height'];
                $height = $opt['height'];
            }
            $obj = $obj->resize($width,$height,function($item){
                $item->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas($width,$height);
        }

        $random = Str::random(40);
        $tempfilepath = 'app/public/tempfile';
        if (!file_exists(storage_path($tempfilepath))) {
            mkdir(storage_path($tempfilepath),  0644, true);
        }
        $tempfile = storage_path($tempfilepath).'/'.$random.'.'.$ext;
        $obj->save($tempfile);
        $obj->destroy();

        if(!isset($opt['filename'])){
            
            $rs = Storage::disk($disk)->putFileAs($this->path, new File($tempfile),$random.'.'.$ext);
            // Log::channel('qwlog')->debug($rs);
            // 如果有缩略图
            if(isset($opt['thumb']) && !empty($opt['thumb'])){
                foreach($opt['thumb'] as $items){
                    $obj = $manager->make($file);
                    $obj = $obj->resize($items[0],$items[1],function($item){
                        $item->aspectRatio();  // 这个应该是同比例缩减
                    })->resizeCanvas($items[0],$items[1]);

                    $tempfile2 = storage_path($tempfilepath).'/'.$random.'_'.$items[0].'.'.$ext;
                    $obj->save($tempfile2);
                    $obj->destroy();

                    Storage::disk($disk)->putFileAs($this->path, new File($tempfile2),$random.'_'.$items[0].'.'.$ext);
                    unlink($tempfile2);
                }
            }
            unlink($tempfile);
        }else{
            $rs = Storage::disk($disk)->putFileAs($this->path, new File($tempfile),$opt['filename'].'.'.$ext);

            // 如果有缩略图
            if(isset($opt['thumb']) && !empty($opt['thumb'])){
                foreach($opt['thumb'] as $items){
                    $obj = $manager->make($file);
                    $obj = $obj->resize($items[0],$items[1],function($item){
                        $item->aspectRatio();  // 这个应该是同比例缩减
                    })->resizeCanvas($items[0],$items[1]);

                    $tempfile2 = storage_path($tempfilepath).'/'.$opt['filename'].'_'.$items[0].'.'.$ext;
                    $obj->save($tempfile2);
                    $obj->destroy();

                    Storage::disk($disk)->putFileAs($this->path, new File($tempfile2),$opt['filename'].'_'.$items[0].'.'.$ext);
                    
                    unlink($tempfile2);
                }
            }
            unlink($tempfile);
        }
        
        $rs = Storage::disk($disk)->url($rs);

        return $rs;
    }


}
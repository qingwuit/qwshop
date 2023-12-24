<?php

namespace App\Qingwuit\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Illuminate\Http\File;

/**
 * UploadsService class
 *
 * @Description 文件上传类
 * @Author hg <bishashiwo@gmail.com>
 * @Time 2021-11-25
 */
class UploadsService extends BaseService
{
    protected $path = 'myfiles'; // 文件上传目录，默认地址
    protected $fileName = 'file'; // 上传的字段
    protected $fileAllow = ['ico', 'crt', 'pem', 'xls', 'xlsx', 'png', 'apk', 'wgt']; // 文件允许上传
    protected $photoAllow = ['jpg', 'png', 'gif', 'jpeg']; // 图片允许上传

    // 上传图片
    public function uploadPic($path = '', $opt = [])
    {
        if (!request()->hasFile($this->fileName)) {
            return $this->formatError(__('tip.upload.fileNotFound'));
        }

        // 获取上传来的文件
        $file = request()->file($this->fileName);

        // 判断是单文件上传还是多文件上传
        if (!isset($opt['many'])) { // many 存在则是多文件，这里if下是单文件
            // 判断是否传错了 单文件上传选择多文件
            if (is_array($file)) {
                return $this->formatError(__('tip.upload.uploadThr'));
            }
            try {
                $rs = $this->photoHandle($file, $path, $opt);
            } catch (\Exception $e) {
                return $this->formatError($e->getMessage());
            }

            return $this->format($rs, __('tip.success'));
        }

        $fileList = []; // 多文件地址
        if (!is_array($file)) {
            return $this->formatError(__('tip.upload.uploadThr'));
        }
        try {
            foreach ($file as $v) {
                $fileList[] = $this->photoHandle($v, $path, $opt);
            }
        } catch (\Exception $e) {
            return $this->formatError($e->getMessage());
        }
        return $this->format($fileList, __('tip.success'));
    }

    // 上传文件
    public function uploadFile($path = '', $filename = '')
    {
        if (!request()->hasFile($this->fileName)) {
            return $this->formatError(__('tip.upload.fileNotFound'));
        }

        // 获取上传来的文件
        $file = request()->file($this->fileName);

        if (!$file->isValid()) { //无效文件
            return $this->formatError(__('tip.upload.invalidFile'));
        }

        $ext = strtolower($file->getClientOriginalExtension()); // 后缀

        if (!in_array($ext, $this->fileAllow)) {
            return $this->formatError(__('tip.upload.notAllow') . '[' . $ext . ']');
        }

        if (!empty($path)) {
            $this->path = $path;
        }

        $configService = $this->getService('Configs');

        try {
            $config = $configService->getFormatConfig('upload');
        } catch (\Exception $e) {
            return $this->formatError(__('configThr'));
        }

        $disk = 'public'; // 默认是本地

        if (!empty($config['save_type'])) {
            $disk = 'alioss';
        }

        $this->path .= '/' . date('Y-m-d'); // 加上日期

        if (empty($filename)) {
            $rs = Storage::disk($disk)->putFileAs($this->path, $file, time() . mt_rand(1000, 9999) . '.' . $ext);
        } else {
            $rs = Storage::disk($disk)->putFileAs($this->path, $file, $filename . '.' . $ext);
        }

        if ($disk != 'public') {
            $rs = Storage::disk($disk)->url($rs);
        } else {
            $rs = '/storage/' . $rs;
        }

        return $this->format($rs, __('tip.success'));
    }


    // 图片处理
    public function photoHandle($file, $path, $opt)
    {
        if (!$file->isValid()) { //无效文件
            throw new \Exception(__('tip.upload.invalidFile'));
        }

        $ext = strtolower($file->getClientOriginalExtension()); // 后缀

        if (!in_array($ext, $this->photoAllow)) {
            throw new \Exception(__('tip.upload.notAllow') . '[' . implode(',', $this->photoAllow) . ']');
        }

        if (!empty($path)) {
            $this->path = $path;
        }

        $configService = $this->getService('Configs');
        try {
            $config = $configService->getFormatConfig('upload');
        } catch (\Exception $e) {
            throw new \Exception(__('tip.configThr'));
        }

        $disk = 'public'; // 默认是本地

        if (!empty($config['save_type'])) {
            $disk = 'alioss';
        }

        $this->path .= '/' . date('Y-m-d'); // 加上日期

        // 实例化扩展 使用gd  imagick
        $manager = new ImageManager(['driver' => 'gd']);
        $obj = $manager->make($file);

        // 如果有配置宽高
        if (isset($opt['width']) && isset($opt['height'])) {
            $width = $obj->width();
            $height = $obj->height();
            if ($width > $opt['width'] || $height > $opt['height']) { // 如果图片大于则进行裁剪
                $width = $opt['height'];
                $height = $opt['height'];
            }
            $obj = $obj->resize($width, $height, function ($item) {
                $item->aspectRatio();  // 这个应该是同比例缩减
            })->resizeCanvas($width, $height);
        }

        $random = Str::random(40);
        $tempfilepath = 'app/public/tempfile';
        if (!file_exists(storage_path($tempfilepath))) {
            mkdir(storage_path($tempfilepath), 0775, true);
        }
        $tempfile = storage_path($tempfilepath) . '/' . $random . '.' . $ext;
        $obj->save($tempfile);
        $obj->destroy();

        if (!isset($opt['filename'])) {
            $rs = Storage::disk($disk)->putFileAs($this->path, new File($tempfile), $random . '.' . $ext);
            // Log::channel('qwlog')->debug($rs);
            // 如果有缩略图
            if (isset($opt['thumb']) && !empty($opt['thumb'])) {
                foreach ($opt['thumb'] as $items) {
                    $obj = $manager->make($file);
                    $obj = $obj->resize($items[0], $items[1], function ($item) {
                        $item->aspectRatio();  // 这个应该是同比例缩减
                    })->resizeCanvas($items[0], $items[1]);

                    $tempfile2 = storage_path($tempfilepath) . '/' . $random . '_' . $items[0] . '.' . $ext;
                    $obj->save($tempfile2);
                    $obj->destroy();

                    Storage::disk($disk)->putFileAs($this->path, new File($tempfile2), $random . '_' . $items[0] . '.' . $ext);
                    unlink($tempfile2);
                }
            }
            unlink($tempfile);
        } else {
            $rs = Storage::disk($disk)->putFileAs($this->path, new File($tempfile), $opt['filename'] . '.' . $ext);

            // 如果有缩略图
            if (isset($opt['thumb']) && !empty($opt['thumb'])) {
                foreach ($opt['thumb'] as $items) {
                    $obj = $manager->make($file);
                    $obj = $obj->resize($items[0], $items[1], function ($item) {
                        $item->aspectRatio();  // 这个应该是同比例缩减
                    })->resizeCanvas($items[0], $items[1]);

                    $tempfile2 = storage_path($tempfilepath) . '/' . $opt['filename'] . '_' . $items[0] . '.' . $ext;
                    $obj->save($tempfile2);
                    $obj->destroy();

                    Storage::disk($disk)->putFileAs($this->path, new File($tempfile2), $opt['filename'] . '_' . $items[0] . '.' . $ext);

                    unlink($tempfile2);
                }
            }
            unlink($tempfile);
        }

        if ($disk != 'public') {
            $rs = Storage::disk($disk)->url($rs);
        } else {
            $rs = '/storage/' . $rs;
        }

        if (stripos($this->path, 'goods') == false) return $rs;

        // 文件空间处理
        try {
            $oldFileName = $file->getClientOriginalName();
            $oldFileName = explode('.', $oldFileName)[0];
            $fileSpaceData = [
                'belong_id' =>  $this->getService('Store')->getStoreId()['data'] ?? 0,
                'name'      =>  $oldFileName,
                'new_name'  =>  $random,
                'ext_name'  =>  $ext,
                'disk_name' =>  $disk,
                'md5'       =>  md5_file(public_path($rs)) ?? '-', // oss的还要做判断
                'url'       =>  $rs,
                'dir_id'    =>  request('dir_id', 0),
                'status'    =>  1,
            ];
            $this->getService('FileSpace', true)->create($fileSpaceData);
        } catch (\Exception $e) {
        }

        return $rs;
    }
}

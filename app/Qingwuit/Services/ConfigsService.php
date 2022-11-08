<?php

namespace App\Qingwuit\Services;

class ConfigsService extends BaseService
{
    public function getFormatConfig($name = null, $manyName = null, $many = false)
    {
        $nameType = is_array($name);
        if ($nameType) {
            $info = $this->getService('Config', true)->whereIn('name', $name)->get();
        } else {
            $info = $this->getService('Config', true)->where('name', $name)->first();
        }

        if (!$info) {
            throw new \Exception(__('tip.configThr'));
            return;
        }

        if (!$many) {
            if ($nameType) {
                $data = [];
                foreach ($info as $v) {
                    if ($v['is_type'] == 0) $data[$v['name']] = $v['content'];
                    if ($v['is_type'] == 1) $data[$v['name']] = json_decode($v['content'], true);
                }
            } else {
                if ($info['is_type'] == 0) $data = $info['content'];
                if ($info['is_type'] == 1) $data = json_decode($info['content'], true);
            }
        } else {
            $data = [];
            $jsonData = json_decode($info['content'], true);
            $data = $jsonData[$manyName] ?? [];
        }

        return $data;
    }

    public function editConfig($name = null, $manyName = null, $many = false)
    {
        if (empty($name)) {
            $resp = request()->all();
        } else {
            $resp = request()->only($name);
        }

        if ($many && !empty($many)) {
            unset($resp['many_name']);
            unset($resp['many']);
            try {
                foreach ($resp as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $key => $vo) {
                            if ($vo == 'true') $v[$key] = true;
                            if ($vo == 'false') $v[$key] = false;
                            if ($vo == 'null') $v[$key] = '';
                        }
                    }
                    $info = $this->getService('Config', true)->where('name', $k)->first();
                    $jsonData = json_decode($info['content'], true);
                    $jsonData[$manyName] = $v;
                    $this->getService('Config', true)->where('name', $k)->update(['content' => json_encode($jsonData)]);
                }
            } catch (\Exception $e) {
                return $this->formatError($e->getMessage());
            }
        } else {
            try {
                foreach ($resp as $k => $v) {
                    if (is_array($v)) {
                        foreach ($v as $key => $vo) {
                            if ($vo == 'true') $v[$key] = true;
                            if ($vo == 'false') $v[$key] = false;
                            if ($vo == 'null') $v[$key] = '';
                        }
                    }
                    $this->getService('Config', true)->where('name', $k)->update(['content' => is_array($v) ? json_encode($v) : $v]);
                }
            } catch (\Exception $e) {
                return $this->formatError($e->getMessage());
            }
        }


        return $this->format($resp);
    }

    // 获取键值config
    public function getKeyVal()
    {
        $data = [];
        $info = $this->getService('Config', true)->get();
        if (!$info) return $this->format([]);
        foreach ($info as $v) {
            $data[$v['name']] = $v['content'];
        }
        return $this->format($data);
    }
}

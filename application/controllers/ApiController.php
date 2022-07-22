<?php

namespace application\controllers;

use Exception;

use function PHPSTORM_META\type;

class ApiController extends Controller
{
    public function categoryList()
    {
        return $this->model->getCategoryList();
    }

    public function productInsert()
    {
        $json = getJson();
        return [_RESULT => $this->model->productInsert($json)];
    }

    public function productList2()
    {
        $result = $this->model->productList2();
        return $result === false ? [] : $result;
    }

    public function ProductDel()
    {
        $productId = getJson();
        try {
            $param = [
                'product_id' => $productId,
            ];
            $this->model->beginTransaction();
            $this->model->ProductImgDel($param);
            $result = $this->model->ProductDel($param);
            if ($result === 1) {
                //이미지 삭제
                rmdirAll(_IMG_PATH . '/' . $productId);
                $this->model->commit();
            } else {
                $this->model->rollback();
            }
        } catch (Exception $e) {
            $this->model->rollback();
        }

        return [_RESULT => 1];
    }

    public function upload()
    {
        $urlPaths = getUrlPaths();
        if (!isset($urlPaths[2]) || !isset($urlPaths[3])) {
            exit();
        }
        $productId = intval($urlPaths[2]);
        $type = intval($urlPaths[3]);
        $dirPath = _IMG_PATH . '/' . $productId . '/' . $type;

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }
        $json = getJson();
        foreach ($json as $item) {
            $image_parts = explode(';base64,', $item['image']);
            $image_type_aux = explode('image/', $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $filePath = $dirPath . '/' . uniqid() . '.' . $image_type;
            file_put_contents($filePath, $image_base64);

            $param = [
                'product_id' => $productId,
                'type' => $type,
                'path' => $filePath,
            ];
            $result = $this->model->productImageInsert($param);
        }
        return [_RESULT => $result];

        //$file = _IMG_PATH . "/" . $productId . "/" . $type . "/" . uniqid() . "." . $image_type;
        //$file = "static/" . uniqid() . "." . $image_type;
    }

    public function productImageList()
    {
        $urlPaths = getUrlPaths();
        if (!isset($urlPaths[2])) {
            exit();
        }

        $productId = intval($urlPaths[2]);

        $param = [
            'product_id' => $productId,
        ];
        $result = $this->model->productImageList($param);

        return $result;
    }
    public function ProductImgDel()
    {
        $urlPaths = getUrlPaths();
        if (!isset($urlPaths[2]) || !isset($urlPaths[3])) {
            exit();
        }

        $productId = intval($urlPaths[2]);
        $type = intval($urlPaths[3]);
        for ($i = 1; $i <= $type; $i++) {
            $dirPath = _IMG_PATH . '/' . $productId . '/' . $i;

            $dir = opendir($dirPath);
            while ($itemName = readdir($dir)) {
                unlink($dirPath . '/' . $itemName);
            }
            closedir($dir);
        }

        $param = [
            'product_id' => $productId,
        ];
        $this->model->ProductImgDel($param);
    }
    public function productUpdate()
    {
        $json = getJson();
        print_r($json);
        return [_RESULT => $this->model->productUpdate($json)];
    }
}

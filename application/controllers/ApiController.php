<?php
namespace application\controllers;

class ApiController extends Controller {
    public function categoryList() {
        return $this->model->getCategoryList();
    }

    public function productInsert() {
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
        $json = getJson();
        print_r($json);
        $this->model->ProductImgDel($json);
        $result = $this->model->ProductDel($json);
        $dirPath = _IMG_PATH . "/" . $json;
        for ($i=1; $i < 4 ; $i++) { 
            $dir = opendir($dirPath . '/' . $i);
            while ($itemName = readdir($dir)) {
            unlink($dirPath . '/' . $i . '/' . $itemName);
            }
            closedir($dir);
            rmdir($dirPath . '/' . $i);
            rmdir($dirPath);
        }

        return [_RESULT => $result];
    }
    public function upload() {
        $urlPaths = getUrlPaths();
        if(!isset($urlPaths[2]) || !isset($urlPaths[3])) {
            exit();
        }
        $productId = intval($urlPaths[2]);
        $type = intval($urlPaths[3]);
        $json = getJson();
        $image_parts = explode(";base64,", $json["image"]);
        $image_type_aux = explode("image/", $image_parts[0]);      
        $image_type = $image_type_aux[1];      
        $image_base64 = base64_decode($image_parts[1]);
        $dirPath = _IMG_PATH . "/" . $productId . "/" . $type;
        $filePath = $dirPath . "/" . uniqid() . "." . $image_type;
        if(!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        } else {
              $dir = opendir($dirPath);
                while ($itemName = readdir($dir)) {
                unlink($dirPath. '/' .$itemName);
                }
            closedir($dir);
        $this->model->ProductImgDel($productId);
        }
        
        //$file = _IMG_PATH . "/" . $productId . "/" . $type . "/" . uniqid() . "." . $image_type;
        //$file = "static/" . uniqid() . "." . $image_type;
        $result = file_put_contents($filePath, $image_base64); 

        $param = [
            'product_id' => $productId,
            'type' => $type,
            'path' => $filePath,
        ];
        $val = $this->model->productImageInsert($param);

        return [_RESULT => $val];
    }

    public function productImageList() {
        $urlPaths = getUrlPaths();
        if(!isset($urlPaths[2])){
            exit();
        }

        $productId = intval($urlPaths[2]);

        $param = [
            'product_id' => $productId,
        ];
        $result = $this->model->productImageList($param);

        return $result;

    }
} 
<?php

namespace application\models;

use PDO;

class ApiModel extends Model
{
  public function getCategoryList()
  {
    $sql = "SELECT * FROM t_category";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function productInsert(&$param)
  {
    $sql = "INSERT INTO t_product
                SET product_name = :product_name
                  , product_price = :product_price
                  , delivery_price = :delivery_price
                  , add_delivery_price = :add_delivery_price
                  , tags = :tags
                  , outbound_days = :outbound_days
                  , seller_id = :seller_id
                  , category_id = :category_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":product_name", $param["product_name"]);
    $stmt->bindValue(":product_price", $param["product_price"]);
    $stmt->bindValue(":delivery_price", $param["delivery_price"]);
    $stmt->bindValue(":add_delivery_price", $param["add_delivery_price"]);
    $stmt->bindValue(":tags", $param["tags"]);
    $stmt->bindValue(":outbound_days", $param["outbound_days"]);
    $stmt->bindValue(":seller_id", $param["seller_id"]);
    $stmt->bindValue(":category_id", $param["category_id"]);
    $stmt->execute();
    return intval($this->pdo->lastInsertId());
  }
  public function productList2()
  {
    $sql = "SELECT t3.*, t4.path FROM (
                    SELECT t1.*, t2.cate1, t2.cate2, t2.cate3
                    FROM t_product t1
                    INNER JOIN t_category t2
                    ON t1.category_id = t2.id
                ) t3
                LEFT JOIN (
                    SELECT * FROM t_product_img where TYPE=1 GROUP BY id
                ) t4
                ON t3.id = t4.product_id
                GROUP BY t3.id
			    ORDER BY t4.id desc";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
  }
  public function ProductDel(&$param)
  {
    $sql = "DELETE FROM t_product WHERE id=:id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":id", $param['product_id']);
    $stmt->execute();

    return $stmt->rowCount();
  }
  public function ProductImgDel(&$param)
  {
    $sql = "DELETE FROM t_product_img WHERE product_id=:product_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":product_id", $param['product_id']);
    $stmt->execute();

    return $stmt->rowCount();
  }

  public function productDetail(&$param)
  {
    $sql = "SELECT t3.*, t4.path FROM (
                    SELECT t1.*, t2.cate1, t2.cate2, t2.cate3
                    FROM t_product t1
                    INNER JOIN t_category t2
                    ON t1.category_id = t2.id
                ) t3
                LEFT JOIN (
                    SELECT * FROM t_product_img where TYPE=1 GROUP BY id
                ) t4
                ON t3.id = t4.product_id
                GROUP BY t3.id
			    ORDER BY t4.id desc";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":product_id", $param["product_id"]);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
  }


  public function productImageInsert(&$param)
  {
    $sql = "INSERT INTO t_product_img
              SET product_id = :product_id
              , type = :type
              , path = :path
            ";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":product_id", $param["product_id"]);
    $stmt->bindValue(":type", $param["type"]);
    $stmt->bindValue(":path", $param["path"]);
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function productUpdate(&$param)
  {
    $sql = "UPDATE t_product
            SET product_name = :product_name
              , product_price = :product_price
              , delivery_price = :delivery_price
              , add_delivery_price = :add_delivery_price
              , tags = :tags
              , outbound_days = :outbound_days
              , seller_id = :seller_id
              , category_id = :category_id
            WHERE id = :product_id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindValue(":product_name", $param["product_name"]);
    $stmt->bindValue(":product_price", $param["product_price"]);
    $stmt->bindValue(":delivery_price", $param["delivery_price"]);
    $stmt->bindValue(":add_delivery_price", $param["add_delivery_price"]);
    $stmt->bindValue(":tags", $param["tags"]);
    $stmt->bindValue(":outbound_days", $param["outbound_days"]);
    $stmt->bindValue(":seller_id", $param["seller_id"]);
    $stmt->bindValue(":category_id", $param["category_id"]);
    $stmt->bindValue(":product_id", $param["id"]);
    $stmt->execute();
    return intval($this->pdo->lastInsertId());
  }
}

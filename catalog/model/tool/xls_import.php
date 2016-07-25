<?php
class ModelToolXLSImport extends Model {
//get store default language. used later for product insertion.
  protected function getDefaultLanguageId() {
    $code = $this->config->get('config_language');
    $sql = "SELECT language_id FROM " . DB_PREFIX . "language WHERE code = '$code'";
    $result = $this->db->query($sql);
    $languageId = 1;
    if ($result->rows) {
      foreach ($result->rows as $row) {
        $languageId = $row['language_id'];
        break;
      }
    }
    return $languageId;
  }
  protected function getCategoryId($productCat) {
    $SQL = "SELECT category_id FROM " . DB_PREFIX . "category_description WHERE name LIKE '" . $this->db->escape($productCat) . "'";
    $result = $this->db->query($SQL);
    $CatId = -1;
    if ($result->rows) {
      foreach ($result->rows as $row) {
        $CatId = $row['category_id'];
        break;
      }
    }
    return $CatId;
  }
  protected function getProductId($pr_name,$pr_issue_num) {
    $SQL = "SELECT * FROM " . DB_PREFIX . "product WHERE model LIKE '" . $this->db->escape($pr_name) . "' AND issue_number = '". $pr_issue_num."'";
    $result = $this->db->query($SQL);
    if($result->num_rows){
      return $result->row['product_id'];
    }
    else{
      return 0;
    }
    
  }

  protected function testDuplicate($model) {
    $SQL = "SELECT product_id FROM " . DB_PREFIX . "product WHERE model LIKE '" . $this->db->escape($model) . "'";
    $result = $this->db->query($SQL);
    if ($result->rows) return true;
    return false;
  }
  
  function checkUrl($value,$param) {

    $SQL = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($value) . "'";
    //echo '<br>'.$SQL;
    $result = $this->db->query($SQL);
    if($result->num_rows){
        $param++;
        
        if(strpos($value,"_") != false){
          list($manual_val,$num) = explode("_",$value);
          
        }
        else
          $manual_val = $value;
      
      $value = $manual_val.'_'.$param;
      return $this->checkUrl($value,$param);
    }
    else{
      return $value;
    }
    
    
    
  }

  function insertProduct($product) {
    //add logging feature
    global $log;
   //print '<pre>';
   //print_r($product);
   //exit;
    
    
    // Get default values
    $LangId = $this->getDefaultLanguageId();
    $StoreId = $this->config->get('config_store_id');

    //$ProdCatId = $this->getCategoryId($product['category']);

    // prevent duplicate entries
    //if($this->testDuplicate($product['article'])){
    //  $log->write("Product model: " . $product['article'] . " already EXIST !" );
    //  //return false;
    //}

    // If products category does exist then skip this product
    //if($ProdCatId == -1){
    //  $log->write("Category <" . $product['category'] . "> for product <". $product['article']."> NOT FOUND." );
    //  //return false;
    //}

//NOTE: All new inserted products will be with default stock status. If you want to override this
//Go to System->Localisations->Stock Statuses and modify default stock status according your needs.
    $StockStatusId = $this->config->get('config_stock_status_id');


//Generate main image filename for product <article>.jpg, replacing all restricted chars by "_"
    $MIFN = str_replace(array('\\', '/', ':', '*', '?', '"', '<', '>', '|', '.', ' '), '_', $product['name']) . '.jpg';
   // copy(DIR_DOWNLOAD . $product['image'], DIR_IMAGE . 'data/' . $MIFN);

    //We use transaction to be able undo the changes if something goes wrong
    $this->db->query('START TRANSACTION');
    

    //Inserts into table product article, price, main image...
    $SQL = "INSERT INTO " . DB_PREFIX . "product (model,sku,status,shipping,price,quantity,stock_status_id,date_added,date_available,image,image1, issue_number,grade,publisher,certification_number,adult,new_release,feature,customer_id) VALUES ('";
    $SQL .= $this->db->escape($product['name']) . "','".$this->db->escape($product['sku']) . "',1,0," . (float) $product['price'] . ",";
    $SQL .= (int)$product['quantity'] . ", " . (int)$StockStatusId . ", NOW(),NOW(), 'catalog/" . $product['image'] . "', 'catalog/" . $product['image1'] . "', '" . $product['issue_number'] . "', " . $product['grade'] . ", '" . $product['publisher'] . "', '" . $product['certification_number'] . "', " . $product['adult'] . ", " . $product['new_release'] . ", ". $product['feature'] .", 1)";
    
    //echo $SQL;exit;
    $objQuery1 = $this->db->query($SQL);

    
    //Get product ID
    $LastId = $this->db->getLastId();

    //Insert product's name and description
    $SQL = "INSERT INTO " . DB_PREFIX . "product_description (product_id,language_id,description,name,meta_title) VALUES(";
    $SQL .= (int) $LastId . "," . (int) $LangId . ",'" . $this->db->escape($product['description']) . "','" . $this->db->escape($product['name']) . "','" . $this->db->escape($product['meta_tag']) . "')";
    $objQuery2 = $this->db->query($SQL);
    

    
  //Inserts relative products if they are present
    
    if($product['related_products']!=""){
      $related_products = explode(";",$product['related_products']);
      //print_r($related_products);
      foreach($related_products as $each_product)
      {
        list($pr_name,$pr_issue_num) = explode("-",$each_product);
        
        $val = $this->getProductId($pr_name,$pr_issue_num);
        if($val){
          
          // Insert In related Table
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$LastId . "' AND related_id = '" . (int)$val . "'");
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$LastId . "', related_id = '" . (int)$val . "'");
          $this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$val . "' AND related_id = '" . (int)$LastId . "'");
          $this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$val . "', related_id = '" . (int)$LastId . "'");
        }
      }
    }
    
    
  //Inserts Seo Friendly url if they are present
  if($product['seo_key']!=""){
    $product['seo_key'] = trim($product['seo_key']);
    $product['seo_key'] = preg_replace('/\s+/', '-', $product['seo_key']);
    $url = $this->checkUrl($product['seo_key'],0);
    //echo '<br>======'.$url;    exit;
   
    $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$LastId . "', keyword = '" . $this->db->escape($url) . "'");
  }
    
    
    
//exit;


//if(($objQuery1) and ($objQuery2)and ($objQuery3)and ($objQuery4)){
if(($objQuery1) and ($objQuery2)){
    $this->db->query("COMMIT");
    return true;
}else{
    $this->db->query("ROLLBACK");
    $log->write("Something wrong with product data" . $product['article']);
    return false;
}

  }
  function prepare($filename) {
    $outFile = 'export.zip';
    $allowed = array('zip');
//move the uploaded file to download folder;
    move_uploaded_file($filename, DIR_DOWNLOAD . $outFile);
//check file extension
    $extension = pathinfo($outFile, PATHINFO_EXTENSION);
    if (!in_array(strtolower($extension), $allowed)) {
      return FALSE;
    }
    else {
      
      //uzip file
      $zip = new ZipArchive();
      $x = $zip->open(DIR_DOWNLOAD . $outFile);
      if ($x === TRUE) {
        $zip->extractTo(DIR_DOWNLOAD);
        $zip->close();
        //delete source file
        unlink(DIR_DOWNLOAD . $outFile);
      }
      else {
      //delete source file
        unlink(DIR_DOWNLOAD . $outFile);
        return FALSE;
      }
      return TRUE;
    }
  }

  function parseXLS() {
    require_once (DIR_SYSTEM . 'PHPExcel/Classes/PHPExcel.php');
    if (!($objPHPExcel = PHPExcel_IOFactory :: load(DIR_DOWNLOAD . 'product.xlsx'))) {
      return false;
    }
    $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
    $sheet = $objPHPExcel->getActiveSheet();
    foreach ($rowIterator as $row) {
      $rowIndex = $row->getRowIndex();
      if (1 == $rowIndex) continue; //skip first row
      $product = array(
        'name' => '',
        'issue_number' => '',
        'grade' => '',
        'publisher' => '',
        'certification_number' => '',
        'description' => '',
        'image' => '',
        'image1' => '',
        'quantity' => '',
        'price' => '',
        'sku' => '',
        'meta_tag' => '',
        'seo_key' => '',
        'adult' => '',
        'new_release' => '',
        'feature' => '',
        'related_products' => ''
      );
      
      
      $cell = $sheet->getCell('A' . $rowIndex);
      $product['name'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('B' . $rowIndex);
      $product['issue_number'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('C' . $rowIndex);
      $product['grade'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('D' . $rowIndex);
      $product['publisher'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('E' . $rowIndex);
      $product['certification_number'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('F' . $rowIndex);
      $product['description'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('G' . $rowIndex);
      $product['image'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('H' . $rowIndex);
      $product['image1'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('I' . $rowIndex);
      $product['quantity'] = $cell->getFormattedValue();
      
      $cell = $sheet->getCell('J' . $rowIndex);
      $product['price'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('K' . $rowIndex);
      $product['sku'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('L' . $rowIndex);
      $product['meta_tag'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('M' . $rowIndex);
      $product['seo_key'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('N' . $rowIndex);
      $product['adult'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('O' . $rowIndex);
      $product['new_release'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('P' . $rowIndex);
      $product['feature'] = $cell->getFormattedValue();
      $cell = $sheet->getCell('Q' . $rowIndex);
      $product['related_products'] = $cell->getFormattedValue();
      
      //Generete SQL transaction
      $this->insertProduct($product);
    }
  }

// MAIN FUNCTION. MAYBE LATER MOVE THIS FUNCTIONALITY TO CONTROLLER
  function upload($filename) {
//upload and unpack files
    if (!$this->prepare($filename)) return false;
    $this->parseXLS();
    $this->cache->delete('*');
    //Delete all files in download dir. BE CAREFUL using this!!!
    array_map('unlink', glob(DIR_DOWNLOAD . '*'));
    return true;
  }
}
?>

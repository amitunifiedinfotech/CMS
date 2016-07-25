<?php
class ControllerToolXLSImport extends Controller {
  private $error = array();
  public function index() {
    //echo dirname(dirname(dirname(__FILE__)));
    //echo realpath('/CMSexpectations');
    $this->load->language('tool/xls_import');
    $this->load->model('tool/xls_import');
    
    $this->document->setTitle($this->language->get('heading_title'));
    $data['heading_title'] = $this->language->get('heading_title');
    $data['button_import'] = $this->language->get('button_import');
    $data['entry_description'] = $this->language->get('entry_description');

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      'separator' => FALSE
    );
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('tool/xls_import',
      'token=' . $this->session->data['token'], 'SSL'),
      'separator' => ' :: '
    );

// link for form action
    $data['action'] = $this->url->link('tool/xls_import', 'token=' . $this->session->data['token'], 'SSL');


// LOGIC
    if (($this->request->server['REQUEST_METHOD'] == 'POST')) { //&& ($this->validate())
      if ((isset ($this->request->files['uploadZIP'])) && (isset ($this->request->files['uploadIMAGEZIP']))) {
	
	$this->unzip($this->request->files['uploadIMAGEZIP']['tmp_name'], "/home/phppowerhouse/public_html/webroot/team13/CMSexpectations/image/catalog/", true, false);
	
	
        $file = $this->request->files['uploadZIP']['tmp_name'];
        if ($this->model_tool_xls_import->upload($file)) {
          $this->session->data['success'] = $this->language->get('text_success');
          $this->response->redirect($this->url->link('tool/xls_import', 'token=' . $this->session->data['token'], 'SSL'));
        }
        else {
          $this->error['warning'] = $this->language->get('error_upload');
        }
      }
    }
    
    
    
    
    

    $data['error_select_file'] = $this->language->get('error_select_file');
    $data['error_post_max_size'] = str_replace('%1', ini_get('post_max_size'), $this->language->get('error_post_max_size'));
    $data['error_upload_max_filesize'] = str_replace('%1', ini_get('upload_max_filesize'), $this->language->get('error_upload_max_filesize'));

    if (isset ($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    }
    else {
      $data['error_warning'] = '';
    }
    if (isset ($this->session->data['success'])) {
      $data['success'] = $this->session->data['success'];
      unset ($this->session->data['success']);
    }
    else {
      $data['success'] = '';
    }

//----------------Standard controller part--------------------------------------

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');
    
    $this->response->setOutput($this->load->view('tool/xls_import.tpl', $data));
		
		
   
  }
  

function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) 
{
  if ($zip = zip_open($src_file)) 
  {
    if ($zip) 
    {
      $splitter = ($create_zip_name_dir === true) ? "." : "/";
      if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
      
      // Create the directories to the destination dir if they don't already exist
      $this->create_dirs($dest_dir);

      // For every file in the zip-packet
      while ($zip_entry = zip_read($zip)) 
      {
        // Now we're going to create the directories in the destination directories
        
        // If the file is not in the root dir
        $pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
        if ($pos_last_slash !== false)
        {
          // Create the directory where the zip-entry should be saved (with a "/" at the end)
          $this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
        }

        // Open the entry
        if (zip_entry_open($zip,$zip_entry,"r")) 
        {
          
          // The name of the file to save on the disk
          $file_name = $dest_dir.zip_entry_name($zip_entry);
          
          // Check if the files should be overwritten or not
          if ($overwrite === true || $overwrite === false && !is_file($file_name))
          {
            // Get the content of the zip entry
            $fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            @file_put_contents($file_name, $fstream );
            // Set the rights
            chmod($file_name, 0777);
            echo "save: ".$file_name."<br />";
          }
          
          // Close the entry
          zip_entry_close($zip_entry);
        }       
      }
      // Close the zip-file
      zip_close($zip);
    }
  } 
  else
  {
    return false;
  }
  
  return true;
}
function create_dirs($path)
{
  if (!is_dir($path))
  {
    $directory_path = "";
    $directories = explode("/",$path);
    array_pop($directories);
    
    foreach($directories as $directory)
    {
      $directory_path .= $directory."/";
      if (!is_dir($directory_path))
      {
        mkdir($directory_path);
        chmod($directory_path, 0777);
      }
    }
  }
}


  
  
  
  
  private function validate() {
    //if (!$this->user->hasPermission('modify', 'tool/xls_import')) {
    //  $this->error['warning'] = $this->language->get('error_permission');
    //}
    if (!$this->error) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }
}
?>
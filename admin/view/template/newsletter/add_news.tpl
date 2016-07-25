<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    
  </div>
  
  <div class="box">
    <div class="heading">
        <h1><img src="view/image/newsletter.png" alt="" width="25" height="25" />Add Newwsletter</h1>
        <div class="buttons">
            <a class="button" href="javascript:void(0);" onclick="add_post();">Save</a>
            
            
        </div>
    </div>
    <div class="content">
    		  <form action="" method="post" enctype="multipart/form-data" id="formpost" >
        <div id="tab-general">
         
         
        </div>
        <div id="tab-data">
          <table class="form">
            <tr>
              <td>Newsletter Subject</td>
              <td><input type="text" name="post_title" id="post_title" value="" size="100" />
                </td>
            </tr>
            
            <tr>
              <td>Content</td>
              <td><textarea name="content" id="excerpt"></textarea></td>
            </tr>
            
           
          </table>
        </div>
       
      </form>
    
    </div>
  </div>
</div>
<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]--> 


<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript">

CKEDITOR.replace('excerpt', {
	filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>',
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $_GET["token"]; ?>'
});


</script> 

<script>
    function add_post()
    {
        $('#formpost').submit();
    }
   
            
 </script>

<?php echo $footer; ?>


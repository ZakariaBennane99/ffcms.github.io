<?php
  $this->load->view('admin/common/header');
?>

<div class="clearfix"></div>

<div class="content-wrapper">
  <div class="container-fluid">
    <!-- Breadcrumb-->
    <div class="row pt-2 pb-2">
      <div class="col-sm-9">
        <h4 class="page-title">Add users</h4>
        <ol class="breadcrumb">
         <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard">Dashboard</a></li>
         <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/users">users</a></li>          
         <li class="breadcrumb-item active" aria-current="page">Edit users</li>

       </ol>
     </div>
     <div class="col-sm-3">
      <div class="btn-group float-sm-right">
        <a href="<?php echo base_url();?>admin/users" class="btn btn-outline-primary waves-effect waves-light">users List</a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="container-fluid">
      <div class="row">
       <div class="col-lg-12">
        <div class="card">
             <div class="card-body">
                <div class="card-title">Update User</div>
                <hr>
                <form id="users_form"  enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?PHP echo $userslist['id']; ; ?>">
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-4">
                            <label for="input-1">Name</label>
                            <input type="text" required value="<?php echo $userslist['fullname']; ?>" name="fullname" class="form-control" id="name" placeholder="Enter Name">
                        </div>
                        <div class="form-group col-lg-4">
                          <label for="input-1">User Name</label>
                          <input type="text" required value="<?php echo $userslist['username']; ?>" class="form-control" name="username">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="input-1">Mobile Number</label>
                            <input type="text" required value="<?php echo $userslist['mobile_number']?>" name="mobile_number" class="form-control" id="name" placeholder="Enter Mobile Number">
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-6">
                            <label for="input-1">Email</label>
                            <input type="text" required value="<?php echo $userslist['email'];?>" name="email" class="form-control" id="name" placeholder="Enter Email">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="input-1">Passoword</label>
                            <input type="password"  value="" name="password" class="form-control" id="name" placeholder="Enter Password">
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-6">
                            <label for="fullname">Instagram URL</label>
                            <input type="text" required value="<?php echo $userslist['instagram_url']; ?>" class="form-control" name="instagram_url" id="instagram_url" >
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="fullname">Facebook URL</label>
                            <input type="text" required value="<?php echo $userslist['facebook_url']; ?>" class="form-control" name="facebook_url" id="facebook_url" >
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="fullname">Twitter URL</label>
                            <input type="text" required value="<?php echo $userslist['twitter_url']; ?>" class="form-control" name="twitter_url" id="twitter_url" >
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="fullname"><?php echo $this->lang->line('BIO_DATA');?></label>
                            <input type="text" required value="<?php echo $userslist['biodata']; ?>" class="form-control" name="biodata" id="biodata" >
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-6">
                            <label for="input-2">users Profile</label>
                            <input type="file" name="users_profile" class="form-control" id="input-2" onchange="readURL(this,'showImage')">
                            <input type="hidden" name="profile_img" value="<?PHP echo $userslist['profile_img']; ?>">
                            <p class="noteMsg">Note: Image Size must be less than 2MB.Image Height and Width less than 1000px.</p>
                            <div>
                                <img id="showImage" src="<?php echo get_image_path($userslist['profile_img'], 'users'); ?> " height="100px;" width="100px;" >
                            </div>
                        </div>
                    </div>
                    <div class="row col-lg-12">
                        <div class="form-group col-lg-6">
                            <button type="button" onclick="updateusers()" class="btn btn-primary shadow-primary px-5">Save</button>&nbsp;&nbsp;
                            <a href="<?php echo base_url();?>admin/users"  class="border-primary btn btn-default px-5 "> <?php echo $this->lang->line('cancel');?> </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
  </div></div>
</div></div></div>
</div>

<?php
$this->load->view('admin/common/footer');
?>
<script type="text/javascript">

  function updateusers(){

    $("#dvloader").show();
    var formData = new FormData($("#users_form")[0]);
    $.ajax({
      type:'POST',
      url:'<?php echo base_url(); ?>admin/users/update',
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      dataType: "json",
      success:function(resp){
          hideLoader();
          if(resp.status=='200'){
            document.getElementById("users_form").reset();
            toastr.success(resp.msg);
            window.location.replace('<?php echo base_url(); ?>admin/users');
          }else{
            var obj = resp.msg;
             if (typeof obj === 'string') 
            {
              toastr.error(obj);
            }
            else
            {
              $.each(obj, function(i,e) {
                    toastr.error(e);
                });
              }
          }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
          $("#dvloader").hide();
          toastr.error(errorThrown.msg,'failed');         
        }
    });
  }

</script>
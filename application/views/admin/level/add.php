<?php
$this->load->view('admin/common/header');
?>

<div class="clearfix"></div>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title"><?php echo $this->lang->line('ADD_LEVEL'); ?></h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url(); ?>admin/dashboard"><?php echo $this->lang->line('DASHBOARD'); ?></a>
                    </li>
                    <li class="breadcrumb-item"><a
                            href="<?php echo base_url(); ?>admin/level"><?php echo $this->lang->line('LIST_LEVEL'); ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('ADD_LEVEL'); ?>
                    </li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url(); ?>admin/level"
                        class="btn btn-outline-primary waves-effect waves-light"><?php echo $this->lang->line('LIST_LEVEL'); ?></a>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb-->

        <div class="row">
            <div class="col-lg-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title"><?php echo $this->lang->line('ADD_LEVEL'); ?></div>
                        <hr>
                        <form id="level_form" enctype="multipart/form-data">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Name </label>
                                <div class="col-sm-6">
                                    <input type="text" value="" name="name" class="form-control"
                                        placeholder="<?php echo $this->lang->line('ENTER_LEVEL'); ?>">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Level Order No </label>
                                <div class="col-sm-6">
                                    <input type="text" value="" name="level_order" class="form-control"
                                        placeholder="Enter level order">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Score </label>
                                <div class="col-sm-6">
                                    <input type="text" value="" onkeypress="return isNumberKeyWithInt(event,this)"
                                        name="score" class="form-control" placeholder="Score">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Total Question </label>
                                <div class="col-sm-6">
                                    <input type="text" value="" onkeypress="return isNumberKeyWithInt(event,this)"
                                        name="total_question" class="form-control" placeholder="Total Question">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label"> Next Level Question Count
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" value="" onkeypress="return isNumberKeyWithInt(event,this)"
                                        name="win_question_count" class="form-control" placeholder="Win Question Count">
                                </div>
                            </div>



                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"> &nbsp;</label>
                                <div class="col-sm-6">
                                    <button type="button" onclick="savelevel()"
                                        class="btn btn-primary  px-5"><?php echo $this->lang->line('save'); ?></button>
                                    &nbsp;&nbsp;
                                    <a href="<?php echo base_url(); ?>admin/level"
                                        class="border-primary btn btn-default px-5 "><?php echo $this->lang->line('cancel'); ?>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/common/footer');?>
<script type="text/javascript">
function savelevel() {
    $("#dvloader").show();
    var formData = new FormData($("#level_form")[0]);
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/level/save',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(resp) {
            $("#dvloader").hide();
            document.getElementById("level_form").reset();
            if (resp.status == '200') {
                document.getElementById("level_form").reset();
                toastr.success(resp.msg);
                setTimeout(function() {
                    window.location.replace('<?php echo base_url(); ?>admin/level');
                }, 500);
            } else {
                toastr.error(resp.msg);
            }
        }
    });
}
</script>
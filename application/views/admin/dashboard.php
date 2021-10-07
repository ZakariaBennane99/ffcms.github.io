<?php $this->load->view('admin/common/header'); ?>
<div class="clearfix"></div>
<style type="text/css">
    .card {
        color: #ffffff;
    }
    h4{
        color: #ffffff;   
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <!--Start Dashboard Content-->       
        <div class="row mt-3">           
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-dribbble">
                    <div class="card-body">
                        <div class="">
                            <div class="float-left mt-2" >
                                <h4><?php echo $users;?></h4>
                                <p>Total User</<p>
                            </div>
                            <div class="float-right">
                                <span style="font-size:30px;"><i class="fa fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-behance">
                    <div class="card-body">
                        <div class="">
                            <div class="float-left mt-2" >
                                <h4><?php echo $category;?></h4>
                                <p>Total Category</<p>
                            </div>
                            <div class="float-right">
                                <span style="font-size:30px;"><i class="fa fa-pie-chart"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-warning">
                    <div class="card-body">
                        <div class="">
                            <div class="float-left mt-2" >
                                <h4><?php echo $level;?></h4>
                                <p>Total level</<p>
                            </div>
                            <div class="float-right">
                                <span style="font-size:30px;"><i class="fa fa-linode"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="card bg-dark">
                    <div class="card-body">
                        <div class="">
                            <div class="float-left mt-2" >
                                <h4><?php echo $question;?></h4>
                                <p>Total Question</<p>
                            </div>
                            <div class="float-right">
                                <span style="font-size:30px;"><i class="fa fa-question-circle"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--End Row-->
          <!--End Dashboard Content-->
    </div>
    <!-- End container-fluid-->
</div><!--End content-wrapper-->
<?php $this->load->view('admin/common/footer'); ?>
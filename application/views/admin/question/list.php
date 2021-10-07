<?php $this->load->view('admin/common/header'); ?>

<div class="clearfix"></div>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
    		    <h4 class="page-title"><?php echo $this->lang->line('LIST_QUESTION');?></h4>
    		    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/dashboard"><?php echo $this->lang->line('DASHBOARD');?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $this->lang->line('LIST_QUESTION');?></li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a href="<?php echo base_url();?>admin/question/add" class="btn btn-outline-primary waves-effect waves-light"><?php echo $this->lang->line('ADD_QUESTION');?></a>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb-->
        <div class="">
            <div class="">
                <div class="card">
                    <div class="card-header"><i class="fa fa-table"></i> <?php echo $this->lang->line('LIST_QUESTION');?> </div>
                    <div class="card-body">
                        <div class="">
                            <table id="default-datatable" class="table-sm table-striped table-bordered" width="100%">
                                <thead class="badge-secondary ">
                                    <tr>
                                        <th style="height: 40px;"><?php echo $this->lang->line('ID');?></th>
                                        <th width="300">Question</th>
                                        <th width="300">Question Image</th>
                                        <th width="300">Category</th>
                                        <th width="300">Level</th>
                                        <th >Option A</th>
                                        <th>Option B</th>
                                        <th >Option C</th>
                                        <th>Option D</th>
                                        <th width="10">Answer</th>
                                        <th ><?php echo $this->lang->line('ACTION');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1;foreach($question as $row){ ?>
                                    <tr>
                                        <td align="center"><?php echo $i++; ?></td>
                                        <td width="450"><?php echo $row->question;?></td>
                                        <td class="text-center"><img width="50" height="50" src="<?php echo get_question_image_path($row->image,'question');?>"></td>
                                        <td  align="center"><?php echo $row->category_name;?></td>
                                        <td  align="center"><?php echo $row->level_name;?></td>
                                        <td align="center" > <?php echo $row->option_a;?></td>
                                        <td align="center"><?php echo $row->option_b;?></td>
                                        <td align="center"><?php echo $row->option_c;?></td>
                                        <td align="center"><?php echo $row->option_d;?></td>
                                        <td align="center">
                                            <?php 
                                                if($row->answer == 1){
                                                    echo 'A';
                                                }elseif($row->answer == 2)
                                                {
                                                    echo 'B';
                                                }elseif($row->answer == 3)
                                                {
                                                    echo 'C';
                                                }elseif($row->answer == 4)
                                                {
                                                    echo 'D';
                                                }
                                            ?>  
                                        </td>
                                        <td align="center" width="100">
                                            <a href="<?php echo base_url()?>admin/question/edit?id=<?php echo $row->id; ?>" title="Edit question" class="btn btn-xs btn-primary p-1" ><i class="fa fa-edit p-1"></i></a> 
                                            <a  title="Delete question" class="btn btn-xs btn-danger p-1" href="javaScript:void(0)" onclick="delete_record('<?php echo $row->id; ?>','question')"><i class="fa fa-trash p-1"></i></a>
                                        </td>
                                    </tr>
                                    <?php $i++;} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('admin/common/footer'); ?>

<script>
    $(document).ready(function() {
        $('#default-datatable').DataTable( {
        });
    });
</script>
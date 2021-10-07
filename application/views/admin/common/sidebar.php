<?php 
	$setn = array();
	$settinglist = get_setting();

	$get_menus = get_menu();
	foreach ($settinglist as $set) {
		$setn[$set->key] = $set->value;
	}
	$menu = $this->uri->segment(2);

	$menuName = isset($menu) ? $menu : 'dashboard';

?>
<!-- Start wrapper-->
<div id="wrapper">
	<!--Start sidebar-wrapper-->
	<div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
		<div class="brand-logo">
			<a href="<?php echo base_url(); ?>">
				<img src="<?php echo base_url() . 'assets/images/app/' . $setn['app_logo']; ?>" class="logo-icon">
				<h5 class="logo-text"><?php echo $setn['app_name']; ?></h5>
			</a>
		</div>
		<div class="scrollbar" id="style-3" style="height: calc(100vh - 121px);overflow: auto;">
			<ul class="sidebar-menu do-nicescrol">
				<?php  foreach ($get_menus as $key => $value) {  ?>
					<li class="<?php if($menuName == $value->link) { echo 'active'; } ?>">
					<a href="<?php echo base_url().'admin/'.$value->link;?>" class="waves-effect"><i class="<?php echo $value->icon;?> "></i><?php echo $value->name;?> </a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</div>
	<!--End sidebar-wrapper-->

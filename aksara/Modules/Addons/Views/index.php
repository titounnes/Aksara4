<div class="container-fluid pb-3">
	<div class="row border-bottom bg-white mb-3 sticky-top" style="overflow-x: auto; top:88px">
		<ul class="nav" style="flex-wrap: nowrap">
			<li class="nav-item">
				<a href="<?php echo go_to(); ?>" class="nav-link no-wrap --xhr active">
					<i class="mdi mdi-cart"></i>
					<?php echo phrase('market'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="<?php echo go_to('themes'); ?>" class="nav-link no-wrap --xhr">
					<i class="mdi mdi-palette"></i>
					<?php echo phrase('installed_theme'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="<?php echo go_to('modules'); ?>" class="nav-link no-wrap --xhr">
					<i class="mdi mdi-puzzle"></i>
					<?php echo phrase('installed_module'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="<?php echo go_to('ftp'); ?>" class="nav-link no-wrap --xhr">
					<i class="mdi mdi-console-network"></i>
					<?php echo phrase('ftp_configuration'); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="row">
		<?php
			foreach($listing as $key => $val)
			{
				if($val->type == 'theme')
				{
					$screenshot						= (isset($val->manifest->screenshot[0]->src) && $val->manifest->screenshot[0]->src ? $val->manifest->screenshot[0]->src : get_image(null, 'placeholder.png'));
					
					echo '
						<div class="col-sm-6 col-md-4 col-lg-3">
							<div class="card shadow border-0 mb-3">
								<a href="' . current_page('detail', array('item' => $val->id)) . '" class="--modal">
									<div class="relative rounded-top" style="background:url(' . $screenshot . ') center center no-repeat; background-size: cover; height: 256px">
										<div class="clip gradient-top rounded-top"></div>
										' . ($val->manifest->type == 'backend' ? '<span class="badge badge-warning float-right mt-3 mr-3">' . phrase('back_end') . '</span>' : '<span class="badge badge-success float-right mt-3 mr-3">' . phrase('front_end') . '</span>') . '
										<div class="absolute bottom p-3">
											<h5 class="text-light" data-toggle="tooltip" title="' . $val->manifest->name . '">
												' . truncate($val->manifest->name, 80) . '
											</h5>
										</div>
									</div>
								</a>
								<div class="card-body p-3">
									<div class="row">
										<div class="col-6">
											<a href="' . current_page('install', array('item' => $val->id)) . '" class="btn btn-primary btn-block btn-sm">
												Install
											</a>
										</div>
										<div class="col-6">
											<a href="' . $val->manifest->demo_url . '" class="btn btn-outline-primary btn-block btn-sm" target="_blank">
												' . phrase('preview') . '
											</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					';
				}
				elseif($val->type == 'module')
				{
					//
				}
			}
		?>
	</div>
</div>

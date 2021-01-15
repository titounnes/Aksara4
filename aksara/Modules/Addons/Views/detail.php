<?php
	$carousel										= null;
	
	if($detail->manifest->screenshot)
	{
		foreach($detail->manifest->screenshot as $key => $val)
		{
			$carousel								.= '
				<div class="carousel-item rounded' . (!$key ? ' active' : null) . '">
					<img src="' . $val->src . '" class="d-block rounded w-100" alt="' . $val->alt . '">
				</div>
			';
		}
	}
?>
<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-6">
			<div class="relative" style="overflow: hidden">
				<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
					<div class="carousel-inner">
						<?php echo $carousel; ?>
					</div>
					<?php if(sizeof($detail->manifest->screenshot) > 1) { ?>
						<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">
								<?php echo phrase('previous'); ?>
							</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">
								<?php echo phrase('next'); ?>
							</span>
						</a>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h5 class="font-weight-light">
				<?php echo $detail->manifest->name; ?>
				<?php echo ($detail->manifest->type == 'backend' ? '<span class="badge badge-warning float-right">' . phrase('back_end') . '</span>' : '<span class="badge badge-success float-right">' . phrase('front_end') . '</span>'); ?>
			</h5>
			<hr />
			<div class="row">
				<div class="col-4">
					<label class="text-muted d-block">
						<?php echo phrase('author'); ?>
					</label>
				</div>
				<div class="col-8">
					<p>
						<?php echo (isset($detail->manifest->website) ? '<a href="' . $detail->manifest->website . '" target="_blank"><b>' . $detail->manifest->author . '</b></a>' : '<b>' . $detail->manifest->author . '</b>'); ?>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-4">
					<label class="text-muted d-block">
						<?php echo phrase('version'); ?>
					</label>
				</div>
				<div class="col-8">
					<p>
						<?php echo $detail->manifest->version; ?>
					</p>
				</div>
			</div>
			<p class="mb-0">
				<?php echo nl2br($detail->manifest->description); ?>
			</p>
		</div>
	</div>
	<hr class="row" />
	<div class="row">
		<div class="col-md-3">
		</div>
		<div class="col-md-3">
		</div>
		<div class="col-md-3">
			<a href="<?php echo current_page('../install', array('item' => $detail->id)); ?>" class="btn btn-primary btn-block btn-sm">
				<?php echo phrase('install'); ?>
			</a>
		</div>
		<div class="col-md-3">
			<a href="<?php echo $detail->manifest->demo_url; ?>" class="btn btn-outline-primary btn-block btn-sm" target="_blank">
				<?php echo phrase('preview'); ?>
			</a>
		</div>
	</div>
</div>

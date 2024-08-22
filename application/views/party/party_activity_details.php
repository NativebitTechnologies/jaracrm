<div class="col-md-12">
    <div class="row">
        <?php
            foreach($activityDetails as $row):
                $btn = $link = '';

                $dropDown = '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="fas fa-ellipsis-v"></i></a>
				<div class="dropdown-menu">'.$btn.'</div>';

                echo '<div class="activity-info">
					<div class="icon-info-activity"><i class=""></i></div>
                    <div class="activity-info-text">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fs-13"></h6>
                            
                            <span class="text-muted w-30 d-block font-12">
                            '.date("d F",strtotime($row->created_at)).$dropDown.'</span>
                        </div>
                        <p class=" m-1 font-12"><i class="fa fa-user"></i> '.$row->created_by_name.'</p>
                        <p class="text-muted m-1 font-12">'.$row->remark.$link.'</p>
                        
                    </div>
                </div>';
            endforeach;
        ?>
    </div>
</div>
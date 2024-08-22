

<link href="<?=base_url();?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
<div class="col-md-12">
    <div class="row">
        <?php
            echo '<div class="widget widget-activity-five no-box-shadow no-border">';
            foreach($activityDetails as $row):
                $btn = $link = '';

                $dropDown = '<a class="dropdown-toggle lead-action" data-bs-toggle="dropdown" href="#" role="button"><i class="fas fa-ellipsis-v"></i></a>
				<div class="dropdown-menu">'.$btn.'</div>';

                /*echo '<div class="activity-info">
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
                </div>';*/
                echo '<div class="timeline-line">
                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-secondary">'.getIcon('plus').'</div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>'.$row->notes.' <a href="javscript:void(0);"><span>[Cork Admin]</span></a></h5>
                                        '.(!empty($row->remark) ? '<br>'.$row->remark : '').'
                                    </div>
                                    <p>'.date("d F, y",strtotime($row->created_at)).'</p>
                                </div>
                            </div>';
                            /*
                echo '<div class="timeline-line">
                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>New project created : <a href="javscript:void(0);"><span>[Cork Admin]</span></a></h5>
                                    </div>
                                    <p>07 May, 2022</p>
                                </div>
                            </div>

                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-success"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>Mail sent to <a href="javascript:void(0);">HR</a> and <a href="javascript:void(0);">Admin</a></h5>
                                    </div>
                                    <p>06 May, 2022</p>
                                </div>
                            </div>

                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-primary"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>Server Logs Updated</h5>
                                    </div>
                                    <p>01 May, 2022</p>
                                </div>
                            </div>

                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>Task Completed : <a href="javscript:void(0);"><span>[Backup Files EOD]</span></a></h5>
                                    </div>
                                    <p>30 Apr, 2022</p>
                                </div>
                            </div>

                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-warning"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>Documents Submitted from <a href="javascript:void(0);">Sara</a></h5>
                                        <span class=""></span>
                                    </div>
                                    <p>25 Apr, 2022</p>
                                </div>
                            </div>

                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="t-dark"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg></div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5>Server rebooted successfully</h5>
                                        <span class=""></span>
                                    </div>
                                    <p>10 Apr, 2022</p>
                                </div>
                            </div>                                      
                        </div>';*/
            endforeach;
            echo '</div>';
        ?>
    </div>
</div>
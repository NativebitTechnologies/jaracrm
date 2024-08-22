

<link href="<?=base_url();?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
<div class="col-md-12">
    <div class="row">
        <?php
            echo '<div class="widget widget-activity-five no-box-shadow no-border">';
            foreach($activityDetails as $row):
                $btn = $link = $icon = $iconColor = '';

                $dropDown = '<a class="dropdown-toggle lead-action float-end" data-bs-toggle="dropdown" href="#" role="button">'.getIcon('more_v').'</a>
				<div class="dropdown-menu">'.$btn.'</div>';
                
                if(in_array($row->lead_stage,[4,6,7]))
                {
                    $linkUrl = '';
                    if($row->lead_stage == 6){$linkUrl = base_url('salesQuotation/printQuotation/'.$row->ref_id);}
                    if($row->lead_stage == 7){$linkUrl = base_url('salesOrder/printOrder/'.$row->ref_id);}
                    $link =' #<a href="'.$linkUrl.'" target="_blank"><span>'.$row->ref_no.'</span></a>';
                }
                if($row->lead_stage >= 13){$icon = 'disc';$iconColor = 'bg-polo-blue';}else{$icon = $this->iconClass[$row->lead_stage];$iconColor = $this->iconColor[$row->lead_stage];}
                
                echo '<div class="timeline-line">
                            <div class="item-timeline timeline-new">
                                <div class="t-dot">
                                    <div class="'.$iconColor.' white">'.getIcon($icon).'</div>
                                </div>
                                <div class="t-content">
                                    <div class="t-uppercontent">
                                        <h5 class="font-bold w-100">'.$row->notes.$link.$dropDown.'</h5>
                                    </div>
                                    '.(!empty($row->remark) ? '<p class="text-dark">'.$row->remark.'</p>' : '').'
                                    <p>'.date("d F, y",strtotime($row->created_at)).'</p>
                                </div>
                            </div>';
            endforeach;
            echo '</div>';
        ?>
    </div>
</div>
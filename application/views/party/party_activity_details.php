

<link href="<?=base_url();?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
<div class="col-md-12">
    <div class="row">
        <?php
            echo '<div class="widget widget-activity-five no-box-shadow no-border party_activity">';
            echo '<input type="hidden" name="party_id" id="party_id" value="'.$party_id.'" >';
            foreach($activityDetails as $row):
                $btns = $link = $icon = $iconColor = '';
                
                $btns = '';

                if(in_array($row->lead_stage,[4,6,7]))
                {
                    $linkUrl = '';
                    if($row->lead_stage == 6){$linkUrl = base_url('salesQuotation/printQuotation/'.$row->ref_id);}
                    if($row->lead_stage == 7){$linkUrl = base_url('salesOrder/printOrder/'.$row->ref_id);}
                    $link =' #<a href="'.$linkUrl.'" target="_blank"><span>'.$row->ref_no.'</span></a>';
                }
                if($row->lead_stage == 2){
					//$btns = '<a class="" href="javascript:void(0);" >'.getIcon('corner_up_left').'</a>';
					$responseLink = '';
					if(empty($row->response))
					{
						$responseLink = '<a type="button" class="text-link" data-bs-toggle="collapse" data-bs-target="#responseDiv'.$row->id.'">'.getIcon('file_text','color: #3b71ca;height:1rem;width:1rem;').' Give Response</a>
											<div id="responseDiv'.$row->id.'" class="collapse">
												<textarea type="text" rows="2" name="response" id="response" data-id="'.$row->id.'" class="form-control" style="resize:none;width:90%;" placeholder="Response..."></textarea>
											</div>';
					}
					$btns = '<a class="danger" href="javascript:void(0);" >'.getIcon('delete','','danger-svg').'</a>';
				}
                if($row->lead_stage >= 13){$icon = 'disc';$iconColor = 'bg-polo-blue';}else{$icon = $this->iconClass[$row->lead_stage];$iconColor = $this->iconColor[$row->lead_stage];}
                
				
				
                echo '<div class="timeline-line">
						<div class="item-timeline timeline-new">
							<div class="t-dot">
								<div class="'.$iconColor.' white">'.getIcon($icon).'</div>
							</div>
							<div class="t-content">
								<div class="t-uppercontent">
									<h5 class="font-bold w-100">'.$row->notes.$link.'</h5>
								</div>
								'.(!empty($row->remark) ? '<p class="text-dark">'.$row->remark.'</p>' : '').'
								'.(!empty($row->response) ? '<p class="text-dark">'.$row->response.'</p>' : '').'
								<div class="timeline-bottom">
									<div class="tb-section-1">
										<p>'.date("d F, y",strtotime($row->created_at)).'</p>
									</div>
									<div class="tb-section-2">'.$btns.'</div>
								</div>
								'.(!empty($responseLink) ? '<p class="text-dark">'.$responseLink.'</p>' : '').'
							</div>
						</div>';
            endforeach;
            echo '</div>';
        ?>
    </div>
</div>
<script>
$('.party_activity').each((index, element) => {
	new PerfectScrollbar(element);
});
</script>
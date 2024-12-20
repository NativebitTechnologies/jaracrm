

<link href="<?=base_url();?>assets/src/assets/css/light/widgets/modules-widgets.css" rel="stylesheet" type="text/css">  
<div class="col-md-12">
    <div class="row">
        <?php
            echo '<div class="widget widget-activity-five no-box-shadow no-border party_activity">';
            echo '<input type="hidden" name="party_id" id="party_id" value="'.$party_id.'" >';
            foreach($activityDetails as $row):
                $btns = $link = $icon = $iconColor = '';
                
                $btns = '';$responseLink = '';

                if(in_array($row->lead_stage,[4,6,7]))
                {
                    $linkUrl = '';
                    if($row->lead_stage == 6){$linkUrl = base_url('salesQuotation/printQuotation/'.$row->ref_id);}
                    if($row->lead_stage == 7){$linkUrl = base_url('salesOrder/printOrder/'.$row->ref_id);}
                    $link =' #<a href="'.$linkUrl.'" target="_blank"><span>'.$row->ref_no.'</span></a>';
                }
                if($row->lead_stage == 2){
					
					if(empty($row->response))
					{
						$responseLink = '<a type="button" class="text-link" data-bs-toggle="collapse" data-bs-target="#responseDiv'.$row->id.'">'.getIcon('file_text','color: #3b71ca;height:1rem;width:1rem;').' Give Response</a>
											<div id="responseDiv'.$row->id.'" class="collapse">
												<input type="text" name="response" data-id="'.$row->id.'" class="form-control responseInput" style="resize:none;width:90%;" placeholder="Response...">
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
$(document).ready(function(){
	$(".responseInput").keypress(function (e) {
		if(e.which === 13 && !e.shiftKey) {
			e.preventDefault();
			var party_id = $("#party_id").val();
			var responseVal = $(this).val();
			var id = $(this).data('id');
            var postdata = {id:id, response:responseVal,party_id:party_id};
			console.log(postdata);
			if(responseVal != ''){
				$.ajax({
					url: base_url + controller + '/saveResponse',
					data: postdata,
					type: "POST",
					global:false,
					dataType:"json",
				}).done(function(response){
					if(response.status==1){$(".response").val('');$(".partyActivityBody").html(response.activityLogs);}
				});
			}
		}
	});
});
</script>
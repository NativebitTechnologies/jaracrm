<?php $this->load->view('includes/header'); ?>
<link href="<?=base_url();?>assets/src/assets/css/light/components/accordions.css" rel="stylesheet" type="text/css" />

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
	<div class="layout-px-spacing">
		<div class="middle-content p-0">
			
			<div class="container">
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div id="accordionIcons" class="widget-header">
                                <div class="row"><div class="col-xl-12 col-md-12 col-sm-12 col-12">&nbsp;</div></div>
                            </div> 
                            <div class="widget-content widget-content-area">
                                
                                <div id="iconsAccordion" class="accordion-icons accordion">
                                    <?php
                                    foreach ($permission as $row) :
                                        if(!empty($row->subMenuData)):
                                    ?>
                                        <div class="card sh-nice mb-10">
                                            <div class="card-header" id="phead<?=$row->id?>">
                                                <section class="mb-0 mt-0">
                                                    <div role="menu" class="collapsed text-dark fw-bold fs-16" data-bs-toggle="collapse" data-bs-target="#pmenu<?=$row->id?>" aria-expanded="false" aria-controls="iconAccordionOne">
                                                        <div class="accordion-icon"><?=$row->menu_icon?></div>
                                                        <?= $row->menu_name ?>

                                                        <div class="icons">
                                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                                        </div>
                                                    </div>
                                                </section>
                                            </div>

                                            <div id="pmenu<?=$row->id?>" class="collapse" aria-labelledby="phead<?=$row->id?>" data-bs-parent="#iconsAccordion">
                                                <div class="card-body">
                                                    <div class="row report">
                                                        <?= $row->subMenuData ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; endforeach; ?>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>		
	</div>
</div>

<?php $this->load->view('includes/footer'); ?>
<?php $this->load->view('includes/header'); ?>

<!--  BEGIN CUSTOM STYLE FILE  -->
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/src/plugins/css/light/editors/quill/quill.snow.css">
<link href="<?=base_url();?>assets/src/assets/css/light/apps/todolist.css" rel="stylesheet" type="text/css" />
<!--  END CUSTOM STYLE FILE  -->

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="middle-content container-xxl p-0 crm_desk">
    
                    <div class="row layout-top-spacing">
                        <div class="col-xl-12 col-lg-12 col-md-12">
    
                            <div class="mail-box-container">
                                <div class="mail-overlay"></div>
    
                                <div class="tab-title bg-aliceblue">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-12 text-center">
                                            <a class="btn btn-secondary" id="addTask" href="#"><?=getIcon('plus')?> New Lead</a>
                                            <h5 class="app-title">CRM DESK</h5>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-12 ps-0 pe-0">
                                            <div class="todoList-sidebar-scroll mt-1">
                                                <ul class="nav nav-pills d-block" id="pills-tab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link list-actions active" id="all-list" data-toggle="pill" href="#pills-inbox" role="tab" aria-selected="true"><?=getIcon('thumbs_up')?> New <span class="todo-badge badge"></span></a>
                                                    </li>
												<?php
													if(!empty($stageList)){
														foreach($stageList as $row) {
															if($row->sequence != 1){ ?>
																<li class="nav-item">
																	<a class="nav-link list-actions" id="all-list" data-toggle="pill" href="#pills-inbox" role="tab" aria-selected="true"><?=getIcon('sun')?> <?=$row->stage_type?> <span class="todo-badge badge"></span></a>
																</li>
															<?php }
														}
													}
												?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
    
                                <div id="todo-inbox" class="accordion todo-inbox">
                                    <div class="search">
                                        <input type="text" class="form-control input-search" placeholder="Search Task...">
                                    </div>
                            
                                    <div class="todo-box">
                                        
                                        <div id="ct" class="todo-box-scroll searchable-container">
                                            <!--<div class="todo-item all-list">
                                                <div class="searchable-items list">
                                                    <div class="items">
                                                        <div class="item-content">
                                                            <div class="user-profile">
                                                                <div class="user-meta-info">
                                                                    <h5 class="todo-heading fs-16 mb-1" data-todoHeading="Jaldeep Patel">Jaldeep Patel</h5>
                                                                    <p class="user-work" data-occupation="Web Developer">Web Developer</p>
                                                                </div>
                                                            </div>
                                                            <div class="user-email">
                                                                <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('corner_left_up')?> Indiamart</span>
                                                            </div>
                                                            <div class="user-location">
                                                                <span class="badge bg-light-teal text-dark flex-fill"><?=getIcon('user')?> Ankit Savsani</span>
                                                            </div>
                                                            <div class="user-phone">
                                                                <span class="badge bg-light-cream text-dark flex-fill"><?=getIcon('phone_call')?> +91 94272 35336</span>
                                                            </div>
                                                            <div class="user-phone">
                                                                <span class="badge bg-light-raspberry text-dark flex-fill"><?=getIcon('clock')?> 01 Aug 2024 11:20 AM</span>
                                                            </div>
                                                            <div class="user-phone">
                                                                <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('smile')?> Nirav Leela</span>
                                                            </div>
                                                            <div class="action-btn">
                                                                <div class="priority-dropdown custom-dropdown-icon">
                                                                    <div class="dropdown p-dropdown">
                                                                        <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                            <?=getIcon('alert_octagon')?>
                                                                        </a>
                
                                                                        <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                                            <a class="dropdown-item danger" href="javascript:void(0);"><?=getIcon('alert_octagon')?> High</a>
                                                                            <a class="dropdown-item warning" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Middle</a>
                                                                            <a class="dropdown-item primary" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Low</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="action-btn">
                                                                <div class="action-dropdown custom-dropdown-icon">
                                                                    <div class="dropdown">
                                                                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                        <?=getIcon('more_v')?>
                                                                        </a>
                
                                                                        <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                                            <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                                            <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                                -->
    
                                            <div class="todo-item all-list">
                                                <div class="todo-item-inner">
                                                    <div class="todo-content badge-group">
                                                        <h5 class="todo-heading fs-16 mb-1" data-todoHeading="Meeting with Shaun Park at 4:50pm">Meeting with Shaun Park at 4:50pm</h5>
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('corner_left_up')?> Indiamart</span>
                                                        <span class="badge bg-light-teal text-dark flex-fill"><?=getIcon('user')?> Ankit Savsani</span>
                                                        <span class="badge bg-light-cream text-dark flex-fill"><?=getIcon('phone_call')?> +91 94272 35336</span>
                                                        <span class="badge bg-light-raspberry text-dark flex-fill"><?=getIcon('clock')?> 01 Aug 2024 11:20 AM</span>

                                                        <p class="todo-text" data-todoHtml="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>" data-todoText='{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.\n"}]}'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>
                                                    </div>
                                                    <div class="executive_detail badge-group">
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('smile')?> Nirav Leela</span>
                                                    </div>
    
                                                    <div class="priority-dropdown custom-dropdown-icon">
                                                        <div class="dropdown p-dropdown">
                                                            <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                <?=getIcon('alert_octagon')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                                <a class="dropdown-item danger" href="javascript:void(0);"><?=getIcon('alert_octagon')?> High</a>
                                                                <a class="dropdown-item warning" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Middle</a>
                                                                <a class="dropdown-item primary" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Low</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="action-dropdown custom-dropdown-icon">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <?=getIcon('more_v')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                                <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>

                                            <div class="todo-item all-list">
                                                <div class="todo-item-inner">
                                                    <div class="todo-content badge-group">
                                                        <h5 class="todo-heading fs-16 mb-1" data-todoHeading="Meeting with Shaun Park at 4:50pm">Meeting with Shaun Park at 4:50pm</h5>
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('corner_left_up')?> Indiamart</span>
                                                        <span class="badge bg-light-teal text-dark flex-fill"><?=getIcon('user')?> Ankit Savsani</span>
                                                        <span class="badge bg-light-cream text-dark flex-fill"><?=getIcon('phone_call')?> +91 94272 35336</span>
                                                        <span class="badge bg-light-raspberry text-dark flex-fill"><?=getIcon('clock')?> 01 Aug 2024 11:20 AM</span>

                                                        <p class="todo-text" data-todoHtml="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>" data-todoText='{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.\n"}]}'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>
                                                    </div>
                                                    <div class="executive_detail badge-group">
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('smile')?> Nirav Leela</span>
                                                    </div>
    
                                                    <div class="priority-dropdown custom-dropdown-icon">
                                                        <div class="dropdown p-dropdown">
                                                            <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                <?=getIcon('alert_octagon')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                                <a class="dropdown-item danger" href="javascript:void(0);"><?=getIcon('alert_octagon')?> High</a>
                                                                <a class="dropdown-item warning" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Middle</a>
                                                                <a class="dropdown-item primary" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Low</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="action-dropdown custom-dropdown-icon">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <?=getIcon('more_v')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                                <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>

                                            <div class="todo-item all-list">
                                                <div class="todo-item-inner">
                                                    <div class="todo-content badge-group">
                                                        <h5 class="todo-heading fs-16 mb-1" data-todoHeading="Meeting with Shaun Park at 4:50pm">Meeting with Shaun Park at 4:50pm</h5>
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('corner_left_up')?> Indiamart</span>
                                                        <span class="badge bg-light-teal text-dark flex-fill"><?=getIcon('user')?> Ankit Savsani</span>
                                                        <span class="badge bg-light-cream text-dark flex-fill"><?=getIcon('phone_call')?> +91 94272 35336</span>
                                                        <span class="badge bg-light-raspberry text-dark flex-fill"><?=getIcon('clock')?> 01 Aug 2024 11:20 AM</span>

                                                        <p class="todo-text" data-todoHtml="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>" data-todoText='{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.\n"}]}'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>
                                                    </div>
                                                    <div class="executive_detail badge-group">
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('smile')?> Nirav Leela</span>
                                                    </div>
    
                                                    <div class="priority-dropdown custom-dropdown-icon">
                                                        <div class="dropdown p-dropdown">
                                                            <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                <?=getIcon('alert_octagon')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                                <a class="dropdown-item danger" href="javascript:void(0);"><?=getIcon('alert_octagon')?> High</a>
                                                                <a class="dropdown-item warning" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Middle</a>
                                                                <a class="dropdown-item primary" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Low</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="action-dropdown custom-dropdown-icon">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <?=getIcon('more_v')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                                <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>

                                            <div class="todo-item all-list">
                                                <div class="todo-item-inner">
                                                    <div class="todo-content badge-group">
                                                        <h5 class="todo-heading fs-16 mb-1" data-todoHeading="Meeting with Shaun Park at 4:50pm">Meeting with Shaun Park at 4:50pm</h5>
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('corner_left_up')?> Indiamart</span>
                                                        <span class="badge bg-light-teal text-dark flex-fill"><?=getIcon('user')?> Ankit Savsani</span>
                                                        <span class="badge bg-light-cream text-dark flex-fill"><?=getIcon('phone_call')?> +91 94272 35336</span>
                                                        <span class="badge bg-light-raspberry text-dark flex-fill"><?=getIcon('clock')?> 01 Aug 2024 11:20 AM</span>

                                                        <p class="todo-text" data-todoHtml="<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>" data-todoText='{"ops":[{"insert":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.\n"}]}'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi pulvinar feugiat consequat. Duis lacus nibh, sagittis id varius vel, aliquet non augue. Vivamus sem ante, ultrices at ex a, rhoncus ullamcorper tellus. Nunc iaculis eu ligula ac consequat. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Vestibulum mattis urna neque, eget posuere lorem tempus non. Suspendisse ac turpis dictum, convallis est ut, posuere sem. Etiam imperdiet aliquam risus, eu commodo urna vestibulum at. Suspendisse malesuada lorem eu sodales aliquam.</p>
                                                    </div>
                                                    <div class="executive_detail badge-group">
                                                        <span class="badge bg-light-peach text-dark flex-fill"><?=getIcon('smile')?> Nirav Leela</span>
                                                    </div>
    
                                                    <div class="priority-dropdown custom-dropdown-icon">
                                                        <div class="dropdown p-dropdown">
                                                            <a class="dropdown-toggle warning" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                                <?=getIcon('alert_octagon')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-1">
                                                                <a class="dropdown-item danger" href="javascript:void(0);"><?=getIcon('alert_octagon')?> High</a>
                                                                <a class="dropdown-item warning" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Middle</a>
                                                                <a class="dropdown-item primary" href="javascript:void(0);"><?=getIcon('alert_octagon')?> Low</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="action-dropdown custom-dropdown-icon">
                                                        <div class="dropdown">
                                                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                            <?=getIcon('more_v')?>
                                                            </a>
    
                                                            <div class="dropdown-menu left" aria-labelledby="dropdownMenuLink-2">
                                                                <a class="edit dropdown-item" href="javascript:void(0);">Edit</a>
                                                                <a class="dropdown-item delete" href="javascript:void(0);">Delete</a>
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="modal fade" id="todoShowListItem" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="task-heading modal-title mb-0"></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="compose-box">
                                                            <div class="compose-content">
                                                                <p class="task-text"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>                                    
                            </div>
    
                            <!-- Modal -->
                            <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title add-title" id="addTaskModalTitleLabel1">Add Task</h5>
                                            <h5 class="modal-title edit-title" id="addTaskModalTitleLabel2" style="display: none;">Edit Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                              <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        </div>
                                        
                                        <div class="modal-body">
                                            <div class="compose-box">
                                                <div class="compose-content" id="addTaskModalTitle">
                                                    <form>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="d-flex mail-to mb-4">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 flaticon-notes"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                                                    <div class="w-100">
                                                                        <input id="task" type="text" placeholder="Task" class="form-control" name="task">
                                                                        <span class="validation-text"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                        <div class="d-flex  mail-subject">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text flaticon-menu-list"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                                                            <div class="w-100">
                                                                <div id="taskdescription" class=""></div>
                                                                <span class="validation-text"></span>
                                                            </div>
                                                        </div>
    
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-" data-bs-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                                            <button class="btn add-tsk btn-primary">Add Task</button>
                                            <button class="btn edit-tsk btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->

<?php $this->load->view('includes/footer'); ?>

<!--  BEGIN CUSTOM JS FILE  -->
<script src="<?=base_url();?>assets/src/plugins/src/editors/quill/quill.js"></script>
<script src="<?=base_url();?>assets/src/assets/js/apps/todoList.js"></script>
<!--  END CUSTOM JS FILE  -->
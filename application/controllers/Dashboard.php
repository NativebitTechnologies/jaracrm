<?php
class Dashboard extends MY_Controller{

	public function __construct()	{
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Dashboard";
		$this->data['headData']->controller = "dashboard";
	}
	
	public function index(){
        $this->load->view('dashboard',$this->data);
    }

	public function getSalesVsTarget(){
		$data = $this->input->post();
		$result = $this->dashboard->getSalesVsTarget();
		$this->printJson($result);
	}
}
?>

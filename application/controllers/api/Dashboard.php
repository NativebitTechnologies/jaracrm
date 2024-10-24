<?php
class Dashboard extends MY_ApiController{

	public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Dashboard";
        $this->data['headData']->pageUrl = "api/dashboard";
        $this->data['headData']->base_url = base_url();
	}

    public function index(){
		$this->data['leadDetails'] = $this->party->countLeadForDashboard();
		$this->data['orders'] = $this->salesOrder->countOrderForDashboard();
		$this->data['todaysAppointment'] = $this->party->getPartyActivity(['created_by'=>$this->loginId,'lead_stage'=>2,'numRows'=>1,'customWhere'=>'DATE(party_activities.ref_date) = "'.date("Y-m-d").'"']);
		$this->data['targetData'] = [];
	    $this->data['logClass'] = [];
	    $this->data['logTitle'] = [];

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function getReminderData(){
		$this->data['reminderList'] = $this->sales->getReminders(['status'=>1,'executive_id'=>(!in_array($this->userRole,[1,-1])?$this->loginId:'')]);

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }
}
?>
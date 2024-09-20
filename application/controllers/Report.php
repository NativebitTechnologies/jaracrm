<?php
class Report extends MY_Controller{

    public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Reports";
		$this->data['headData']->controller = "report";    
        $this->data['headData']->pageUrl = "product";    
    }

    public function inactivePartyAnalysis(){
        $result = $this->report->getInactivePartyDetail(['limit'=>100]);
        print_r($result);exit;
    }

}
?>
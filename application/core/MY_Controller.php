<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );
class MY_Controller extends CI_Controller{
	public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $empRole = ["1"=>"Admin","2"=>"Production Manager","3"=>"Accountant","4"=>"Sales Manager","9" => "Sale Executive","5"=>"Purchase Manager","6"=>"Employee"/* ,"7"=>"Client" *//* ,"8"=>"Driver" */,"10"=>"ASM"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];

	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];

	public $logTitle = ['','CONGRATULATIONS!','FOLLOW UP','REMINDER','ENQUIRY','QUOTATION','ORDER','Ohh..No ! We Lost..&#128542;','Won','QUOTATION REQUEST','EXECUTIVE ASSIGNED','REOPEN LEAD','INACTIVE','ACTIVE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE','STATUS CHANGE'];

	public $iconClass = ['','las la-check-circle bg-soft-success','fas fa-comment-dots bg-soft-info','far fa-bell bg-soft-danger','fas fa-question-circle bg-soft-primary','fas fa-file-alt bg-soft-info','mdi mdi-cart-plus bg-soft-success','fas fa-frown bg-soft-dark','fas fa-hand-peace bg-soft-success','far fa-registered bg-soft-success','fas fa-user-check bg-soft-success','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info','fas fa-comment-dots bg-soft-info'];

    public function __construct(){
		parent::__construct();
		//echo '<br><br><hr><h1 style="text-align:center;color:red;">We are sorry!<br>Your ERP is Updating New Features</h1><hr><h2 style="text-align:center;color:green;">Thanks For Co-operate</h1>';exit;
		$this->isLoggedin();
		$this->data['headData'] = new StdClass;
		$this->data['DT_TABLE'] = false;
		$this->data['MAX_LEAD_STAGE'] = $this->MAX_LEAD_STAGE = 10; // Including New, Won & Lost
		$this->load->library('form_validation');
		
		$this->load->model('masterModel');
		$this->load->model('DashboardModel','dashboard');
		$this->load->model('PermissionModel','permission');
		
		/* User Models */
		$this->load->model('usersModel','usersModel');

		/* Configration Models */
		$this->load->model('ConfigurationModel','configuration');
		$this->load->model("SalesExpenseMaster",'salesExpense');

		/* Party Master Models */
		$this->load->model('PartyModel','party');

		/* Item Master Models */
		$this->load->model('ProductModel','product');

		/* Sales Model */
		$this->load->model("SalesOrderModel",'salesOrder');
		//$this->load->model('SalesModel','sales');

		/* Service Model */
		//$this->load->model('ServiceModel','service');

		/* Expense Manager Model */
		//$this->load->model('ExpenseModel','expense');

		/* Meeting & Event Model */
		//$this->load->model('meetingModel','meeting');

		/* Master Model */
		$this->load->model('TransactionMainModel','transMainModel');
		//$this->load->model('LocationLogModel','locationLog');
		//$this->load->model('VisitModel','visit'); 

		$this->setSessionVariables(["masterModel","dashboard","permission","party","product","configuration","salesOrder","salesExpense","transMainModel"]);

		//$this->data['companyList'] = $this->masterModel->getCompanyList();
	}
	
	public function setSessionVariables($modelNames){

		$this->loginId = $this->data['loginId'] = $this->session->userdata('loginId');
		$this->userName = $this->data['userName'] = $this->session->userdata('user_name');
		$this->userRole = $this->data['userRole'] = $this->session->userdata('role');
		$this->userRoleName = $this->data['userRoleName'] = $this->session->userdata('roleName');

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);

			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
		endforeach;
		return true;
	}
	
	public function isLoggedin(){
		if(!$this->session->userdata("loginId")):
			echo '<script>window.location.href="'.base_url().'";</script>';
		endif;
		return true;
	}
	
	public function printJson($data){
		print json_encode($data);exit;
	}
	
	public function checkGrants($url){
		$empPer = $this->session->userdata('emp_permission');
		if(!array_key_exists($url,$empPer)):
			redirect(base_url('error_403'));
		endif;
		return true;
	}

	public function callcURL($param = []){
	    $response = new StdClass;
	    if(isset($param['callURL']) AND (!empty($param['callURL'])))
	    {
    	    $curl = curl_init();
    
            curl_setopt_array($curl, array(
              CURLOPT_URL => $param['callURL'],
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
	    }
        return $response;
	}

	public function getMonthListFY(){
		$monthList = array();
		$start    = (new DateTime($this->startYearDate))->modify('first day of this month');
        $end      = (new DateTime($this->endYearDate))->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $i=0;
        foreach ($period as $dt) {
            $monthList[$i]['val'] = $dt->format("Y-m-d");
            $monthList[$i++]['label'] = $dt->format("F-Y");
        }
		return $monthList;
	}

	public function getCityList(){
		$data = $this->input->post();
		$result = $this->party->getCityList($data);
		$this->printJson($result);
	}

	public function getVoucherSeries($postData=[]){
		$data = (!empty($postData))?$postData:$this->input->post();

		$condition = "";
		if(!empty($data['dateColumn'])):
			$data['entry_date'] = (!empty($data['entry_date']))?$data['entry_date']:date("Y-m-d");
			$fyDates = getFinDates($data['entry_date']);
			$condition = $data['dateColumn']." >= '".$fyDates[0]."' AND ".$data['dateColumn']." <= '".$fyDates[1]."'";
		else:
			$condition = $data['condition'];
		endif;

		$vsData = [
			'tableName' => $data['tableName'],
			'vou_name_s' => $data['vou_name_s'],
			'numberColumn' => $data['numberColumn'],
			'condition' => $condition
		];
		$result = $this->transMainModel->getVouNumber($vsData);
		
		if(!empty($postData)):
			return $result;
		else:
			$this->printJson(['status'=>1,'data'=>$result]);
		endif;
	}

	public function getPartyDetails(){
		$data = $this->input->post();
		$partyDetail = $this->party->getParty($data);
		$this->printJson(['status'=>1,'data'=>['partyDetail'=>$partyDetail]]);
	}

	public function getItemDetails(){
		$data = $this->input->post();
		$itemDetail = $this->product->getProductList($data);
		$this->printJson(['status'=>1,'data'=>['itemDetail'=>$itemDetail]]);
	}

	public function getItemOrderUnits(){
		$data = $this->input->post();
		$itemDetail = $this->product->getProductList(['id'=>$data['item_id']]);

		$options = '<option value="">Select Order Unit</option><option value="'.$itemDetail->unit_name.'">'.$itemDetail->unit_name.'</option>';

		$this->printJson(['status'=>1,'data'=>['orderUnitList'=>$options]]);
	}
}
?>
<?php 
defined( 'BASEPATH' )OR exit( 'No direct script access allowed' );

header('Content-Type:application/json');
if (isset($_SERVER['HTTP_ORIGIN'])):
    header("Access-Control-Allow-Origin:*");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
endif;

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'):
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE,OPTIONS");
    
	if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers:{$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
endif;

class MY_ApiController extends CI_Controller{
    public $termsTypeArray = ["Purchase","Sales"];
	public $gstPer = ['0'=>"NILL",'0.10'=>'0.10 %','0.25'=>"0.25 %",'1'=>"1 %",'3'=>"3%",'5'=>"5 %","6"=>"6 %","7.50"=>"7.50 %",'12'=>"12 %",'18'=>"18 %",'28'=>"28 %"];
	public $empRole = [1 => "Admin", 2 => "Employee", 3 => "Customer"];
    public $gender = ["M"=>"Male","F"=>"Female","O"=>"Other"];

	public $gstRegistrationTypes = [1=>'Registerd',2=>'Composition',3=>'Overseas',4=>'Un-Registerd'];

	public $iconClass = ['','check_circle','bell','message_circle','help_circle','','file_text','shoping_bag','user_close','user_check','smile','sad_face','refresh'];

	public $iconColor = ['','bg-success','bg-danger','bg-info','bg-primary','','bg-warning','bg-success','bg-tumblr','bg-skype','bg-green','bg-dark','bg-secondry'];

	public $reminderModes = ['Phone','Email','Visit','Whatsapp','Other'];

    public function __construct(){
        parent::__construct();
        $this->checkAuth();

        $this->data['headData'] = new StdClass;

        //Load Defualt Library
        $this->load->library('form_validation');

        //Load Models
        $this->load->model('masterModel');
        $this->load->model('PermissionModel','permission');

        /* User Models */
		$this->load->model('userMasterModel','user');
		$this->load->model('EmployeeModel','employee');

		/* Configration Models */
		$this->load->model('ConfigurationModel','configuration');
		$this->load->model("SalesExpenseMaster",'salesExpense');
		$this->load->model("AddressDetailModel",'address');

        /* Party Master Models */
		$this->load->model('PartyModel','party');

        /* Item Master Models */
		$this->load->model('ProductModel','product');

		/* Sales Model */
		$this->load->model("SalesEnquiryModel",'salesEnquiry');
		$this->load->model("SalesQuotationModel",'salesQuotation');
		$this->load->model("SalesOrderModel",'salesOrder');
		$this->load->model("VisitModel",'visit');

        /* Expense Manager Model */
		$this->load->model('ExpenseModel','expense');

        /* Master Model */
		$this->load->model('TransactionMainModel','transMainModel');

        /* Report Models */
		$this->load->model("ReportModel","report");

        $this->setSessionVariables(["masterModel","permission","user","employee","configuration","salesExpense","address","party","product","salesEnquiry","salesQuotation","salesOrder","visit","expense","transMainModel","report"]);
    }

    public function setSessionVariables($modelNames){
        $headData = json_decode(base64_decode($this->input->get_request_header('sign')));

		$fyDate = getFinDates(date('Y-m-d'));
		$this->startYearDate = $fyDate[0];
		$this->endYearDate = $fyDate[1];

		$this->loginId = $headData->loginId;
		$this->userName = $headData->user_name;
		$this->userRole = $headData->role;
		$this->userRoleName = $headData->roleName;
		$this->superAuth = $headData->superAuth;
		$this->authId = $headData->authId;
		$this->zoneId = $headData->zoneId;
		$this->leadRights = $headData->leadRights;

		$models = $modelNames;
		foreach($models as $modelName):
			$modelName = trim($modelName);
			$this->{$modelName}->loginId = $this->loginId;
			$this->{$modelName}->userName = $this->userName;
			$this->{$modelName}->userRole = $this->userRole;
			$this->{$modelName}->userRoleName = $this->userRoleName;
			$this->{$modelName}->superAuth = $this->superAuth;
			$this->{$modelName}->authId = $this->authId;
    		$this->{$modelName}->zoneId = $this->zoneId;
    		$this->{$modelName}->leadRights = $this->leadRights;
		endforeach;

		return true;
	}

    public function checkAuth(){
        if($token = $this->input->get_request_header('authToken')):
            $this->load->model('LoginModel','loginModel');
            $result = $this->loginModel->checkToken($token);

            if($result == 0):
                $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
            endif;

            if(!$this->input->get_request_header('sign')):
                $this->printJson(['status'=>0,'message'=>"Sign not found.",'data'=>null],401);
            endif;

            return true;  
        else:
            $this->printJson(['status'=>0,'message'=>"Unauthorized",'data'=>null],401);
        endif;
    }

    public function printJson($response,$headerStatus=200){
        $this->output->set_status_header($headerStatus)->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
        exit;
	}

    
}
?>
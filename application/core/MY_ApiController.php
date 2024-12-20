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

    public function callcURL($param = []){
	    $response = new StdClass;
	    if(isset($param['callURL']) AND (!empty($param['callURL']))):
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
	    endif;
        return $response;
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

	public function trashFiles(){
        /** define the directory **/
        $dirs = [
            realpath(APPPATH . '../assets/uploads/temp_files/')
        ];

        foreach($dirs as $dir):
            $files = array();
            $files = scandir($dir);
            unset($files[0],$files[1]);

            /*** cycle through all files in the directory ***/
            foreach($files as $file):
                /*** if file is 24 hours (86400 seconds) old then delete it ***/
                if(time() - filectime($dir.'/'.$file) > 86400):
                    unlink($dir.'/'.$file);
                    //print_r(filectime($dir.'/'.$file)); print_r("<hr>");
                endif;
            endforeach;
        endforeach;

        return true;
    }

}
?>
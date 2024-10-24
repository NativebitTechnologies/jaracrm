<?php
class Attendance extends MY_ApiController{	

	public function __construct(){
		parent::__construct();
        $this->data['headData']->pageTitle = "Attendance";
        $this->data['headData']->pageUrl = "api/attendance";
        $this->data['headData']->base_url = base_url();
	}
	
	public function getEmployeeDetail(){
        $this->data['employeeDetail'] = $this->employee->getEmployeeData();
        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function getAttendanceList(){
        $data = $this->input->post();
        $logData = $this->employee->getEmpLogData($data);

        $sendData = []; $i=($data['start'] + 1);
        foreach($logData as $row):
            $sendData[] = [
               'type'  => $row->type,
               'punch_date' => $row->punch_date
            ];
            $i++;
        endforeach;

        $this->printJson(['status'=>1,'data'=>['dataList'=>$sendData]]);
    }

	public function saveAttendance(){
        $data = $this->input->post();

        if(!empty($_FILES['img_file'])):
            if($_FILES['img_file']['name'] != null || !empty($_FILES['img_file']['name'])):
                $this->load->library('upload');
                $_FILES['userfile']['name']     = $_FILES['img_file']['name'];
                $_FILES['userfile']['type']     = $_FILES['img_file']['type'];
                $_FILES['userfile']['tmp_name'] = $_FILES['img_file']['tmp_name'];
                $_FILES['userfile']['error']    = $_FILES['img_file']['error'];
                $_FILES['userfile']['size']     = $_FILES['img_file']['size'];
                
                $imagePath = realpath(APPPATH . '../assets/uploads/attendance_log/');
                $config = ['file_name' => $this->loginId."_".$data['type']."_".$_FILES['userfile']['name'],'allowed_types' => '*','max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

                $this->upload->initialize($config);
                if (!$this->upload->do_upload()):
                    $errorMessage['img_file'] = $this->upload->display_errors();
                    $this->printJson(["status"=>0,"message"=>$errorMessage]);
                else:
                    $uploadData = $this->upload->data();
                    $data['img_file'] = $uploadData['file_name'];
                endif;
            endif;
        endif;

        $data['emp_id'] = $this->loginId;
        $data['punch_date'] = date("Y-m-d H:i:s");
        $data['start_at'] = date("Y-m-d H:i:s");

        $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : "");
        unset($data['s_lat'],$data['s_lon']);
        $data['loc_add']='';

        if(!empty($data['start_location'])):
		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
		    $add = (!empty($add) ? json_decode($add) : new StdClass);
		    $data['loc_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
		endif;

        $this->printJson($this->employee->saveAttendance($data));
    }
}
?>
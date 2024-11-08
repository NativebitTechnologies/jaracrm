<?php
class Visit extends MY_ApiController{

    public function __construct(){
        parent::__construct();        
        $this->data['headData']->pageTitle = "Visit";
        $this->data['headData']->pageUrl = "api/visit";
        $this->data['headData']->base_url = base_url();
    }

    public function getVisitListing(){
        $data = $this->input->post();
        $data['sales_executive'] = $this->loginId;
        $visistList = $this->visit->getVisitList($data);

        $sendData = [];$i=($data['start'] + 1);
        foreach($visistList as $row):
            $duration = 0;
            if(!empty($row->end_at)):
                $d1 = new DateTime($row->start_at);
                $d2 = new DateTime($row->end_at);
                $interval = $d1->diff($d2);
                $diffInSeconds = $interval->s;
                $diffInMinutes = $interval->i; 
                $diffInHours   = $interval->h;
                $duration=($diffInHours*60)+$diffInMinutes+($diffInSeconds/60);
            endif;

            $sendData[] = [
                'id' => $row->id,
                'party_id' => $row->party_id,
                'party_name' => $row->party_name,
                'contact_person' => $row->contact_person,
                'purpose' => $row->purpose,
                'start_at' => $row->start_at,
                'end_at' => $row->end_at,
                'lead_stage' => $row->lead_stage,
                'duration' => $duration,
                'voice_notes' => (!empty($row->voice_notes))?base_url("assets/uploads/voice_notes/".$row->voice_notes):""
            ];
            $i++;
        endforeach;
        $this->printJson(['status'=>1,'data'=>['dataList'=>$sendData]]);
    }

    public function addVisit(){
        $this->data['partyList'] = $this->party->getPartyList();
        $this->data['leadStages'] =$this->configuration->getLeadStagesList();
        $this->data['startVisit'] = $this->visit->getVisitList(['visit_status'=>1,'sales_executive'=>$this->loginId,'single_row'=>1]);

        $this->printJson(['status'=>1,'message'=>'Data Found.','data'=>$this->data]);
    }

    public function saveVisit(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['party_id']))
            $errorMessage['party_id'] = "Party name is required.";
        if(empty($data['contact_person']))
            $errorMessage['contact_person'] = "Contact Person is required.";
        if(empty($data['purpose']))
            $errorMessage['purpose'] = "Purpose is required.";

        $startVisit = $this->visit->getVisitList(['visit_status'=>1,'sales_executive'=>$this->loginId,'single_row'=>1]);
        if(!empty($startVisit))
            $errorMessage['general_error'] = "You can not save visit, You have to complete your runnig visit";

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['start_at'] = date("Y-m-d H:i:s");
            $data['start_location'] = ((!empty($data['s_lat']) AND !empty($data['s_lon'])) ? $data['s_lat'].','.$data['s_lon'] : NULL);
            unset($data['s_lat'],$data['s_lon']);

            if(!empty($data['start_location'])):
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['start_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['s_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		endif;

            $this->printJson($this->visit->save($data));
        endif;
    }

    public function saveEndVisit(){
        $data = $this->input->post();
        $errorMessage = array();
        if(empty($data['discussion_points'])):
            $errorMessage['discussion_points'] = "Discussion Point is required.";
        endif;

        if($data['next_visit'] == 'Yes'):
            if(empty($data['reminder_date'])): $errorMessage['reminder_date'] = "Reminder Date is required."; endif;
            if(empty($data['reminder_time'])):$errorMessage['reminder_time'] = "Reminder Time is required."; endif;
            if(empty($data['reminder_note'])):$errorMessage['reminder_note'] = "Reminder Note is required."; endif;
        endif;

        if(isset($_FILES['voice_notes'])):
			if($_FILES['voice_notes']['name'] != null || !empty($_FILES['voice_notes']['name'])):
				$this->load->library('upload');
				$_FILES['userfile']['name']     = $_FILES['voice_notes']['name'];
				$_FILES['userfile']['type']     = $_FILES['voice_notes']['type'];
				$_FILES['userfile']['tmp_name'] = $_FILES['voice_notes']['tmp_name'];
				$_FILES['userfile']['error']    = $_FILES['voice_notes']['error'];
				$_FILES['userfile']['size']     = $_FILES['voice_notes']['size'];
				
				$imagePath = realpath(APPPATH . '../assets/uploads/voice_notes/');
                $visitDetail = $this->visit->getVisit($data['id']);
                $ext = pathinfo($_FILES['voice_notes']['name'], PATHINFO_EXTENSION);
				$config = ['file_name' => $visitDetail->party_id."_".date("Y_m_d_H_i_s").".".$ext, 'allowed_types' => '*', 'max_size' => 10240,'overwrite' => FALSE, 'upload_path' => $imagePath];

				$this->upload->initialize($config);
				if (!$this->upload->do_upload()):
					$errorMessage['voice_notes'] = $this->upload->display_errors();
				else:
					$uploadData = $this->upload->data();
					$data['voice_notes'] = $uploadData['file_name'];
				endif;
			endif;
		endif;
      
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            //$data['id'] = $data['main_id'];
            $data['end_at'] = date("Y-m-d H:i:s");
            $data['end_location'] = ((!empty($data['e_lat']) AND !empty($data['e_lon'])) ? $data['e_lat'].','.$data['e_lon'] : NULL);
            $data['updated_by'] = $this->loginId;
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            unset($data['e_lat'],$data['e_lon'],$data['main_id']);
            $data['e_add']='';
            if(!empty($data['end_location'])):
    		    $add = $this->callcUrl(['callURL'=>'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$data['end_location'].'&key='.GMAK]);
    		    $add = (!empty($add) ? json_decode($add) : new StdClass);
    		    $data['e_add'] = (isset($add->results[0]->formatted_address) ? $add->results[0]->formatted_address : "");
    		endif;

            $this->printJson($this->visit->saveEndVisit($data));
        endif;
    }
}
?>
<?php
class Report extends MY_Controller{
    private $indexPage = "reports/index";
  
	public function __construct(){
		parent::__construct();
		$this->isLoggedin();
		$this->data['headData']->pageTitle = "Report List";
		$this->data['headData']->controller = "report";
	}
	
	public function index(){          
        $this->data['pageHeader'] ='Reports';
        $this->data['permission'] = $this->permission->getEmployeeReportMenus();
        $this->load->view($this->indexPage,$this->data);
    }

    public function inactivePartyAnalysis(){
        $this->data['headData']->pageTitle = "Report / Sales / Inactive Party Analysis";
        $this->data['headData']->pageUrl = "report/inactivePartyAnalysis";
        $this->data['DT_TABLE'] = true;
        $this->data['executiveList'] = $this->employee->getEmployeeDetails();
        $this->load->view("reports/inactive_party_analysis",$this->data);
    }

    public function getInactivePartyList(){
        $data = $this->input->post();
        $result = $this->report->getInactivePartyDetail($data);

        $responseHtml = "";$i=($data['start'] + 1);
        foreach($result as $row):
            $responseHtml .= '<tr>
                <td> '.$i.' </td>
                <td>'.$row->party_code.'</td>
                <td>'.$row->party_name.'</td>
                <td>'.$row->business_type.'</td>
                <td>'.$row->contact_person.'</td>
                <td>'.$row->contact_no.'</td>
                <td>'.$row->executive_name.'</td>
                <td>'.$row->state.', '.$row->district.', '.$row->city.'</td>
            </tr>';

            $i++;
        endforeach;
        $this->printJson(['status'=>1,'dataList'=>$responseHtml,'totalRecords'=>0]);
    }

    public function partyBudgetAnalysis(){
        $this->data['headData']->pageTitle = "Report / Sales / Party Budget Analysis";
        $this->data['headData']->pageUrl = "report/inactivePartyAnalysis";
        $this->data['DT_TABLE'] = true;
        $this->data['executiveList'] = $this->employee->getEmployeeDetails();
        $this->load->view("reports/party_budget_analysis",$this->data);
    }

    public function getPartyBudgetDetails(){
        $data = $this->input->post();
        $data['from_date'] = $this->startYearDate;
        $data['to_date'] = $this->endYearDate;

        $result = $this->report->getPartyBudgetAnalysis($data);
        $reportType = (!empty($data['filters']['report_type']))?$data['filters']['report_type']:1;

        $monthColumn = $monthSubColumn = "";
        $monthList = $this->getMonthListFY();
        if($reportType == 2):
            foreach($monthList as $row):
                $monthColumn .= '<th colspan="3" class="text-center">'.date("M",strtotime($row['label'])).'</th>';

                $monthSubColumn .= '<th>Taxable<br>Amount</th>';
                $monthSubColumn .= '<th>Monthly<br>Budget</th>';
                $monthSubColumn .= '<th>Per (%)</th>';
            endforeach;
        endif;

        $responseHeader = '<tr>';
            $responseHeader .= '<th rowspan="2">Party Name</th>';
            $responseHeader .= '<th rowspan="2">Contact No.</th>';
            $responseHeader .= '<th rowspan="2">Address</th>';
            $responseHeader .= '<th rowspan="2">Sales Executive</th>';
            $responseHeader .= $monthColumn;
            $responseHeader .= '<th colspan="3" class="text-center">Total</th>';
        $responseHeader .= '</tr>';

        
        $responseHeader .= '<tr>';
            $responseHeader .= $monthSubColumn;
            $responseHeader .= '<th>Total Taxable<br>Amount</th>';
            $responseHeader .= '<th>Total<br>Budget</th>';
            $responseHeader .= '<th>Per (%)</th>';
        $responseHeader .= '</tr>';

        $responseHtml = "";

        $groupedResult = array_reduce($result, function($itemData, $row) {
            $taxableAmount = round($row->taxable_amount,0);
            $monthlyCapacity = round(($row->business_capacity / 12),0);

            if(isset($itemData[$row->party_id])):
                $itemData[$row->party_id]['monthData'][date("Y-m",strtotime($row->month))] = [
                    'taxable_amount' => $taxableAmount,
                    'monthly_capacity' => $monthlyCapacity,
                    'per' => ($taxableAmount > 0 && $monthlyCapacity > 0)?round((($taxableAmount * 100) / $monthlyCapacity),0):0
                ];
            else:
                $itemData[$row->party_id] = [
                    'party_name' => $row->party_name,
                    'contact_no' => $row->contact_no,
                    'address' => $row->state.', '.$row->district.', '.$row->city,
                    'business_capacity' => $row->business_capacity,
                    'executive_name' => $row->executive_name,
                    'monthData' => [
                        date("Y-m",strtotime($row->month)) => [
                           'taxable_amount' => $taxableAmount,
                            'monthly_capacity' => $monthlyCapacity,
                            'per' => ($taxableAmount > 0 && $monthlyCapacity > 0)?round((($taxableAmount * 100) / $monthlyCapacity),0):0                 
                        ]
                    ]
                ];
            endif;

            return $itemData;
        }, []); 

        foreach($groupedResult as $row):
            $responseHtml .= '<tr>';
                $responseHtml .= '<td>'.$row['party_name'].'</td>';
                $responseHtml .= '<td>'.$row['contact_no'].'</td>';
                $responseHtml .= '<td>'.$row['address'].'</td>';
                $responseHtml .= '<td>'.$row['executive_name'].'</td>';

                $totalTaxableAmt = $businessCapacity = $taxableAmount = $monthlyCapacity = $per = 0;
                foreach($monthList as $monthRow):
                    $month = date("Y-m",strtotime($monthRow['label']));
                    $taxableAmount = (!empty($row['monthData'][$month]['taxable_amount']))?$row['monthData'][$month]['taxable_amount']:0;
                    $monthlyCapacity = (!empty($row['monthData'][$month]['monthly_capacity']))?$row['monthData'][$month]['monthly_capacity']:0;
                    $per = (!empty($row['monthData'][$month]['per']))?$row['monthData'][$month]['per']:0;

                    if($reportType == 2):
                        if(date("Y-m") >= $month):
                            $responseHtml .= '<td>'.$taxableAmount.'</td>';
                            $responseHtml .= '<td>'.$monthlyCapacity.'</td>';
                            $responseHtml .= '<td>'.$per.'</td>';
                        else:
                            $responseHtml .= '<td>-</td>';
                            $responseHtml .= '<td>-</td>';
                            $responseHtml .= '<td>-</td>';

                            $taxableAmount = $monthlyCapacity = 0;
                        endif;
                    endif;

                    $totalTaxableAmt += $taxableAmount;
                    $businessCapacity += $monthlyCapacity;
                endforeach;
                
                $totalBudget = $row['business_capacity'];
                $avgPer = ($totalTaxableAmt > 0 && $totalBudget > 0)?round((($totalTaxableAmt * 100) / $totalBudget),0):0;

                $responseHtml .= '<td>'.$totalTaxableAmt.'</td>';
                $responseHtml .= '<td>'.$totalBudget.'</td>';
                $responseHtml .= '<td>'.$avgPer.'</td>';

            $responseHtml .= '</tr>';
        endforeach;

        $this->printJson(['status'=>1,'dataHeader' => $responseHeader,'dataList'=>$responseHtml,'totalRecords'=>0]);
    }
}
?>
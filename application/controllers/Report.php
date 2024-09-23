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

    public function getInactivePartyList($jsonData = ""){
        $data = (!empty($jsonData))?decodeUrl($jsonData,true):$this->input->post();
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
                <td>'.$row->inactive_days.'</td>
                <td>'.formatDate($row->last_activity_date,'d-m-Y h:i:s A').'</td>
            </tr>';

            $i++;
        endforeach;

        if(empty($jsonData)):
            $this->printJson(['status'=>1,'dataList'=>$responseHtml,'totalRecords'=>0]);
        else:
            $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
            $logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
			$logo = base_url('assets/images/' . $logoFile);

            $thead = '<tr>
                <th>#</th>
                <th>Party Code</th>
                <th>Party Name</th>
                <th>Business Type</th>
                <th>Contact Person</th>
                <th>Contact No.</th>
                <th>Sales Executive</th>
                <th>Address</th>
                <th>Inactive Days</th>
                <th>Last Activity Date</th>
            </tr>';

            $pdfData = '<table id="commanTable" class="table table-bordered item-list-bb" repeat_header="1">
                <thead class="thead-dark" id="theadData">'.$thead.'</thead>
                <tbody id="receivableData">'.$responseHtml.'</tbody> 
            </table>';

            $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:10px;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">Date : '.date('d-m-Y').'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">Inactive Party Details</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>';  

			$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';

            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'inactive_party_details.pdf';
            $stylesheet = file_get_contents(base_url('assets/src/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('P','','','','',5,5,35,15,5,5,'','','','','','','','','','A4-P');
            $mpdf->WriteHTML($pdfData);
            $mpdf->Output($pdfFileName, 'I');	
        endif;
    }

    public function partyBudgetAnalysis(){
        $this->data['headData']->pageTitle = "Report / Sales / Party Budget Analysis";
        $this->data['headData']->pageUrl = "report/inactivePartyAnalysis";
        $this->data['DT_TABLE'] = true;
        $this->data['executiveList'] = $this->employee->getEmployeeDetails();
        $this->load->view("reports/party_budget_analysis",$this->data);
    }

    public function getPartyBudgetDetails($jsonData = ""){
        $data = (!empty($jsonData))?decodeUrl($jsonData,true):$this->input->post(); 

        $data['from_date'] = $this->startYearDate;
        $data['to_date'] = $this->endYearDate;

        $result = $this->report->getPartyBudgetAnalysis($data);
        $reportType = (!empty($data['filters']['report_type']))?$data['filters']['report_type']:1;

        $monthColumn = $monthSubColumn = "";
        $monthList = $this->getMonthListFY();
        if($reportType == 2):
            foreach($monthList as $row):
                $monthColumn .= '<th colspan="3" class="text-center">'.date("M",strtotime($row['label'])).'</th>';

                $monthSubColumn .= '<th>Taxa.<br>Amt.</th>';
                $monthSubColumn .= '<th>Budget</th>';
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
            $responseHeader .= '<th>Taxa.<br>Amt.</th>';
            $responseHeader .= '<th>Budget</th>';
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
        
        if(empty($jsonData)):
            $this->printJson(['status'=>1,'dataHeader' => $responseHeader,'dataList'=>$responseHtml,'totalRecords'=>0]);
        else:
            $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
            $logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
			$logo = base_url('assets/images/' . $logoFile);

            $pdfData = '<table id="commanTable" class="table table-bordered item-list-bb" repeat_header="1">
                <thead class="thead-dark" id="theadData">'.$responseHeader.'</thead>
                <tbody id="receivableData">'.$responseHtml.'</tbody> 
            </table>';

            $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:10px;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">Date : '.date('d-m-Y').'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">Party Budget Analysis</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>';  

			$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';

            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'party_budget_analysis.pdf';
            $stylesheet = file_get_contents(base_url('assets/src/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('P','','','','',5,5,35,15,5,5,'','','','','','','','','','A4-L');
            $mpdf->WriteHTML($pdfData);
            $mpdf->Output($pdfFileName, 'I');	
        endif;
    }

    public function unsoldProducts(){
        $this->data['headData']->pageTitle = "Report / Sales / Unsold Products";
        $this->data['headData']->pageUrl = "report/unsoldProducts";
        $this->data['DT_TABLE'] = true;
        $this->data['categoryList'] = $this->product->getCategoryList(['category_type'=>1,'final_category'=>1]);
        $this->load->view("reports/unsold_products",$this->data);
    }

    public function getUnsoldProductList($jsonData = ""){
        $data = (!empty($jsonData))?decodeUrl($jsonData,true):$this->input->post();
        $result = $this->report->getUnsoldProductsDetails($data);
        
        $responseHtml = "";$i=($data['start'] + 1);
        foreach($result as $row):
            $responseHtml .= '<tr>
                <td> '.$i.' </td>
                <td>'.$row->item_code.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->category_name.'</td>
                <td>'.$row->hsn_code.'</td>
                <td>'.$row->gst_per.'</td>
                <td>'.$row->price.'</td>
                <td>'.$row->mrp.'</td>
                <td>'.$row->unsold_days.'</td>
                <td>'.formatDate($row->last_sold_date,'d-m-Y').'</td>
            </tr>';

            $i++;
        endforeach;

        if(empty($jsonData)):
            $this->printJson(['status'=>1,'dataList'=>$responseHtml,'totalRecords'=>0]);
        else:
            $this->data['companyData'] = $companyData = $this->masterModel->getCompanyInfo();
            $logoFile = (!empty($companyData->company_logo)) ? $companyData->company_logo : 'logo.png';
			$logo = base_url('assets/images/' . $logoFile);

            $thead = '<tr>
                <th>#</th>
                <th>Product code</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>HSN Code</th>
                <th>GST(%)</th>
                <th>Price <small>(Exc. Tax)<small></th>
                <th>MRP <small>(Inc. Tax)<small></th>
                <th>Unsold Days</th>
                <th>Last Sold Date</th>
            </tr>';

            $pdfData = '<table id="commanTable" class="table table-bordered item-list-bb" repeat_header="1">
                <thead class="thead-dark" id="theadData">'.$thead.'</thead>
                <tbody id="receivableData">'.$responseHtml.'</tbody> 
            </table>';

            $htmlHeader = '<table class="table" style="border-bottom:1px solid #036aae;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%"></td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">'.$companyData->company_name.'</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:2px;">
                <tr><td class="org-address text-center" style="font-size:13px;">'.$companyData->company_address.'</td></tr>
            </table>
            <table class="table" style="border-bottom:1px solid #036aae;margin-bottom:10px;">
                <tr>
                    <td class="org_title text-uppercase text-left" style="font-size:1rem;width:30%">Date : '.date('d-m-Y').'</td>
                    <td class="org_title text-uppercase text-center" style="font-size:1.3rem;width:40%">Unsold Product Details</td>
                    <td class="org_title text-uppercase text-right" style="font-size:1rem;width:30%"></td>
                </tr>
            </table>';  

			$htmlFooter = '<table class="table top-table" style="margin-top:10px;border-top:1px solid #545454;">
                <tr>
                    <td style="width:50%;font-size:12px;">Printed On ' . date('d-m-Y') . '</td>
                    <td style="width:50%;text-align:right;font-size:12px;">Page No. {PAGENO}/{nbpg}</td>
                </tr>
            </table>';

            $mpdf = new \Mpdf\Mpdf();
            $pdfFileName = 'unsold_product_details.pdf';
            $stylesheet = file_get_contents(base_url('assets/src/pdf_style.css'));
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->SetWatermarkImage($logo, 0.08, array(120, 120));
            $mpdf->showWatermarkImage = true;
            $mpdf->SetHTMLHeader($htmlHeader);
            $mpdf->SetHTMLFooter($htmlFooter);
            $mpdf->AddPage('P','','','','',5,5,35,15,5,5,'','','','','','','','','','A4-P');
            $mpdf->WriteHTML($pdfData);
            $mpdf->Output($pdfFileName, 'I');	
        endif;
    }
}
?>
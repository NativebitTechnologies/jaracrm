<?php
class Product extends MY_Controller{
    private $index = "product/index";
    private $form = "product/form";
	
	public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Product";
		$this->data['headData']->controller = "product";    
        $this->data['headData']->pageUrl = "product";    
    }
	
	public function index(){
        $this->load->view($this->index,$this->data); 
    }
	
	public function getProductListing(){
		$data = $this->input->post();
        $productList = $this->product->getProductList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($productList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-lg', 'form_id' : 'editProduct', 'title' : 'Update Product','call_function':'editProduct','fnsave' : 'saveProduct'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Product','fndelete':'deleteProduct'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
		
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->item_code.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->category_name.'</td>
                <td>'.$row->hsn_code.'</td>
                <td>'.$row->gst_per.'</td>
                <td>'.$row->price.'</td>
                <td>'.$row->mrp.'</td>
                <td>
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
							'.$editButton.$deleteButton.'
                        </div>
                    </div>
                </td>
            </tr>';

            $i++;
        endforeach;
        $this->printJson(['status'=>1,'dataList'=>$tbody]);
	}

	public function addProduct(){
        $this->data['gstPer'] = $this->gstPer;
        $this->data['unitList'] = $this->product->getUnitList();
        $this->data['categoryList'] = $this->product->getCategoryList(['category_type'=>1,'final_category'=>1]);
        $this->load->view($this->form,$this->data);
	}

    public function saveProduct(){
        $data = $this->input->post(); 
        $errorMessage = array();
        /*if(empty($data['item_name'])){
            $errorMessage['item_name'] = "Item Name is required.";
        }
        if(empty($data['item_code'])){
            $errorMessage['item_code'] = "Item Code is required.";
        }*/

        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:        
            $this->printJson($this->product->saveProduct($data)); 
        endif;
    }

    public function editProduct(){
        $data = $this->input->post();
        $this->data['gstPer'] = $this->gstPer;
        $this->data['unitList'] = $this->product->getUnitList();
        $this->data['categoryList'] = $this->product->getCategoryList(['category_type'=>1,'final_category'=>1]);
        $this->data['dataRow'] = $this->product->getProductList($data);
        $this->load->view($this->form,$this->data);
    }

    public function deleteProduct(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->product->deleteProduct($data));
        endif;
    }
}
?>
	
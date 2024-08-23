<?php
class Product extends MY_Controller{
    private $index = "product/index";
    private $form = "product/form";
    private $productDetailForm = "product/product_detail_form";
	private $category_index = "product/category_index";
	private $category_form = "product/category_form";
	
	public function __construct(){
        parent::__construct();
		$this->data['headData']->pageTitle = "Product";
		$this->data['headData']->controller = "product";    
        $this->data['headData']->pageUrl = "product";    
    }
	
	/********** Item Master **********/
	public function index(){
        $this->data['DT_TABLE'] = true;
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

            if($row->is_temp_item == 0):
                $row->item_status = '<span class="badge bg-success text-dark flex-fill">'.$row->item_status.'</span>';
            else:
                $row->item_status = '<span class="badge bg-info text-dark flex-fill">'.$row->item_status.'</span>';
            endif;

            $productDetailParam = "{'postData':{'item_id' : ".$row->id."},'modal_id' : 'modal-md', 'call_function':'updateProductDetail', 'fnsave' : 'saveProductDetail', 'form_id' : 'productDetailForm', 'title' : 'Update Product Detail'}";
            $productDetailButton = '<a class="dropdown-item" href="javascript:void(0);" onclick="modalAction('.$productDetailParam.');">'.getIcon('plus').' Product Detail</a>';
		
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$row->item_code.'</td>
                <td>'.$row->item_name.'</td>
                <td>'.$row->category_name.'</td>
                <td>'.$row->hsn_code.'</td>
                <td>'.$row->gst_per.'</td>
                <td>'.$row->price.'</td>
                <td>'.$row->mrp.'</td>
                <td>'.$row->item_status.'</td>
                <td>
                    <div class="d-inline-block jpdm">
                        <a class="dropdown-toggle" href="#" role="button" id="elementDrodpown3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"></circle><circle cx="19" cy="12" r="1"></circle><circle cx="5" cy="12" r="1"></circle></svg>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="elementDrodpown3" style="will-change: transform;">
							'.$editButton.$deleteButton.$productDetailButton.'
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

    public function updateProductDetail(){
        $data = $this->input->post();
        $this->data['item_id'] = $data['item_id'];
        $this->data['dataRow'] = $this->product->getItemUdfData($data);
        $this->data['customFieldList'] = $this->configuration->getCustomFieldList(['type'=>1]);
        $this->data['masterDetailList'] = array();
        $this->load->view($this->productDetailForm,$this->data);
    }

    public function saveProductDetail(){
        $data = $this->input->post();
        $this->printJson($this->product->saveProductDetails($data));
    }

    public function deleteProduct(){
        $data = $this->input->post();
        if(empty($data['id'])):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->product->deleteProduct($data));
        endif;
    }
	/********** Item Master **********/
	
	/********** Item Category **********/
	public function categoryIndex($ref_id=1){
        $this->data['DT_TABLE'] = true;
		$this->data['ref_id'] = $ref_id;
		
		$parentCategory = $this->product->getCategoryList(['id'=>$ref_id]);
		$this->data['headData']->pageTitle = ((!empty($parentCategory->category_name) && $ref_id != 1)?$parentCategory->category_name:'Product Category');
        $this->data['main_ref_id'] = (isset($parentCategory->ref_id)?$parentCategory->ref_id:1);
		
        $this->load->view($this->category_index,$this->data); 
    }
	
	public function getCategoryListing(){
		$data = $this->input->post();
        $productList = $this->product->getCategoryList($data);

        $tbody = "";$i=($data['start'] + 1);
        foreach($productList as $row):
			$editParam = "{'postData':{'id' : ".$row->id."},'modal_id' : 'modal-md', 'form_id' : 'editCategory', 'title' : 'Update Category','call_function':'editCategory','fnsave' : 'saveCategory'}";
			$editButton = '<a class="dropdown-item permission-modify" href="javascript:void(0)" datatip="Edit" flow="down" onclick="modalAction('.$editParam.');">'.getIcon('edit').' Edit</a>';

			$deleteParam = "{'postData':{'id' : ".$row->id."},'message' : 'Product','fndelete':'deleteCategory'}";
			$deleteButton = '<a class="dropdown-item permission-remove" href="javascript:void(0)" onclick="trash('.$deleteParam.');" datatip="Remove" flow="down">'.getIcon('delete').' Delete</a>';
		
			if($row->final_category == 0){$category_name = '<a href="' . base_url("product/categoryIndex/" . $row->id) . '">' . $row->category_name . '</a>';}
			else{$category_name = $row->category_name;}
            $tbody .= '<tr>
                <td class="checkbox-column"> '.$i.' </td>
                <td>'.$category_name.'</td>
                <td>'.$row->parent_category.'</td>
                <td>'.$row->is_final.'</td>
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

	public function addCategory(){
        $ref_id = $this->input->post('ref_id');
        $this->data['parentCategory'] = $this->product->getCategoryList(['final_category'=>0]);
        $this->data['ref_id'] = $ref_id;
        $this->load->view($this->category_form,$this->data);
    }
	
	public function saveCategory(){
        $data = $this->input->post();
        $errorMessage = array();
		
        if(empty($data['category_name']))
            $errorMessage['category_name'] = "Category is required.";
        if(empty($data['ref_id']))
            $errorMessage['ref_id'] = "Main Category is required.";
        
       
        $nextlevel='';
        if(!empty($data['category_level']) && empty($data['id'])):
            $level = $this->product->getCategoryList(['ref_id'=>$data['ref_id']]);
            $count = count($level);
            $nextlevel = $data['category_level'].'.'.($count+1);
            $data['category_level'] = $nextlevel;
        endif;
        
        if(!empty($errorMessage)):
            $this->printJson(['status'=>0,'message'=>$errorMessage]);
        else:
            $data['created_by'] = $this->loginId;
            $this->printJson($this->product->saveCategory($data));
        endif;
    }

    public function editCategory(){
		$data = $this->input->post();
        $this->data['parentCategory'] = $this->product->getCategoryList(['final_category'=>0]);
        $this->data['dataRow'] = $this->product->getCategoryList(['id'=>$data['id']]);
        $this->load->view($this->category_form,$this->data);
    }
	
	public function deleteCategory(){
        $id = $this->input->post('id');
        if(empty($id)):
            $this->printJson(['status'=>0,'message'=>'Somthing went wrong...Please try again.']);
        else:
            $this->printJson($this->product->deleteCategory($id));
        endif;
    }
	/********** Item Category **********/
}
?>
	
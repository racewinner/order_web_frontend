<?php
namespace App\Controllers;
use App\Models\Presell_Import;

class Presells_import extends Admin_area {
	

	public function __construct()
	{
		parent::__construct();
		
	}

	function index()
	{
		echo view('presells_import');
	}
	
	function load_ref()
	{
		$Presell_Import = new Presell_Import();

		$result = $Presell_Import->select();
		if($result->getNumRows() > 0)
		{
			$ref[] = "";
			$output = '';
			foreach($result->getResult() as $row)
			{
				if(!in_array($row->period_ref, $ref)){
					$ref[] = $row->period_ref;
				    $output .= '<option value="'.$row->period_ref.'">'.$row->period_ref.'</option>';
				}
			}
			echo $output;
		}
	}
	
	function load_data()
	{
		$Presell_Import = new Presell_Import();
		$result = $Presell_Import->select();
		$output = '
		 <h3 align="center">Presell Order Entries in Database</h3>
          <div class="table-responsive">
        	<table class="table table-bordered table-striped">
        		<tr>
        			<th>#</th>
        			<th>Period Ref</th>
        			<th>Unique ID</th>
        			<th>Product Code</th>
        			<th>U 0 S</th>
        			<th>Description</th>
        			<th>Pack Desc.</th>
        			<th>V A T</th>
        			<th>G Desc</th>
        			<th>Prod Code 1</th>
        			<th>Price List</th>
        			<th>L 3</th>
        			<th>Sell</th>
        			<th>RRP</th>
        			<th>Wholesale</th>
        			<th>Retail</th>
        			<th>P Size</th>
        			<th>D</th>
        			<th>P</th>
        			<th>Van</th>
        			<th>Shelf Life</th>
        			<th>G Qty</th>
        			<th>G Min</th>
        			<th>G Max</th>
        			<th>S Qty</th>
        			<th>S Min</th>
        			<th>S Max</th>
        			<th>M Qty</th>
        			<th>M Min</th>
        			<th>M Max</th>
        			<th>L Qty</th>
        			<th>L Min</th>
        			<th>L Max</th>
        			<th>E Qty</th>
        			<th>E Min</th>
        			<th>E Max</th>
        		</tr>
		';
		$count = 0;
		if($result->getNumRows() > 0)
		{
			foreach($result->getResult() as $row)
			{
				$count = $count + 1;
				$output .= '
				<tr>
					<td>'.$count.'</td>
					<td>'.$row->period_ref.'</td>
					<td><a href="javascript:popup_dialog('.$row->prod_id.' , 0)">'.$row->unique_id.'</a></td>
					<td>'.$row->prod_code.'</td>
					<td>'.$row->prod_uos.'</td>
					<td>'.$row->prod_desc.'</td>
					<td>'.$row->prod_pack_desc.'</td>
					<td>'.$row->vat_code.'</td>
					<td>'.$row->group_desc.'</td>
					<td>'.$row->prod_code1.'</td>
					<td>'.$row->price_list.'</td>
					<td>'.$row->prod_level3.'</td>
					<td>'.$row->prod_sell.'</td>
					<td>'.$row->prod_rrp.'</td>
					<td>'.$row->wholesale.'</td>
					<td>'.$row->retail.'</td>
					<td>'.$row->p_size.'</td>
					<td>'.$row->is_disabled.'</td>
					<td>'.$row->promo.'</td>
					<td>'.$row->van.'</td>
					<td>'.$row->shelf_life.'</td>
					<td>'.$row->g_qty.'</td>
					<td>'.$row->g_min.'</td>
					<td>'.$row->g_max.'</td>
					<td>'.$row->s_qty.'</td>
					<td>'.$row->s_min.'</td>
					<td>'.$row->s_max.'</td>
					<td>'.$row->m_qty.'</td>
					<td>'.$row->m_min.'</td>
					<td>'.$row->m_max.'</td>
					<td>'.$row->l_qty.'</td>
					<td>'.$row->l_min.'</td>
					<td>'.$row->l_max.'</td>
					<td>'.$row->e_qty.'</td>
					<td>'.$row->e_min.'</td>
					<td>'.$row->e_max.'</td>
				</tr>
				';
			}
		}
		else
		{
			$output .= '
			<tr>
	    		<td colspan="37" align="center">Data not Available</td>
	    	</tr>
			';
		}
		$output .= '</table></div>';
		echo $output;
	}

	function import()
	{
		$Presell_Import = new Presell_Import();
		$file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
		foreach($file_data as $row)
		{
			if($row["Period Reference"]){
				
				}
			$data[] = array(
				'period_ref'	=>	$row["Period Reference"],
				'unique_id'     =>	$row["Unique ID"],
				'prod_code'	    =>	$row["Code"],
				'prod_uos'	    =>	$row["UOS"],
				'prod_desc'	    =>	$row["Description"],
				'prod_pack_desc'=>	$row["Pack Description"],
				'vat_code'      =>	$row["VAT"],
				'group_desc'    =>	$row["Group Description"],
				'prod_code1'    =>	$row["Code 1"],
				'price_list'    =>	sprintf("%02d", $row["Price List"]),
				'prod_level3'   =>	$row["Level 3"],
				'prod_sell'     =>	$row["Sell"],
				'prod_rrp'      =>	$row["RRP"],
				'wholesale'     =>	intval(floatval($row["Wholesale"])),
				'retail'        =>	intval(floatval($row["Retail"])),
				'p_size'        =>	$row["Size"],
				'is_disabled'   =>	$row["Disabled"],
				'promo'         =>	$row["Promo"],
				'van'           =>	$row["Van"],
				'shelf_life'    =>	$row["Shelf Life"],
				'g_qty'         =>	$row["Group Qty"],
				'g_min'  	    =>	$row["Group Min"],
				'g_max'  	    =>	$row["Group Max"],
				's_qty'  	    =>	$row["Small Qty"],
				's_min' 	    =>	$row["Small Min"],
				's_max'  	    =>	$row["Small Max"],
				'm_qty'  	    =>	$row["Medium Qty"],
				'm_min' 	    =>	$row["Medium Min"],
				'm_max'  	    =>	$row["Medium Max"],
				'l_qty'  	    =>	$row["Large Qty"],
				'l_min'  	    =>	$row["Large Min"],
				'l_max'  	    =>	$row["Large Max"],
				'e_qty'  	    =>	$row["Elite Qty"],
				'e_min'   	    =>	$row["Elite Min"],
				'e_max'   	    =>	$row["Elite Max"],
			);
		}
		
		echo $Presell_Import->insert($data, $data[0]);
	}
	
	function process()
	{
		$Presell_Import = new Presell_Import();
		$ref = request()->getPost('period_reference');
		echo $Presell_Import->process($ref);
	}
	
	function get()
	{		
		echo $this->presell_import->get( $this->input->post('prod_id') );
		//print_r( $data );
	}
	
	function add()
	{
		$data[] = array(
				'period_ref'	=>	$this->input->post('period_reference'),
				'unique_id'     =>	$this->input->post('unique_id'),
				'prod_code'	    =>	$this->input->post('prod_code'),
				'prod_uos'	    =>	$this->input->post('prod_uos'),
				'prod_desc'	    =>	$this->input->post('prod_desc'),
				'prod_pack_desc'=>	$this->input->post('prod_pack_desc'),
				'vat_code'      =>	$this->input->post('vat_code'),
				'group_desc'    =>	$this->input->post('group_desc'),
				'prod_code1'    =>	$this->input->post('prod_code1'),
				'price_list'    =>	$this->input->post('price_list'),
				'prod_level3'   =>	$this->input->post('prod_level3'),
				'prod_sell'     =>	$this->input->post('prod_sell'),
				'prod_rrp'      =>	$this->input->post('prod_rrp'),
				'wholesale'     =>	intval( floatval( $this->input->post('wholesale') ) ),
				'retail'        =>	intval( floatval( $this->input->post('retail') ) ),
				'p_size'        =>	$this->input->post('p_size'),
				'is_disabled'   =>	$this->input->post('is_disabled'),
				'promo'         =>	$this->input->post('promo'),
				'van'           =>	$this->input->post('van'),
				'shelf_life'    =>	$this->input->post('shelf_life'),
				'g_qty'         =>	$this->input->post('g_qty'),
				'g_min'  	    =>	$this->input->post('g_min'),
				'g_max'  	    =>	$this->input->post('g_max'),
				's_qty'  	    =>	$this->input->post('s_qty'),
				's_min' 	    =>	$this->input->post('s_min'),
				's_max'  	    =>	$this->input->post('s_max'),
				'm_qty'  	    =>	$this->input->post('m_qty'),
				'm_min' 	    =>	$this->input->post('m_min'),
				'm_max'  	    =>	$this->input->post('m_max'),
				'l_qty'  	    =>	$this->input->post('l_qty'),
				'l_min'  	    =>	$this->input->post('l_min'),
				'l_max'  	    =>	$this->input->post('l_max'),
				'e_qty'  	    =>	$this->input->post('e_qty'),
				'e_min'   	    =>	$this->input->post('e_min'),
				'e_max'   	    =>	$this->input->post('e_max')
			);
			
		echo $this->presell_import->add($data, $this->input->post('unique_id'));
		//print_r( $data );
	}
	function edit()
	{
		$i = json_decode( $this->presell_import->get( $this->input->post('prod_id') ), true );
		
		if( $i['g_qty'] != $this->input->post('g_qty') || $i['g_min'] != $this->input->post('g_min') || $i['g_max'] != $this->input->post('g_max') ||
		    $i['s_qty'] != $this->input->post('s_qty') || $i['s_min'] != $this->input->post('s_min') || $i['s_max'] != $this->input->post('s_max') || 
            $i['m_qty'] != $this->input->post('m_qty') || $i['m_min'] != $this->input->post('m_min') || $i['m_max'] != $this->input->post('m_max') ||
            $i['l_qty'] != $this->input->post('l_qty') || $i['l_min'] != $this->input->post('l_min') || $i['l_max'] != $this->input->post('l_max') ||
            $i['e_qty'] != $this->input->post('e_qty') || $i['e_min'] != $this->input->post('e_min') || $i['e_max'] != $this->input->post('e_max') )
		  { $equal = 0; } else { $equal = 1; }		
		
		$data = array(
				'period_ref'	=>	$this->input->post('period_reference'),
				'unique_id'     =>	$this->input->post('unique_id'),
				'prod_code'	    =>	$this->input->post('prod_code'),
				'prod_uos'	    =>	$this->input->post('prod_uos'),
				'prod_desc'	    =>	$this->input->post('prod_desc'),
				'prod_pack_desc'=>	$this->input->post('prod_pack_desc'),
				'vat_code'      =>	$this->input->post('vat_code'),
				'group_desc'    =>	$this->input->post('group_desc'),
				'prod_code1'    =>	$this->input->post('prod_code1'),
				'price_list'    =>	$this->input->post('price_list'),
				'prod_level3'   =>	$this->input->post('prod_level3'),
				'prod_sell'     =>	$this->input->post('prod_sell'),
				'prod_rrp'      =>	$this->input->post('prod_rrp'),
				'wholesale'     =>	intval( floatval( $this->input->post('wholesale') ) ),
				'retail'        =>	intval( floatval( $this->input->post('retail') ) ),
				'p_size'        =>	$this->input->post('p_size'),
				'is_disabled'   =>	$this->input->post('is_disabled'),
				'promo'         =>	$this->input->post('promo'),
				'van'           =>	$this->input->post('van'),
				'shelf_life'    =>	$this->input->post('shelf_life')
			);			
			
			if($equal == 0){
				$data['g_qty']  =   $this->input->post('g_qty');
				$data['g_min']  =   $this->input->post('g_min');
				$data['g_max']  =   $this->input->post('g_max');
				$data['s_qty']  =   $this->input->post('s_qty');
				$data['s_min']  =   $this->input->post('s_min');
				$data['s_max']  =   $this->input->post('s_max');
				$data['m_qty']  =   $this->input->post('m_qty');
				$data['m_min']  =   $this->input->post('m_min');
				$data['m_max']  =   $this->input->post('m_max');
				$data['l_qty']  =   $this->input->post('l_qty');
				$data['l_min']  =   $this->input->post('l_min');
				$data['l_max']  =   $this->input->post('l_max');
				$data['e_qty']  =   $this->input->post('e_qty');
				$data['e_min']  =   $this->input->post('e_min');
				$data['e_max']  =   $this->input->post('e_max');
			}
			
	    //echo "UID: ".$this->input->post('unique_id')." PID: ".$this->input->post('prod_id');
		echo $this->presell_import->edit($data, $this->input->post('unique_id'), $this->input->post('prod_id'));
		//print_r( $data );
	}
	
	function delete()
	{
		echo $this->presell_import->delete($this->input->post('unique_id'), $this->input->post('prod_id'));
	}
	
	function delete_entries()
	{
		$ref = $this->input->post('period_reference_delete');
		echo $this->presell_import->delete_entries($ref);
	}
		
}

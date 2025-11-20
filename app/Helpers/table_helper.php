<?php
use App\Models\Employee;
use App\Models\Product;
function get_customer_manage_table($people , $controller , $sort_key = 1 , $user_info)
{
	$CI = \Config\Services::codeigniter();
	$table='<table class="tablesorter" id="sortable_table">';

	$headers = array(lang('Main.common_user_name'),
	lang('Main.common_email'),
	'&nbsp;');

	$table.='<thead><tr>';
	$nCount = 1;
	$nCount2 = 0;
	foreach($headers as $header)
	{
		if($header == '&nbsp;')
		{
			$table .= "<th>".$header."</th>";
			$nCount2 ++;
			continue;
		}
		$nCount1 = $nCount + 1;

		if($nCount == $sort_key)
			$table .= "<th class='headerSortDown' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount1 == $sort_key)
			$table .= "<th class='headerSortUp' onclick='sort_product(this);'>".$header."</th>";
		else if($nCount < 5)
			$table .= "<th class='header' onclick='sort_product(this);'>".$header."</th>";

		$nCount += 2;
	}
	$table.='</tr></thead><tbody>';
	$table.=get_customer_manage_table_data_rows($people , $controller , $user_info);
	$table.='</tbody></table>';
	return $table;
}


function get_customer_manage_table_data_rows($people , $controller , $user_info)
{
	$CI = \Config\Services::codeigniter();
	$table_data_rows = '';
	$nCount = 0;

	foreach($people->getResult() as $person)
	{
		if($person->username != "admin")
		{
			$table_data_rows .= get_customer_data_row($person , $controller , $nCount);
			$nCount ++;
		}
		else if($person->username == "admin")
		{
			if($user_info->username == "admin")
			{
				$table_data_rows .= get_customer_data_row($person , $controller , $nCount);
				$nCount ++;
			}
		}
	}

	if($people->getNumRows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='3'><div class='warning_message' style='padding:7px;'>".lang('Main.common_no_persons_to_display')."</div></tr></tr>";
	}

	return $table_data_rows;
}

function get_customer_data_row($person , $controller , $nCount = 0)
{
	$CI = \Config\Services::codeigniter();
	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();
	if($nCount % 2 == 0)
		$table_data_row = '<tr style="background-color:#FFFFFF;">';
	else
		$table_data_row = '<tr style="background-color:#FFFFFF;">';
	$table_data_row .= '<td width="25%">'.character_limiter($person->username,13).'</td>';
	$table_data_row .= '<td width="65%">'.mailto($person->email,character_limiter($person->email,22)).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:center;">';
	$table_data_row .= "<a href='javascript:void();' onclick='popup_dialog(".$person->person_id.")'><i class='material-icons adjust'>person</i></a>";
	$table_data_row .= '</tr>';
	return $table_data_row;
}
function get_products_manage_table($products, $priceList, $controller, $sort_key, $img_host, $view_mode='grid', $mobile=0, $spresell=0)
{
	if($view_mode == 'grid') {
		$html = "<div class='grid-wrapper'>";
		$html .= get_products_manage_table_data_rows($products, $priceList, $controller, $img_host, $view_mode, $mobile, $spresell);
		$html .= "</div>";
		return $html;
	} else if($view_mode == 'list') {
		$html = "<div class='list-wrapper'>";
		$html .= get_products_manage_table_data_rows($products, $priceList, $controller, $img_host, $view_mode, $mobile, $spresell);
		$html .= "</div>";
		return $html;
	}
}

function get_products_manage_table_data_rows($products, $priceList, $controller, $img_host, $view_mode='grid', $mobile=0, $spresell=0)
{
	$session = session();
	$mobile = intval(session()->get('is_mobile') ?? 0);

    $CI = \Config\Services::codeigniter();
    $table_data_rows = '';
    if (is_object($products)) {
        $nCount = 0;
        foreach ($products->getResult() as $product) {
            $nCount++;
			if($view_mode == 'grid') {
				$table_data_rows .=	get_product_data_row($product, $priceList, $controller, $img_host, $spresell);
			} else {
				if($mobile == 1) {
					$table_data_rows .= get_product_data_row_listview_mobile($product, $priceList, $controller, $img_host, $spresell);
				} else {
					$table_data_rows .= get_product_data_row_listview($product, $priceList, $controller, $img_host, $spresell);
				}
			}
        }
    } else {
        // Handle the case when $products is not a valid result set (e.g., an integer)
        $table_data_rows .= "<tr><td colspan='10'><div class='warning_message' style='padding:7px;'>" . lang('Main.products_no_products_to_display') . "</div></tr></tr>";
    }

    return $table_data_rows;
}

function get_product_data_row_listview($product, $priceList, $controller, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$Employee = new Employee();
	$Product = new Product();
	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();

	if($Employee->is_logged_in()) {
		$pid = $Employee->get_logged_in_employee_info()->person_id;
		$f_state = $Product->get_favorite_state($pid, $product->prod_code);
		if($f_state  == '') $f_state = '-';

		$cart_prod_quantity = $controller->get_cart_quantities($product->prod_code);
		if($controller_name=='presells'){ 
			$cart_presell_quantity = $controller->get_presell_quantities($product->prod_code);
		}
	}
	
	$hide = "";
	$case='';

	Product::getAvailable($product);

	if ($product->promo=='Y' && $controller_name!='presells'){
		Product::getPriceInfo($product, $priceList);
		if(	$product->price_list!='08' && 
			$product->price_list!='10' && 
			$product->price_list!='11' && 
			$product->price_list!='12' && 
			$product->price_list!='999' &&
			$controller_name=='promos'
		) {
			$hide = $controller->get_duplicate($product->prod_code);
		}
	} else {
		$product->price = number_format($product->prod_sell,2,'.','');
		$product->ribbon_background = '';
		$product->p_label = '';
		$product->promo_end_text = '';
	}

	if($hide != true) {
		/*
		$case = "&nbsp;<b>" . Product::getCase($product) . "</b>";

		$data_row = '<div class="d-flex list-item mb-4">
						<div class="d-flex flex-column">';
		$data_row .= 		'<div class="flex-fluid d-flex justify-content-center align-items-center img p-2">';
		// corner ribbon
		if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') {
			$data_row .= 		"<div class='ribbon ribbon-top-left'><span style='background-color:".$product->ribbon_background."'>".$product->p_label."</span></div>";
		}
		$data_row .=			'<img class="prod-image" src="'.$img_host.'/product_images/'.$product->image_url.'?v='.$product->image_version.'" onclick="gotoProductDetail('.$product->prod_id.')">';
		$data_row .=		'</div>';
		$data_row .=	'</div>';
		$data_row .=	'<div class="ms-4 py-2 flex-fluid d-flex flex-column">';
		$data_row .=		'<div class="description position-relative">';
		$data_row .=			'<span>'.$product->prod_desc.'</span>';

		if($Employee->is_logged_in()) {
			$data_row .=		'<div class="favorite cursor-pointer">
									<a onclick="favorite('.$pid.', '.$product->prod_id.',\''.$product->prod_code.'\');">
										<i id="f_'.$product->prod_id.'" class="material-icons - '.$f_state.'">favorite</i>
									</a>
								</div>';
		}

		$data_row .=		'</div>';
		$data_row .=		'<div class="flex-fluid d-flex">';
		$data_row .=			'<div class="flex-fluid d-flex flex-column justify-content-end">';
		if($product->brand) {
			$data_row .=			'<div class="d-flex align-items-center"><div class="label">Brand:</div><span class="prod-prop">'.$product->brand.'</span></div>';
		} else {
			$data_row .=			'<div>&nbsp;</div>';
		}
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">Pack:</div> <span class="prod-prop">'.$product->prod_pack_desc.'<span class="mx-1 text-gray">x</span>'.$product->prod_uos.$case.'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">Code:</div> <span class="prod-prop">'.$product->prod_code.'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">RSP:</div> <span style="color:black;">£'.number_format($product->prod_rrp,2,'.','').'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">POR:</div> <span class="prod-prop">'.$product->por.'%</span></div>';
		if($product->shelf_life) {
			$data_row .=			'<div class="d-flex align-items-center"><div class="label">Shelf Life:</div> <span class="prod-prop">'.$product->shelf_life.'</span></div>';
		} else {
			$data_row .=			'<div>&nbsp;</div>';
		}
		$data_row .=			'</div>';

		$data_row .=			'<div class="d-flex flex-column justify-content-end pb-2 me-2">';
		if(isset($product->pfp) && $product->pfp == "1") {
			$data_row .=			'<div class="profit">
										<img src="'.$img_host.'/images/icons/top-selling-line.png" style="width:50px; height:50px;" title="top-selling-line" />
									</div>';
		}
		if( $product->available['icon_name'] ) {
			$data_row .=			'<div class="mt-2 availability">
										<img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" style="width:50px; height:50px;" title="'.$product->available['icon_title'].'" />
									</div>';
		}
		$data_row .=			'</div>';

		if(!$Employee->is_logged_in()) {
			$data_row .=		"<div class='d-flex align-items-center me-2'><a class='login-link' href='/login'>Log in to see price</a></div>";
		} else if($product->price >= 0) {
			$data_row .=		'<div class="d-flex flex-column">
									<div class="flex-fluid d-flex flex-column align-items-end justify-content-center px-2">
										<div class="price ' . ($product->price == 0 ? 'call-for-price' : '') . '">'.($product->price == 0 ? 'Call For Price' : ('£' . $product->price)).'</div>
										<div>' . ($product->promo_end_text ?? '') . '</div>
									</div>';
			$data_row .=			'<div class="d-flex align-items-center justify-content-end">
										<span class="icon">
											<a class="'.($product->available['avail'] ? "" : "disabled").'"  onclick="inc_quantity(2, '.$product->prod_id.',\''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
												<i class="material-icons remove" style="font-size: 30px;">remove_circle</i>
											</a>
										</span>
										<span class="price_per_pack_empty quantity" data-prod-id=' . $product->prod_id . ' id="prod_'.$product->prod_id.'" onclick="edit_quantity('.$product->prod_id.', \''.$product->prod_code.'\');">
											<span id="span_'.$product->prod_id.'">'.$cart_prod_quantity.'</span>
											<input type="text" class="quantity_cell py-1" id="input_'.$product->prod_id.'" value="'.$cart_prod_quantity.'" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);"'.($product->available['avail'] ? "" : "disabled").' >
										</span>
										<span class="icon">
											<a class="'.($product->available['avail'] ? "" : "disabled") .'"  onclick="inc_quantity(1, '.$product->prod_id.',\''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
												<i class="material-icons" style="font-size: 30px;">add_circle</i>
											</a>
										</span>
									</div>
								</div>';
		}
		$data_row .=		'</div>
						</div>
					</div>';
		*/
		$data_row = '';
		$data_row.='<div class="one-product card border bg-transparent list-view p-2 p-sm-3 h-100 show-in-desktop"
						data-prod-id="'. $product->prod_id.'"
						data-prod-code="'. $product->prod_code.'"
						data-prod-desc="'. $product->prod_desc.'"
					>';
        if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') { 
            $data_row.="<div class='prod-label'><span style='background-color:" . $product->ribbon_background."' >" . $product->p_label . "</span></div>";
        }

        if($Employee->is_logged_in()) {
            $data_row.='<i class="favorite bi bi-heart '. (!empty($product->favorite) ? $product->favorite : '') .'"></i>';
        }

        $data_row.='<div class="card-body p-0 d-flex">
            <div class="d-flex justify-content-center align-items-center">';
                if(!empty($product->image_url)) {
                    $data_row.='<img class="prod-image" src="'. $img_host . '/product_images/' . $product->image_url . '?v=' . $product->image_version .'" alt="" loading="lazy">';
                } else {
                    $data_row.='<img class="prod-image" src="/images/icons/ribbon/no-product.png" alt="" loading="lazy">';
                }
			$data_row.='</div>';

            $data_row.='<div class="ms-2 ms-md-4 flex-fill d-flex">
                <div class="flex-fill position-relative">
                    <h6 class="card-title prod-desc" style="padding-top: 10px; min-height: 40px;">'. $product->prod_desc .'</h6>

                    <div class="flex-fill d-flex flex-column prod-other-props justify-content-end">';
                        if(!empty($product->brand)) { 
                            $data_row.='<div class="prod-brand">
                                <label>Brand: </label>
                                <span class="ms-2 prop-value">'. ($product->brand ?? '') . '</span>
                            </div>';
                        } else { 
                            $data_row.='<div class="prod-brand">&nbsp;</div>';
                        } 
                        
                        $data_row.='<div class="prod-spec">
                            <label>Pack: </label>
                            <span class="ms-2" style="color:black;">
                                <span class="prop-value">'. $product->prod_pack_desc .'</span>
                                <label class="mx-1 text-gray">x</label>
                                <span class="prop-value">'. $product->prod_uos .'</span>
                                <span class="ms-1 fw-bold prop-value">'. ($product->case ?? '') .'</span>
                            </span>
                        </div>

                        <div class="prod-spec">
                            <label>Code: </label>
                            <span class="ms-2 prop-value prod_code_2do" 
                                  data-trolley-type="'. (!empty($product->type) ? $product->type : 'not-sure') .'"
                                  data-can-reorder="yes">'. $product->prod_code .'</span>
                        </div>

                        <div>
                            <span class="prod-rrp">
                                <label>RRP: </label>
                                <span class="ms-2 prop-value">£'. number_format($product->prod_rrp,2,'.','') .'</span>
                            </span>
                            <span class="prod-por inline">
                                <label class="mx-1">|</label>
                                <label>POR: </label>
                                <span class="ms-2 prop-value">'. $product->por . '%</span>
                            </span>
                        </div>
                        <div class="prod-por">
                            <label>POR: </label>
                            <span class="ms-2 prop-value">'. $product->por . '%</span>
                        </div>';
                        if(!empty($product->shelf_life)) { 
                            $data_row.='<div>
                                <label>Shelf Life:</label>
                                <span class="ms-2 prop-value">'. $product->shelf_life .'</span>
                            </div>';
                        } else { 
                            $data_row.='<div>&nbsp;</div>';
                        } 
                    $data_row.='</div>';
                    
                $data_row.='</div>';
                $data_row.='<div class="d-flex">
                    <div class="d-flex align-items-end">
                        <div class="pt-1 ps-1 pe-1">';
                            if(isset($product->pfp) && $product->pfp == "1") { 
                                $data_row.='<div class="profit mt-1 d-none">
                                    <img src="'.$img_host.'/images/icons/top-selling-line.png" title="top-selling-line" />
                                </div>
                                <div class="profit mt-1">
                                    <img src="/images/icons/ribbon/top-selling-line.png" title="top-selling-line" />
                                </div>';
                            } 
                            if($product->available['icon_name']) { 
                                if ($product->available['icon_name'] == 'out-of-stock' || 
                                        $product->available['icon_name'] == 'new-item' || 
                                        $product->available['icon_name'] == 'low-stock' || 
                                        $product->available['icon_name'] == 'coming-soon') { 
                                    $data_row.='<div class="stock-avail" style="">
                                        <img src="/images/icons/ribbon/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } else { 
                                    $data_row.='<div class="stock-avail mt-1">
                                        <img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } 
                            } 
                        $data_row.='</div>';
                        $data_row.='<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 100px;">';
                            if($Employee->is_logged_in()) { 
                                if($product->price >= 0) {
                            
                                $data_row.='<div class="d-flex justify-content-center align-items-end mt-2">
                                    <div class="prod-price d-flex flex-column align-items-center justify-content-center">';
                                        if($product->price == 0) { 
                                            $data_row.='<div class="current-price call-for-price">Call for Price</div>';
                                        } else { 
                                            $data_row.='<div class="d-flex align-items-center">
                                                <span class="current-price">£'. $product->price .'</span>';
                                                if(!empty($product->is_show_non_promo_price)) { 
                                                	$data_row.='<span class="deprecated ms-2">£'. $product->non_promo_price .'</span>';
                                                } 
                                            $data_row.='</div>';
                                        } 
                                            
                                        if(!empty($product->promo_end_text) && $product->promo_end_text) { 
                                            $data_row.='<div class="promo-end-text" style="font-size: 80% !important;">'. $product->promo_end_text .'</div>';
                                        } 
                                    $data_row.='</div>';
                                    
                                $data_row.='</div>';
                            
                                } 
                            } else { 
                            
                                $data_row.='<div class="d-sm-flex justify-content-center align-items-end p-1 p-sm-3">
                                    <a class="text-red login-to-see-price" href="/login">Log in to see price</a>
                                </div>';
                            }  

                            $data_row.='<div class="purchase-action d-flex align-items-center px-1 mt-2">
                                <i class="bi bi-dash minus-cart"></i>
                                <input class="form-control cart-quantity" value="'. ($product->cart_quantity ?? 0) .'" />
                                <i class="bi bi-plus-lg add-cart"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    $data_row.='<div class="one-product card border bg-transparent list-view p-2 p-sm-3 h-100 show-in-mobile" 
         style="padding: 20px 20px 20px 10px !important;"
         data-prod-id="'. $product->prod_id .'"
         data-prod-code="'. $product->prod_code .'"
         data-prod-desc="'. $product->prod_desc .'"
    >';
        if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') { 
            $data_row.="<div class='prod-label'><span style='background-color:" . $product->ribbon_background."' >" . $product->p_label . "</span></div>";
        }

        if($Employee->is_logged_in()) { 
            $data_row.='<i class="favorite bi bi-heart '. (!empty($product->favorite) ? $product->favorite : '') .'"></i>';
        } 

        $data_row.='<div class="card-body p-0 d-flex">
            <div class="d-flex justify-content-center align-items-center" style="width: 80px !important;">';
                if(!empty($product->image_url)) { 
                    $data_row.='<img class="prod-image prod-image2" src="'. $img_host . '/product_images/' . $product->image_url . '?v=' . $product->image_version  .'" alt="" loading="lazy">';
                } else { 
                    $data_row.='<img class="prod-image prod-image2" src="/images/icons/ribbon/no-product.png" alt="" loading="lazy">';
                } 
            $data_row.='</div>';

            $data_row.='<div class="ms-2 ms-md-4 flex-fill d-flex" style="width: calc(100% - 80px) !important;">
                <div class="flex-fill position-relative" style="">
                    <h6 class="card-title prod-desc min-height-auto">'. $product->prod_desc .'</h6>

                    <div class="flex-fill d-flex flex-column prod-other-props justify-content-end">';
                        if(!empty($product->brand)) { 
                            $data_row.='<div class="prod-brand">
                                <label>Brand: </label>
                                <span class="ms-2 prop-value">'. ($product->brand ?? '') .'</span>
                            </div>';
                        } else { 
                            // $data_row.='<div class="prod-brand">&nbsp;</div>';
                        } 
                        
                        $data_row.='<div class="prod-spec">
                            <label>Pack: </label>
                            <span class="ms-2" style="color:black;">
                                <span class="prop-value">'. $product->prod_pack_desc .'</span>
                                <label class="mx-1 text-gray">x</label>
                                <span class="prop-value">'. $product->prod_uos.'</span>
                                <span class="ms-1 fw-bold prop-value">'. ($product->case ?? '') .'</span>
                            </span>
                        </div>

                        <div class="prod-spec">
                            <label>Code: </label>
                            <span class="ms-2 prop-value prod_code_2do" 
                                  data-trolley-type="'. (!empty($product->type) ? $product->type : 'not-sure') .'"
                                  data-can-reorder="yes">'. $product->prod_code .'</span>
                        </div>

                        <div>
                            <span class="prod-rrp">
                                <label>RRP: </label>
                                <span class="ms-2 prop-value">£'. number_format($product->prod_rrp,2,'.','') .'</span>
                            </span>
                            <span class="prod-por">
                                <label class="mx-1">|</label>
                                <label>POR: </label>
                                <span class="ms-2 prop-value">'. $product->por .'%</span>
                            </span>
                        </div>';
                        if(!empty($product->shelf_life)) { 
                            $data_row.='<div>
                                <label>Shelf Life:</label>
                                <span class="ms-2 prop-value">'. $product->shelf_life .'</span>
                            </div>';
                        } else { 
                            // $data_row.='<div>&nbsp;</div>';
                        } 
                    $data_row.='</div>';

                    $data_row.='<div class="profit-avail" style="right: -14px">';
                        if(isset($product->pfp) && $product->pfp == "1") { 
                            $data_row.='<div class="profit mt-1 d-none">
                                <img src="'.$img_host.'/images/icons/top-selling-line.png" title="top-selling-line" />
                            </div>
                            <div class="profit" style="margin-top: 1.5rem">
                                <img src="/images/icons/ribbon/top-selling-line.png" title="top-selling-line" />
                            </div>';

                            if($product->available['icon_name']) { 
                                if ($product->available['icon_name'] == 'out-of-stock' || 
                                        $product->available['icon_name'] == 'new-item' || 
                                        $product->available['icon_name'] == 'low-stock' || 
                                        $product->available['icon_name'] == 'coming-soon') { 
                                    $data_row.='<div class="stock-avail" style="margin-top: 0.2rem">
                                        <img src="/images/icons/ribbon/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } else { 
                                    $data_row.='<div class="stock-avail mt-1">
                                        <img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } 
                            } 
                        } else { 
                            if($product->available['icon_name']) { 
                                if ($product->available['icon_name'] == 'out-of-stock' || 
                                        $product->available['icon_name'] == 'new-item' || 
                                        $product->available['icon_name'] == 'low-stock' || 
                                        $product->available['icon_name'] == 'coming-soon') { 
                                    $data_row.='<div class="stock-avail" style="margin-top: 1.5rem">
                                        <img src="/images/icons/ribbon/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } else { 
                                    $data_row.='<div class="stock-avail mt-1">
                                        <img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
                                    </div>';
                                } 
                            } 
                        } 
                        
                    $data_row.='</div>';

                    if($Employee->is_logged_in()) { 
                        if($product->price >= 0) {
                    
                        $data_row.='<div class="d-flex align-items-center mt-1 ms-0 ms-md-2" style="justify-content: space-between;">
                            <div class="prod-price d-flex flex-column align-items-start justify-content-center">';
                                if($product->price == 0) { 
                                    $data_row.='<div class="current-price call-for-price">Call for Price</div>';
                                } else { 
                                    $data_row.='<div class="d-flex align-items-center">
                                        <span class="current-price">£'. $product->price .'</span>';
                                        if(!empty($product->is_show_non_promo_price)) { 
											$data_row.='<span class="deprecated ms-2">£'. $product->non_promo_price .'</span>';
                                        } 
                                    $data_row.='</div>';
                                } 
                                    
                                if(!empty($product->promo_end_text) && $product->promo_end_text) { 
                                    $data_row.='<div class="promo-end-text">'. $product->promo_end_text .'</div>';
                                } 
							$data_row.='</div>
                            <div class="purchase-action d-flex align-items-center px-1">
                                <i class="bi bi-dash minus-cart"></i>
                                <input class="form-control cart-quantity" value="'. ($product->cart_quantity ?? 0) .'" />
                                <i class="bi bi-plus-lg add-cart"></i>
                            </div>
                        </div>';
                    
                        } 
                    } else { 
                        $data_row.='<div class="d-sm-flex justify-content-center align-items-end p-1 p-sm-3">
                            <a class="text-red login-to-see-price" href="/login">Log in to see price</a>
                        </div>';
                    }  
                $data_row.='</div>
            </div>
        </div>
    </div>';
	}

	return $data_row;
}

function get_product_data_row_listview_mobile($product, $priceList, $controller, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$Employee = new Employee();
	$Product = new Product();
	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();

	if($Employee->is_logged_in()) {
		$pid = $Employee->get_logged_in_employee_info()->person_id;
		$cart_prod_quantity = $controller->get_cart_quantities($product->prod_code);
		if($controller_name=='presells'){ 
			$cart_presell_quantity = $controller->get_presell_quantities($product->prod_code);
		}

		$f_state = $Product->get_favorite_state($pid, $product->prod_code);
		if($f_state  == '') $f_state = '-';
	}

	$hide = "";
	$case='';

	Product::getAvailable($product);

	if ($product->promo=='Y' && $controller_name!='presells'){
		Product::getPriceInfo($product, $priceList);
		if(	$product->price_list!='08' && 
			$product->price_list!='10' && 
			$product->price_list!='11' && 
			$product->price_list!='12' && 
			$product->price_list!='999' &&
			$controller_name=='promos'
		) {
			$hide = $controller->get_duplicate($product->prod_code);
		}
	} else {
		$product->price = number_format($product->prod_sell,2,'.','');
		$product->ribbon_background = '';
		$product->p_label = '';
		$product->promo_end_text = '';
	}

	if($hide != true) {
		$case = "&nbsp;<b>" . Product::getCase($product) . "</b>";
	
		$data_row = '<div class="d-flex list-item mobile mb-4">
						<div class="d-flex flex-column">';
		$data_row .= 		'<div class="flex-fluid d-flex justify-content-center align-items-center img p-1">';
		// corner ribbon
		if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') {
			$data_row .= 		"<div class='ribbon ribbon-top-left'><span style='background-color:".$product->ribbon_background."'>".$product->p_label."</span></div>";
		}
		$data_row .=			'<img class="prod-image" src="'.$img_host.'/product_images/'.$product->image_url.'?v='.$product->image_version.'" onclick="gotoProductDetail('.$product->prod_id.')" >';
		$data_row .=		'</div>';
		$data_row .=	'</div>';
		$data_row .=	'<div class="ms-1 py-2 flex-fluid d-flex flex-column">';
		$data_row .=		'<div class="position-relative">';
		$data_row .=			'<div class="description">'.$product->prod_desc.'</div>';

		if($Employee->is_logged_in()) {
			$data_row .=		'<div class="favorite">
									<a onclick="favorite('.$pid.', '.$product->prod_id.', \''.$product->prod_code.'\');">
										<i id="f_'.$product->prod_id.'" class="material-icons - '.$f_state.'">favorite</i>
									</a>
								</div>';
		}

		$data_row .=		'</div>';
		$data_row .=		'<div class="flex-fluid d-flex">';
		$data_row .=			'<div class="flex-fluid d-flex flex-column justify-content-end">';
		if($product->brand) {
			$data_row .=			'<div class="d-flex align-items-center"><div class="label">Brand:</div><span class="prod-prop">'.$product->brand.'</span></div>';
		} else {
			$data_row .=			'<div>&nbsp;</div>';
		}
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">Pack:</div> <span class="prod-prop">'.$product->prod_pack_desc.'<span class="mx-1 text-gray">x</span>'.$product->prod_uos.$case.'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">Code:</div> <span class="prod-prop">'.$product->prod_code.'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">RSP:</div> <span style="color:black;">£'.number_format($product->prod_rrp,2,'.','').'</span></div>';
		$data_row .=				'<div class="d-flex align-items-center"><div class="label">POR:</div> <span class="prod-prop">'.$product->por.'%</span></div>';
		if($product->shelf_life) {
			$data_row .=			'<div class="d-flex align-items-center"><div class="label">Shelf Life:</div> <span class="prod-prop">'.$product->shelf_life.'</span></div>';
		} else {
			$data_row .=			'<div>&nbsp;</div>';
		}
		$data_row .=			'</div>';

		$data_row .=			'<div class="d-flex flex-column position-relative">';
		$data_row .=				'<div class="d-flex flex-column justify-content-end position-absolute" style="left: -30px; bottom: 5px;">';
		if(isset($product->pfp) && $product->pfp == "1") {
			$data_row .=				'<div class="profit">
											<img src="'.$img_host.'/images/icons/top-selling-line.png" style="width:30px; height:30px;" title="top-selling-line" />
										</div>';
		}
		if($product->available['icon_name']) {
			$data_row .=				'<div class="stock-avail">
											<img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" style="width:30px; height:30px;" title="'.$product->available['icon_title'].'" />
										</div>';
		}
		$data_row .=				'</div>';

		if(!$Employee->is_logged_in()) {
			$data_row .= 			"<div class='d-flex justify-content-center align-items-center h-100 p-1'>
										<a class='login-link text-center' href='/login'>
											<span>Log in</span><br/>
											<span>to see price</span>
										</a>
									</div>";
		} else if($product->price >= 0) {
			$data_row .=			'<div class="flex-fluid d-flex flex-column align-items-end justify-content-end mb-2 me-2">
										<div class="price '. ($product->price == 0 ? 'call-for-price' : '') .'">'.($product->price == 0 ? 'Call For Price' : ('£' . $product->price)).'</div>
										<div>' . $product->promo_end_text . '</div>
									</div>';			
			$data_row .=			'<div class="d-flex align-items-center justify-content-end">
		 								<span class="icon">
											<a class="'.($product->available['avail'] ? "" : "disabled").'"  onclick="inc_quantity(2, '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
												<i class="material-icons remove" style="font-size: 25px;">remove_circle</i>
											</a>
										</span>
										<span class="price_per_pack_empty quantity" data-prod-id='.$product->prod_id.' id="prod_'.$product->prod_id.'" onclick="edit_quantity('.$product->prod_id.', \''.$product->prod_code.'\');">
											<span id="span_'.$product->prod_id.'">'.$cart_prod_quantity.'</span>
											<input type="text" class="quantity_cell py-1" id="input_'.$product->prod_id.'" value="'.$cart_prod_quantity.'" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);"'.($product->available['avail'] ? "" : "disabled").' >
										</span>
										<span class="icon">
											<a class="'.($product->available['avail'] ? "" : "disabled") .'"  onclick="inc_quantity(1, '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
												<i class="material-icons" style="font-size: 25px;">add_circle</i>
											</a>
										</span>
									</div>';
		}
		$data_row .=			'</div>';
		$data_row .=		'</div>';
		$data_row .=	'</div>';
		$data_row .='</div>';
	}

	return $data_row;
}

function get_product_data_row($product, $priceList, $controller, $img_host, $spresell=0)
{
	$CI = \Config\Services::codeigniter();
	$Employee = new Employee();
	$Product = new Product();
    $hide = "";
	$controller_name = strtolower(get_class($CI));
	$case='';

	Product::getAvailable($product);

	if($Employee->is_logged_in()) {
		// To get the quantity in cart
		$cart_prod_quantity = $controller->get_cart_quantities($product->prod_code, $spresell);
		if($controller_name=='presells') {
			$cart_presell_quantity = $controller->get_presell_quantities($product->prod_code);
		}
		if ($cart_prod_quantity == 0) { 
			$data_qty = "0"; 
			$data_wrap  = "price_per_pack_empty"; 
		} else { 
			$data_qty = $cart_prod_quantity; 
			$data_wrap = "price_per_pack"; 
		}

		// To get favorite state
		$pid = $Employee->get_logged_in_employee_info()->person_id;
		$f_state = $Product->get_favorite_state($pid, $product->prod_code);
		if($f_state  == '') $f_state = '-';
	}

	$data_row = '';

	if ($product->promo=='Y' && $controller_name!='presells'){
		Product::getPriceInfo($product, $priceList);
		if(	$product->price_list!='08' && 
			$product->price_list!='10' && 
			$product->price_list!='11' && 
			$product->price_list!='12' && 
			$product->price_list!='999' &&
			$controller_name=='promos'
		) {
			$hide = $controller->get_duplicate($product->prod_code);
		}
	} else {
		$product->price = number_format($product->prod_sell,2,'.','');
		$product->ribbon_background = '';
		$product->p_label = '';
		$product->promo_end_text = '';
	}
	
	if($hide != true){
		/*
		// case of product
		$case = "&nbsp;<b>" . Product::getCase($product) . "</b>";

		$data_row .= "<div class='grid-item' title='".$product->prod_desc."'>";
		// corner ribbon
		if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != ''){
			$data_row .= "<div class='ribbon ribbon-top-left'><span style='background-color:".$product->ribbon_background."'>".$product->p_label."</span></div>";
		}

		// Add Favorite Icon
		if($Employee->is_logged_in()) {
			$data_row .= "<span class='favorite'><a onclick=\"favorite( ".$pid.", ".$product->prod_id.", '". $product->prod_code . "');\" >";
			$data_row .= '<i id="f_'.$product->prod_id.'" class="material-icons '.$f_state.'">favorite</i></a></span>';
		}
		
		// Add Profit Icon
		if(isset($product->pfp) && $product->pfp == "1"){
			$icon_name = "top-selling-line";
			$data_row .= "<img class='profit' src='".$img_host."/images/icons/".$icon_name.".png' title='".$icon_name."' />";
		}

		// Add Availability Icon
		if($product->available['icon_name']){
			$data_row .= "<span class='stock-avail'><img src='".$img_host."/images/icons/".$product->available['icon_name'].".png' title='".$product->available['icon_name']."' style='background:none; border:none; margin-left:-50px; width:50px; opacity:0.7' /></span>";
		}
		
		$data_row .= '<span class="code">'.$product->prod_code.'</span><br />';
		$data_row .= '<img class="prod-image" src="'.$img_host.'/product_images/'.$product->image_url.'?v='.$product->image_version.'" width="200" height="200" onclick="gotoProductDetail('.$product->prod_id.')"><br />';
		$data_row .= '<span class="description">'.$product->prod_desc.'</span>';
		if($product->brand) {
			$data_row .= "<div>Brand: <span class='ms-2' style='color:black;'>" . $product->brand . "</span></div>";
		} else {
			$data_row .= "<div>&nbsp;</div>";
		}
		$data_row .= '<span>Pack: <span style="color:black;">'.$product->prod_pack_desc.'<span class="mx-1 text-gray">x</span>'.$product->prod_uos.$case.'</span></span><br />';
		$data_row .= '<span>';
		$data_row .= 	'RSP: <span style="color:black;">£'.number_format($product->prod_rrp,2,'.','').'</span>&nbsp;&nbsp;';
		$data_row .=	'POR: <span style="color:black;">'.$product->por.'%</span>';
		$data_row .= '</span><br/>';
		if(!empty($product->shelf_life)) {
			$data_row .= '<span>Shelf Life: <span style="color:black;">' . $product->shelf_life . '</span></span><br/>';
		} else {
			$data_row .= '<span>&nbsp;</span><br/>';
		}
	
		if(!$Employee->is_logged_in())
		{
			$data_row .= "<span><a class='login-link' href='/login'>Log in to see price</a></span>";
		} else if($product->price < 0){ 
			$data_row .= '<span>&nbsp;</span><br /><span>&nbsp;</span><br /><span>&nbsp;</span><br />'; 
		} else { 
			if($product->price == 0) {
				$data_row .= '<span class="price call-for-price">Call For Price</span><br />'; 
			} else {
				$data_row .= '<span class="price"> '. '£' . $product->price . '</span><br />'; 
			}
			$data_row .= '<span>' . ($product->promo_end_text ?? '').'</span><br/>';
			$data_row .= '<span class="icon"><a class="'.($product->available['avail'] ? "" : "disabled") .'" onclick="inc_quantity(2 , '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons remove" style="font-size: 30px;">remove_circle</i></a></span>';		   
			$data_row .= '<span class="'.$data_wrap.' quantity" data-prod-id='.$product->prod_id.' id="prod_'.$product->prod_id.'" onclick="edit_quantity('.$product->prod_id.', \''.$product->prod_code.'\');">
								<span id="span_'.$product->prod_id.'">'.$data_qty.'</span>
								<input type="text" class="quantity_cell py-1" id="input_'.$product->prod_id.'" value="0" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);" '.($product->available['avail'] ? "" : "disabled") .'>
							</span>';
			$data_row .= '<span class="icon"><a class="'.($product->available['avail'] ? "" : "disabled") .'" onclick="inc_quantity(1 , '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons" style="font-size: 30px;">add_circle</i></a></span>';	
		}
		
		$data_row .= '</div>';
		*/
		// 1
		$data_row.='<div class="one-product card border bg-transparent grid-view p-2"
						data-prod-id="'.$product->prod_id.'"
						data-prod-code="'.$product->prod_code.'"
						data-prod-desc="'.$product->prod_desc.'"
					>';
        if(isset($product->pfp) && $product->pfp == "1") { 
			// 2
            $data_row.='<div class="profit d-none">
							<img src="'.$img_host.'/images/icons/top-selling-line.png" title="top-selling-line" />
						</div>';
			// 2
			$data_row.='<div class="profit">
							<img src="/images/icons/ribbon/top-selling-line.png" title="top-selling-line" />
						</div>';
        } 

        if(!empty($product->p_label) && $product->p_label != 'CC' && $product->p_label != '') { 
			// 2
            $data_row.='<div class="prod-label">
							<span style="background-color:'.$product->ribbon_background.'">'.$product->p_label.'</span>
						</div>';
        }

        if($product->available['icon_name']) { 
            if ($product->available['icon_name'] == 'out-of-stock' || 
				$product->available['icon_name'] == 'new-item' || 
				$product->available['icon_name'] == 'low-stock' || 
				$product->available['icon_name'] == 'coming-soon') { 
				$data_row.='<div class="stock-avail" style="right: 6px; top:45px">
								<img src="/images/icons/ribbon/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
							</div>';
            } else { 
                $data_row.='<div class="stock-avail">
								<img src="<?=$img_host?>/images/icons/'.$product->available['icon_name'].'.png" title="'.$product->available['icon_title'].'" />
							</div>';
            } 
        } 
       

        if($Employee->is_logged_in()) { 
            $data_row.='<i class="favorite bi bi-heart '.(!empty($product->favorite) ? 'text-red' : '').' "></i>';
        }

        $data_row.='<div class="card-header bg-light rounded d-flex justify-content-center p-2">';
            if(!empty($product->image_url)) {
                $data_row.='<img class="prod-image" src="'.$img_host.'/product_images/'.$product->image_url.'?v='.$product->image_version.'" alt="" loading="lazy">';
            } else {
                $data_row.='<img class="prod-image" src="/images/icons/ribbon/no-product.png" alt="" loading="lazy">';
            }
        $data_row.='</div>';

        // Card body
        $data_row.='<div class="card-body p-2">';
            // Title
            $data_row.='<h6 class="card-title prod-desc prod-desc-hover">'.$product->prod_desc.'</h6>';

            
///////////////////
$data_row.='<div class="prod-other-props">';
                if(!empty($product->brand)) {
                    $data_row.='<div>
                        <label>Brand: </label>
                        <span class="ms-2 prop-value">'.($product->brand ?? '').'</span>
                    </div>';
                } else {
                    $data_row.='<div>&nbsp;</div>';
                }
                
                $data_row.='<div>
                    <label>Pack: </label>
                    <span class="ms-2" style="color:black;">
                        <span class="prop-value">'.$product->prod_pack_desc.'</span>
                        <label class="mx-1">x</label>
                        <span class="prop-value">'.$product->prod_uos.'</span>
                        <span class="ms-1 fw-bold prop-value">'.($product->case ?? '').'</span>
                    </span>
                </div>

                <div>
                    <label>Code: </label>
                    <span class="ms-2 prop-value prod_code_2do" 
                          data-trolley-type="'.(!empty($product->type) ? $product->type : 'not-sure').'"
                          data-can-reorder="yes">'.$product->prod_code.'</span>
                </div>

                <div>
                    <label>RRP: </label>
                    <span class="ms-2 prop-value">£'.number_format($product->prod_rrp,2,'.','').'</span>
                    <label class="mx-1">|</label>

                    <label>POR: </label>
                    <span class="ms-2 prop-value">'.$product->por.'%</span>
                </div>';

                if(!empty($product->shelf_life)) {
                    $data_row.='<div>
                        <label>Shelf Life:</label>
                        <span class="ms-2 prop-value">'.($product->shelf_life ?? '&nbsp;').'</span>
                    </div>';
                } else {
                    $data_row.='<div>&nbsp;</div>';
                }
            $data_row.='</div>';



//////////////////
if($Employee->is_logged_in()) { 
	if($product->price >= 0) {

	$data_row.='<div class="d-flex align-items-center mt-1" style="min-height: 40px;">
		<div class="prod-price flex-fill">';
			if($product->price == 0) {
				$data_row.='<div class="current-price call-for-price">Call for Price</div>';
			} else { 
				$data_row.='<div class="d-flex align-items-center">
					<span class="current-price">£'.$product->price.'</span>';
					if(!empty($product->is_show_non_promo_price)) {
						$data_row.='<span class="deprecated ms-2">£'.$product->non_promo_price.'</span>';
					}
				$data_row.='</div>';
			}

			if(!empty($product->promo_end_text)) {
				$data_row.='<div class="promo-end-text" style="font-size: 70%">'.$product->promo_end_text.'</div>';
			}
		$data_row.='</div>';

		if($product->available['avail']) { 
		
		} 
		if($product->available['avail']) { 
			$data_row.='<div class="purchase-action d-flex align-items-center px-1">
			  <i class="bi bi-dash minus-cart"></i>
			  <input class="form-control cart-quantity" value="'.($product->cart_quantity ?? 0).'" />
			  <i class="bi bi-plus-lg add-cart"></i>
		  </div>';
		} else { 
			$data_row.='<div class="purchase-action d-flex align-items-center px-1 must-hide">
			  <i class="bi bi-dash minus-cart"></i>
			  <input class="form-control cart-quantity" value="'.($product->cart_quantity ?? 0).'" />
			  <i class="bi bi-plus-lg add-cart"></i>
		  </div>';
		} 
	   
	$data_row.='</div>';

	} 
} else { 

	$data_row.='<div class="d-flex justify-content-center p-1">
		<a class="text-red login-to-see-price" href="/login">Log in to see price</a>
	</div>';
} 




//////////////////










        $data_row.='</div>';
		$data_row.='</div>';

	}

	return $data_row;
}

function get_cart_order_manage_table_mobile($cart_orders, $priceList, $type='general', $controller, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$controller_name=strtolower(get_class($CI));

	$table = '<div class="list-wrapper">';
	$table .= get_cart_orders_manage_table_data_rows_mobile($cart_orders, $priceList, $controller, $img_host, $type);
	$table .= "</div>";

	return $table;
}

function get_cart_orders_manage_table_data_rows_mobile($cart_orders, $priceList, $controller, $img_host, $type)
{
	$CI = \Config\Services::codeigniter();
	$table_data_rows = '';
	$nCount = 0;
    $prod_code1=0;

	if($cart_orders->getNumRows() == 0) {
		$table_data_rows = "<div class='d-flex justify-content-center'>".lang('Main.orders_empty_trolley')."</div>";
	} else {
		foreach($cart_orders->getResult() as $cart_order)
		{
			$nCount ++;
			$table_data_rows .= get_cart_order_data_row_mobile($cart_order, $priceList, $controller , $nCount , $prod_code1, $img_host, $type);
		}
	}

	return $table_data_rows;
}

function get_cart_order_data_row_mobile($cart_order, $priceList, $controller , $nCount , &$prod_code1, $img_host, $type)
{
	$CI = \Config\Services::codeigniter();
	$controller_name = strtolower(get_class($CI));

	$Employee = new Employee();
	$user_info = $Employee->get_logged_in_employee_info();

	$product = Product::getLowestPriceProductByCode($user_info, $cart_order->prod_code, true, $cart_order->group_type == 'spresell');
	if(!$product) $product = Product::getLowestPriceProductByCode($user_info, $cart_order->prod_code, false, $cart_order->group_type == 'spresell');
	if(!$product) return;
	
    $colortxt = "color:black;";
    if ($prod_code1==$product->prod_code1) $colortxt = "color:red;";
    else $prod_code1=$product->prod_code1;
	if($nCount % 2 == 0) {
		$background= "FFFFFF";
	} else {
		$background= "FFFFFF";
		//$background= "E4E4FF";
	}
	
	$product->price = number_format($product->prod_sell,2,'.','');
	$product->ribbon_background = '';
	$product->p_label = '';
	$product->promo_end_text = '';

	$total = number_format($product->prod_sell * $cart_order->quantity,2,'.','');
	$case='';

	Product::getAvailable($product);
	
	if($product->is_disabled == 'Y') {
		$product->ribbon_background = '#ff0000';
		$product->p_label = 'Unavailable';
		$product->available['avail'] = false;
	} elseif ($product->promo=='Y' && $controller_name!='presells') {
		Product::getPriceInfo($product, $priceList);
		if(	$product->price_list!='08' && 
			$product->price_list!='10' && 
			$product->price_list!='11' && 
			$product->price_list!='12' && 
			$product->price_list!='999' &&
			$controller_name=='promos'
		) {
			$hide = $controller->get_duplicate($product->prod_code);
		}
	}

	$time = $product->price_end + ((23* 3600) + 3599);
	if(time()>=$product->price_start && time() <= $time ) {
		if( (time() >= $time-(86400*7)) ){
			$pdates = "<br />Ending Soon"; 	}
		else{
			$pdates = "<br />".date('d/m/Y h:i:s A', $time);	
		}
	}
	else { 
		$product->price = 0; 
		$total = ''; 
	}

	$case = "&nbsp;<b>" . Product::getCase($product) . "</b>";
	
	$data_row = '<div class="d-flex list-item mobile mb-4">
					<div class="d-flex flex-column">';
	$data_row .= 		'<div class="flex-fluid d-flex justify-content-center align-items-center img p-1">';
	// corner ribbon
	if(!empty($product->p_label) && $product->p_label != 'CC') {
		$data_row .= 		"<div class='ribbon ribbon-top-left'><span style='background-color:".$product->ribbon_background."'>".$product->p_label."</span></div>";
	}
	$data_row .=			'<img class="prod-image" src="'.$img_host.'/product_images/'.$product->image_url.'?v='.$product->image_version.'" onclick="gotoProductDetail('.$product->prod_id.')">';
	$data_row .=		'</div>';
	$data_row .=	'</div>';
	$data_row .=	'<div class="ms-1 py-2 flex-fluid d-flex flex-column">';
	$data_row .=		'<div class="position-relative">';
	$data_row .=			'<div class="description">'.$product->prod_desc.'</div>';
	$data_row .=			'<div class="rm-from-cart">';
	$data_row .= 				'<a onclick="inc_quantity(3, '.$product->prod_id.',\''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><span>';
	$data_row .=					'<i class="material-icons delete">close</i>';
	$data_row .=				'</span></a>';
	$data_row .=			'</div>';
	$data_row .=		'</div>';
	$data_row .=		'<div class="flex-fluid d-flex">';
	$data_row .=			'<div class="flex-fluid d-flex flex-column justify-content-end">';
	if($product->brand) {
		$data_row .=			'<div class="d-flex align-items-center"><div class="label">Brand:</div><span class="prod-prop">'.$product->brand.'</span></div>';
	} else {
		$data_row .=			'<div>&nbsp;</div>';
	}
	$data_row .=				'<div class="d-flex align-items-center"><div class="label">Pack:</div> <span class="prod-prop">'.$product->prod_pack_desc.'<span class="mx-1 text-gray">x</span>'.$product->prod_uos.$case.'</span></div>';
	$data_row .=				'<div class="d-flex align-items-center"><div class="label">Code:</div> <span class="prod-prop">'.$product->prod_code.'</span></div>';
	$data_row .=				'<div class="d-flex align-items-center"><div class="label">RSP:</div> <span style="color:black;">£'.number_format($product->prod_rrp,2,'.','').'</span></div>';
	if($product->shelf_life) {
		$data_row .=			'<div class="d-flex align-items-center"><div class="label">Shelf Life:</div> <span class="prod-prop">'.$product->shelf_life.'</span></div>';
	} else {
		$data_row .=			'<div>&nbsp;</div>';
	}
	$data_row .=			'</div>';

	$data_row .=			'<div class="d-flex flex-column position-relative">';
	$data_row .=				'<div class="d-flex flex-column justify-content-end position-absolute" style="left: -30px; bottom: 5px;">';
	if(isset($product->pfp) && $product->pfp == "1") {
		$data_row .=				'<div class="profit">
										<img src="'.$img_host.'/images/icons/top-selling-line.png" style="width:30px; height:30px;" title="top-selling-line" />
									</div>';
	}
	if($product->available['icon_name']) {
		$data_row .=				'<div class="mt-1 availability">
										<img src="'.$img_host.'/images/icons/'.$product->available['icon_name'].'.png" style="width:30px; height:30px;" title="'.$product->available['icon_title'].'" />
									</div>';
	}
	$data_row .=				'</div>';
	$data_row .=				'<div class="flex-fluid d-flex flex-column align-items-end justify-content-end mb-2 me-2">
									<div class="price">'.$total.'</div>
									<div class="price mt-2" style="color:#888 !important; font-size: 100% !important;">'.($product->price == 0 ? 'Call For Price' : ('£' . $product->price)).'</div>
									<div>' . $product->promo_end_text . '</div>
								</div>
								<div class="d-flex align-items-center justify-content-end">';
	$data_row .= 					'<span class="icon">
										<a class="'.($product->available['avail'] ? "" : "disabled").'"  onclick="inc_quantity(2, '.$product->prod_id.',\''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
											<i class="material-icons remove" style="font-size: 25px;">remove_circle</i>
										</a>
									</span>
									<span class="price_per_pack_empty quantity" data-prod-id='.$product->prod_id.' id="prod_'.$product->prod_id.'" onclick="edit_quantity('.$product->prod_id.', \''.$product->prod_code.'\');">
										<span id="span_'.$product->prod_id.'">'.$cart_order->quantity.'</span>
										<input type="text" class="quantity_cell py-1" id="input_'.$product->prod_id.'" value="'.$cart_order->quantity.'" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);"'.($product->available['avail'] ? "" : "disabled").' >
									</span>
									<span class="icon">
										<a class="'.($product->available['avail'] ? "" : "disabled") .'"  onclick="inc_quantity(1, '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');">
											<i class="material-icons" style="font-size: 25px;">add_circle</i>
										</a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>';
	
	return $data_row;
}

function get_cart_order_manage_table($cart_orders, $type='general', $controller, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$controller_name=strtolower(get_class($CI));

	$table = '<table class="tablesorter" id="sortable_table">';

	$headers = array('SKU' ,
					 lang('Main.products_product_code') ,
					 lang('Main.products_description') ,
					 lang('Main.products_unit_pk_size') ,
					 lang('Main.products_product_uos') ,
					 lang('Main.orders_per') ,
					 lang('Main.orders_qty') ,
					 'Adjust',
					 lang('Main.orders_line_total')
	);
	$headers1 = array(lang('Main.products_product_code') ,
					 lang('Main.products_description') ,
					 lang('Main.orders_per') ,
					 lang('Main.orders_qty') ,
					 'Adjust',
					 lang('Main.orders_line_total')
	);
    
	$table .= '<thead class="small-screen"><tr>';
	foreach($headers1 as $header){
		if($header == 'Adjust')	    $table .= "<th style='text-align:center;'>".$header."</th>";
		else if($header == 'Total')	$table .= "<th style='text-align:center;'>".$header."</th>";
		else                    	$table .= "<th>".$header."</th>";
	}	
	$table .= '</tr></thead>';
	$table.='<thead class="large-screen"><tr>';
	foreach($headers as $header){
		if($header == 'Adjust')	    $table .= "<th colspan='3' style='text-align:center;'>".$header."</th>";
		else if($header == 'Total')	$table .= "<th colspan='2' style='text-align:center;'>".$header."</th>";
		else                    	$table .= "<th>".$header."</th>";
	}
	$table .= '</tr></thead>';
	$table .= '<tbody>';
	$table .= get_cart_orders_manage_table_data_rows($cart_orders , $controller, $img_host, $type);
	$table .= '</tbody></table>';
	return $table;
}

function get_cart_orders_manage_table_data_rows($cart_orders , $controller, $img_host, $type)
{
	$CI = \Config\Services::codeigniter();
	$table_data_rows = '';

	$nCount = 0;
    $prod_code1=0;
	foreach($cart_orders->getResult() as $cart_order)
	{
		$nCount ++;
		$table_data_rows .= get_cart_order_data_row($cart_order , $controller , $nCount , $prod_code1, $img_host, $type);
	}

	if($cart_orders->getNumRows() == 0)
	{
		$table_data_rows .= "<tr><td colspan='12'><div class='warning_message' style='padding:100px 20px;'>".lang('Main.orders_empty_trolley')."</div></tr></tr>";
	}

	return $table_data_rows;
}


function get_cart_order_data_row($cart_order , $controller , $nCount , &$prod_code1, $img_host, $type)
{
	$CI = \Config\Services::codeigniter();

	$controller_name = strtolower(get_class($CI));

	$Employee = new Employee();
	$user_info = $Employee->get_logged_in_employee_info();

	$product = Product::getLowestPriceProductByCode($user_info, $cart_order->prod_code, true, $cart_order->group_type == 'spresell');
	if(!$product) $product = Product::getLowestPriceProductByCode($user_info, $cart_order->prod_code, false, $cart_order->group_type == 'spresell');
	if(!$product) return;

    $colortxt = "color:black;";
    if ($prod_code1==$product->prod_code1) $colortxt = "color:red;";
    else $prod_code1=$product->prod_code1;

	if($nCount % 2 == 0) $background= "FFFFFF";
	else $background= "FFFFFF";
	
	$pdates = ''; $epoints = '';
	$price = number_format($product->prod_sell,2,'.','');
	$total = number_format($product->prod_sell * $cart_order->quantity,2,'.','');
	
    if (isset($product->promo) && $product->promo=='Y'){
	    if($product->price_list=='08'){ $background="#a0e2c8"; $promotion="<br /><b>DAY-TODAY EXPRESS ELITE</b>"; } 
	    else if($product->price_list=='10'){ $background="#a0e2c8"; $promotion="<br /><b>DAY-TODAY PRICE</b>"; } 
	    else if($product->price_list=='11'){ $background="#a0e2c8"; $promotion="<br /><b>DAY-TODAY EXPRESS PRICE</b>"; }
		else if($product->price_list=='12'){ $background="#f5c1c2"; $promotion="<br /><b>USAVE PRICE</b>";  } 
		else {$background="#b4e9f8"; $promotion="<br /><b>C & C PROMOTION</b>";  }		
		$time = $product->price_end + ((23* 3600) + 3599);
		if(time()>=$product->price_start && time() <= $time ){
			if( (time() >= $time-(86400*7)) ){
			    $pdates = "<br />Ending Soon"; 	}
			else{
				$pdates = "<br />".date('d/m/Y h:i:s A', $time);	
			}
		}
		else{ $price = 'Call For Price'; $total = 'Call For Price'; }
	}
	else{ $promotion=''; $pdates=''; $epoints='';}
	
    $table_data_row = '<tr style="" class="large">';
	$table_data_row .= '<td width="5%">'.$product->prod_code.' '.$epoints.'</td>';
	$table_data_row .= '<td width="8%" style="text-align:center;"><img src="'.$img_host.'/product_images/'.$product->image_url.'?v=' . $product->image_version . '" width="60" height="60" style="border:#fff 5px solid;" onclick='.$product->prod_id.'></td>';
	$table_data_row .= '<td width="25%" style="background: linear-gradient(to right, #ffffff, '.$background.' 30%, #ffffff)">'.$product->prod_desc.' '.$promotion.'</b>'.$pdates.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:left;">'.$product->prod_pack_desc.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:left;">'.$product->prod_uos.'</td>';
	if($price != 'Call For Price'){ $price = '£'.$price; $total = '£'.$total;}
	$table_data_row .= '<td width="7%" style="text-align:left;">'.$price.'</td>';
	$table_data_row .= '<td width="5%" style="text-align:left; cursor:pointer;" onclick="edit_quantity('.$product->prod_id.', \''.$product->prod_code.'\');"><span id="span_'.$product->prod_id.'">';
	$table_data_row .= $cart_order->quantity.'</span><input type="text" class="quantity_cell" id="input_';
	$table_data_row .= $product->prod_id;
	$table_data_row .= '" value="'.$cart_order->quantity.'" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);" style="display:none;"></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><a onclick="set_qty(this , '.$product->prod_id.', \''.$product->prod_code.'\');"><i class="material-icons adjust">swap_vert</i></a></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><a onclick="inc_quantity(1 , '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons">add_circle</i></a></td>';
	$table_data_row .= '<td width="5%" style="text-align:center;"><a onclick="inc_quantity(2 , '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons remove">remove_circle</i></a></td>';
	$table_data_row .= '<td width="8%" style="text-align:right;" id="product_total">'.$total.'</td>';
	$table_data_row .= '<td width="3%" style="text-align:center;"><a onclick="inc_quantity(3 ,'.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><span><i class="material-icons delete">close</i>';
	$table_data_row .= '</span></a></td>';
	$table_data_row.='</tr><tr  style="background: linear-gradient(to right,#ffffff, '.$background.' 30%, #ffffff 70%)" class="small">';
	//$total = number_format($product->prod_sell * $cart_order->quantity,2,'.','');
	//$price = number_format($product->prod_sell,2,'.','');
	$table_data_row .= '<td width="8%" style="text-align:center;"><img src="'.$img_host.'/product_images/100px/'.intval(substr($product->prod_code,1)).'.jpg" width="60" height="60" style="border:#fff 5px solid;"><br />'.$product->prod_code.' '.$epoints.'</td>';
	$table_data_row .= '<td width="25%">'.$product->prod_desc.' '.$promotion.' '.$pdates.'<br />SIZE/LIFE: '.$product->prod_pack_desc.'<br />UOS: '.$product->prod_uos.'</td>';
	if($price != 'Call For Price'){ $price = $price; }
	$table_data_row .= '<td width="7%" style="text-align:left;"><b>'.$price.'</b></td>';
	$table_data_row .= '<td width="5%" style="text-align:left; cursor:pointer;" onclick="edit_quantity('.$product->prod_id.',\''.$product->prod_code.'\');"><span id="span_'.$product->prod_id.'">';
	$table_data_row .= $cart_order->quantity.'</span><input type="text" class="quantity_cell" id="input_';
	$table_data_row .= $product->prod_id;
	$table_data_row .= '" value="'.$cart_order->quantity.'" onmouseover="this.select()" onkeyup="change_quantity('.$product->prod_id.', \''.$product->prod_code.'\', event);" style="display:none;"></td>';
	$table_data_row .= '<td width="10%" style="text-align:center;"><a onclick="set_qty(this , '.$product->prod_id.', \''.$product->prod_code.'\');"><i class="material-icons adjust">swap_vert</i></a><a  onclick="inc_quantity(1 , '.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons">add_circle</i></a><a  onclick="inc_quantity(2 , '.$product->prod_id.',\''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><i class="material-icons remove">remove_circle</i></a></td>';
	$table_data_row .= '<td width="8%" style="text-align:right;" id="product_total">'.$total.' <a "onclick="inc_quantity(3 ,'.$product->prod_id.', \''.$product->prod_code.'\', \''.$product->prod_desc.'\');"><span><i class="material-icons delete">close</i></span></a></td>';
	$table_data_row.='</tr>';
	
	return $table_data_row;
}


function get_orders_manage_table($orders , $controller , $sort_key = 4 , $user_info, $segment = 0, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$table = '<table class="tablesorter" id="sortable_table" >';
	$table.='<thead class="large-screen"><tr>';
    $temp_table=array();
    $temp_table[1] = "<th>".lang('Main.orders_line_no')."</th>";
    $temp_table[2] = "<th class='header' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";
    $temp_table[3] = "<th class='header' onclick='sort_product(this);'>".lang('Main.date')."</th>";
    $temp_table[4] = "<th>".lang('Main.time')."</th>";
    $temp_table[5] = "<th>".lang('Main.pastorders_total_amount')."</th>";
    $temp_table[6] = "<th class='header' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";
    if ($sort_key==1)
      $temp_table[2] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";
    if ($sort_key==2)
      $temp_table[2] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";

    if ($sort_key==3)
      $temp_table[3] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.date')."</th>";
    if ($sort_key==4)
      $temp_table[3] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.date')."</th>";

    if ($sort_key==5)
      $temp_table[6] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";
    if ($sort_key==6)
      $temp_table[6] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";

    $table .=$temp_table[1];
    $table .=$temp_table[2];
    $table .=$temp_table[3];
    $table .=$temp_table[4];
    $table .=$temp_table[5];
    $table .=$temp_table[6];

    $table .= "<th>".lang('Main.pastorders_file_name')."</th>";
    $table .= "<th>Info</th>";
    $table .= "<th>Type</th>";
	if($user_info->person_id == 1){
    	$table .= "<th>Resend</th>";
	}
	$table.='</tr></thead><thead class="small-screen"><tr>';
    $temp_table=array();
    $temp_table[7] = "<th>".lang('Main.orders_line_no')."</th>";
    $temp_table[8] = "<th class='header' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";
    $temp_table[9] = "<th class='header' onclick='sort_product(this);'>".lang('Main.date')."</th>";
    $temp_table[10] = "<th>".lang('Main.time')."</th>";
    $temp_table[11] = "<th>".lang('Main.pastorders_total_amount')."</th>";
    $temp_table[12] = "<th class='header' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";
    if ($sort_key==1)
      $temp_table[8] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";
    if ($sort_key==2)
      $temp_table[8] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.pastorders_username')."</th>";

    if ($sort_key==3)
      $temp_table[9] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.date')."</th>";
    if ($sort_key==4)
      $temp_table[9] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.date')."</th>";

    if ($sort_key==5)
      $temp_table[12] = "<th class='headerSortDown' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";
    if ($sort_key==6)
      $temp_table[12] = "<th class='headerSortUp' onclick='sort_product(this);'>".lang('Main.pastorders_status')."</th>";

    $table .=$temp_table[7];
    $table .=$temp_table[8];
    $table .=$temp_table[9];
    $table .=$temp_table[10];
    $table .=$temp_table[11];
    $table .=$temp_table[12];

    $table .= "<th>Info</th>";
	$table .= '</tr></thead><tbody>';
	
	if($segment == '') $segment = 0;
	$table .= get_orders_manage_table_data_rows($orders , $user_info , $controller , $segment, $img_host);
	$table .= '</tbody></table>';
	return $table;
}


function get_orders_manage_table_data_rows($orders , $user_info , $controller , $segment, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$table_data_rows = '';
	if($segment == '') $segment = 0;
	$nCount = $segment;
	foreach($orders->getResult() as $order)
	  {
		$nCount ++;
		$table_data_rows .= get_order_data_row($order , $user_info , $controller , $nCount, $img_host);
	  }
	if($orders->getNumRows() == 0) $table_data_rows .= "<tr><td colspan='8'><div class='warning_message' style='padding:7px;'>".lang('Main.orders_no_orders_to_display')."</div></tr></tr>";
	return $table_data_rows;
}

function get_order_data_row($order , $user_info , $controller , $nCount, $img_host)
{
	$CI = \Config\Services::codeigniter();
	$controller_name = strtolower(get_class($CI));
	$width = $controller->get_form_width();
	$total = number_format($controller->get_total_amount($order->order_id),2,'.','');
	if($order->presell == 1){$adjust = "local_parking"; $adjust_color="purple-text";} else{ $adjust = "visibility"; $adjust_color="";}
	if($order->type == "general"){$order_type = "format_shapes";} else if($order->type == "tobacco"){ $order_type = "smoking_rooms"; }else{$order_type = "ac_unit"; }
	if($nCount % 2 == 0)
		$table_data_row = "<tr style='background-color:#FFFFFF; height:50px;' class='large'>";
	else
		$table_data_row = "<tr style='background-color:#FFFFFF; height:50px;' class='large'>";
	$table_data_row .= '<td width="10%">wo2-'.$order->order_id.'</td>';
	$table_data_row .= '<td width="10%">'.$order->username.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">'.substr($order->order_date , 0 , 4)."/".substr($order->order_date , 4 , 2)."/".substr($order->order_date , 6 , 2).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">'.substr($order->order_time , 0 , 2).":".substr($order->order_time , 2 , 2).":".substr($order->order_time , 4 , 2).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">£'.$total.'</td>';

	if($order->completed == 1)
	{
		$table_data_row .= '<td width="10%" style="text-align:left; padding-left:20px;"><i class="material-icons po">check_circle</i></td>';
		$table_data_row .= "<td width='40%' style='text-align:left; font-size:11px;'>".$order->filename."</td>";
	}
	else
	{
		$table_data_row .= '<td width="10%" style="text-align:left;"><i class="material-icons remove po">cloud_done</i></td>';
		$table_data_row .= "<td width='40%' style='text-align:left;'>&nbsp;</td>";
	}
	$table_data_row .= "<td width=\"10%\" style=\"text-align:center;\"><a href='javascript:void();' onclick='popup_dialog(".$order->order_id.",".'"'.ucfirst($order->type).'"'.",".$order->completed.",".$order->presell.")' title='View Order Details'><i class='material-icons adjust ".$adjust_color."'>".$adjust."</i></a>";
	
	$table_data_row .= "<td width=\"10%\" style=\"text-align:center;\"><i class='material-icons'>".$order_type."</i>";
	
	if($user_info->person_id == 1 && $order->completed == 1){
		$table_data_row .= "</td><td width=\"10%\" style=\"text-align:center;\"><a href='javascript:void();' onclick='resend_order(".$order->order_id.")' title='Resend Order Copy'><i class='material-icons red-text'>content_copy</i></a></td>";
	}else{
		$table_data_row .= '</td><td>&nbsp;</td>';	
	}
	// Small Screen
	$table_data_row .= "</tr><tr style='background-color:#FFFFFF; height:50px;' class='small'>";
	$table_data_row .= '<td width="10%">wo2-'.$order->order_id.'</td>';
	$table_data_row .= '<td width="10%">'.$order->username.'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">'.substr($order->order_date , 0 , 4)."/".substr($order->order_date , 4 , 2)."/".substr($order->order_date , 6 , 2).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">'.substr($order->order_time , 0 , 2).":".substr($order->order_time , 2 , 2).":".substr($order->order_time , 4 , 2).'</td>';
	$table_data_row .= '<td width="10%" style="text-align:left;">£'.$total.'</td>';	
	if($order->completed == 1)	{
		$table_data_row .= '<td width="10%" style="text-align:left;"><i class="material-icons po">check_circle</i></td>';
	}else{
		$table_data_row .= '<td width="10%" style="text-align:left;"><i class="material-icons remove po">cloud_done</i></td>';
	}
	$table_data_row .= "<td width=\"10%\" style=\"text-align:center;\"><a href='javascript:void();' onclick='popup_dialog(".$order->order_id.",".$order->completed.",".$order->presell.")'><i class='material-icons adjust ".$adjust_color."'>".$adjust."</i></a>";
	$table_data_row .= '</td></tr>';
	return $table_data_row;
	//'.lang('Main.pastorders_not_placed').' '.lang('Main.pastorders_placed').' ".lang('Main.pastorders_show_me')."
}


if (!function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        foreach ($args as $arg) {
			echo "<pre>";
            print_r($arg);
			echo "</pre>";
			echo "<hr>";
        }

        die(1);
    }
}

?>

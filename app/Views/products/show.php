<?php 
echo view("partial/header"); 
?>

<div id="content_area_wrapper">
  <div id="content_area">
    <div class="d-flex justify-content-center" style="background: white; border-radius: 5px;">
      <div class="product-show p-4 w-100 d-flex flex-column align-items-center">
        <?= view('v2/components/Product', ['view_mode' => 'list']) ?>

        <?php if(!empty($all_attributes['calculatedNutrition'])) {
        ?>
        <div class="guideline-daily-amount mt-4 w-100">
          <h5 class="mb-4 fw-bold text-color-555 text-center" style="font-size: 20px;">Guideline Daily Amounts</h5>
          <h6 class="fw-bold text-color-555 text-center">
            <?php
              $calc_header = '';
              if(!empty($all_attributes['calculatedNutrition']['headers']['perServing'])) {
                $calc_header = $all_attributes['calculatedNutrition']['headers']['perServing'];
              }
              if(!empty($all_attributes['calculatedNutrition']['headers']['per100Used'])) {
                $calc_header = $all_attributes['calculatedNutrition']['headers']['per100Used'];
              }
              if(!empty($all_attributes['calculatedNutrition']['headers']['per100'])) {
                $calc_header = $all_attributes['calculatedNutrition']['headers']['per100'];
              }
              
              $product_type = stripos($calc_header, 'ml') >= 0 ? 'drink' : 'food';

              echo $calc_header;
            ?>
          </h6>
          <div class="d-flex justify-content-center mt-4" style='flex-wrap: wrap;'>
            <?php
              foreach(Nutrition_Criteria_Items as $key => $item) {
                $percent = 0;
                $contain_lvl = '';
                $founds = array_filter($all_attributes['calculatedNutrition']['rowData'], function($d) use($key) {
                    if(str_find($d['nameValue'], $key) !== false) return true;
                });

                if( !empty($founds) && count($founds) ) {
            ?>
              <div class="one-nutrition d-flex flex-column align-items-center mx-2 p-0 mb-3">
                <div class="p-4 d-flex flex-column align-items-center amount">
                  <div><?= $key ?></div>

                  <?php foreach($founds as $f) { ?>
                  <div class="fw-bold">
                    <?php
                      // To get unit
                      preg_match('/\((.*?)\)/', $f['nameValue'], $matches);
                      $unit = $matches[1];

                      if(!empty($f['per100'])) $amount = floatV($f['per100']['value']);
                      else if(!empty($f['per100Used'])) $amount = floatV($f['per100Used']['value']);
                      else if(!empty($f['perServing'])) $amount = floatV($f['perServing']['value']);

                      if($unit == $item['unit']) {
                        $criteria_field = "{$product_type}_criteria";
                        $percent = floatV($amount * 100 / $item['one-day-intake']);
                        if($product_type == 'drink' && !empty($item[$criteria_field])) {
                          if($amount < $item[$criteria_field]['low']['high']) $contain_lvl = 'Low';
                          else if($amount >= $item[$criteria_field]['med']['low'] && $amount < $item[$criteria_field]['med']['high']) $contain_lvl = 'Medium';
                          else if($amount > $item[$criteria_field]['high']['low']) $contain_lvl = 'High';
                        }
                      }

                      echo $amount . " " . $unit;
                    ?>
                  </div>
                  <?php } ?>
                </div>

                <div class="p-4 d-flex flex-column align-items-center container-lvl <?= $contain_lvl ?>">
                    <div class="fw-bold"><?= number_format($percent,1) ?>%</div>
                    <div class=""><?= $contain_lvl ?></div>
                </div>
              </div>
            <?php
                  }
                }
            ?>
            
            <?php
              }
            ?>
          </div>
        </div>

        <!-- Product Detail Information  -->
        <div class="product-detail-info w-100 mt-4">
          <h5 class="mb-4 fw-bold text-color-555" style="font-size: 20px;">Detail Information</h5>
          <?php if(!empty($detail)) {
          ?>
            <ul class="collapsible w-100">
              <li class="w-100 active">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Description</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <div><?= text2html($detail['description']) ?></div>
                  <?php if(!empty($all_attributes['alternativeDescription'])) {
                    foreach($all_attributes['alternativeDescription'] as $item) {
                      echo "<div class='fw-bold mt-4'>{$item['nameValue']}</div>";
                      echo "<p>{$item['text']}</p>";
                    }
                  }
                  ?>
                </div>
              </li>

              <?php if(!empty($all_attributes['productMarketing'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Marketing Information</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <p><?= text2html($all_attributes['productMarketing']) ?></p>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['allergyAdvice'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Allergy Advice</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <?php 
                    echo "<ul>";
                    foreach($all_attributes['allergyAdvice'] as $item) {
                      echo "<li><span>{$item['lookupValue']}</span><span class='fw-bold ms-2'>{$item['nameValue']}</span></li>";
                    }
                    echo "</ul>";
                  ?>
                </div>
              </li>
              <?php } ?>

              <!-- Alcolhol information (start) -->
              <?php if(!empty($all_attributes['alcoholType']) || !empty($all_attributes['alcoholUnitsOtherText'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Alcohol Information</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <?php 
                  if(!empty($all_attributes['alcoholType'])) {
                    echo "<div class='fw-bold'>Alcohol Type:</div>";
                    echo "<ul class='ms-2'>";
                    foreach($all_attributes['alcoholType'] as $at) {
                      echo "<li>{$at['lookupValue']}</li>";
                    }
                    echo "</ul>";
                  }
                  if(!empty($all_attributes['alcoholUnitsOtherText'])) {
                    echo "<div class='mt-2 fw-bold'>Alcohol Units Information:</div>";
                    echo "<ul class='ms-2'>";
                    foreach($all_attributes['alcoholUnitsOtherText'] as $a) {
                      echo "<li>$a</li>";
                    }
                    echo "</ul>";
                  }
                  ?>
                </div>
              </li>
              <?php } ?>
              <!-- Alcolhol information (end) -->

              <?php if(!empty($all_attributes['country'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Country Information</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <ul>
                  <?php
                    foreach($all_attributes['country'] as $item) {
                      echo "<li>";
                      echo "<span>{$item['nameValue']}:</span>";
                      echo "<span class='ms-2 fw-bold'>{$item['lookupValue']}</span>";
                      echo "</li>";
                    }
                  ?>
                  </ul>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['ingredients']) && count($all_attributes['ingredients']) > 0) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Ingredients</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <ul>
                  <?php
                  foreach($all_attributes['ingredients'] as $item) {
                  ?>
                    <li><?= text2html($item) ?></li>
                  <?php } ?>
                  </ul>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['allergenTagText'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Allergy Information</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <?= text2html($all_attributes['allergenTagText']) ?>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['preparationAndUsage'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Preparation and Usage</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <?= text2html($all_attributes['preparationAndUsage']) ?>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['storage'])) {?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Store</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <?= text2html($all_attributes['storage']) ?>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['upperAgeLimit'])) { ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Upper Age Limit</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <ul>
                    <?php
                      foreach($all_attributes['upperAgeLimit'] as $item) {
                    ?>
                    <li>
                      <span><?= $item['nameValue'] ?>: </span>
                      <span class="ms-2"><?= $item['text'] ?>&nbsp; <?= $item['lookupValue'] ?></span>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['lowerAgeLimit'])) { ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Lower Age Limit</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <ul>
                    <?php
                      foreach($all_attributes['lowerAgeLimit'] as $item) {
                    ?>
                    <li>
                      <span><?= $item['nameValue'] ?>: </span>
                      <span class="ms-2"><?= $item['text'] ?>&nbsp; <?= $item['lookupValue'] ?></span>
                    </li>
                    <?php } ?>
                  </ul>
                </div>
              </li>
              <?php } ?>

              <?php if(!empty($all_attributes['nutrition'])) {
                $td_width = 100 / (count($all_attributes['nutrition']['columnHeaders']) + 1);
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Nutritional Information</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4">
                  <table>
                    <thead>
                      <tr>
                        <th style="width:<?= $td_width ?>%">Typical Values</th>
                        <?php
                        foreach($all_attributes['nutrition']['columnHeaders'] as $column) {
                        ?>
                        <th style="width:<?= $td_width ?>%"><?= $column ?></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        foreach($all_attributes['nutrition']['rowData'] as $data) {
                      ?>
                      <tr>
                        <td><?= !empty($data['nutrient']) ? $data['nutrient'] : '-' ?></td>
                        <?php
                        foreach($data['values'] as $v) {
                        ?>
                        <td><?= $v ?></td>
                        <?php }?>
                      </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </li>
              <?php } ?>

              <?php 
              if( !empty($all_attributes['companyName']) || !empty($all_attributes['manufacturersAddress']) || !empty($all_attributes['returnTo'])) {
              ?>
              <li class="w-100">
                <div class="collapsible-header d-flex align-items-center">
                  <div class="flex-fluid">Name & Address</div>
                  <div><img src="/images/icons/line-angle-down-white.svg" style="width:15px; height: 15px;"/></div>
                </div>
                <div class="collapsible-body p-4 px-8">
                  <?php if(!empty($all_attributes['companyName'])) {
                    echo "<div>";
                    echo "  <h6 class='fw-bold'>Company Name</h6>";
                    echo "  <ul class='ms-2'>";
                    foreach($all_attributes['companyName'] as $item) {
                      echo $item;
                    }
                    echo "  </ul>";
                    echo "</div>";
                  }
                  ?>

                  <?php if(!empty($all_attributes['manufacturersAddress'])) {
                  ?>
                  <div class='mt-4'>
                    <h6 style="font-weight:bold;">Manufacturer Address</h6>
                    <div><?= text2html($all_attributes['manufacturersAddress']) ?></div>
                  </div>
                  <?php } ?>

                  <?php if(!empty($all_attributes['returnTo'])) {
                  ?>
                  <h6 style="font-weight:bold;">Return To</h6>
                  <div><?= text2html($all_attributes['returnTo']) ?></div>
                  <?php } ?>
                </div>
              </li>
              <?php
              }
              ?>

            </ul>
          <?php } else {
          ?>
            No Detail Information
          <?php } ?>
        </div>
        <!-- Product Detail Information (end)-->

        <div class="disclaimer mt-4">
          <h6 class='fw-bold'>Disclaimer:</h6>
          <p>Whilst every care has been taken to ensure product information is correct, food products are constantly being reformulated, so ingredients, nutrition content, dietary and allergens may change. You should always read the product label and not rely solely on the information provided on the website. If you have any queries, or you'd like advice, please contact the product manufacturer. Although product information is regularly updated, we are unable to accept liability for any incorrect information. This does not affect your statutory rights. This information is supplied for as is for personal use only, and may not be reproduced in any way without our prior written consent and without due acknowledgement.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function(){
  })

  function inc_quantity(mode, prod_id, prod_code, prod_desc) {
    cart_inc_quantity(mode, prod_id, prod_code, 0, prod_desc);
  }

  var arr = <?=  !empty($detail) ? json_encode($detail) : '' ?>;
  var all_attributes = <?= !empty($all_attributes) ? json_encode($all_attributes) : '' ?>;
  console.log(all_attributes);
  console.log(arr);
</script>

<?php echo view("partial/footer"); ?>
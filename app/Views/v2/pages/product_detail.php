<?= $this->extend('v2/layout/main_layout') ?>

<?= $this->section('css') ?>
<style>
.guideline-daily-amount {
    .one-nutrition {
        min-width: 100px;
        font-size: 14px;

        @media only screen and (max-width: 772px) {
            font-size: 12px;
            min-width: 50px !important;
            margin-left: 3px !important;
            margin-right: 3px !important;

            .amount,
            .container-lvl {
                padding-left: 2px !important;
                padding-right: 2px !important;
            }

            .amount {
                border-top-left-radius: 50% 15% !important;
                border-top-right-radius: 50% 15% !important;
                height: 80px !important;
            }

            .container-lvl {
                border-bottom-left-radius: 50% 15% !important;
                border-bottom-right-radius: 50% 15% !important;
                height: 60px !important;
            }
        }

        .divider {
            width: 100%;
            height: 1px;
            background: #888;
        }

        .amount {
            border-top-left-radius: 50% 25%;
            border-top-right-radius: 50% 25%;
            border: 1px solid #888;
            width: 100%;
            border-bottom: none;
            height: 100px;
        }

        .container-lvl {
            border-bottom-left-radius: 50% 25%;
            border-bottom-right-radius: 50% 25%;
            border: 1px solid #888;
            width: 100%;
            height: 80px;

            &.Low {
                background: #2fe92f;
            }

            &.Medium {
                background: rgb(255, 196, 0);
            }

            &.High {
                background: red;
                color: white;
            }
        }
    }
}
.one-product {
    width: 100%;
    max-width: 800px;
    padding: 20px !important;
}
.product-detail-info {
    ul.collapsible {
        li {
            .collapsible-header {
                background: #ff3535;
                color: #ddd;

                img {
                    transition: all 0.3s;
                }
            }

            &.active .collapsible-header {
                color: white;
                font-weight: bold;

                img {
                    transform: rotate(-180deg);
                }
            }

            .collapsible-body {
                padding: 10px;
                min-height: 60px !important;

                table {
                    border: 1px solid #ddd;
                    border-radius: 5px;
                }
            }
        }
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex flex-column justify-content-center p-4">
    <div class="product-show w-100 d-flex flex-column align-items-center">
        <?= view('v2/components/Product', ['view_mode' => 'list']) ?>

        <?php if(!empty($all_attributes['calculatedNutrition'])) { ?>
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
            <?php foreach(Nutrition_Criteria_Items as $key => $item) {
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
            <?php } } ?>
            </div>
        </div>
        <?php } ?>

        <!-- Product Detail Information  -->
        <div class="product-detail-info w-100 mt-4">
            <h5 class="mb-4 fw-bold text-color-555" style="font-size: 20px;">Detail Information</h5>
            <div class="accordion" id="detail-accordion">
                <?php if(!empty($detail) || !empty($all_attributes['alternativeDescription'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-description">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-description" aria-expanded="true" aria-controls="collapse-description">
                            Description
                        </button>
                    </h6>
                    <div id="collapse-description" class="accordion-collapse collapse show" aria-labelledby="heading-description" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <div><?= text2html($detail['description']) ?></div>
                            <?php if(!empty($all_attributes['alternativeDescription'])) {
                                foreach($all_attributes['alternativeDescription'] as $item) {
                                    echo "<div class='fw-bold mt-4'>{$item['nameValue']}</div>";
                                    echo "<p>{$item['text']}</p>";
                                }
                            }
                            ?>                                
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['productMarketing'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-marketing">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-marketing" aria-expanded="true" aria-controls="collapse-marketing">
                            Marketing Information    
                        </button>
                    </h6>
                    <div id="collapse-marketing" class="accordion-collapse collapse" aria-labelledby="heading-marketing" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <p><?= text2html($all_attributes['productMarketing'] ?? '') ?></p>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['allergyAdvice'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-allergy-advice">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-allergy-advice" aria-expanded="true" aria-controls="collapse-allergy-advice">
                            Allergy Advice                                
                        </button>
                    </h6>
                    <div id="collapse-allergy-advice" class="accordion-collapse collapse" aria-labelledby="heading-allergy-advice" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <ul>
                                <?php foreach($all_attributes['allergyAdvice'] as $item) { ?>
                                <li><span><?=$item['lookupValue']?></span><span class='fw-bold ms-2'><?=$item['nameValue']?></span></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['alcoholType']) || !empty($all_attributes['alcoholUnitsOtherText'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-alcohol">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-alcohol" aria-expanded="true" aria-controls="collapse-alcohol">
                            Alcohol Information    
                        </button>
                    </h6>
                    <div id="collapse-alcohol" class="accordion-collapse collapse" aria-labelledby="heading-alcohol" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                        <?php  if(!empty($all_attributes['alcoholType'])) 
                        {
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
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['country'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-country">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-country" aria-expanded="true" aria-controls="collapse-country">
                            Country Information
                        </button>
                    </h6>
                    <div id="collapse-country" class="accordion-collapse collapse" aria-labelledby="heading-country" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
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
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['ingredients']) && count($all_attributes['ingredients']) > 0) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-ingredient">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-ingredient" aria-expanded="true" aria-controls="collapse-ingredient">
                            Ingredients          
                        </button>
                    </h6>
                    <div id="collapse-ingredient" class="accordion-collapse collapse" aria-labelledby="heading-ingredient" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <ul>
                            <?php
                            foreach($all_attributes['ingredients'] as $item) {
                            ?>
                                <li><?= text2html($item) ?></li>
                            <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['allergenTagText'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-allergy-tag">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-allergy-tag" aria-expanded="true" aria-controls="collapse-allergy-tag">
                            Allergy Information          
                        </button>
                    </h6>
                    <div id="collapse-allergy-tag" class="accordion-collapse collapse" aria-labelledby="heading-allergy-tag" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <?= text2html($all_attributes['allergenTagText']) ?>
                        </div>
                    </div>
                </div>                    
                <?php } ?>

                <?php if(!empty($all_attributes['preparationAndUsage'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-prepare-usage">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-prepare-usage" aria-expanded="true" aria-controls="collapse-prepare-usage">
                            Preparation and Usage          
                        </button>
                    </h6>
                    <div id="collapse-prepare-usage" class="accordion-collapse collapse" aria-labelledby="heading-prepare-usage" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <?= text2html($all_attributes['preparationAndUsage']) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['storage'])) {?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-store">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-store" aria-expanded="true" aria-controls="collapse-store">
                            Store          
                        </button>
                    </h6>
                    <div id="collapse-store" class="accordion-collapse collapse" aria-labelledby="heading-store" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <?= text2html($all_attributes['storage']) ?>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['upperAgeLimit'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-upper-age-limit">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-upper-age-limit" aria-expanded="true" aria-controls="collapse-upper-age-limit">
                            Upper Age Limit          
                        </button>
                    </h6>
                    <div id="collapse-upper-age-limit" class="accordion-collapse collapse" aria-labelledby="heading-upper-age-limit" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
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
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['lowerAgeLimit'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-lower-age-limit">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-lower-age-limit" aria-expanded="true" aria-controls="collapse-lower-age-limit">
                            Lower Age Limit          
                        </button>
                    </h6>
                    <div id="collapse-lower-age-limit" class="accordion-collapse collapse" aria-labelledby="heading-lower-age-limit" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
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
                    </div>
                </div>
                <?php } ?>

                <?php if(!empty($all_attributes['nutrition'])) {
                    $td_width = 100 / (count($all_attributes['nutrition']['columnHeaders']) + 1);
                ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-nutrition">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-nutrition" aria-expanded="true" aria-controls="collapse-nutrition">
                            Nutritional Information            
                        </button>
                    </h6>
                    <div id="collapse-nutrition" class="accordion-collapse collapse" aria-labelledby="heading-nutrition" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <table class="table order-primary-table">
                                <thead>
                                    <tr>
                                        <th style="width:<?= $td_width ?>%">Typical Values</th>
                                        <?php foreach($all_attributes['nutrition']['columnHeaders'] as $column) { ?>
                                            <th style="width:<?= $td_width ?>%"><?= $column ?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($all_attributes['nutrition']['rowData'] as $data) { ?>
                                    <tr>
                                        <td><?= !empty($data['nutrient']) ? $data['nutrient'] : '-' ?></td>
                                        <?php foreach($data['values'] as $v) { ?>
                                            <td><?= $v ?></td>
                                        <?php }?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if( !empty($all_attributes['companyName']) || !empty($all_attributes['manufacturersAddress']) || !empty($all_attributes['returnTo'])) { ?>
                <div class="accordion-item">
                    <h6 class="accordion-header" id="heading-name-address">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-name-address" aria-expanded="true" aria-controls="collapse-name-address">
                            Name & Address          
                        </button>
                    </h6>
                    <div id="collapse-name-address" class="accordion-collapse collapse" aria-labelledby="heading-name-address" data-bs-parent="#detail-accordion">
                        <div class="accordion-body">
                            <?php if(!empty($all_attributes['companyName'])) {
                                echo "<div>";
                                echo "  <h6 class='fw-bold'>Company Name</h6>";
                                foreach($all_attributes['companyName'] as $item) {
                                echo "<p>" . $item . "</p>";
                                }
                                echo "</div>";
                            }
                            ?>

                            <?php if(!empty($all_attributes['manufacturersAddress'])) { ?>
                            <div class='mt-4'>
                                <h6 style="font-weight:bold;">Manufacturer Address</h6>
                                <div><?= text2html($all_attributes['manufacturersAddress']) ?></div>
                            </div>
                            <?php } ?>

                            <?php if(!empty($all_attributes['returnTo'])) { ?>
                            <h6 style="font-weight:bold;">Return To</h6>
                            <div><?= text2html($all_attributes['returnTo']) ?></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>                    
            </div>
        </div>

        <div class="disclaimer mt-4">
            <h6 class='fw-bold'>Disclaimer:</h6>
            <p>Whilst every care has been taken to ensure product information is correct, food products are constantly being reformulated, so ingredients, nutrition content, dietary and allergens may change. You should always read the product label and not rely solely on the information provided on the website. If you have any queries, or you'd like advice, please contact the product manufacturer. Although product information is regularly updated, we are unable to accept liability for any incorrect information. This does not affect your statutory rights. This information is supplied for as is for personal use only, and may not be reproduced in any way without our prior written consent and without due acknowledgement.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script>
  var arr = <?=  !empty($detail) ? json_encode($detail) : '' ?>;
  var all_attributes = <?= !empty($all_attributes) ? json_encode($all_attributes) : '' ?>;
</script>
<?= $this->endSection() ?>
<div class="sidebar collapsed" id="category-sidebar">
    <div class="sidebar-content">
        <ul>
        <?php foreach($top_categories as $top_category) { ?>
            <li class="one-top-category">
                <a class="category-link <?= !empty($active_categories['top']) && $active_categories['top']['category_id'] == $top_category['category_id'] ? 'active' : '' ?>" 
                    href="#" 
                    data-bs-auto-close="outside" 
                    data-category-id="<?= $top_category['category_id'] ?>"
                >
                    <?= $top_category['alias'] ?>
                    <div class="toggle-show-hide" data-show-hide-target="#submenu-<?= $top_category['category_id'] ?>">
                    </div>
                </a>
                <div class="p-1 submenu d-none" id="submenu-<?= $top_category['category_id'] ?>">
                    <ul class="list-unstyled">
                        <?php foreach($top_category['sub_categories'] as $sub_category) { ?>
                            <li> 
                                <a class="category-link <?= !empty($active_categories['sub']) && $active_categories['sub']['category_id'] == $sub_category['category_id'] ? 'active' : '' ?>" 
                                    href="#" 
                                    data-category-id="<?= $sub_category['category_id'] ?>"
                                >
                                    <?= $sub_category['category_name'] ?>
                                </a> 
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </li>
        <?php } ?>
        </ul>
    </div>
</div>
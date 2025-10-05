<div id="actions">
    <ul class="pagination">
        <li class="go_page">
            <input 
                type="text" 
                id="go_page" 
                name="page" 
                class="curd_page" 
                value="<?= $pagination['curd_page']?>" 
                size="4" 
                onkeyup="set_direct_page(event)" 
                onclick="this.select();" 
                style="height:2rem; background:#fbfbfb !important;"
            >
            <i class="material-icons go" id="go_btn">slideshow</i>
        </li>
        <li class="waves-effect" id="prev">
            <a href="javascript:void();" onclick="prevPage('<?= $pagination['url'] ?>');">
                <i class="material-icons">chevron_left</i></a>
        </li>
        <?php
            $t = $pagination['total_page']; 
            for($i=1; $i<=$t; $i++){ 
                $h = intval($t/2);
                if($pagination['curd_page'] > 3){$h = $pagination['curd_page']; }
                if( $i < 4 || ($i <= $h +1 && $i>= $h -1) || ($i == 4 && $t==6 ) ){?>
                    <li class="waves-effect num" id="p<?= $i ?>">
                    <a href="javascript:void()" onclick="goto_page(<?= $i ?>)">
                        <?= $i ?>
                    </a>
                    </li> 
                <?php } else if ( $i == $t ){ ?>
                    <li class="waves-effect num" id="p<?= $i ?>">
                    <a href="javascript:void()" onclick="goto_page(<?= $i ?>)"><?= $i ?></a>
                    </li> 
                <?php } else if( $i == 5 || $i == $t-1 ){ ?>
                    <li class="disabled"><a><i class="material-icons">more_horiz</i></a></li> 
                <?php }
        }?>
        <li class="waves-effect" id="next">
            <a href="javascript:void();" onclick="nextPage();" >
                <i class="material-icons">chevron_right</i>
            </a>
        </li>
    </ul>
    <br style="clear:both;">
</div>

<script type="text/javascript">
var url = "<?= $pagination['url'] ?>";

function prevPage() {
    
}

function nextPage() {

}

function goto_page(page) {

}

</script>
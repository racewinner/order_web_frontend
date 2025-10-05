<div class="d-none d-md-block w-100">
	<h5>Unfortunately, The Following Products Are Not Found</h5>
	<p class="d-flex align-items-center justify-content-center">
        You can search for similar products by clicking on search icon
        <span class="ms-1"><i class="material-icons search" style="color:var(--primary-blue)">search</i></span>
    </p>
	<table striped class="w-100">
		<thead>
			<tr>
				<th>Description</th>
				<th>SIZE/LIFE</th>
				<th>UOS</th>
				<th>BRAND</th>
				<th>QTY</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach($unknown_products as $up) {
			?>
			<tr>
				<td><?= $up->prod_desc ?></td>
				<td><?= $up->prod_pack_desc ?></td>
				<td><?= $up->prod_uos ?></td>
				<td><?= $up->brand ?? "" ?></td>
				<td><?= $up->quantity ?></td>
				<td class="action">
					<i class="material-icons delete" data-id="<?= $up->id ?>">close</i>
                    <i class="material-icons search" data-description="<?= $up->prod_desc ?>">search</i>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<div class="d-block d-md-none w-100">
    <h5>Unfortunately, The Following Products Are Not Found</h5>
	<p class="d-flex align-items-center justify-content-center">
        You can search for similar products by clicking on search icon
        <span class="ms-1"><i class="material-icons search" style="color:var(--primary-blue)">search</i></span>
    </p>
	<table>
        <thead>
            <tr>
                <th>Product Description</th>
                <th>Orders Qty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                foreach($unknown_products as $up) {
            ?>
            <tr>
                <td>
                    <div>
                        <span>Brand:</span>
                        <span class="ms-2"><?= $up->brand ?></span>
                    </div>
                    <div>
                        <span>UOS:</span>
                        <span class="ms-2"><?= $up->prod_uos ?></span>
                    </div>
                    <div>
                        <span>SIZE/LIFE:</span>
                        <span class="ms-2"><?= $up->prod_pack_desc ?></span>
                    </div>
                    <div>
                        <?= $up->prod_desc ?>
                    </div>
                </td>
                <td>
                    <?= $up->quantity ?>
                </td>
                <td class="action">
                    <i class="material-icons delete" data-id="<?= $up->id ?>">close</i>
                    <i class="material-icons search" data-description="<?= $up->prod_desc ?>">search</i>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

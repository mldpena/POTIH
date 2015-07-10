<div class="cpanel-header">
	<img src="<?= base_url().IMG ?>hitop-main.png">
	<div class="title">Hi - Top Merchandising, Inc.</div>
</div>
<div class="main-content cpanel">
	<?php if($section_permissions['data']) : ?>

	<div class="content-form">
		<div class="form-header">
			Data
		</div>
		<div class="form-body default">
			<?php if($page_permissions['product']) : ?>

			<a href="<?= base_url() ?>product/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>product.png">
					<div class="btn-title">Product</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['material']) : ?>

			<a href="<?= base_url() ?>material/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>materialtype.png">
					<div class="btn-title">Material Type</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['subgroup']) : ?>

			<a href="<?= base_url() ?>subgroup/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>subgrouping.png">
					<div class="btn-title">Sub Grouping</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['user']) : ?>

			<a href="<?= base_url() ?>user/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>user.png">
					<div class="btn-title">User</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['branch']) : ?>

			<a href="<?= base_url() ?>branch/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>branch.png">
					<div class="btn-title">Branch</div>
				</div>
			</a>

			<?php endif; ?>

		</div>
	</div>

	<?php endif; ?>

	<?php if($section_permissions['purchase']) : ?>

	<div class="content-form">
		<div class="form-header">
			Purchase
		</div>
		<div class="form-body default">
			<?php if($page_permissions['purchase']) : ?>
			
			<a href="<?= base_url() ?>purchase/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>purchaseorder.png">
					<div class="btn-title">Purchase Order</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['purchase_receive']) : ?>
			
			<a href="<?= base_url() ?>poreceive/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>purchasereceive.png">
					<div class="btn-title">Purchase Receive</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['purchase_return']) : ?>

			<a href="<?= base_url() ?>purchaseret/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>purchasereturn.png">
					<div class="btn-title">Purchase Return</div>
				</div>
			</a>

			<?php endif; ?>
			
		</div>
	</div>

	<?php endif; ?>

	<?php if($section_permissions['delivery']) : ?>

	<div class="content-form">
		<div class="form-header">
			Delivery
		</div>
		<div class="form-body default">
			<?php if($page_permissions['stock_delivery']) : ?>

			<a href="<?= base_url() ?>delivery/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>stockdelivery.png">
					<div class="btn-title">Item Delivery</div>
				</div>
			</a>

			<?php endif; ?>

			<a href="<?= base_url() ?>delivery/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>stockdelivery.png">
					<div class="btn-title">Stock Request to Other Branches</div>
				</div>
			</a>

			<?php if($page_permissions['stock_receive']) : ?>

			<a href="<?= base_url() ?>delreceive/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>stockreceive.png">
					<div class="btn-title">Stock Receive</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['customer_receive']) : ?>

			<a href="<?= base_url() ?>custreceive/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>customerreceive.png">
					<div class="btn-title">Customer Receive</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['customer_return']) : ?>
			
			<a href="<?= base_url() ?>return/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>return.png">
					<div class="btn-title">Customer Return</div>
				</div>
			</a>

			<?php endif; ?>

		</div>
	</div>

	<?php endif; ?>
	
	<?php if($section_permissions['return']) : ?>

	<div class="content-form">
		<div class="form-header">
			Damage
		</div>
		<div class="form-body default">

			<?php if($page_permissions['damage']) : ?>

			<a href="<?= base_url() ?>damage/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>damage.png">
					<div class="btn-title">Damage</div>
				</div>
			</a>

			<?php endif; ?>

		</div>
	</div>

	<?php endif; ?>

	<div class="content-form">
		<div class="form-header">
			Pickup
		</div>
		<div class="form-body default">
			<a href="<?= base_url() ?>adjust/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>inventoryadjust.png">
					<div class="btn-title">Pickup Assortment</div>
				</div>
			</a>

			<?php if($page_permissions['release']) : ?>

			<a href="<?= base_url() ?>release/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>warehouserelease.png">
					<div class="btn-title">Warehouse Release</div>
				</div>
			</a>

			<a href="<?= base_url() ?>release/pickup">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>warehouserelease.png">
					<div class="btn-title">Pick-Up Summary</div>
				</div>
			</a>

			<?php endif; ?>
		</div>
	</div>

	<?php if($section_permissions['others']) : ?>

	<div class="content-form">
		<div class="form-header">
			Inventory Adjust
		</div>
		<div class="form-body default">
			<?php if($page_permissions['inventory_adjust']) : ?>

			<a href="<?= base_url() ?>adjust/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>inventoryadjust.png">
					<div class="btn-title">Inventory Adjust</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['pending_adjust']) : ?>

			<a href="<?= base_url() ?>pending/list">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>pendinginventoryadjust.png">
					<div class="btn-title">Pending Inventory Adjust</div>
				</div>
			</a>

			<?php endif; ?>

		</div>
	</div>

	<?php endif; ?>

	<?php if($section_permissions['reports']) : ?>

	<div class="content-form">
		<div class="form-header">
			Reports
		</div>
		<div class="form-body default">
			<?php if($page_permissions['inventory_warning']) : ?>

			<a href="<?= base_url() ?>product/warning">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>productinventorywarning.png">
					<div class="btn-title">Product Inventory Warning</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['branch_inventory']) : ?>

			<a href="<?= base_url() ?>product/inventory">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>productbranchinventory.png">
					<div class="btn-title">Product Branch Inventory</div>
				</div>
			</a>

			<?php endif; ?>

			<?php if($page_permissions['transaction_summary']) : ?>

			<a href="<?= base_url() ?>product/summary">
				<div class="each-btn" align="center">
					<img src="<?= base_url().IMG ?>producttransactionsummary.png">
					<div class="btn-title">Product Transaction Summary</div>
				</div>
			</a>

			<?php endif; ?>

		</div>
	</div>

	<?php endif; ?>
</div>
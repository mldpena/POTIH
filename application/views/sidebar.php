<div class="sidebar pull-left" align="center">
	<a href="<?= base_url() ?>controlpanel">
		<div class="sidebar-logo">
			<img src="<?= base_url().IMG ?>hitop-main.png" class="mainlogo">
		</div>
	</a>
	<div class="sidebar-links-panel">
		<?php if($section_permissions['data']) : ?>

		<div class="sidebar-group" id="data-group">
			<div class="header subgroup-toggle">
				<div>Data</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['product']) : ?>

				<a href="<?= base_url() ?>product/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>product.png">
						<div>Product</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['material']) : ?>

				<a href="<?= base_url() ?>material/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>materialtype.png">
						<div>Material Type</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['subgroup']) : ?>

				<a href="<?= base_url() ?>subgroup/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>subgrouping.png">
						<div>Sub Grouping</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['user']) : ?>

				<a href="<?= base_url() ?>user/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>user.png">
						<div>User</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['branch']) : ?>

				<a href="<?= base_url() ?>branch/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>branch.png">
						<div>Branch</div>
					</div>
				</a>

				<?php endif; ?>
			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['purchase']) : ?>

		<div class="sidebar-group" id="purchase-group">
			<div class="header subgroup-toggle">
				<div>Purchase</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['purchase']) : ?>

				<a href="<?= base_url() ?>purchase/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>purchaseorder.png">
						<div>Purchase Order</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['purchase_receive']) : ?>

				<a href="<?= base_url() ?>poreceive/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>purchasereceive.png">
						<div>Purchase Receive</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['customer_return']) : ?>

				<a href="<?= base_url() ?>return/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>return.png">
						<div>Customer Return</div>
					</div>
				</a>

				<?php endif; ?>

			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['return']) : ?>

		<div class="sidebar-group" id="return-group">
			<div class="header subgroup-toggle">
				<div>Return</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['damage']) : ?>

				<a href="<?= base_url() ?>damage/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>damage.png">
						<div>Damage</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['purchase_return']) : ?>

				<a href="<?= base_url() ?>purchaseret/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>purchasereturn.png">
						<div>Purchase Return</div>
					</div>
				</a>

				<?php endif; ?>

			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['delivery']) : ?>

		<div class="sidebar-group" id="delivery-group">
			<div class="header subgroup-toggle">
				<div>Delivery and Stock Transferring</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['stock_delivery']) : ?>

				<a href="<?= base_url() ?>delivery/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>stockdelivery.png">
						<div>Stock Delivery</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['stock_receive']) : ?>

				<a href="<?= base_url() ?>delreceive/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>stockreceive.png">
						<div>Stock Receive</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['customer_receive']) : ?>

				<a href="<?= base_url() ?>custreceive/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>customerreceive.png">
						<div>Customer Receive</div>
					</div>
				</a>

				<?php endif; ?>

			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['others']) : ?>

		<div class="sidebar-group" id="others-group">
			<div class="header subgroup-toggle">
				<div>Others</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['inventory_adjust']) : ?>

				<a href="<?= base_url() ?>adjust/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>inventoryadjust.png">
						<div>Inventory Adjust</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['pending_adjust']) : ?>

				<a href="<?= base_url() ?>pending/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>pendinginventoryadjust.png">
						<div>Pending Inventory Adjust</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['release']) : ?>

				<a href="<?= base_url() ?>release/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>warehouserelease.png">
						<div>Warehouse Release</div>
					</div>
				</a>

				<?php endif; ?>
			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['reports']) : ?>

		<div class="sidebar-group" id="report-group">
			<div class="header subgroup-toggle">
				<div>Report</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['inventory_warning']) : ?>

				<a href="<?= base_url() ?>product/warning">
					<div class="link">
						<img src="<?= base_url().IMG ?>productinventorywarning.png">
						<div>Product Inventory Warning</div>
					</div>
				</a>

				<?php endif; ?>
				
				<?php if($page_permissions['branch_inventory']) : ?>

				<a href="<?= base_url() ?>product/inventory">
					<div class="link">
						<img src="<?= base_url().IMG ?>productbranchinventory.png">
						<div>Product Branch Inventory</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['transaction_summary']) : ?>

				<a href="<?= base_url() ?>product/summary">
					<div class="link">
						<img src="<?= base_url().IMG ?>productbranchinventory.png">
						<div>Product Transaction Summary</div>
					</div>
				</a>

				<?php endif; ?>

			</div>
		</div>

		<?php endif; ?>

	</div>
</div>
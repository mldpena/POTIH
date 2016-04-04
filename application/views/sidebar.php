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

				<?php if($page_permissions['customer']) : ?>

				<a href="<?= base_url() ?>customer/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>customer.png">
						<div>Customer</div>
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

		<?php if($section_permissions['sales']) : ?>

		<div class="sidebar-group" id="sales-group">
			<div class="header subgroup-toggle">
				<div>Sales</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['sales_reservation']) : ?>

				<a href="<?= base_url() ?>reservation/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>sales-reservation.png">
						<div>Sales Reservation</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['sales']) : ?>

				<a href="<?= base_url() ?>sales/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>sales.png">
						<div>Sales Invoice</div>
					</div>
				</a>

				<?php endif; ?>
			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['delivery']) : ?>

		<div class="sidebar-group" id="delivery-group">
			<div class="header subgroup-toggle">
				<div>Delivery</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">

				<?php if($page_permissions['stock_request_to']) : ?>

				<a href="<?= base_url() ?>requestto/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>itemrequeststootherbranches.png">
						<div>Request Item from Other Branches</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['stock_request_from']) : ?>

				<a href="<?= base_url() ?>requestfrom/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>itemrequestsfromotherbranches.png">
						<div>Item Requested by Other Branches</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['stock_delivery']) : ?>

				<a href="<?= base_url() ?>delivery/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>stockdelivery.png">
						<div>Item Delivery</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['stock_receive']) : ?>

				<a href="<?= base_url() ?>delreceive/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>stockreceive.png">
						<div>Item Receive</div>
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

		<?php if($section_permissions['damage']) : ?>

		<div class="sidebar-group" id="damage-group">
			<div class="header subgroup-toggle">
				<div>Damage</div>
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

			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['pickup']) : ?>

		<div class="sidebar-group" id="pickup-group">
			<div class="header subgroup-toggle">
				<div>Pick-Up</div>
				<div><i class="fa fa-plus-square"></i></div>
			</div>
			<div class="link-menu">
				<?php if($page_permissions['assortment']) : ?>

				<a href="<?= base_url() ?>assort/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>pickupassortment.png">
						<div>Pick-Up Assortment</div>
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

				<?php if($page_permissions['pickup']) : ?>

				<a href="<?= base_url() ?>pickup/list">
					<div class="link">
						<img src="<?= base_url().IMG ?>pickupsummary.png">
						<div>Pick-Up Summary</div>
					</div>
				</a>

				<?php endif; ?>

			</div>
		</div>

		<?php endif; ?>

		<?php if($section_permissions['adjust']) : ?>

		<div class="sidebar-group" id="adjust-group">
			<div class="header subgroup-toggle">
				<div>Inventory Adjust</div>
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
						<img src="<?= base_url().IMG ?>producttransactionsummary.png">
						<div>Product Transaction Summary</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['sales_report']) : ?>

				<a href="<?= base_url() ?>sales/report">
					<div class="link">
						<img src="<?= base_url().IMG ?>sales-report.png">
						<div>Sales Report</div>
					</div>
				</a>

				<?php endif; ?>

				<?php if($page_permissions['book_report']) : ?>

				<a href="<?= base_url() ?>sales/book_report">
					<div class="link">
						<img src="<?= base_url().IMG ?>sales-book-report.png">
						<div>Sales Book Report</div>
					</div>
				</a>

				<?php endif; ?>
			</div>
		</div>

		<?php endif; ?>

	</div>
</div>
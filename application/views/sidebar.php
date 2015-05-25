<script type="text/javascript">
	$(function(){
		var currentUrl = window.location.href;
		$(".sidebar-group a[href='"+currentUrl+"'] .link").css({
			"background" : "#ecffe8",
			"border-left" : "2px #00923F solid"
		});

		$(".sidebar-group a[href='"+currentUrl+"']").parent().css("display", "block");
	})
</script>
<div class="sidebar pull-left" align="center">
	<a href="controlpanel.php">
		<div class="sidebar-logo">
			<img src="<?= base_url().IMG ?>hitop-main.png" class="mainlogo">
		</div>
	</a>
	<div class="sidebar-links-panel">
		<div class="sidebar-group" id="data-group">
			<div class="header">Data</div>
			<a href="<?= base_url() ?>product/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>product.png">
					<div>Product</div>
				</div>
			</a>
			<a href="<?= base_url() ?>material/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>materialtype.png">
					<div>Material Type</div>
				</div>
			</a>
			<a href="<?= base_url() ?>subgroup/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>subgrouping.png">
					<div>Sub Grouping</div>
				</div>
			</a>
			<a href="<?= base_url() ?>user/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>user.png">
					<div>User</div>
				</div>
			</a>
			<a href="<?= base_url() ?>branch/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>branch.png">
					<div>Branch</div>
				</div>
			</a>
		</div>
		<div class="sidebar-group" id="purchase-group">
			<div class="header">Purchase</div>
			<a href="<?= base_url() ?>purchaseorder/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>purchaseorder.png">
					<div>Purchase Order</div>
				</div>
			</a>
			<a href="<?= base_url() ?>purchasereceive/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>purchasereceive.png">
					<div>Purchase Receive</div>
				</div>
			</a>
			<a href="<?= base_url() ?>damage/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>damage.png">
					<div>Damage</div>
				</div>
			</a>
			<a href="<?= base_url() ?>return/list">
				<div class="link">
					<img src="<?= base_url().IMG ?>purchasereturn.png">
					<div>Return</div>
				</div>
			</a>
		</div>
	</div>
</div>
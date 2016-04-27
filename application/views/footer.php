			<?php if ($page != 'login'): ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="container-fluid footer">
				Hi-Top Copyright &copy; <?= date('Y') ?>. All Rights Reserved.<br>
				Powered by Nelsoft Technology Inc.
			</div>
		</div>
		<script type="text/javascript" src="<?= base_url().SCRIPTS ?>helpers/element_helper.js"></script>   
		<script type="text/javascript" src="<?= base_url().SCRIPTS ?>helpers/error_helper.js"></script>    
		<script type="text/javascript" src="<?= base_url().SCRIPTS ?>helpers/table.helper.js"></script>   
		<script type="text/javascript" src="<?= base_url().SCRIPTS ?>enum.js"></script>   
		<?php if(isset($script)) { require_once(SCRIPTS.$script); } ?>
	</body>	
</html>
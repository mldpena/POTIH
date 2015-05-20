			<?php
				if ($page != 'login') {
			?>
					</div>
				</div>
			<?php
				}
			?>
			<div class="container-fluid footer">
				Hi-Top Copyright &copy; <?= date('Y') ?>. All Rights Reserved.<br>
				Powered by Nelsoft Technology Inc.
			</div>
		</div>
		<?php if(isset($script)) { require_once(SCRIPTS.$script); } ?>
		<?php require_once(SCRIPTS.'helpers/table_helper_js.php') ?>
		<?php require_once(SCRIPTS.'helpers/error_helper_js.php') ?>
		<?php require_once(SCRIPTS.'helpers/element_helper_js.php') ?>
	</body>	
</html>
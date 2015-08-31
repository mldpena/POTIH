/**
 * Version: 1.0
 * Added Helper functions for MYJSTABLE. Current method supports js table, and other methods were specifically 
 * created for Hi - Top Project.
 **********************************************************
 * Created by Lawrence Pena.
 * Date Created: 6/16/2015
 * Version: 1.0
 */

var ProductType = {
	Stock : 1,
	NonStock : 0
};

var InventoryState = {
	Sufficient  : 0,
	Minimum     : 1,
	Negative    : 2
}

var InventoryCheckerType = {
	MaxInv  : 1,
	MinInv : 0
}

var TableHelper = function(tableOptions,options) {

	this._flag = 0;

	this._jsTable;
	this._jsTableArray;
	this.globalRowIndex = 0;
	this.globalId = 0;

	this._settings = { 
		updateIconClass : 'imgupdate',
		deleteIconClass : 'imgdel',
		editIconClass   : 'imgedit',
		memoClass   : 'txtmemo',
		deleteButtonId   : 'delete',
		saveButtonId      : 'save',
		quantityClass : 'txtqty',
		deleteModalID    : 'deleteModal',
		createModalID    : 'createModal',
		insertDetailName : 'insert_detail',
		updateDetailName : 'update_detail',
		deleteDetailName : 'delete_detail',
		deleteHeadName : 'delete_head',
		totalQtySpan : 'total_qty',
		productClass : 'txtproduct',
		nonStackClass : 'nonStackDescription',
		modalFieldClass : 'modal-fields',
		createButtonId : 'create_new',
		tableID : 'tbl',
		loadingImgId : 'loadingimg',
		searchButtonId : 'search',
		searchTextId : 'search_string, #itemcode, #material, #product',
		clearExcluded : '#date_from, #date_to, #tableid_txtpagenumber, #tableid_txtfilternumber',
		columnClass : 'column_click',
		notFoundMessage : 'No entry found!',
		recentNameElementId : 'dummy',
		isAddRow : true
	};

	if (options) {
		$.extend(this._settings,options);
	}

	if (tableOptions) {
		this._jsTable = tableOptions.tableObject;
		this._jsTableArray = tableOptions.tableArray;
	};

	//Bind rules to columns
	$('.' + this._settings.quantityClass).binder('setRule','numeric');

	var self = this;

	self.contentProvider = {
		addRow : function(elementID)
		{
			var lastRowIndex = self._jsTable.get_row_count();
			self._jsTable.add_new_row();
			self._jsTable.setvalue_to_rowindex_tdclass([lastRowIndex],lastRowIndex,self._jsTableArray['number'].td_class);

			if (elementID) 
				$('.' + elementID + ':last').focus();
		},

		getData : function(rowIndex,arrayColumn,arrayColumnIndex)
		{
			var value = "";
			var columnIndex = 0;

			if (arrayColumnIndex) 
				columnIndex = arrayColumnIndex;

			value = self._jsTable.getvalue_by_rowindex_tdclass(rowIndex, self._jsTableArray[arrayColumn].td_class)[columnIndex];

			return value;
		},

		setData : function(rowIndex,arrayColumn,values)
		{
			self._jsTable.setvalue_to_rowindex_tdclass(values,rowIndex,self._jsTableArray[arrayColumn].td_class);
		},

		getElement : function(rowIndex,arrayColumn)
		{
			return self._jsTable.getelem_by_rowindex_tdclass(rowIndex,self._jsTableArray[arrayColumn].td_class)[0];
		},

		recomputeTotalQuantity : function()
		{
			var totalQty = 0;
			for (var i = 1; i < self._jsTable.get_row_count(); i++) {
				var currentQty = self.contentProvider.getData(i,'qty');
				totalQty += Number(currentQty);
			};

			$('#' + self._settings.totalQtySpan).html(totalQty);
		},

		recomputeRowNumber : function(t)
		{
			for (var i = 1; i < self._jsTable.get_row_count(); i++)
				self._jsTable.setvalue_to_rowindex_tdclass([i],i,self._jsTableArray["number"].td_class);
		}
	},

	self.detailContent = {
		bindUpdateEvents : function(onBeforeSubmit,isCheckInventory,onAfterSubmit)
		{
			$('.' + self._settings.updateIconClass).live('click',function(){
				if (isCheckInventory && isCheckInventory == true)
					self.contentHelper.checkCurrentInventory($(this),onBeforeSubmit,onAfterSubmit);
				else
					self.contentHelper.sendUpdateRequest($(this),onBeforeSubmit,onAfterSubmit);
			});

			$('.' + self._settings.memoClass).live('keydown',function(e){
				if (e.keyCode == 13) {
					e.preventDefault();

					if (isCheckInventory && isCheckInventory == true)
						self.contentHelper.checkCurrentInventory($(this),onBeforeSubmit,onAfterSubmit);
					else
						self.contentHelper.sendUpdateRequest($(this),onBeforeSubmit,onAfterSubmit);
				};
			});
		},

		bindEditEvents : function()
		{
			$('.' + self._settings.editIconClass).live('click',function(){
				var rowIndex = $(this).parent().parent().index();
				self._jsTable.edit_row(rowIndex);
				self.contentHelper.descriptionAccessibilty(rowIndex);
			});

			$('.' + self._settings.quantityClass).live('blur',function(){
				self.contentProvider.recomputeTotalQuantity();
			});
		},

		bindDeleteEvents : function(onAfterDelete)
		{
			$('.' + self._settings.deleteIconClass).live('click',function(){
				self.globalRowIndex    = $(this).parent().parent().index();
				self.globalId          = self.contentProvider.getData(self.globalRowIndex,'id');
				
				if (self.globalId != 0) 
				{
					clear_message_box();
					$('#' + self._settings.deleteModalID).find('.message-content').show();
					$('#' + self._settings.deleteModalID).find('.btn-default').html('Cancel');
					$('#' + self._settings.deleteButtonId).show();
					$('#' + self._settings.deleteModalID).modal('show');
				}
				else
				{
					self.contentProvider.setData(self.globalRowIndex,'product',['',0,'','','']);
					self.contentProvider.setData(self.globalRowIndex,'qty',['']);
					self.contentProvider.setData(self.globalRowIndex,'memo',['']);
					self.contentProvider.setData(self.globalRowIndex,'code',['']);
					self.contentProvider.recomputeTotalQuantity();
					self.contentHelper.descriptionAccessibilty(self.globalRowIndex,true);

				}
			});

			$('#' + self._settings.deleteButtonId).click(function(){
				if (self._flag == 1) 
					return;

				self._flag = 1;

				var rowIndex       = self.globalRowIndex;
				var rowUniqueId  = self.globalId;
				
				var arr =   { 
								fnc         : self._settings.deleteDetailName, 
								detail_id   : rowUniqueId
							};
			   
				$.ajax({
					type: "POST",
					dataType : 'JSON',
					data: 'data=' + JSON.stringify(arr) + token,
					success: function(response) {
						clear_message_box();

						if (response.error != '')
						{ 
							$('#' + self._settings.deleteModalID).find('.message-content').hide();
							$('#' + self._settings.deleteModalID).find('.btn-default').html('OK');
							$('#' + self._settings.deleteButtonId).hide();
							build_message_box('messagebox_2',response.error,'danger');
						}
						else
						{
							self._jsTable.delete_row(rowIndex);
							self.contentProvider.recomputeRowNumber();
							self.contentProvider.recomputeTotalQuantity();
							$('#' + self._settings.deleteModalID).modal('hide');

							if (onAfterDelete)
							{
								self._flag = 0;
								onAfterDelete();
							}
						}

						self._flag = 0;
					}       
				});
			});

			$('#' + self._settings.deleteModalID).live('hidden.bs.modal', function (e) {
				self.globalRowIndex = 0;
				self.globalId = 0;
			});
		},

		bindAutoComplete : function (onAfterSubmit)
		{
			my_autocomplete_add(token,"." + self._settings.productClass,self._settings.controller, {
				enable_add : false,
				fnc_callback : function(x, label, value, ret_datas, error){
					var rowIndex = $(x).parent().parent().index();
					if (onAfterSubmit)
						onAfterSubmit(rowIndex, error, ret_datas);
					else
					{

						var descriptionElement = $(x).parent().find('.' + self._settings.nonStackClass);

						if (error.length > 0) {
							self.contentProvider.setData(rowIndex,'product',['',0,'','','']);
							self.contentProvider.setData(rowIndex,'code',['']);
							self.contentProvider.setData(rowIndex,'qty',['']);
							self.contentProvider.setData(rowIndex,'memo',['']);
							self.contentProvider.setData(rowIndex,'uom',['']);
							
							if (self._jsTableArray.hasOwnProperty("receivedby")) 
								self.contentProvider.setData(rowIndex,'receivedby',['']);
							
							$(descriptionElement).hide();
						}
						else
						{
							var newLine = '';
							if (ret_datas[3] == ProductType.NonStock)
							{
								newLine = '<br/>';
								$(descriptionElement).show();
							}
							else
								$(descriptionElement).val('').hide();

							self.contentProvider.setData(rowIndex,'product',[ret_datas[1],ret_datas[0],ret_datas[3],newLine,'']);
							self.contentProvider.setData(rowIndex,'code',[ret_datas[2]]);
							self.contentProvider.setData(rowIndex,'uom',[ret_datas[4]]);
						}
					}
					
				},
				fnc_render : function(ul, item){
					return my_autocomplete_render_fnc(ul, item, "code_name", [2,1], 
						{ width : ["100px","auto"] });
				}
			});
		},

		bindRecentNameAutoComplete : function ()
		{
			my_autocomplete_add(token,"#" + self._settings.recentNameElementId,self._settings.controller, {
				enable_add : false,
				fnc_callback : function(x, label, value, ret_datas, error){

					if (error.length == 0)
						$(x).val(value);                             
				},
				fnc_render : function(ul, item){
					return my_autocomplete_render_fnc(ul, item, "code_name", [0], 
						{ width : ["200px"] });
				}
			},'recent_name_autocomplete');
		},

		bindSaveTransactionEvent : function(onBeforeSubmit, onAfterSubmit)
		{
			$('#' + self._settings.saveButtonId).click(function(){

				if (self._flag == 1)
					return;

				var arr = onBeforeSubmit();

				if (!arr)
					return;

				var addRowExist = self.contentProvider.getData(self._jsTable.get_row_count() - 1, 'id');
				var rowsToSubtract = addRowExist == 0 ? 2 : 1;

				if (self._jsTable.get_row_count() - rowsToSubtract == 0) 
				{
					alert('Please encode at least one product!');
					return;
				}

				for (var i = 1; i <= self._jsTable.get_row_count() - rowsToSubtract; i++) 
				{
					var updateImage = self.contentProvider.getElement(i,'colupdate');
					if ($(updateImage).hasClass('imgupdate')) 
					{
						alert('Please finalize all rows!');
						return;
					}
				};

				self._flag = 1;

				$.ajax({
					type: "POST",
					dataType : 'JSON',
					data: 'data=' + JSON.stringify(arr) + token,
					success: function(response) {
						clear_message_box();

						if (response.error != '') 
							build_message_box('messagebox_1',response.error,'danger');
						else
						{
							if (onAfterSubmit) 
								onAfterSubmit();
							else
								window.location = self._settings.baseURL + self._settings.controller + "/list";
						}

						self._flag = 0;
					}       
				});
			});
		},
		bindAllEvents : function(callbackOptions)
		{
			self.detailContent.bindUpdateEvents(callbackOptions.updateEventsBeforeCallback, callbackOptions.addInventoryChecker, callbackOptions.updateEventsAfterCallback);
			self.detailContent.bindEditEvents();
			self.detailContent.bindDeleteEvents(callbackOptions.deleteEventsAfterCallback);
			self.detailContent.bindAutoComplete();
			self.detailContent.bindSaveTransactionEvent(callbackOptions.saveEventsBeforeCallback,callbackOptions.saveEventsAfterCallback);
			self.detailContent.bindRecentNameAutoComplete();
		}
	},

	self.headContent = {
		bindDeleteEvents : function(onAfterDelete)
		{
			$('.' + self._settings.deleteIconClass).live('click',function(){
				self.globalRowIndex    = $(this).parent().parent().index();
				self.globalId            = self.contentProvider.getData(self.globalRowIndex,'id');

				if (self.globalId != 0) 
				{
					clear_message_box();
					$('#' + self._settings.deleteModalID).find('.message-content').show();
					$('#' + self._settings.deleteModalID).find('.btn-default').html('Cancel');
					$('#' + self._settings.deleteButtonId).show();
					$('#' + self._settings.deleteModalID).modal('show');
				}
			});

			$('#' + self._settings.deleteButtonId).click(function(){
				if (self._flag == 1)
					return;

				self._flag = 1;

				var rowIndex       = self.globalRowIndex;
				var rowUniqueId  = self.globalId;

				var arr =   { 
								fnc       : self._settings.deleteHeadName, 
								head_id   : rowUniqueId
							};

				$.ajax({
					type: "POST",
					dataType : 'JSON',
					data: 'data=' + JSON.stringify(arr) + token,
					success: function(response) {
						clear_message_box();

						if (response.error != '') 
						{
							$('#' + self._settings.deleteModalID).find('.message-content').hide();
							$('#' + self._settings.deleteModalID).find('.btn-default').html('OK');
							$('#' + self._settings.deleteButtonId).hide();
							build_message_box('messagebox_2',response.error,'danger');
						}
						else
						{
							self._jsTable.delete_row(rowIndex);
							self.contentProvider.recomputeRowNumber();

							if (self._jsTable.get_row_count() == 1) 
								$('#' + self._settings.tableID).hide();

							$('#' + self._settings.deleteModalID).modal('hide');

							if (onAfterDelete)
							{
								self._flag = 0;
								onAfterDelete();
							}
						}

						self._flag = 0;
					}       
				});
			});

			$('#' + self._settings.deleteModalID + ', #' + self._settings.createModalID).live('hidden.bs.modal', function (e) {
				$('.' + self._settings.modalFieldClass).val('');
				$('.' + self._settings.modalFieldClass).html('');
				$('.' + self._settings.modalFieldClass).removeAttr('checked');
				$('#new_min').val(0);
				$('#new_max').val(0);
				$('#messagebox_2, #messagebox_3').html('');
				self.globalRowIndex = 0;
				self.globalId       = 0;
			});
		},

		bindSearchEvent : function(filterGetter)
		{
			$('#' + self._settings.searchButtonId).click(function(){
				//self.contentHelper.refreshTable(onBeforeSubmit,onAfterSubmit);
				filterGetter();
			});

			$('#' + self._settings.searchTextId).keypress(function(e){
				if (e.keyCode == 13) 
				{
					e.preventDefault();
					filterGetter();
					//self.contentHelper.refreshTable(onBeforeSubmit,onAfterSubmit);
				}
			});
		},

		bindViewEvent : function()
		{
			if (self._settings.permissions !== undefined && self._settings.permissions.allow_to_view == false)
				return;

			$('.' + self._settings.columnClass).live('click',function(){
				var rowIndex = $(this).parent().index();
				var headId   = self.contentProvider.getData(rowIndex,'id');

				window.open(self._settings.baseURL + self._settings.controller + '/view/' + headId);
			});
		},

		bindCreateReferenceEvent : function ()
		{
			$('#' + self._settings.createButtonId).click(function(){
				if (self._flag == 1) 
					return;

				self._flag = 1;

				var arr = { fnc : 'create_reference_number' };

				$.ajax({
					type: "POST",
					dataType : 'JSON',
					data: 'data=' + JSON.stringify(arr) + token,
					success: function(response) {
						clear_message_box();

						if (response.error != '') 
							build_message_box('messagebox_1',response.error,'danger');
						else
							window.location = self._settings.baseURL + self._settings.controller + '/view/' + response.id;

						self._flag = 0;
					}       
				});
			});
		},

		bindAllEvents : function(callbackOptions)
		{
			self.headContent.bindDeleteEvents(callbackOptions.deleteEventsAfterCallback);
			self.headContent.bindSearchEvent(callbackOptions.searchEventsBeforeCallback);
			self.headContent.bindViewEvent();
			self.headContent.bindCreateReferenceEvent();
		}
	},

	self.contentHelper = {
		sendUpdateRequest : function(element,onBeforeSubmit,onAfterSubmit)
		{
			if (self._flag == 1) 
				return;

			var rowIndex = $(element).parent().parent().index();

			if (onBeforeSubmit)
			{ 
				var arr = onBeforeSubmit(element);

				if (!arr) 
					return;
			}
			else
			{
				var productId       = self.contentProvider.getData(rowIndex,'product',1);
				var qty             = self.contentProvider.getData(rowIndex,'qty');
				var memo            = encodeURIComponent(self.contentProvider.getData(rowIndex,'memo'));
				var rowUniqueId     = self.contentProvider.getData(rowIndex,'id');
				var nonStackDescription  = self.contentProvider.getData(rowIndex,'product',4);
				var actionFunction  = rowUniqueId != 0 ? self._settings.updateDetailName : self._settings.insertDetailName;

				var errorList = $.dataValidation([  {   
														value : productId,
														fieldName : 'Product',
														required : true,
														isNotEqual : { value : 0, errorMessage : 'Please select a valid product!'}
													},
													{
														value : qty,
														fieldName : 'Quantity',
														required : true,
														rules : 'numeric',
														isNotEqual : { value : 0, errorMessage : 'Quantity must be greater than 0!'}
													}
													]);

				if (errorList.length > 0) {
					clear_message_box();
					build_message_box('messagebox_1',build_error_message(errorList),'danger');
					return;
				};

				var arr =   { 
								fnc         : actionFunction, 
								product_id  : productId,
								qty         : qty,
								memo        : memo,
								detail_id   : rowUniqueId,
								description : nonStackDescription
							};
			}

			self._flag = 1;

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + token,
				success: function(response) {
					clear_message_box();

					if (response.error != '') 
						build_message_box('messagebox_1',response.error,'danger');
					else
					{
						self._jsTable.update_row(rowIndex);

						self.contentHelper.descriptionAccessibilty(rowIndex);
						
						if (arr.detail_id == 0) {
							self.contentProvider.setData(rowIndex,'id',[response.id]);
							if (self._settings.isAddRow)
								self.contentProvider.addRow(self._settings.productClass);
						}

						if (onAfterSubmit)
							onAfterSubmit(rowIndex,response);
					}

					self._flag = 0;
				}       
			});
		},

		/*refreshTable : function(onBeforeSubmit,onAfterSubmit)
		{
			if (self._flag == 1)
				return;

			var arr = onBeforeSubmit();
			
			if (!arr) 
				return;

			$('#' + self._settings.loadingImgId).show();
			
			self._flag = 1;

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + token,
				success: function(response) {
					self._jsTable.clear_table();
					clear_message_box();

					if (response.rowcnt == 0) 
					{
						$('#' + self._settings.tableID).hide();
						build_message_box('messagebox_1',self._settings.notFoundMessage,'info');
					}
					else
					{
						if(response.rowcnt <= 10)
							self._jsTable.mypage.set_last_page(1);
						else
							self._jsTable.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(self._jsTable.mypage.filter_number)));

						self._jsTable.insert_multiplerow_with_value(1,response.data);

						$('#' + self._settings.tableID).show();
					}

					if (onAfterSubmit) 
						onAfterSubmit(response);

					$('#' + self._settings.loadingImgId).hide();
					
					self._flag = 0;
				}       
			});
		},*/

		refreshTableWithLimit : function(filterValues, callbackAfterRequest)
		{
			if (self._flag == 1)
				return;

			$('#' + self._settings.loadingImgId).show();
			
			self._flag = 1;

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(filterValues) + token,
				success: function(response) {
					
					if((typeof filterValues.row_start === 'undefined') && (typeof filterValues.row_end === 'undefined'))
						self._jsTable.clear_table();
					else
						self._jsTable.clean_table();
						
					clear_message_box();

					if (response.rowcnt == 0) 
					{
						$('#' + self._settings.tableID).hide();
						build_message_box('messagebox_1',self._settings.notFoundMessage,'info');
					}
					else
					{
						if(response.rowcnt <= 10)
							self._jsTable.mypage.set_last_page(1);
						else
							self._jsTable.mypage.set_last_page( Math.ceil(Number(response.rowcnt) / Number(self._jsTable.mypage.filter_number)));

						self._jsTable.insert_multiplerow_with_value(1,response.data);

						$('#' + self._settings.tableID).show();
					}

					if (callbackAfterRequest) 
						callbackAfterRequest(response);
					
					$('#' + self._settings.loadingImgId).hide();
					
					self._flag = 0;
				}       
			});
		},

		checkProductTypeDescription : function()
		{
			for (var i = 1; i < self._jsTable.get_row_count(); i++) 
				self.contentHelper.descriptionAccessibilty(i);
		},

		descriptionAccessibilty : function(rowIndex ,isForceHide)
		{
			var productType = Number(self.contentProvider.getData(rowIndex,'product',2));
			var productId   = Number(self.contentProvider.getData(rowIndex,'product',1));

			var productElement = self.contentProvider.getElement(rowIndex,'product');
			var descriptionElement = $(productElement).parent().find('.' + self._settings.nonStackClass);

			if (productId != 0) 
			{
				if (productType == ProductType.NonStock)
					$(descriptionElement).show();
				else
				   $(descriptionElement).hide();  
			};
			
			if (isForceHide)
				$(descriptionElement).hide();  
		},

		checkCurrentInventory : function(element,onBeforeSubmit,onAfterSubmit)
		{
			if (self._flag == 1) 
				return;

			var continueTransaction = true;
			var rowIndex    = $(element).parent().parent().index();
			var orderFor    = $('#orderfor').val() !== undefined ? $('#orderfor').val() : 0;
			var rowUniqueId = tableHelper.contentProvider.getData(rowIndex,'id');
			var productId   = tableHelper.contentProvider.getData(rowIndex,'product',1);
			var enteredQty  = tableHelper.contentProvider.getData(rowIndex,'qty');

			var errorList = $.dataValidation([  {   
													value : productId,
													fieldName : 'Product',
													required : true,
													isNotEqual : { value : 0, errorMessage : 'Please select a valid product!'}
												},
												{
													value : enteredQty,
													fieldName : 'Quantity',
													required : true,
													rules : 'numeric',
													isNotEqual : { value : 0, errorMessage : 'Quantity must be greater than 0!'}
												}
												]);

			if (errorList.length > 0) {
				clear_message_box();
				build_message_box('messagebox_1',build_error_message(errorList),'danger');
				return;
			};

			var arr =   {
							fnc : 'check_product_inventory',
							product_id : productId,
							qty : enteredQty,
							row_id : rowUniqueId,
							order_for : orderFor
						}

			self._flag = 1;

			$.ajax({
				type: "POST",
				dataType : 'JSON',
				data: 'data=' + JSON.stringify(arr) + token,
				success: function(response) {
					clear_message_box();

					if (response.checker == InventoryCheckerType.MinInv)
					{
						if (response.is_insufficient != InventoryState.Sufficient) {
							var confirmMessage = (response.is_insufficient == InventoryState.Minimum) ? 'Current inventory is (' + response.current_inventory + ' pcs).  You will reach minimum inventory level. Do you want to continue?' : 'Current inventory is not sufficient (' + response.current_inventory + ' pcs). Do you want to continue?';
							continueTransaction = confirm(confirmMessage);
						} 
					}
					else
					{
						if (response.is_excess) {
							var confirmMessage = 'Current inventory is (' + response.current_inventory + ' pcs).  You will reach maximum inventory level. Do you want to continue?';
							continueTransaction = confirm(confirmMessage);
						}
					}
					

					self._flag = 0;

					if (continueTransaction) 
						self.contentHelper.sendUpdateRequest(element,onBeforeSubmit,onAfterSubmit);
					else
						return;
				}       
			});
		}
	}
};

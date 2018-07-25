var EcommerceProducts = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

    var handleProducts = function() {
        var grid = new Datatable();

        grid.init({
            src: $("#datatable_products"),
            onSuccess: function (grid) {
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            loadingMessage: 'Loading...',
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                "lengthMenu": [
                    [10, 20, 50, 100, 150],
                    [10, 20, 50, 100, 150] // change per page values here 
                ],
                "pageLength": 10, // default record count per page
                "ajax": {
					"headers": { 'X-CSRF-Token': $('meta[name="_token"]').attr('content') },
                    "url": 'products/loadItemsAJAX', // ajax source
					data : { 'specialpromo_filter': localStorage['specialpromo_filter'], }, //GTD: page number here specify
                },
                "order": [
                    [1, "asc"]
                ] // set first column as a default sort by asc
            }
        });

         // handle group "Promo list actions" submit button click
        grid.getTableWrapper().on('click', '.table-group-promo-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-promo-action-input", grid.getTableWrapper());
            
			if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                
				//I don't understand internal AJAX in Metronic
				/*
				grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();
				*/
				
				//Temporary dog-nail!
				$.ajax({
				   "headers": { 'X-CSRF-Token': $('meta[name="_token"]').attr('content') },
				   "url" : 'products/updatePromoAJAX',
				   method : "POST",
				   data : { "action": action.val(), "items_ids": grid.getSelectedRows(), },
				   success : function(data)
				   {
					  location.reload(); //Refresh page
				   },
				   error: function(errmsg) {
					  console.log(errmsg.responseText)
				   }
			   });
				
				
            } else if (action.val() == "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'Please select an action',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: 'No record selected',
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            handleProducts();
            initPickers();
            
        }

    };

}();
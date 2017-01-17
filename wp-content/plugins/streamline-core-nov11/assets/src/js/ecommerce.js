if (typeof ga === 'function') {
	//console.log('GA loaded');
	
	ga('require', 'ecommerce');
	ga('ecommerce:addTransaction', {
		  'id': streamline_transaction.id,                     // Transaction ID. Required.
		  'affiliation': streamline_transaction.affiliation,   // Affiliation or store name.
		  'revenue': streamline_transaction.revenue,               // Grand Total.
		  'shipping': streamline_transaction.shipping,                  // Shipping.
		  'tax': streamline_transaction.tax                    // Tax.
		});

	ga('ecommerce:addItem', {
	  'id': streamline_transaction.id,                     // Transaction ID. Required.
	  'name': streamline_transaction.unit_name,    // Product name. Required.				  
	  'price': streamline_transaction.revenue,                 // Unit price.
	  'quantity': '1'                   // Quantity.
	});
	
	ga('ecommerce:send');
	//console.log('ecommerce sent');
}

if (typeof __gaTracker === 'function') {
	//console.log('GA loaded');
	
	__gaTracker('require', 'ecommerce');
	__gaTracker('ecommerce:addTransaction', {
		  'id': streamline_transaction.id,                     // Transaction ID. Required.
		  'affiliation': streamline_transaction.affiliation,   // Affiliation or store name.
		  'revenue': streamline_transaction.revenue,               // Grand Total.
		  'shipping': streamline_transaction.shipping,                  // Shipping.
		  'tax': streamline_transaction.tax                    // Tax.
		});

	__gaTracker('ecommerce:addItem', {
	  'id': streamline_transaction.id,                     // Transaction ID. Required.
	  'name': streamline_transaction.unit_name,    // Product name. Required.				  
	  'price': streamline_transaction.revenue,                 // Unit price.
	  'quantity': '1'                   // Quantity.
	});

	__gaTracker('ecommerce:send');
	//console.log('ecommerce sent');
}
var varienGridMassaction = Class.create(varienGridMassaction, {
   apply:function ($super) {
      
	   
	   var rowvals = [];
	   
	   $('itemlistGrid_table').getElementsBySelector('.massaction-checkbox').each(function (row) {
		   if(row.checked)
			 { 
			   var iteminfo = $('order_qty_'+row.value);
			    
			   orderqty = iteminfo.value;
			   if($('qty_'+row.value+'_1')){
				   orderqtyreciv1 =  $('qty_'+row.value+'_1').value;
			   }
			   else
				{
				   orderqtyreciv1 = 0;
				}
			   if($('qty_'+row.value+'_2')){
				   orderqtyreciv2 =  $('qty_'+row.value+'_2').value;
			   }
			   else
				{
				   orderqtyreciv2 = 0;
				}
			   if($('qty_'+row.value+'_3')){
				   orderqtyreciv3 =  $('qty_'+row.value+'_3').value;
			   }
			   else
				{
				   orderqtyreciv3 = 0;
				}
			    
			   var totalrecive = parseInt(orderqtyreciv1) +parseInt(orderqtyreciv2) +parseInt(orderqtyreciv3) ;  
			   
			   if(totalrecive>orderqty)
			   {
				   alert("Your Received qty should not be greater then Ordered Qty for "+iteminfo.readAttribute('rel'));
				   return $supers;
			   }
			   
			   var valrow1 = '';
		       $('itemlistGrid_table').getElementsBySelector('.row1-class-'+row.value).each(function (row1val) {
		    	   valrow1 +=   (row1val.value)+'::';
		       });
		        
		       
		       var valrow2 = '';
		       $('itemlistGrid_table').getElementsBySelector('.row2-class-'+row.value).each(function (row2val) {
		    	   valrow2 +=   (row2val.value)+'::';
		       });
		        
		       
		       var valrow3 = '';
		       $('itemlistGrid_table').getElementsBySelector('.row3-class-'+row.value).each(function (row3val) {
		    	   valrow3 +=   (row3val.value)+'::';
		       });
		       
		       $totalval = valrow1 + '|' + valrow2 + '|'+valrow3;
		       
		       rowvals.push(row.value+'###'+$totalval );
			 }
	   });
	   new Insertion.Bottom(this.formAdditional, this.fieldTemplate.evaluate({name:'rowvals', value:rowvals}));
       
	   
	   name = 'order_id';
	   name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	    var regex = new RegExp("[\\?&/]" + name + "/([^&#/]*)");
	        results = regex.exec(location.href);
	   var orderid =  results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	    
       
       
       new Insertion.Bottom(this.formAdditional, this.fieldTemplate.evaluate({name:'orderid', value:orderid}));
       
       return $super();
   }
});
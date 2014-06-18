
var AdminOrder = Class.create(AdminOrder,{
	
	 applyCoupon : function(code){
	       alert(code);
	    },

});

 
function updateAxaj(code,custid,url)
{
	 
	new Ajax.Request(url, {
		  method:'post',
		  parameters: {couponcode:  code, customerid:  custid},
		  onSuccess: function(transport) {
		    var response = transport.responseText || "no response text";
		     
		     
		  },
		  onFailure: function() { alert('Coupon code not available '); }
		});
};
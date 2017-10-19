'use strict';

var categoryList = ['requestFactory',function (requestFactory) {
	return {
		restrict : 'A',
		link:function(scope,element,attr){
			jQuery('.categoryList li ul').css('display','none');
			  jQuery('.categoryList li span').click(function(e){
			    e.stopPropagation();
			    if(jQuery(this).parent().find(' > ul').css('display') == 'none') {
			    	jQuery(this).parent().find(' > ul i').removeClass('fa-folder-open');
			    	jQuery(this).parent().find(' > ul i').addClass('fa-folder');
			    	jQuery(' i',this).removeClass('fa-folder');
			    	jQuery(' i',this).addClass('fa-folder-open');
			    }
			    if(jQuery(this).parent().find(' > ul').css('display') == 'block') {
			    	jQuery(this).parent().find(' > ul ul').hide();
			    	jQuery(this).parent().find(' > ul i').removeClass('fa-folder-open');
			    	jQuery(this).parent().find(' > ul i').addClass('fa-folder');
			    	jQuery(' i',this).removeClass('fa-folder-open');
			    	jQuery(' i',this).addClass('fa-folder');
			    }
			    jQuery(this).parent().find(' > ul').toggle();
			});
			    scope.categoryId = attr.categoryid;
			    console.log(attr.categoryid);
			    $('input[name=parent_id]').each(function(index){
			    	var value = $(this).val();
			    	if(value == scope.categoryId) {
			    		var currentElement = $(this);
			    		var currentParentNode = currentElement[0].parentNode;
			    		while(currentParentNode.className != 'categoryList') {
			    			currentParentNode.style.display = 'block';
			    			currentParentNode = currentParentNode.parentNode;
			    		}
			    	}
			    });				
				  
		},
	};
}];
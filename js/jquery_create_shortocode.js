var create_shortcode_string = {
		 str:"",
		 main_string:"",
		 start_string:"[sub_page",
		 end_string:"]",
		 str_title:"",
		 str_sort_order:"",
		 str_sort_by:"",
		 str_exclude_page:"",
		 str_depth_level:"",
		 str_sort_order_parent:"",
		
		config:{
			
		},
		
		init:function(){

			jQuery( "#title" ).change(function() {	

				create_shortcode_string.createString1(jQuery(this));
		     });
			
			jQuery( "#child_sort_order" ).change(function() {	
				create_shortcode_string.createString2(jQuery(this));
		     });
			
			jQuery( "#sorting_criteria" ).change(function() {	
				create_shortcode_string.createString3(jQuery(this));
		     });
			
			jQuery( "#exclude_page" ).change(function() {	
				create_shortcode_string.createString4(jQuery(this));
		     });
			
			jQuery( "#depth_level" ).change(function() {	
				create_shortcode_string.createString5(jQuery(this));
		     });
			
			jQuery( "#parent_sort_order" ).change(function() {	
				create_shortcode_string.createString6(jQuery(this));
		     });
			
			jQuery( "#title" ).trigger('change');
			jQuery("#child_sort_order" ).trigger('change');
			jQuery( "#sorting_criteria" ).trigger('change');
			jQuery( "#exclude_page" ).trigger('change');
			jQuery( "#depth_level" ).trigger('change');
			jQuery( "#parent_sort_order" ).trigger('change');
		},
		
		createString1:function(obj){
			
			if(obj.val() != '')
			{	
				create_shortcode_string.str_title="title='"+obj.val()+"'";
			}
			else
			{
				create_shortcode_string.str_title="";
			}
			create_shortcode_string.appendStr();
		},

		createString2:function(obj){
			
			if(obj.val() != '')
			{
				create_shortcode_string.str_sort_order="sort_order='"+obj.val()+"'";
			}
			else
			{
				create_shortcode_string.str_sort_order='';
			}
			create_shortcode_string.appendStr();
		},
		createString3:function(obj){
			
				
				create_shortcode_string.str="";
				count=obj.find('option:selected').length
				cnt=0;
				if(count>0)
				{
				obj.find('option:selected').each(function () {
					cnt++;
					if(cnt==count)
					{
						create_shortcode_string.str+= jQuery(this).val();
					}
					else
					{
						create_shortcode_string.str+= jQuery(this).val() + ",";
					}
					
				});				
				create_shortcode_string.str_sort_by="sort_by_values='"+create_shortcode_string.str+"'";
			}
			else
			{
				create_shortcode_string.str_sort_by="";
			}
			create_shortcode_string.appendStr();
		},
		createString4:function(obj){
			
			
				create_shortcode_string.str1="";
				count1=obj.find('option:selected').length
				cnt1=0;
				if(count1>0)
				{
				obj.find('option:selected').each(function () {
					cnt1++;
					if(cnt1==count1)
					{
						create_shortcode_string.str1+= jQuery(this).val();
					}
					else
					{
						create_shortcode_string.str1+= jQuery(this).val() + ",";
					}
					
				});				
				create_shortcode_string.str_exclude_page="exclude_page_id='"+create_shortcode_string.str1+"'";
			}
			else
			{
				create_shortcode_string.str_exclude_page="";
			}
			create_shortcode_string.appendStr();
		},
		createString5:function(obj){
			
			if(obj.val() != '')
			{
				create_shortcode_string.str_depth_level="depth='"+obj.val()+"'";
			}
			else
			{
				create_shortcode_string.str_depth_level='';
			}
			create_shortcode_string.appendStr();
		},
		createString6:function(obj){
			
			if(obj.val() != '')
			{
				create_shortcode_string.str_sort_order_parent="sort_order_parent='"+obj.val()+"'";
			}
			else
			{
				str_sort_order_parent='';
			}
			create_shortcode_string.appendStr();
		},

		appendStr:function(){
			create_shortcode_string.main_string=create_shortcode_string.start_string +" "+ create_shortcode_string.str_title +" "+" "+create_shortcode_string.str_sort_order+" "+create_shortcode_string.str_sort_by+" "+create_shortcode_string.str_exclude_page+" "+create_shortcode_string.str_depth_level+" "+create_shortcode_string.str_sort_order_parent+" "+create_shortcode_string.end_string;
			
			jQuery("#shortcode").html(create_shortcode_string.main_string);		
			
		},
};	
jQuery(document).ready(function(){
	create_shortcode_string.init();
});


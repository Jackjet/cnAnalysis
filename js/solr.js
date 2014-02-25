/*
 *core js
 *@project 中国舆情
 *@version 2.0
 *@author c1avie (www.c1avie.com)
 *@copyright newthread
 */

$(function() {
	var search_button = $('.search-button'),
		search_text = $('#global-search'),
		high_search = $('#high-search'),
		high_search_title = $('#high-search-title'),
		high_search_content = $('#high-search-content'),
		high_search_cat = $('#high-search-cat'),
		high_search_from = $('#high-search-from'),
		// high_search_value = $('.pop ul li a'),
		main = $('.main');
		// solr_url = "http://210.42.151.79/solr/testColl/select?wt=json&q=content_ik%3A%22";

	search_text.focus(function(){
		var search_val = search_text.attr('value');
		search_text.attr('value','');
	}).blur(function(){
		var search_val = search_text.attr('value');
		if(search_val == ""){
			search_text.attr('value','在此输入您想检索的内容');
		}
		else{
			return true;
		}
	});

	search_button.click(function() {
		var search_text_value = search_text.val();
		// solr_text_url = solr_url + search_text_value + "%22";
		// console.log(solr_text_url);
		$.ajax({
			type: "get",
			url: "solr_select.php",
			data: {
				address: search_text_value
			},
			dataType : "json",
			// jsonp: "callbackparam",//服务端用于接收callback调用的function名的参数
			success: function(result) {
				if(0 == result['responseHeader'].status && 0 != result['response'].numFound) {
					var data = result['response'].docs;
					main.html("");
					var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
		            $.each(data,function(i,n) {
		                solr_list += "<li>" + '<a href="' + n.from_ik + '">' + n.title_ik + "</a>" + "<span>" + n.time_ik +"</span></li>";
		                solr_list += "<p>" + n.content_ik + "</p>";
		            });
		            solr_list += "</ul></div>";
		            main.append(solr_list);
				}
				else {
					main.html("");
					var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
					solr_list += "<li>共有0个搜索结果。</li>";
					solr_list += "</ul></div>";
		            main.append(solr_list);
				}
			}
		});
	});
	$('#body').keydown(function(obj) {
	    if(obj.keyCode == 13){
            search_button.click();
            obj.returnValue = false;
        }
	});
	high_search.click(function() {
		var high_search_title_value = high_search_title.val();
			high_search_content_value = high_search_content.val(),
			high_search_cat_value = high_search_cat.val(),
			high_search_from_value = high_search_from.val();

		// if(high_search_cat_value == "语情动态") {
		// 	high_search_cat_value = 0;
		// }
		// else if(high_search_cat_value == "语言生活动态") {
		// 	high_search_cat_value = 1;
		// }
		// else if(high_search_cat_value == "语言事件与活动") {
		// 	high_search_cat_value = 2;
		// }
		// else if(high_search_cat_value == "语言应用") {
		// 	high_search_cat_value = 3;
		// }
		// else {
		// 	high_search_cat_value = 3;
		// }
		$.ajax({
			type: "get",
			url: "solr_select.php",
			data: {
				address: high_search_content_value,
				cat: high_search_cat_value,
				from: high_search_from_value,
				title: high_search_title_value
			},
			dataType : "json",
			success: function(result) {
				if(0 == result['responseHeader'].status && 0 != result['response'].numFound) {
					var data = result['response'].docs;
					main.html("");
					var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
		            $.each(data,function(i,n) {
		                solr_list += "<li>" + '<a href="' + n.from_ik + '">' + n.title_ik + "</a>" + "<span>" + n.time_ik +"</span></li>";
		                solr_list += "<p>" + n.content_ik + "</p>";
		            });
		            solr_list += "</ul></div>";
		            main.append(solr_list);
				}
				else {
					main.html("");
					var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
					solr_list += "<li>共有0个搜索结果。</li>";
					solr_list += "</ul></div>";
		            main.append(solr_list);
				}
			}
		});
	});

	//点击高级搜索内容
	// high_search_value.click(function() {
	// 	$.ajax({
	// 		type: "get",
	// 		url: "http://localhost:801/project/07_Code/cnAnalysis/solr_select.php",
	// 		data: {
	// 			address: high_search_value.val(),
	// 		},
	// 		dataType : "json",
	// 		success: function(result) {
	// 			if(0 == result['responseHeader'].status && 0 != result['response'].numFound) {
	// 				var data = result['response'].docs;
	// 				main.html("");
	// 				var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
	// 	            $.each(data,function(i,n) {
	// 	                solr_list += "<li>" + '<a href="' + n.from_ik + '">' + n.title_ik + "</a>" + "<span>" + n.time_ik +"</span></li>";
	// 	                solr_list += "<p>" + n.content_ik + "</p>";
	// 	            });
	// 	            solr_list += "</ul></div>";
	// 	            main.append(solr_list);
	// 			}
	// 			else {
	// 				main.html("");
	// 				var solr_list = "<h4><a href='#'>首页</a>&gt;&gt;搜索结果</h4><div class='line'><ul>";
	// 				solr_list += "<li>共有0个搜索结果。</li>";
	// 				solr_list += "</ul></div>";
	// 	            main.append(solr_list);
	// 			}
	// 		}
	// 	});
	// });
});
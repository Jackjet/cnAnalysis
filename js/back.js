/*
 *back js
 *@project 中国舆情
 *@version 2.0
 *@author c1avie (www.c1avie.com)
 *@copyright newthread
 */
/*******************************文章操作******************************************************/
//设置审核通过
function doPass(id) {
	var dopassURL = $('#dopass'+id);
	$.ajax({
		type: "post",
		 url: artopsubURL,
		data: {
			ajax: 'dopass',
			back_aid:id
		},
		success: function(result2) {
			console.log(result2);
			var json2 = JSON.parse(result2.toString());
			if(json2.flag == true) 
			{
				alert("设置成功");
				dopassURL.remove();
			}
			else {
				alert("设置失败，详细原因：" + json2.berror);
			}
		}
	});
}

//设置审核不通过
function doNoPass(id) {
	var donopassURL = $('#donopass'+id);
	$.ajax({
		type: "post",
		 url: artopsubURL,
		data: {
			ajax: 'donopass',
			back_aid:id
		},
		success: function(result2) {
			var json2 = JSON.parse(result2.toString());
			if(json2.flag == true) 
			{
				alert("设置成功");
				donopassURL.remove();
			}
			else {
				alert("设置失败，详细原因：" + json2.berror);
			}
		}
	});
}


//删除文章
function doDelete(id) {
   if(confirm("警告：您是否确定真的删除！(无法恢复)")){
	$.ajax({
		type: "post",
		 url: artopsubURL,
		data: {
			ajax: 'dodelete',
			back_aid:id
		},
		success: function(result2) {
			console.log(result2);
			var json2 = JSON.parse(result2.toString());
			if(json2.flag == true) 
			{
				alert("操作成功：" + json2.addinfo);
				window.location.href = json2.backUrl;
			}
			else {
				alert("操作失败，详细原因：" + json2.berror);
			}
		}
	});
   }	
}

//修改文章
$(function() {
	var publishnew = $('#publishnew'),
		back_aid   = $('#aid');
		back_title = $('#title'),
		back_from = $('#from'),
		back_fromlink = $('#fromlink'),
		back_author = $('#author'),
		back_typeid = $('#typeid'),
		

		//文章更新
		publishnew.click(function() {
			editor.sync();                   //同步编辑器的内容到textarea
			back_editor_id = $('#editor_id');
			console.log(back_editor_id.val());
			$.ajax({
				type: "post",
				 url: artopsubURL,
				data: {
					ajax:'editpost',
					back_aid: back_aid.val(),
					back_title: back_title.val(),
					back_from: back_from.val(),
					back_fromlink: back_fromlink.val(),
					back_author: back_author.val(),
					//back_editor: back_editor.val(),
					back_typeid: back_typeid.val(),
					back_editor_id: back_editor_id.val()
				},
				success: function(result2) {
					console.log(result2);
					var json2 = JSON.parse(result2.toString());
					if(json2.flag == true) {
						alert("文章更新成功");
						window.location.reload();
					}
					else {
						alert("文章更新失败，原因：" + json2.berror);
					}
				}
			});
		});
		
});
/*********************************************************用户操作************************************************************/
//更改用户权限
function changeAuthority(id) {
var group = $('#back_group'+id);	
	$.ajax({
		type: "post",
		 url: useropsubURL,
		data: {
			ajax: 'alterauth',
			back_uid:id,
			back_group:group.val()
		},
		success: function(result2) {
			console.log(result2);
			var json2 = JSON.parse(result2.toString());
			if(json2.flag == true) 
			{
				alert("操作成功！");
			}
			else {
				alert("操作失败，详细原因：" + json2.berror);
			}
		}
	});	
}
//删除用户
function deleteUser(id) 
{

	////////////////////////////////验证处理//////////////////////////////////
	if(confirm("警告：您是否确定真的删除！(无法恢复)"))
	{

	$.ajax({
		type: "post",
		 url: useropsubURL,
		data: {
			ajax: 'deleteuser',
			back_uid:id,
		},
		success: function(result2) {
			console.log(result2);
			var json2 = JSON.parse(result2.toString());
			if(json2.flag == true) 
			{
				alert("操作成功！");
				history.go(-1);
			}
			else {
				alert("操作失败，详细原因：" + json2.berror);
			}
		}
	});
	   
	}	
}
$(document).ready(function(){
	$("#addantmiss").click(function()
	{
		//
		
		var newdomain = $("#crawlerdomain").val();
		var newurl    = $("#crawlerurl").val();
		var urlpar= /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/
		if(!urlpar.test(newurl))
		{
			alert('非法的入口URL!请重试！');
			return;
		}
		if(newurl.match(newdomain))
		{
			$.ajax({

				type: "post",
				 url: antmisssuburl,
				data: {
					ajax: 'addantmiss',
					newdomain : newdomain,
					newurl : newurl,
				},
				success: function(result) 
				{
					console.log(result);
					var json = JSON.parse(result.toString());
					if(json.flag == true) 
					{
						alert("操作成功！");
						window.location.reload();
					}
					else 
					{
						alert("操作失败，详细原因：" + json.berror);
					}
				}

			});
			
		}
		else
		{
			alert('输入的入口URL与所选域名不匹配！请重试');
			return;
		}

	});
});
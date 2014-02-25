/*
 *core js
 *@project 中国舆情
 *@version 2.0
 *@author c1avie (www.c1avie.com)
 *@copyright newthread
 */
 //判断用户是否登录
 $(function() {
 	var reg_login = $('#reglogin');
    var local = (window.location.href);
    var submit = "./index.php/Users";
    var url = "./index.php/homeAN/Userhome";
    var url1 = "./";
    var reg=/\S{0,}Userhome{1}\S{0,}/;
    var reg1 = /\S{0,}Login{1}\S{0,}/;
    var reg2 = /\S{0,}Register{1}\S{0,}/;
    if ((reg.test(local))||(reg1.test(local))||(reg2.test(local))) {
        submit = "../../index.php/Users";
        url = "../../index.php/homeAN/Userhome";
        url1 = "../../";
    }
 	$.ajax({
 		type: "post",
 		//同楼下
 		url: getLoginURL,
 		data: {
 			ajax: 'getLogin'
 		},
 		success: function(result) {
 			//console.log(result);
 			var json = JSON.parse(result.toString());
            if(json.flag == true) {
            	//不应该用绝对路径，不然部署的时候烦死
                // var uidname = '欢迎您！<a href='+url+'>' + json.username + '</a>' + 
                // '<a id="exit" href="#">' + "[退出]" + '</a>' + '<a href='+json.backurl+'>' +'[进入后台]'+ '</a>';
                var uidname = '<span>' + json.username  + '</span>' + 
                '<a id="exit" href="javascript:void(0);">' + "[退出]" + '</a>' + '<a href='+json.backurl+'>' +'[进入后台]'+ '</a>';
                reg_login.html(uidname);
            }

        	$('#exit').click(function() {
        		var lasturl = window.location.href;
		 		$.ajax({
		 			type: "post",
		 			url: getLoginURL,
		 			data: {
		 				ajax: 'logout',
		 				lasturl: lasturl
		 			},
		 			success: function(result2) {
		 				console.log(result2);
		 				var json2 = JSON.parse(result2.toString());
		 				if(json2.flag == true) {
		 					window.location.href = json2.returnurl;
		 				}
		 			}
		 		});
		 	});
	 	}
 	});

 });


 //tab选项卡
$(function () {
    var li_first = $("ul.menu li:first-child a");
    var content = $(".message");
    var layout = $(".message div.layout");
    var li_array = $("ul.menu li");
    li_first.addClass("current-menu");
    content.find(".layout:not(:first-child)").hide();
    layout.attr("id", function () {
        var linumber = idNumber("number") + layout.index(this);
        return linumber;
    });
    li_array.click(function () {
    	$(this).siblings().find('a').removeClass('current-menu');
    	$(this).find('a').addClass('current-menu');
        var li = li_array;
        var index = li.index(this);
        var number = idNumber("number");
        show(li, index, number);
    });
    function show (menu_li, menu_index, menu_number) {
        var content = menu_number + menu_index;
        $('#' + content).show().siblings().hide();
        menu_li.eq(menu_index).addClass("current").siblings().removeClass("current");
    };
    function idNumber (prefix) {
        var idNum = prefix;
        return idNum;
    };
});

//form validate
$(function() {
	var username = $('#username'),
        account = $('#account'),
		password = $('#password'),
		oldpassword = $('#oldpassword'),
		repassword = $('#repassword'),
		email = $('#email'),
		name = $('#name'),
		phone = $('#phone'),
		address = $('#address'),
		vcode = $('#vcode'),
		error = $('#error'),
        account_error = $('#account-error'),
		user_error = $('#username-error'),
		pass_error = $('#pass-error'),
		vcode_error = $('#vcode-error'),
		repass_error = $('#repass-error'),
		oldpass_error = $('#oldpass-error'),
		name_error = $('#name-error'),
		phone_error = $('#phone-error'),
		email_error = $('#email-error'),
		address_error = $('#address-error'),
		vcode_error = $('#vcode-error');


	//失去焦点事件
    account.blur(function() {
        var reg1 =  /^[a-zA-Z][a-zA-Z0-9_]*$/,
            reg2 = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
        if(account.val() == "") {
            account_error.html("账号为空!");
        }
        else if(!(reg1.test(account.val()) || reg2.test(account.val()))) {
            account_error.html("账号输入错误!");
        }
        else {
            account_error.addClass('right');
        }
    });
	username.blur(function() {
		var reg = /^[a-zA-Z][a-zA-Z0-9_]*$/;
		if(username.val() == "") {
			user_error.html("用户名为空!");
		}
		else if(!reg.test(username.val())) {
			user_error.html("用户名输入错误!");
		} 
		else {
            $.ajax({
                type: "post",
                url: "../../index.php/Users",
                data: {
                    ajax: 'checkUsername',
                    username: username.val()
                },
                success: function(result) {
                    var json = JSON.parse(result.toString());
                    if(json.flag) {
                        user_error.addClass("right");
                    }
                    else {
                        user_error.html("用户名已注册!");
                    }
                }
            });
		}
	});
	password.blur(function() {
		if(password.val() == "") {
			pass_error.html("密码为空!");
		}
		else if(password.val().length < 6 || password.val().length > 14) {
			pass_error.html("密码长度应为6到14位!");
		}
		else {
			pass_error.addClass("right");
		}
	});
	oldpassword.blur(function() {
		if(oldpassword.val() == "") {
			oldpass_error.html("密码为空!");
		}
		else if(oldpassword.val().length < 6 || password.val().length > 14) {
			oldpass_error.html("密码长度应为6到14位!");
		}
		else {
			oldpass_error.addClass("right");
		}
	});
	repassword.blur(function() {
		if(repassword.val() == "") {
			repass_error.html("重复密码为空!");
		}
		else if(repassword.val() !== password.val()) {
			repass_error.html("两次输入的密码不一致!");
		}
		else {
			repass_error.addClass('right');
		}
	});
	vcode.blur(function() {
		if(vcode.val() == "") {	
			vcode_error.html('请输入验证码!');
		}
		else {
			$.ajax({
                type: "post",
                url: "../../index.php/Users",
                data: {
                    ajax: 'checkvcode',
                    vcode: vcode.val()
                },
                success: function(result) {
                    var json = JSON.parse(result.toString());
                    // console.log(json.flag);
                    if(json.flag) {
                        vcode_error.addClass('right');
                    }
                    else {
                        vcode_error.html("验证码输入错误!");
                    }
                }
            });
		}
	});
	email.blur(function() {
		var reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
		if(email.val() == "") {
			email_error.html("邮箱为空!");
		}
		else if(!reg.test(email.val())) {
			email_error.html("邮箱输入错误!");
		}
		else {
            $.ajax({
                type: "post",
                url: "../../index.php/Users",
                data: {
                    ajax: 'checkEmail',
                    email: email.val()
                },
                success: function(result) {
                    var json = JSON.parse(result.toString());
                    if(json.flag) {
                        email_error.addClass("right");
                    }
                    else {
                        email_error.html("邮箱已注册!");
                    }
                }
            });
		}
	});
	name.blur(function() {
		var reg = /^[\u4E00-\u9FA5a-zA-Z]/;
		if(name.val() == "") {
			return;
		}
		if(!reg.test(name.val())) {
			name_error.html("真实姓名只能输入中文或字母!")
		}
		else {
			name_error.addClass("right");
		}
	});
	phone.blur(function() {
		var reg = /^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}/;
		if(phone.val() == "") {
			return;
		}
		else if(!reg.test(phone.val())) {
			phone_error.html("请输入正确的电话格式!");
		}
		else {
			phone_error.addClass("right");
		}
	});
	address.blur(function() {
		if(address.val() == "") {
			return;
		}
		else {
			address_error.addClass('right');
		}
	});

	//获取焦点事件
    account.focus(function() {
    	error.html("");
        account_error.html("");
        account_error.removeClass("right");
    });
	username.focus(function() {
		error.html("");
		user_error.html("");
		user_error.removeClass("right");
	});
	password.focus(function() {
		error.html("");
		pass_error.html("");
		pass_error.removeClass("right");
	});
	repassword.focus(function() {
		repass_error.html("");
		repass_error.removeClass("right");
	});
	oldpassword.focus(function() {
		oldpass_error.html("");
		oldpass_error.removeClass("right");
	});
	email.focus(function() {
		email_error.html("");
		email_error.removeClass("right");
	});
	name.focus(function() {
		name_error.html("");
		name_error.removeClass("right");
	});
	phone.focus(function() {
		phone_error.html("");
		phone_error.removeClass("right");
	});
	address.focus(function() {
		error.html("");
		address_error.removeClass("right");
	});
	vcode.focus(function() {
		error.html("");
		vcode_error.html("");
		vcode_error.removeClass("right");
	});
	//登录
	$('#login').click(function() {
		var checkUserName = $('#checkUserName').is(":checked");
		username.blur();
		password.blur();
		vcode.blur();
		// console.log(checkUserName);
		if(account_error.hasClass('right') && pass_error.hasClass('right') && vcode_error.hasClass('right')) {
            var account_ajax,
                reg = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
            if(reg.test(account.val())) {
                account_ajax = 'loginEmail';
            }
            else {
                account_ajax = 'loginUsername';
            }
			$.ajax({
                type: "post",
                url: "../../index.php/Users",
                data: {
                    ajax: account_ajax,
                    username: account.val(),
                    email:account.val(),
                    passwd: password.val(),
                    vcode: vcode.val(),
                    checkUserName: checkUserName
                },
                success: function(result) {
                	//console.log(result);
                    var json = JSON.parse(result.toString());
                    if(json.flag) {
                        if(json.group == 1 || json.group == 2) {
                        	window.location.href = "../../index.php/backAn";
                        }
                        else {
                        	window.location.href = "../../";
                        }
                    }
                    else {
                        error.html("账号或者密码错误!");
                    }
				}
			});
			// console.log("right");
		}
	});

	//注册
	$('#register').click(function() {
        username.blur();
        email.blur();
        password.blur();
        repassword.blur();
		if(user_error.hasClass('right') && pass_error.hasClass('right') && vcode_error.hasClass('right') && repass_error.hasClass('right')) {
			$.ajax({
				type: "post",
				url: "../../index.php/Users",
				data: {
                    ajax: 'reg',
					username: username.val(),
					email: email.val(),
					passwd: password.val(),
					name: name.val(),
					phone: phone.val(),
					address: address.val(),
					vcode: vcode.val()
				},
				success: function(result) {
                    var json = JSON.parse(result.toString());
                    if(json.flag == true) {
                       window.location.href = "../../";
                    }
                    else {
                        error.html("注册失败，请重新注册!");
                    }
				}
			});
		}
	});

	//个人信息
	$('#change').click(function() {
		if(oldpass_error.hasClass('right') && pass_error.hasClass('right') && repass_error.hasClass('right')) {
			$.ajax({
				type: "post",
				url: "",
				data: {
					name: name,
					phone: phone,
					address: address,
					oldpasswd: oldpassword,
					passwd: password,
					repasswd: repassword,
				},
				success: function(result) {
					//console.log(result);
					if(1 == result) {
						window.location.href = "";
					}
					else {
						error.html("用户名或者密码错误!");
					}
				}
			});
		}
	});
});
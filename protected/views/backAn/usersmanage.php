<?php
/**
*用户管理 view
*@package JelCore 
*@version 2014-01-06
*@var $oid  the operation type
*@var $this BackAnController 
*/
//定义资源引用路径
$ImagesURL = Yii::app()->request->baseUrl . "/images";  //图片资源
$JsURL     = Yii::app()->request->baseUrl . "/js";      //Js脚本
$CssURL    = Yii::app()->request->baseUrl . "/css";	    //css样式表
$HomeURL   = Yii::app()->homeUrl;		    			//主页URL
?><?php if($oid == 1)://编辑用户?>
<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>查找用户</span></h2>
				<div id="edit">
				<div class="form-group">
				<h3>从下面任选其一</h3>
				</div>
				<form method="post" name="findusers" action="<?php echo $this->createUrl('UsersManage',array('tid'=>'EditFindUsers'));?>">
					<div class="form-group">
						<label for="username">用户名包含：</label>
						<input type="text" name="username" id="usercon" value="<?php if($searchtype === 'username'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<label>邮箱包含：</label>
						<input type="text" name="email" id="emailcon" value="<?php if($searchtype === 'email'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<input type="submit" value="立即查找" id="login" />
					</div>
				</form>
				</div>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 2)://编辑用户二级页面  检索结果页?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>用户<?php if(isset($successInfo)){echo "($successInfo)";}?></span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">ID</th>
							<th scope="col" class="manage-column">用户名</th>
							<th scope="col" class="manage-column">邮箱</th>
							<th scope="col" class="manage-column">姓名</th>
							<th scope="col" class="manage-column">联系电话</th>
							<th scope="col" class="manage-column">地址</th>
							<th scope="col" class="manage-column">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($users as $key => $oneuser)
					{
						?>
						<tr>
							<td><?php echo $oneuser->uid;?></td>
							<td><?php echo $oneuser->username;?></td>
							<td><?php echo $oneuser->email;?></td>
							<td><?php echo $oneuser->name;?></td>
							<td><?php echo $oneuser->phone;?></td>
							<td><?php echo $oneuser->address;?></td>
							<td><a href="<?php echo $this->createUrl('UsersManage',array('tid'=>'EditUsersAfter','uid'=> $oneuser->uid));?>">[编辑]</a></td>
						</tr>
						<?php	
					}
					if(count($users) === 0)
						{
							?>
							<tr>
								<td>找不到结果！</td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
				<div class="paging">
							<?php
							$this->widget('CLinkPager',array(
																'header'=>'',
																'firstPageLabel'=>'首页', //指定回到首页的文本显示下面的类似，
																'lastPageLabel' =>'末页', //原本YII默认CSS设置隐藏首页和末页显示，这里已经作了修改。见/framework/web/pagers/pager.css
																'prevPageLabel' =>'上一页',
																'nextPageLabel' =>'下一页',
																'pages'=>$pages,
																'maxButtonCount'=>13,
															)
										);
							?>
				</div>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 3)://编辑用户三级页面?>
<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>编辑用户</span></h2>
				<form name="edituser" method="post" action="<?php echo $this->createUrl('UpdateUser',array('theuid'=>$theuser->uid));?>">
				<div class="form-group">
					<label>姓名：</label>
					<input type="text" name="name" id="name" value="<?php echo $theuser->name;?>"/>
					<div class="form-group-error" id="name-error"></div>
				</div>
				<div class="form-group">
					<label>联系电话：</label>
					<input type="text" name="phone" id="phone" value="<?php echo $theuser->phone;?>" />
					<div class="form-group-error" id="phone-error"></div>
				</div>
				<div class="form-group">
					<label>地址：</label>
					<input type="text" name="address" id="address" value="<?php echo $theuser->address;?>" />
					<div class="form-group-error" id="address-error"></div>
				</div>
				<div class="form-group">
					<input type="submit" value="立即修改" id="change" />
				</div>
				</form>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 4)://添加用户?>
		<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout4">
				<h2><i></i><span>添加用户<?php if(isset($successInfo)){echo "($successInfo)";}?></span></h2>
				<div class="form-group">
				<form method="post" name="doadduser" action="<?php echo $this->createUrl('NewUser');?>">
					<label for="username"><span>*</span>用户名：</label>
					<input type="text" name="username" id="username" />
					<div class="form-group-error" id="username-error"></div>
				</div>
				<div class="form-group">
					<label><span>*</span>邮箱：</label>
					<input type="text" name="email" id="email" />
					<div class="form-group-error" id="email-error"></div>
				</div>
				<div class="form-group">
					<label><span>*</span>密码：</label>
					<input type="password" name="passwd" id="passwd" />
					<div class="form-group-error" id="pass-error"></div>
				</div>
				<div class="form-group">
					<label><span>*</span>重复密码：</label>
					<input type="password" name="repasswd" id="repasswd" />
					<div class="form-group-error" id="repass-error"></div>
				</div>
				<div class="form-group">
					<input type="submit" value="立即添加" id="login" />
				</div>
				</form>
			</div>

		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 5)://删除用户?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>查找用户<?php if(isset($successInfo)){echo "($successInfo)";}?></span></h2>
				<div id="edit">
				<div class="form-group">
				<h3>从下面任选其一</h3>
				</div>
				<form method="post" name="findusers" action="<?php echo $this->createUrl('UsersManage',array('tid'=>'DeleteFindUsers'));?>">
					<div class="form-group">
						<label for="username">用户名包含：</label>
						<input type="text" name="username" id="usercon" value="<?php if($searchtype === 'username'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<label>邮箱包含：</label>
						<input type="text" name="email" id="emailcon" value="<?php if($searchtype === 'email'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<input type="submit" value="立即查找" id="login" />
					</div>
				</form>
				</div>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 6):?>
<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>删除用户</span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">ID</th>
							<th scope="col" class="manage-column">用户名</th>
							<th scope="col" class="manage-column">邮箱</th>
							<th scope="col" class="manage-column">姓名</th>
							<th scope="col" class="manage-column">联系电话</th>
							<th scope="col" class="manage-column">地址</th>
							<th scope="col" class="manage-column">操作</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($users as $key => $oneuser)
					{
						?>
						<tr>
							<td><?php echo $oneuser->uid;?></td>
							<td><?php echo $oneuser->username;?></td>
							<td><?php echo $oneuser->email;?></td>
							<td><?php echo $oneuser->name;?></td>
							<td><?php echo $oneuser->phone;?></td>
							<td><?php echo $oneuser->address;?></td>
							<td><a href="javascript:deleteUser(<?php echo $oneuser->uid;?>);">[删除]</a></td>
						</tr>
						<?php
					}
					if(count($users) === 0)
						{
							?>
							<tr>
								<td>找不到结果！</td>
							</tr>
							<?php
						}
					?>
					</tbody>
				</table>
				<div class="paging">
							<?php
							$this->widget('CLinkPager',array(
																'header'=>'',
																'firstPageLabel'=>'首页', //指定回到首页的文本显示下面的类似，
																'lastPageLabel' =>'末页', //原本YII默认CSS设置隐藏首页和末页显示，这里已经作了修改。见/framework/web/pagers/pager.css
																'prevPageLabel' =>'上一页',
																'nextPageLabel' =>'下一页',
																'pages'=>$pages,
																'maxButtonCount'=>13,
															)
										);
							?>
				</div>
			</div>
			
		</div>
	</div>
	<div id="hiddenlogin" style="display:none;">
		<form>
			<label>密码确认：</label><input type="password" name="conpasswd" id="conpasswd">
			<input type="button" value="确认" id="doconfirm">
 		</form>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 7)://权限管理:查找用户?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>权限分配</span></h2>
				<div id="edit">
				<div class="form-group">
				<h3>从下面任选其一</h3>
				</div>
				<form method="post" name="findusers" action="<?php echo $this->createUrl('UsersManage',array('tid'=>'AuthManageFindUsers'));?>">
					<div class="form-group">
						<label for="username">用户名包含：</label>
						<input type="text" name="username" id="usercon" value="<?php if($searchtype === 'username'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<label>邮箱包含：</label>
						<input type="text" name="email" id="emailcon"  value="<?php if($searchtype === 'email'){if($searchdata !== null)echo $searchdata;else echo "*";}?>" />
						<div class="form-group-error form-group-con">通配符"*"表示查找所有</div>
					</div>
					<div class="form-group">
						<input type="submit" value="立即查找" id="login" />
					</div>
				</form>
				</div>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
<?php if($oid == 8)://权限管理:编辑用户?>
	<!-- conent part begin -->
	<div class="content">
		<div class="main">

			<div class="layout" id="layout3">
				<h2><i></i><span>权限分配</span></h2>
				<table>
					<thead>
						<tr>
							<th scope="col" class="manage-column">ID</th>
							<th scope="col" class="manage-column">用户名</th>
							<th scope="col" class="manage-column">邮箱</th>
							<th scope="col" class="manage-column">姓名</th>
							<th scope="col" class="manage-column">联系电话</th>
							<th scope="col" class="manage-column">地址</th>
							<th scope="col" class="manage-column">变更权限</th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach($users as $key => $oneuser)
						{
							?>
							<tr>
								<td><?php echo $oneuser->uid;?></td>
								<td><?php echo $oneuser->username;?></td>
								<td><?php echo $oneuser->email;?></td>
								<td><?php echo $oneuser->name;?></td>
								<td><?php echo $oneuser->phone;?></td>
								<td><?php echo $oneuser->address;?></td>
								<td>
								<select id="back_group<?php echo $oneuser->uid;?>">
								<?php
								foreach($groups as $key => $onegroup)
								{
									?>
									<option value="<?php echo $onegroup->gid;?>" <?php if($onegroup->gid == $oneuser->group){echo "selected='selected'";}?> ><?php echo $onegroup->groupname;?></option>
									<?php
								}
								 ?>
								</select>
								<input type="button" value="变更" class="assign-submit" id="changeauth" onclick="javascript:changeAuthority(<?php echo $oneuser->uid;?>);">
							</td>
							</tr>

							<?php
						}
						if(count($users) === 0)
						{
							?>
							<tr>
								<td>找不到结果！</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<div class="paging">
							<?php
							$this->widget('CLinkPager',array(
																'header'=>'',
																'firstPageLabel'=>'首页', //指定回到首页的文本显示下面的类似，
																'lastPageLabel' =>'末页', //原本YII默认CSS设置隐藏首页和末页显示，这里已经作了修改。见/framework/web/pagers/pager.css
																'prevPageLabel' =>'上一页',
																'nextPageLabel' =>'下一页',
																'pages'=>$pages,
																'maxButtonCount'=>13,
															)
										);
							?>
				</div>
			</div>
			
		</div>
	</div>
	<!-- content part end -->
<?php endif;?>
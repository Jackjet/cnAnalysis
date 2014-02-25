/*
*@modifed by JelCore 2014-01-30
*
*
*
*
*/
$(document).ready(function(e) {
  var TreeName = 'TreeList';//树状ID
  var PrentNodeClass = 'ParentNode';//父节点的标识
  var ChildNodeClass = 'ChildNode';//没有下级子节点的标识
  var ChildrenListClass = 'Row';//子节点被包着的外层样式
  var NewNodeName = '新建类别';//默认新建节点的名称
  var Orititle = 'Temptitle';//保存原来名称的属性名称
  
  var TModuleNode,TChildNode,TModuleNodeName;
  TModuleNode = $('#TreeList .'+PrentNodeClass);//顶层节点
  TChildNode = $('.'+ChildNodeClass);
  TModuleNodeName = $('#TreeList .' + PrentNodeClass + ' .title');//顶层节点名称
  TModuleNode.removeClass('show').addClass('hidden');
  if(TModuleNode.next().hasClass(ChildrenListClass)){
    TModuleNode.next().css('display','none');//紧跟的下一个是子节点
  }
  
  //========================编辑区域的HTML源码=====================================================================================================
  function EditHTML(NewName){
    var str = '<div class="title">' + NewName + '</div>';
    str += '<div class="editBT"></div>';
    str += '<div class="editArea"><span>[编辑]</span><span>[添加下级类别]</span><span>[添加同级类别]</span><span>[删除]</span></div>';
    return str;
  } 
  //===============================================================================================================================================


  //==========================树状展开收缩的效果===================================================================================================
  TModuleNodeName.click(function(){
    TModuleNodeName_Click($(this));
  });
  
  //-------------------------------(定义:树状展开收缩的效果)------------
  function TModuleNodeName_Click(Obj){
    if(Obj.has('input').length==0){//非编辑模式下进行
      var tempNode = Obj.parent();
      if(tempNode.hasClass('hidden')){
        tempNode.removeClass('hidden').addClass('show');
        if(tempNode.next().hasClass(ChildrenListClass)){
        tempNode.next().css('display','');
        }
      }
      else{
        tempNode.removeClass('show').addClass('hidden');
        if(tempNode.next().hasClass(ChildrenListClass)){
          tempNode.next().css('display','none');
        }
      }
    }
  } 
  //==========================(定义:鼠标经过、离开节点的效果)============================  
  with(TModuleNode){
    mouseover(function(){
    setTimeout(TNode_MouseOver($(this)),3000); 
    });
    
    mouseout(function(){
    TNode_MouseOut($(this));
    });
  }
  
  with(TChildNode){
    mouseover(function(){
    setTimeout(TNode_MouseOver($(this)),3000);
    });
    
    mouseout(function(){
    TNode_MouseOut($(this));
    });
  }
  
  //-------------------------------(定义:TNode_MouseOver,TNode_MouseOut)----------------------------------
  function TNode_MouseOver(Obj){

/*ADD:ajax加载某分类下的文章总数*/
    var postsnum = 0;
    var tid = Obj.attr('id');
    //AJAX交互:提交请求
  $.ajax({
    type: "post",
     url: arttypesubURL,//类别维护的后端AJAX接口
    data: {
      ajax: 'getpostsnum',//后端请求类型是获取分类文章数
      typeid:tid
    },
    success:function(resultj) 
    {
    //console.log(resultj);
      var jsonj = JSON.parse(resultj.toString());
      if(jsonj.flag == true) 
      {
        postsnum = jsonj.postsnum;
        Obj.attr('title','该分类共有文章数:' + postsnum.toString());
        if(!(Obj.hasClass('show'))){
        Obj.addClass('mouseOver');
    }
      }
      else {
            Obj.attr('title',"获取失败:" + jsonj.berror);
            if(!(Obj.hasClass('show'))){
              Obj.addClass('mouseOver');
            }
      }
    }
  });
  }
  
  function TNode_MouseOut(Obj){
    Obj.removeClass('mouseOver');
  }
//===================================================================================================================================================
 
//==========================编辑区操作=============================================================================================================
  $('.editArea').each(function(){
    EditArea_Event($(this));
  });
  //-------------------------------(定义:EditArea_Event)----------------------------------
  function EditArea_Event(Obj){
    var objParent = Obj.parent();
    var objTitle = objParent.find('.title');//节点名称
     //-----------------编辑区的鼠标效果------------------ 
    Obj.children().each(function(){
      with($(this)){
        mouseover(function(){
        $(this).addClass('mouseOver');
        });
        mouseout(function(){
        $(this).removeClass('mouseOver');
        });
      }
    });
     //------------------------------------------------- 
    Obj.children().each(function(index, element) {
      $(this).click(function(){
        if($('#TreeList').has('input').length==0){
        switch(index){
/*M*/     case 0: EditNode(objTitle,objTitle.html(),"edit");break;//编辑
          case 1: AddNode(1,objParent,NewNodeRename(1,objTitle));break;//添加下级目录
          case 2: AddNode(0,objParent,NewNodeRename(0,objTitle));break;//添加同级目录
          case 3: DelNode(objParent);break;//删除
        }
        }
        else{
        alert('请先取消编辑状态！'); 
        }
      });
    });
  }
  //************************************************************************************************************
  //************************************************************************************************************
  
  //===============================验证编辑结果================================
  function CheckEdititon(pNode,text){
    var SameLevelTags = new Array(PrentNodeClass,ChildNodeClass);
    var SameLevelTag  = '';
    for(var i=0;i<SameLevelTags.length;i++){
      if(pNode.parent().attr('class').indexOf(SameLevelTags[i]) > -1){
        SameLevelTag = SameLevelTags[i];
        break;
      }
    }
    if(SameLevelTag!=''){
      if(text!=''){
      //---------------- 根据节点样式遍历同级节点 --------------------
      var IsExsit = false;
      pNode.parent().parent().children('div').children('.title').each(function(){
        if(pNode.find('input').val()==$(this).html()){
          IsExsit = true;
          alert('抱歉！同级已有相同名称！');
          return false;
        }
      });
      return !IsExsit;
      }
      
      else{
        alert('不能为空!');
        return false;
      }
    }
  }
  //===================================================================================================================================================
  //=================================自动命名================================
  function NewNodeRename(tag,pNode){
    //---------------- 根据节点样式遍历同级节点 --------------------
    var MaxNum = 0;
    var TObj;
    if(tag==0){//添加同级目录
      if(pNode.attr('id')==TreeName){
        TObj = pNode.children('div').children('.title');
      }
      else{
        TObj = pNode.parent().parent().children('div').children('.title');
      }
    }
    else{//添加下级目录
      if(pNode.parent().next().html()!=null){//原来已有子节点
        TObj = pNode.parent().next().children('div').children('.title');
      }
      else{//没有子节点
        TObj = null;
      }
    }
    
    if(TObj){
      TObj.each(function(){
        var CurrStr = $(this).html();
        var temp;
        if(CurrStr.indexOf(NewNodeName)>-1){
          temp = parseInt(CurrStr.replace(NewNodeName,''));
          if(!isNaN(temp)){
            if(!(temp<MaxNum)){
              MaxNum = temp + 1;
            }
          }
          else{
          MaxNum = 1;  
          }
        }
      });
    }
    
    var TempNewNodeName = NewNodeName;
    if(MaxNum>0){
      TempNewNodeName += MaxNum;
    }
    return TempNewNodeName;
  }
  





  //=============================== 编辑节点 ================================
/*M*/  function EditNode(obj,text,etype){
    obj.attr(Orititle,text);//将原来的text保存到Orititle中
    obj.html("<input type='text' class=input value=" + text + ">");//切换成编辑模式
    obj.parent().find('.editBT').html("<div class=ok title=确定></div><div class=cannel title=取消></div>");
    obj.has('input').children().first().focusEnd();//聚焦到编辑框内
  
    obj.parent().find('.ok').click(
        function(){
/*M*/      Edit_OK(obj,etype);
    });
    
    obj.parent().find('.cannel').click(
        function(){
      Edit_Cannel(obj,etype);
    });
  }
  





  //=============================== 添加节点 ================================
  function AddNode(tag,obj,NameStr){
    /*ADD*/ 
    //AJAX交互:获取新的类别ID
    var newtid = '';//新的类别ID
      $.ajax({
        type: "post",
         url: arttypesubURL,//类别维护的后端AJAX接口
        data: {
          ajax: 'getnewtypeid'//后端请求类型是请求新的类别ID
        },
        success:function(resultj) 
        {
          //console.log(resultj);
          var jsonj = JSON.parse(resultj.toString());
          if(jsonj.flag == true) 
          {
            newtid = jsonj.newtid;//得到新的类别ID
            //alert(newtid);
            if(tag==0 || tag==1){
              var newNode = $('<div class="' + ChildNodeClass + '" id= "' + newtid + '"></div>');
              if(tag==0){//添加同级目录
              newNode.appendTo(obj.parent());
              }
              else{//添加下级目录
              if(!(obj.next()) || (obj.next().attr('class')!=ChildrenListClass)){//最后一个节点和class!=ChildrenListClass都表示没有子节点
                var ChildrenList = $('<div class=' + ChildrenListClass + '></div>');
                ChildrenList.insertAfter(obj);//将子节点的”外壳“加入到对象后面
                newNode.appendTo(ChildrenList);//将子节点加入到”外壳“内
              }
              else{
                newNode.appendTo(obj.next());//将子节点加入到”外壳“内
              }
              obj.attr('class',PrentNodeClass + ' show');//激活父节点展开状态模式
              obj.next().css('display','');//展开子节点列表
              }
              
              with(newNode){
                if(newNode.parent().parent().parent().parent().find('.Row')) {
                  console.log("true");
                } 
                html(EditHTML(NameStr));
                //---------------------------------动态添加事件-------------------------------
                mouseover(function(){
                TNode_MouseOver($(this));
                });
                
                mouseout(function(){
                TNode_MouseOut($(this));
                });
                
                find('.title').click(function(){
                  TModuleNodeName_Click($(this));
                });
                
                find('.editArea').each(function(){
                  EditArea_Event($(this));
                });
                //---------------------------------------------------------------------------
              }
        /*M*/ EditNode(newNode.find('.title'),newNode.find('.title').html(),"new");//添加后自动切换到编辑状态
            }


          }
          else {
            alert("分配新的ID时发生错误，详细原因：" + jsonj.berror);
            return;
          }
        }
      });       
      
  }
  


  //=============================== 删除节点 ================================
  function DelNode(obj){
    if(confirm('确定要删除吗？')){
      var objParent = obj.parent();
      var objChildren = obj.next('.Row');
      var objParent_r  = obj.parent('.Row');
/*ADD:删除操作时改变目录结构*/
      var typeid = obj.attr('id');
      var parenttypeid = objParent_r.prev('.ParentNode').attr('id');
      //AJAX交互:提交删除的类别信息
      $.ajax({
        type: "post",
         url: arttypesubURL,//类别维护的后端AJAX接口
        data: {
          ajax: 'deletetype',//后端请求类型是编辑类别
          typeid:typeid,
          parenttypeid:parenttypeid
        },
        success:function(resultj) 
        {
          console.log(resultj);
          var jsonj = JSON.parse(resultj.toString());
          if(jsonj.flag == true) 
          {
              alert('删除成功！');
              if(typeof(objChildren.attr('class')) == 'undefined')//没有子分类
              {
                objChildren.remove();//删除Row外壳下的所有元素
              }
              else
              {
                 var objGrandChildren = objChildren.children();//当前节点的所有子节点(去掉了ROW外壳)
                 objParent_r.append(objGrandChildren);
                 objChildren.remove();//删除Row外壳下的所有元素
              }
              obj.remove();//基于Jquery是利用析构函数，所以“删除”后其相关属性仍然存在，除非针对ID来操作就可以彻底删除
              ChangeParent(objParent);
        }
          else {
            alert("删除失败，详细原因：" + jsonj.berror);
          }
        }
      });
    }
  }
  



  //=============================== 编辑[确定]按钮 ================================
/*M*/  function Edit_OK(obj,etype){
    var tempText = obj.has('input').children().first().val();
    
/*ADD*/var tid = obj.parent().attr('id');//获取分类ID 
    if(CheckEdititon(obj,tempText)){
/*ADD*/ if(etype == "edit")
        {
            //AJAX交互:提交修改的类别信息
              $.ajax({
                type: "post",
                 url: arttypesubURL,//类别维护的后端AJAX接口
                data: {
                  ajax: 'edittype',//后端请求类型是编辑类别
                  typeid:tid,
                  typename:tempText
                },
                success:function(resultj) 
                {
                  var jsonj = JSON.parse(resultj.toString());
                  if(jsonj.flag == true) 
                  {
                    alert("修改成功！");
                    obj.html(tempText);
                  }
                  else {
                    alert("修改失败，详细原因：" + jsonj.berror);
                    obj.html(obj.attr(Orititle));
                  }
                }
              });
        }
        if(etype == 'new')
        {
            var ptid = obj.parent().parent().prev().attr('id');//获取新建分类的父亲分类ID
            //AJAX交互:提交修改的类别信息
              $.ajax({
                type: "post",
                 url: arttypesubURL,//类别维护的后端AJAX接口
                data: {
                  ajax: 'addtype',//后端请求类型是编辑类别
                  newtypeid:tid,
                  newtypename:tempText,
                  parenttypeid:ptid
                },
                success:function(resultj) 
                {
                  var jsonj = JSON.parse(resultj.toString());
                  if(jsonj.flag == true) 
                  {
                    alert("添加成功！");
                    obj.html(tempText);
                  }
                  else {
                    alert("添加失败，详细原因：" + jsonj.berror);
                    obj.html(obj.attr(Orititle));
                  }
                }
              });    
        }
    }
    else{
      obj.html(obj.attr(Orititle));  
    }
    obj.removeAttr(Orititle);
    obj.parent().find('.editBT').html('');
  }
  


  //=============================== 编辑[取消]按钮 ================================
  function Edit_Cannel(obj,etype){
    if(etype=='edit')
    {
        obj.html(obj.attr(Orititle));
        obj.removeAttr(Orititle);
        obj.parent().find('.editBT').html('');
    }
    if(etype=='new')
    {
        obj.parent().remove();
    }
  }
  






  
  //=============================== 改变父节点样式 ==============================
  function ChangeParent(obj){
    if(obj.find('.ChildNode').length==0){//没有子节点
      obj.prev('.'+PrentNodeClass).attr('class',ChildNodeClass);
      obj.remove();
    }
  }
  
  
  //=============================== 设置聚焦并使光标设置在文字最后 ==============
  $.fn.setCursorPosition = function(position){  
    if(this.lengh == 0) return this;  
    return $(this).setSelection(position, position);  
  }  
    
  $.fn.setSelection = function(selectionStart, selectionEnd) {  
    if(this.lengh == 0) return this;  
    input = this[0];  
    
    if (input.createTextRange) {
      var range = input.createTextRange();  
      range.collapse(true); 
      range.moveEnd('character', selectionEnd);  
      range.moveStart('character', selectionStart);  
      range.select(); 
    } else if (input.setSelectionRange) {  
      input.focus(); 
      input.setSelectionRange(selectionStart, selectionEnd);  
    }  
    return this;  
  }  
    
  $.fn.focusEnd = function(){
    this.setCursorPosition(this.val().length);  
  }
  
  //==================================================================================
  
});
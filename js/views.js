/*JelCore*/
var lastindex = -1;
var lasttext = "";
$("#typeid").change(function()
{
	//
	var copt = $("#typeid").find("option:selected");
	var copttext = copt.text();


	if(lastindex != -1 && $("#typeid ").get(0).selectedIndex != lastindex)
	{
		document.getElementById('typeid').options[lastindex].text = lasttext;
	}
	//保存历史的值
	lastindex = $("#typeid ").get(0).selectedIndex;
	lasttext = copttext;

	var tmpcopt = copttext.replace(/\s/g,"");
	copt.text(tmpcopt);
});

$("#artlistsel").change(function()
{
	//
	var link = $("#artlistsel").val();
	window.location.href = link;

});
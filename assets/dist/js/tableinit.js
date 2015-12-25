
function initTable() {
	$("#main-table").flexigrid({
		url: 'users/api_entry_list',
		dataType: 'json',
		colModel : [
			{display: 'User name', name : 'username', width : 150, sortable : true, align: 'left'},
			{display: 'Email', name : 'email', width : 150, sortable : true, align: 'left'},
			{display: 'udid', name : 'udid', width : 300, sortable : true, align: 'left'},
			{display: 'Device token', name : 'devicetoken', width : 340, sortable : true, align: 'left'},
			{display: 'Full name', name : 'fullname', width : 140, sortable : true, align: 'left'},
			{display: 'Church', name : 'church', width : 140, sortable : true, align: 'left'},
			{display: 'Province', name : 'province', width : 140, sortable : true, align: 'left'},
			{display: 'City', name : 'city', width : 140, sortable : true, align: 'left'},
			{display: 'Birth day', name : 'bday', width : 140, sortable : true, align: 'left'},
			{display: 'QB id', name : 'qbid', width : 100, sortable : true, align: 'left'},
			{display: 'Avatar URL', name : 'avatar', width : 200, sortable : true, align: 'left'},
			{display: 'Token', name : 'token', width : 200, sortable : true, align: 'left'},
		],
		buttons : [
			{name: 'Edit', bclass: 'edit', onpress : doCommand},
			{name: 'Delete', bclass: 'delete', onpress : doCommand},
			{separator: true}
		],
		searchitems : [
			{display: 'User name', name : 'username'},
			{display: 'email', name : 'email', isdefault: true},
		],
		sortname: "id",
		usepager: true,
		title: "Registered iPrayees",
		useRp: true,
		rp: 10,
		showTableToggleBtn: false,
		resizable: true,
		singleSelect: true,
		onDoubleClick: onDblClick,
		height: 700
    });
	
	$(".btn-edit").click(function() {
		$(".fbutton .edit").click();
	});

	$(".btn-delete").click(function() {
		$(".fbutton .delete").click();
	});
};

function onDblClick(target, grid) {
	var index = $(target).attr("data-id");

	window.location.assign(siteUrl + "Users/edit/" + index);
}
  
function doCommand(comm) {
	var selectedUser = $(".flexigrid .trSelected").attr("data-id");

	if (!selectedUser) {
		alert("Please select the record that is going to be modified.");
		return;
	}

	if (comm == "Edit") {
		window.location.assign(siteUrl + "Users/edit/" + selectedUser);
	}
	else if (comm == "Delete") {
		if (!confirm("Are you sure to delete this iprayee? You can never undo this action.")) {
			return;
		}

		window.location.assign(siteUrl + "Users/del/" + selectedUser);
	}
}
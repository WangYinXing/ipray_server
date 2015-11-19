
function initTable() {
	$("#main-table").flexigrid({
		url: 'users/api_entry_get_userlist',
		dataType: 'json',
		colModel : [
		{display: 'User name', name : 'username', width : 150, sortable : true, align: 'left'},
		{display: 'Email', name : 'email', width : 150, sortable : true, align: 'left'},
		{display: 'QBID', name : 'qbID', width : 150, sortable : true, align: 'left'},
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
		resizable: false,
		singleSelect: true
    });
};
	
  
function doCommand(comm) {

}
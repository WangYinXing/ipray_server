
$(function() {
	$(document).ready(function() {
		$("#main-table").flexigrid({
			url: 'http://ipraytest.com/users/api_entry_get_userlist',
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
				{display: 'First Name', name : 'first_name'},
				{display: 'Surname', name : 'surname', isdefault: true},
				{display: 'Position', name : 'position'}
			],
			sortname: "id",
			sortorder: "asc",
			usepager: true,
			title: "Staff",
			useRp: true,
			rp: 10,
			showTableToggleBtn: false,
			resizable: false,
			singleSelect: true
		});
	});
	
	function doCommand(comm) {

	}
});
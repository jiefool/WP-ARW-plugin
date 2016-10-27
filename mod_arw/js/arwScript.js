function confirmDelete() {
  if (confirm("Are you sure you want to delete")) {
    return true;
  }
  return false;
}

jQuery(function(){
	jQuery('#toplevel_page_arWizardSettings ul li:gt(2)').hide();
	//jQuery('#toplevel_page_arWizardSettings .wp-submenu-wrap
});

checked = false;
function checkedAll () {
	if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
		document.getElementById('myform').elements[i].checked = checked;
	}
}
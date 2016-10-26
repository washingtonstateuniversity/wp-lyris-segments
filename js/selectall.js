// JavaScript Document
jQuery(document).ready(function() {

jQuery('#select_all').click(function() {
    var c = this.checked;
    jQuery(':checkbox').prop('checked',c);
});
});
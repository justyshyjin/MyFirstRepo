$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
	baseValidator.initateThroughJquery($('form[name="userForm"]'),'userForm').setLocale(window.Mara.locale);
});
$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
	baseValidator.initateThroughJquery($('form[name="staticContentForm"]'),'staticContentForm').setLocale(window.Mara.locale);
});
$(document).ready(function(){
    var loader = $('#preloader');
    loader.find('#status').css('display','none');
    loader.css('display','none');
	baseValidator.initateThroughJquery($('form[name="smsTemplateForm"]'),'smsTemplateForm').setLocale(window.Mara.locale);
});
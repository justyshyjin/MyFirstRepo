'use strict';

var ReportsController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
    var self = this;
    this.info = {};
    this.selectedRecords = [];
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument(this);
    requestFactory.toggleLoader();
    var percentageArray = [];
    var countArray = [];
    var totalVideoArray;
    var jsonData = [];
    var videojsonData = [];
    var subscribedUserjsonData = [];
	this.usercharttype = 'day';
	this.videocharttype = 'day';
	this.subscribeduser = 'day';
    this.defineProperties = function(data) {
    	requestFactory.toggleLoader();
    	jsonData = [];
    	videojsonData = [];
    	subscribedUserjsonData = [];
        this.info = data.info;
        this.totalNumberOfVideos = data.info.total_number_of_videos;
        totalVideoArray =  data.info.total_number_of_videos;
        this.totalProgressingVideos = data.info.total_progressing_videos;
        this.totalVideoPresets = data.info.total_video_presets;
        this.activeVideoPresets = data.info.active_video_presets;
        this.awsStats = data.info.aws_stats;
        this.latestVideos = data.info.latest_videos;
        this.progressingVideos = data.info.progressing_videos;
        this.topCategories = data.info.top_categories;
        this.dateWiseVideoUploadCount = data.info.date_wise_video_upload_count;
        this.currentYearMonthString = data.info.current_year_month_string;
        this.totalAwsCost = data.info.total_aws_cost;
        this.monthlyAwsCost = data.info.monthly_aws_cost;
        this.totalNumberOfActiveVideos = data.info.total_number_of_active_videos;
        this.subcribedCount = data.info.subcribed_count;
        this.totalNumberOfInActiveVideos = data.info.total_number_of_inactive_videos;
        this.totalNumberOfLiveVideos = data.info.total_number_of_live_videos;
        this.totalNumberOfCustomer = data.info.total_number_of_customer;
        this.totalNumberOfActiveCustomer = data.info.total_number_of_active_customer;
        this.totalNumberOfInactiveCustomer = data.info.total_number_of_inactive_customer;
        this.calculateVideoPercentage(data.info.total_number_of_videos,data.info.total_number_of_active_videos,data.info.total_number_of_inactive_videos,data.info.total_number_of_live_videos);
        this.calculateCustomerPercentage(data.info.total_number_of_customer,data.info.total_number_of_active_customer,data.info.total_number_of_inactive_customer,data.info.subcribed_count);
        var accurateProgressingPercentage = (this.totalProgressingVideos/this.totalNumberOfVideos)*100;
        this.progressingVideosPercentage = Math.round(accurateProgressingPercentage * 100) / 100;
        this.pdfcount = data.info.pdf_count;
        this.wordcount = data.info.word_count;
        this.mp3count = data.info.mp3_count;
        this.commentcount = data.info.comment_count;
        
       
        angular.forEach(data.info.customer_data, function(value, key) {
        	var temp = new Object();
            temp["x"] = new Date(value.month);
            temp["y"] = value.count;
            jsonData.push(temp); 
        	});
        angular.forEach(data.info.video_data, function(value, key) {
        	var temp = new Object();
            temp["x"] = new Date(value.month);
            temp["y"] = value.count;
            videojsonData.push(temp); 
        	}); 
        angular.forEach(data.info.subscribed_user_data, function(value, key) {
        	var temp = new Object();
            temp["x"] = new Date(value.month);
            temp["y"] = value.count;
            subscribedUserjsonData.push(temp); 
        	}); 
        
        
        this.drawUsersChart();
        this.drawVideoChart();
        this.drawSubscribedUserChart();
        $("#preloader").attr('style','display:none');
    };
    this.calculateVideoPercentage = function(totalVideos,activeVideos,inactiveVideos,liveVideos){    	 
   	 this.activeVideoPercent = ((activeVideos/totalVideos)*100).toFixed(0);
   	 this.inactiveVideoPercent = ((inactiveVideos/totalVideos)*100).toFixed(0);
   	 this.liveVideoPercent  = ((liveVideos/totalVideos)*100).toFixed(0);
   	 percentageArray.push(this.activeVideoPercent);
   	 percentageArray.push(this.inactiveVideoPercent);
   	 percentageArray.push(this.liveVideoPercent);
   	 countArray.push(activeVideos);
   	 countArray.push(inactiveVideos);
   	 countArray.push(liveVideos);
   }
   this.calculateCustomerPercentage = function(totalCustomer,activeCustomer,inactiveCustomer,subscribedCustomer){    	 
  	 this.activeCustomerPercent = ((activeCustomer/totalCustomer)*100).toFixed(0);
  	 this.inactiveCustomerPercent = ((inactiveCustomer/totalCustomer)*100).toFixed(0);
  	 this.subcribedUserPercentage = ((subscribedCustomer/totalCustomer)*100).toFixed(0);
  }
    scope.selectCustType = function(type) {
    	scope.reportCtrl.fetchInfo(type,scope.reportCtrl.videocharttype,scope.reportCtrl.subscribeduser);
    	scope.reportCtrl.usercharttype = type;
    	}
    scope.selectVideoType = function(type) {
    	scope.reportCtrl.fetchInfo(scope.reportCtrl.usercharttype,type,scope.reportCtrl.subscribeduser);
    	scope.reportCtrl.videocharttype = type;
   	}
    scope.selectSubcribedUserType = function(type) {
    	scope.reportCtrl.fetchInfo(scope.reportCtrl.usercharttype,scope.reportCtrl.videocharttype,type);
    	scope.reportCtrl.subscribeduser = type;
   	}   
    
    this.drawUsersChart = function() {
		var chart = new CanvasJS.Chart("users-chart",
		{
			axisX:{
				gridThickness: 0,
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 10,
				labelAngle: -50,
			},
			axisY:{
				title: "User Count",
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				titleFontSize: 16,
			},
			data: [
			{
				type: "splineArea",
		        markerSize: 5,
				color: '#5b74a8',
				dataPoints: jsonData
			}             
			],
		});
		chart.render();
    };
    this.drawVideoChart = function() {
		var chart = new CanvasJS.Chart("video-chart",
		{
			axisX:{
				gridThickness: 0,
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				labelAngle: -50,
			},
			axisY:{
				gridThickness: 2,
				title: "Video Count",
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				titleFontSize: 16,
			},
			data: [
			{
				type: "splineArea",
				markerSize: 5,
				color: '#f49c70',
				dataPoints: videojsonData
			}             
			]
		});
		chart.render();
    };
    this.drawSubscribedUserChart = function() {
		var chart = new CanvasJS.Chart("subscribed-user",
		{
			axisX:{
				gridThickness: 0,
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				labelAngle: -50,
			},
			axisY:{
				gridThickness: 2,
				title: "Subscribed User Count",
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				titleFontSize: 16,
			},
			data: [
			{
				type: "splineArea",
				markerSize: 5,
				color: '#f49c70',
				dataPoints: subscribedUserjsonData
			}             
			]
		});
		chart.render();
    };
    
    
    this.addLeadingZero = function(number) {
    	if(number < 10) {
    		return '0'+number;
    	}
    	else {
    		return String(number);
    	}
    };
    
    this.fetchInfo = function($custType, $videoType,$subscribeduser) {
      requestFactory.get(requestFactory.getUrl("reports/info/"+$custType+"/"+$videoType+"/"+$subscribeduser),self.defineProperties,function(){});
    };
    
    this.fetchInfo('day','day','day');
}];


window.gridControllers = {ReportsController : ReportsController};
window.gridDirectives  = {
	//baseValidator    : validatorDirective,
};
'use strict';

var DashboardController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function(scope,requestFactory,$window,$sce,$timeout,$compile,$interval){
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
    this.defineProperties = function(data) {
    	requestFactory.toggleLoader();
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
        this.total_revenue = data.info.total_revenue;
        this.monthlyAwsCost = data.info.monthly_aws_cost;
        this.totalNumberOfActiveVideos = data.info.total_number_of_active_videos;
        this.subcribedCount = data.info.subcribed_count;
        this.totalNumberOfInActiveVideos = data.info.total_number_of_inactive_videos;
        this.totalNumberOfLiveVideos = data.info.total_number_of_live_videos;
        this.totalNumberOfCustomer = data.info.total_number_of_customer;
        this.totalNumberOfActiveCustomer = data.info.total_number_of_active_customer;
        this.totalNumberOfInactiveCustomer = data.info.total_number_of_inactive_customer;
        this.calculateVideoPercentage(data.info.total_number_of_videos,data.info.total_number_of_active_videos,data.info.total_number_of_inactive_videos,data.info.total_number_of_live_videos);
        this.calculateCustomerPercentage(data.info.total_number_of_customer,data.info.total_number_of_active_customer,data.info.total_number_of_inactive_customer);
        var accurateProgressingPercentage = (this.totalProgressingVideos/this.totalNumberOfVideos)*100;
        this.progressingVideosPercentage = Math.round(accurateProgressingPercentage * 100) / 100;
       
        angular.forEach(data.info.customer_data, function(value, key) {
        	var temp = new Object();
            temp["x"] = new Date(value.month);
            temp["y"] = value.count;
            jsonData.push(temp); 
        	});
        
        document.querySelector('.processing-status .progress-bar-success').style.width = this.progressingVideosPercentage+'%';
        
        this.drawUsersChart();
        this.drawAwsChart();
        
        $(".circular-progress-bar").loading(totalVideoArray,percentageArray,countArray);
    };
    this.calculateVideoPercentage = function(totalVideos,activeVideos,inactiveVideos,liveVideos){    	 
    	 this.activeVideoPercent = isNaN(((activeVideos/totalVideos)*100).toFixed(0))?0:((activeVideos/totalVideos)*100).toFixed(0);
    	 this.inactiveVideoPercent = isNaN(((inactiveVideos/totalVideos)*100).toFixed(0))?0:((inactiveVideos/totalVideos)*100).toFixed(0);
    	 this.liveVideoPercent  = isNaN(((liveVideos/totalVideos)*100).toFixed(0))?0:((liveVideos/totalVideos)*100).toFixed(0);
    	 percentageArray.push(this.activeVideoPercent);
    	 percentageArray.push(this.inactiveVideoPercent);
    	 percentageArray.push(this.liveVideoPercent);
    	 countArray.push(activeVideos);
    	 countArray.push(inactiveVideos);
    	 countArray.push(liveVideos);
    }
    this.calculateCustomerPercentage = function(totalCustomer,activeCustomer,inactiveCustomer){    	 
   	 this.activeCustomerPercent = isNaN(((activeCustomer/totalCustomer)*100).toFixed(0))?0:((activeCustomer/totalCustomer)*100).toFixed(0);
   	 this.inactiveCustomerPercent = isNaN(((inactiveCustomer/totalCustomer)*100).toFixed(0))?0:((inactiveCustomer/totalCustomer)*100).toFixed(0);
   }
    this.drawAwsChart = function() {
    	var chart = new CanvasJS.Chart("aws-chart",
		{
			title:{
				text: ""
			},
			toolTip:{
				content: '{name}',
			},
			data: [
			{
				type: "pie",
				startAngle:-90,
				dataPoints: [
					{ y: 60, color: "#C2C2C2", name: 'Space Used: 60 GB' },
					{ y: 40, color: "#45ABBE", name: 'Space Remaining: 40 GB' }
				]
			}
			]
		});
		chart.render();
    };
    
    this.drawUsersChart = function() {
		var chart = new CanvasJS.Chart("users-chart",
		{
			title:{
				text: ""
			},
			axisX:{
				gridThickness: 0,
				tickLength: 2,
				tickThickness: 2,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,
				labelAngle: -30,
				labelFontFamily: "'Montserrat', sans-serif"
			},
			axisY:{
				gridThickness: 2,
				tickLength: 0,
				tickThickness: 0,
				gridColor: '#F7F7F7',
				lineThickness: 0,
				labelFontSize: 12,				
				labelFontFamily: "'Montserrat', sans-serif"
			},
			options: {
		        responsive: false
		    },
			data: [
			{
				type: "line",
				markerSize: 5,
				color: '#00d9fe',
				fillOpacity: 1,
				lineThickness: 3,
				dataPoints: jsonData
			}             
			]
		});
		chart.render();
    };
    
    this.drawChart = function() {
    	var chartData = [];
    	var currentDay = 1;
    	var day;
    	for(var i = 0; i < this.dateWiseVideoUploadCount.length; i++) {
    		if(currentDay < this.dateWiseVideoUploadCount[i].day_of_month) {
    			for(currentDay; currentDay < this.dateWiseVideoUploadCount[i].day_of_month; currentDay++) {
    				day = this.addLeadingZero(currentDay);
        			chartData.push({ date: this.currentYearMonthString+'-'+day, no_of_videos: 0 });
    			}
    		}
    		
    		chartData.push({ date: this.dateWiseVideoUploadCount[i].date, no_of_videos: this.dateWiseVideoUploadCount[i].no_of_videos });
    		currentDay = this.dateWiseVideoUploadCount[i].day_of_month;
    	}
    	
    	Morris.Line({
    	    element: 'line-example',
    	    data: chartData,
    	    xkey: 'date',
    	    ykeys: ['no_of_videos'],
    	    labels: ['Number of Videos']
    	});
    };
    
    this.addLeadingZero = function(number) {
    	if(number < 10) {
    		return '0'+number;
    	}
    	else {
    		return String(number);
    	}
    };
    
    this.fetchInfo = function() {
      requestFactory.get(requestFactory.getUrl('dashboard/info'),this.defineProperties,function(){});
    };
    
    this.fetchInfo();
}];


window.gridFilters = {INR : function () {
    return function (input) {
        if (! isNaN(input)) {
            var currencySymbol = '';
            //var output = Number(input).toLocaleString('en-IN');   <-- This method is not working fine in all browsers!           
            var result = input.toString().split('.');

            var lastThree = result[0].substring(result[0].length - 3);
            var otherNumbers = result[0].substring(0, result[0].length - 3);
            if (otherNumbers != '')
                lastThree = ',' + lastThree;
            var output = otherNumbers.replace(/\B(?=(\d{2})+(?!\d))/g, ",") + lastThree;
            
            if (result.length > 1) {
                output += "." + result[1];
            }            

            return currencySymbol + output;
        }
    }
}};
window.gridControllers = {DashboardController : DashboardController};
window.gridDirectives  = {
	//baseValidator    : validatorDirective,
};   


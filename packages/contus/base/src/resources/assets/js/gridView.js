/**
 * This file is used to create list view with pagination,search,sorting and
 * delete features
 */
var gridObj = {
		/**
         * Object property to hold the request param prefix
         * 
         * @var string
         */		
		requestParamPrefix : 'request',
		/**
         * Object property to hold the request param Concatenator
         * 
         * @var string
         */		
		requestParamKeyConcatenator : '_',
		/**
         * Function is used to show pagination link
         * 
         * @param object
         *            scope
         * @param int
         *            totalLinks
         * @return void
         */
		paginate : function(scope,totalLinks) {
			scope.links = [];
		    if(scope.currentPage > totalLinks) {
			    return false;
		    }
			    var counter = Math.floor(scope.currentPage/5);
		    if(counter == 0 ) {
		        counter = 1;
		    }
		    else {
		        counter = counter * 5;			    
		    }
		    if((totalLinks - counter) >= 5 ) {
		        counterLimit = counter + 5;
		    }
		    else {
		        counterLimit = totalLinks;
		    }
		    var initialCounter = counter + 5;
		    if((scope.currentPage > 1 ) && (totalLinks > 1)) {
		        scope.links.push({value:'Previous',pageNumber:scope.currentPage - 1, current:false }); 
		    }
		    /*
             * if((counter >= 5 ) && (totalLinks > 1) ) {
             * scope.links.push({value:'First',pageNumber:1, current:false }); }
             */
		    if((counter >= 4 ) && (totalLinks > 1) ) {
		        scope.links.push({value:'First',pageNumber:1, current:false });
		    }
            for(counter; counter <= counterLimit; counter++) {
			 
			    if(scope.currentPage == counter ) {
			        scope.links.push({value:counter,pageNumber:counter,current:true });
			    }
			    else {
			        scope.links.push({value:counter,pageNumber:counter,current:false });
			    }					   
		    }
		   
		    if((initialCounter < totalLinks - 1) && totalLinks > 1  ) {
		        scope.links.push({value: '...',pageNumber: null, current:false });
		        scope.links.push({value: totalLinks - 1,pageNumber: totalLinks - 1, current:false });
		        scope.links.push({value: totalLinks,pageNumber: totalLinks , current:false });
		        scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
		    }
		    /* latest */
		    else if((initialCounter == totalLinks - 1) && totalLinks > 1) {
		        scope.links.push({value: totalLinks,pageNumber: totalLinks , current:false });
		        scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
		    }
		    else if(scope.currentPage != totalLinks && totalLinks > 1 ) {
		        scope.links.push({value:'Next',pageNumber:scope.currentPage + 1, current:false });
		    }
		    else {
		    	//
		    }
		},
		/**
         * Function is used to call getRecords method with startOffset and
         * endOffset to get required set or records
         * 
         * @param object
         *            scope
         * @param int
         *            pageNumber
         * @param boolean
         *            orderStatus
         * @return void
         */
		getListRecord : function(scope,pageNumber,orderStatus) {
			if((scope.currentPage == pageNumber || pageNumber == null) && orderStatus == false) {
				return false;
			}
			scope.showRecords = false;
			scope.gridLoadingBar = true;
			scope.currentPage = pageNumber;
			scope.getRecords();
		},
		
        
		/**
         * Function is used to call getListRecord method to sort(asc/desc)
         * required field.
         * 
         * @param object
         *            scope
         * @param object
         *            event
         * @param string
         *            field
         * @return void
         */
		listFieldSorting : function(scope,event,field) {			
			var element = event.target;
			if(element.id == 'gridAsc' || element.id == '') {
				angular.element('.listHeading').find('span').attr('id','');
				element.id = 'gridDesc';
				scope.fieldName = field.toLowerCase();
				scope.sortOrder = 'desc';
			}
			else {
				angular.element('.listHeading').find('span').attr('id','');
				element.id = 'gridAsc';
				scope.fieldName = field.toLowerCase();
				scope.sortOrder = 'asc';
			}
			this.getListRecord(scope,1,true);
		},
		/**
         * Function is used to delete required records.
         * 
         * @param object
         *            scope
         * @param object
         *            http
         * @param int
         *            id
         * @return void
         */
		deleteRecords : function(scope,http,id) {
			scope.deleteParams = '';
			scope.showRecords = false;
			scope.gridLoadingBar = true;
			var deleteIdLength = id.length;

			scope.deleteRequest = scope.request.post(scope.request.getUrl(scope.routeName+'/action'),angular.extend({},{selectedCheckbox:id},scope.requestParams),function(data){
				this.responseMessage = data.message;
				this.showResponseMessage = true;
				scope.deleteId = [];
				angular.element('#selectall').removeAttr('checked');
				if(scope.records.length - deleteIdLength > 0 ) {
					scope.getRecords(true);
				}
				else {
					pageNumber = (scope.currentPage - 1 == 0 ) ? 1 : scope.currentPage - 1;
					scope.currentPage = pageNumber;
					scope.getRecords(true);
				}
			});
		},
		/**
         * Function is used to covert camel case to hypen string.
         * 
         * @param string
         *            str
         * @return string
         */
		camelCaseToHypens : function(str) {
			return angular.isString(str) ? str.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase() : ''; 
		},
		/**
         * Function is used check directive data attribute should be used send
         * in request or not also the request param is pushed
         * 
         * @param array
         *            attributeSplits
         * @return boolean
         */
		isGridRequestParam : function(attributeSplits) {
			return angular.isArray(attributeSplits) && attributeSplits.length > 0 && attributeSplits[0] == this.requestParamPrefix;
		},	
		/**
         * Function is used format and push the request params key will be
         * concatenation of object property requestParamKeyConcatenator
         * 
         * @param object
         *            scope
         * @param array
         *            attributeSplits
         * @param string
         *            value
         * @return void
         */
		pushToRequestParams : function(scope,attributeSplits,value) {
			if(angular.isArray(attributeSplits) && angular.isObject(scope) && angular.isObject(scope.requestParams) && angular.isDefined(value)){
				/**
                 * remove the prefix(gridObj.requestParamPrefix)
                 */
				attributeSplits.shift();
				
				scope.requestParams[attributeSplits.join(this.requestParamKeyConcatenator)] = value;
			}
		},			
		/**
         * Function is used to prepare attributes. set data attributes to scope
         * property
         * 
         * @param object
         *            scope
         * @param object
         *            attrs
         * @return void
         */
		prepareAttributes : function(scope,attrs) {
			angular.forEach(attrs,function($item,$key){
				if(angular.isObject(scope) && angular.isString($item)) {
					var attributeSplits = gridObj.camelCaseToHypens($key).split('-');
					
					if(gridObj.isGridRequestParam(attributeSplits)){
						gridObj.pushToRequestParams(scope,attributeSplits,$item);
					} else {
						scope[$key] = $item;
					}
				}
			});
		},		
};

var gridView = ['$document','requestFactory',function($document,requestFactory){
	var request = requestFactory;
	return {
		restrict : 'A',
		controllerAs : 'gridCtrl',
		controller : ['$http','$scope','$controller','$document','$attrs',function($http,$scope,controller,$document,$attrs) {
			$scope.count = (typeof $scope.count === 'undefined') ? 'false' : $scope.count;
			$scope.requestParams = {};
			gridObj.prepareAttributes($scope,$attrs);
			$scope.currentPage = 1;
			$scope.totalRecords = '';
			$scope.records = '';
			$scope.links = '';
			$scope.fieldName = '';
			$scope.sortOrder = '';
			$scope.showRecords = false;
			$scope.gridLoadingBar = true;
			$scope.tableHeading = true;
			$scope.grid = {searchBy:'',rows:$scope.rowsPerPage};
			$scope.searchValue = '';
			$scope.searchTotal = false;
			$scope.pageNumberLimit = '';
			$scope.requiredPageNumber = '';
			$scope.pageLimit = '';
			$scope.showGridError = true;
			$scope.gridError = '';
			$scope.deleteId = [];
			$scope.suggesstions = '';
			$scope.showSuggestion = false;
			$scope.userSuggestion = '';
			$scope.noRecords = false;
			$scope.showDeleteBox = false;
			$scope.deleteParams = '';
			$scope.confirmationDeleteBox = false;
			$scope.alertBox = false;
			$scope.filterRecords = true;
			$scope.filterValue = null;
			$scope.request = request;
			$scope.tableLoader = false;
			$scope.searchRecords = {};
			$scope.filters = {};
			$scope.filters = {};
			$scope.resetRecord = {};
			/**
             * Function is used to retrieve records from database
             * 
             * @param boolean
             *            intialRequest
             * @return object records details
             */
			
			$scope.selectTab = function(tab) {
                $scope.filters = {};
                $scope.filters.tab = tab;   
                $scope.tabSelected = tab;
                $scope.currentPage = 1;
                $scope.showRecords = false;             
                $scope.gridLoadingBar = true;               
                $scope.getRecords(true);
            };  
			$scope.getRecords = function(intialRequest) {
				$scope.tableLoader = true;
				$scope.showGridError = true;
				$scope.gridError = '';
				$scope.intialRequest = angular.isDefined(intialRequest) ? 1 : 0;
				angular.element('#selectall').removeAttr('checked');
				
				var params = {
					page             : $scope.currentPage,
					searchRecord     : $scope.searchRecords,
					rowsPerPage      : $scope.rowsPerPage,
					orderByFieldName : null,
					filter           : $scope.filterValue,
					filters          : $scope.filters,
					intialRequest    : $scope.intialRequest
				};
				
				if($scope.fieldName != '' && $scope.searchValue == '') {
					params.orderByFieldName = $scope.fieldName;
					params.sortOrder = $scope.sortOrder;
				}

				$scope.request.post(
					$scope.request.getUrl($scope.routeName+'/records'),
					angular.extend({},params,$scope.requestParams),
					$scope.successCallback,
					$scope.errorCallback
				);
			}
			/**
             * Function used to get searched records
             * 
             */
			$scope.search = function() {

				if(typeof $scope.searchRecords === 'object' && Object.keys($scope.searchRecords).length > 0 ) {
					angular.forEach($scope.searchRecords,function(item,$key){
						if(typeof item === 'string' && item == '') {
							delete $scope.searchRecords[$key];
						}
					});
				}
				if(typeof $scope.searchRecords === 'object' && Object.keys($scope.searchRecords).length > 0 ) {
					$scope.filterRecords = true;
					$scope.searchTotal   = false;
					$scope.noRecords = false;
					$scope.requiredPageNumber = 1;
					$scope.grid.searchBy = '';
					$scope.showRecords = false;
					$scope.gridLoadingBar = true;
					$scope.searchValue   = '';
					$scope.fieldName     = '';
					$scope.tableHeading  = true;
					$scope.getRecords();
				}				
			}
			$scope.setSearchRecords = function(field,value) {
				try {
					if(typeof field === 'string' && field.length > 0 && typeof value === 'string' && value.length > 0) {
						$scope.searchRecords = {};
						$scope.searchRecords[field] = value;
						if(Object.keys($scope.resetRecord).length > 0 ) {
							$scope[Object.keys($scope.resetRecord)[0]] = true;
						}
						$scope.resetRecord = {};
						$scope.resetRecord[field] = value;
						$scope[field] = false;
						$scope.search();
					}
					else {
						$scope[Object.keys($scope.resetRecord)[0]] = true;
						$scope.resetRecord = {};
						$scope.gridReset();
					}
				}
				catch(e) {
					console.log(e);
				}				
			}
			
			$scope.successCallback = function(response) {
				$scope.totalRecords = response.data.total;
				$scope.rowsPerPage  = response.data.per_page;
				$scope.currentPage  = response.data.current_page;
	    		$scope.tableLoader = false;
	    		$scope.gridLoadingBar = false;
				
				$scope.pageLimit = Math.ceil($scope.totalRecords/$scope.rowsPerPage);
				$scope.pageNumberLimit = 'Limit(1 - '+$scope.pageLimit+')';
				
				if($scope.intialRequest && angular.isObject(response.heading)){
					$scope.heading = response.heading.heading;
				}
				
				if($scope.intialRequest && angular.isObject(response.recordsCount)){
					$scope.recordsCount = response.recordsCount;
				}
				
		    	if(response.data.total > 0 ) {
		    		$scope.showRecords = true;
		    		$scope.noRecords = false;
		    		$scope.records = response.data.data;
		    		$scope.showGridError = true;
					$scope.gridError = '';
					$scope.$emit('afterGetRecords',response);
		    	}
		    	else {
		    		$scope.showGridError = false;
		    		$scope.records = '';
		    		$scope.noRecords = true;
					$scope.gridError = 'No records found';
					$scope.pageNumberLimit = '';
					$scope.searchValue = '';
					return false;
		    	}
		    	
		    	gridObj.paginate($scope,$scope.pageLimit);
		    };

			$scope.errorCallback = function(data){
				$scope.tableLoader = false;
	    		$scope.gridLoadingBar = false;
			    $scope.showGridError = false;
			    $scope.noRecords = true;
			    $scope.searchValue = '';
			    $scope.records = '';
				$scope.gridError = 'No records found';
				$scope.pageNumberLimit = '';
		    };	
		    
		    $scope.getRecords(true);

			/**
             * Function is used to call getListRecord method to get required set
             * or records
             * 
             * @param int
             *            pageNumber
             * @param boolean
             *            orderStatus
             * @return void
             */
			$scope.loadRecords = function(pageNumber,orderStatus) {
				gridObj.getListRecord($scope, pageNumber, orderStatus);
			}
			/**
             * Function is used to call listFieldSorting method to sort field
             * 
             * @param object
             *            event
             * @param string
             *            field
             * @return void
             */
			$scope.fieldOrder = function(event,field) {	
				if($scope.filterRecords == false ) {
					return false;
				}
				$scope.noRecords = false;
				gridObj.listFieldSorting($scope, event, field);
			}
			/**
             * Function is used to call getTotalRecords method to get total
             * number of search records
             * 
             * @return void
             */
			$scope.searchKeyword = function() {
				if($scope.filterRecords == false ) {
					return false;
				}
				$scope.searchTotal = true;
				$scope.requiredPageNumber = '';
				$scope.fieldName = '';
				$scope.sortOrder = '';

				if($scope.searchValue != '' ) {
					gridObj.getTotalRecords($scope,true);
				}
			}
			/**
             * Function is used to get filter records by calling gridReset
             * method when user select any filter
             * 
             * @return void
             */
			$scope.setFilter = function() {
				if($scope.filterValue != null ) {
					$scope.gridReset(2);
					$scope.deleteId = [];
				}
			}
			/**
             * Function is used get the record between dates
             * 
             * @return void
             */	
			$scope.doDateFilter = function(){				
				if($scope.showRecords.dateFrom || $scope.showRecords.dateTo){					
					/**
                     * made sure always while tab is selected first page is
                     * loaded
                     */
					$scope.currentPage = 1;			
					$scope.getRecords();
				}
			};			
			/**
             * Function used to call search to get searched recoreds
             * 
             */
			$document.bind('keyup', function(e) {
		          if( e.keyCode == 13) {
		        	  $scope.search();
		          }
			});
			/**
             * Function is used to list view
             * 
             * @return void
             */
			$scope.gridReset = function(id) {
				if(id == 1) {
					$scope.filterValue = null;
				}	
				$scope.filterRecords = true;
				$scope.searchTotal   = false;
				$scope.noRecords = false;
				$scope.requiredPageNumber = 1;
				$scope.grid.searchBy = '';
				$scope.showRecords = {};
				$scope.gridLoadingBar = true;
				$scope.searchValue   = '';
				$scope.fieldName     = '';
				$scope.tableHeading  = true;
				angular.element('#move_collection').attr('disabled','disabled');
				if(Object.keys($scope.resetRecord).length > 0 ) {
					$scope.searchRecords = $scope.resetRecord;
				}
				else {
					$scope.searchRecords = {};
				}
				$scope.$emit('gridReset');
				$scope.getRecords();
			}
			/**
             * Function is used to check pageLimit with the number entered on
             * number field
             * 
             * @return void
             */
			$scope.checkPageNumber = function(event) {
				var pattern  = /^\d*$/;
				var value = event.currentTarget.value;

				if(value > $scope.pageLimit || (pattern.test(value) == false )) {
					event.currentTarget.value='';
					event.preventDefault();
				}
			}
			/**
             * Function is used to show list view with required number of rows
             * 
             * @return void
             */
			$scope.changeRows = function() {
				$scope.currentPage = 1;
				$scope.searchTotal   = false;
				$scope.showRecords = false;
				$scope.gridLoadingBar = true;
				$scope.rowsPerPage = Number($scope.grid.rows);
				$scope.fieldName     = '';
				$scope.tableHeading  = true;
				$scope.records = '';
				if($scope.grid.searchBy !='' && $scope.searchValue !='') {
					$scope.searchTotal = true;
				}
				$scope.deleteId = [];
				$scope.getRecords();
			}
			/**
             * Function is used to redirect user to required page
             * 
             * @param int
             *            value
             * @return void
             */
			$scope.changeNum = function(event) {
				var pattern = /^\d+$/;
				var value = event.currentTarget.value;
				if(value == $scope.currentPage) {
					return false;
				}
				if(pattern.test(value)) {
					if(value > 0 && value <= $scope.totalRecords) {
						gridObj.getListRecord($scope, value, false);
					}
				}
			}
			/**
             * Function is used to provide search suggestion list
             * 
             * @param string
             *            value
             * @return object
             */
			$scope.searchSuggestion = function(value) {
				if($scope.filterRecords == false ) {
					return false;
				}
				if(value != '' && $scope.grid.searchBy !='' && value != $scope.userSuggestion && value.length > 3) {
					$scope.request.post($scope.request.getUrl($scope.routeName+'/search'),{
							startOffset : 0,
							endOffset   : 10,
							searchBy    : $scope.grid.searchBy,
							searchValue : value,
							searchSuggestion : 1,
							filter           : $scope.filterValue,
					},function(response){
						if(response.data.length != 0 ) {
							$scope.showSuggestion = true;
							$scope.suggestions = response.data;
						}
						else
						{
							$scope.showSuggestion = false;
						}
					});
				}

			}
		    /**
             * Function to update status of a record
             * 
             * @param object
             *            record
             * @return void
             */			
			$scope.updateStatus = function(record) {
				var status = record.is_active == 1 ? 0 : 1;
				record.is_active = status;
				
		    	$scope.request.post(
		    		$scope.request.getUrl($scope.routeName+'/update-status/'+record.id),
		    		angular.extend({},{status: status},$scope.requestParams),
		    		function(){
						record.is_active = status;
					}
				);
		    }
			
			/**
             * Function to update mode of a record
             * 
             * @param object
             *            record
             * @return void
             */			
			$scope.updateMode = function(record) {
				var mode = record.is_test == 1 ? 0 : 1;
				record.is_test = mode;
				
		    	$scope.request.post(
		    		$scope.request.getUrl($scope.routeName+'/update-mode/'+record.id),
		    		angular.extend({},{mode: mode},$scope.requestParams),
		    		function(){
						record.is_test = mode;
					}
				);
		    }
			/**
             * Function is used to include table body
             * 
             * @return void
             */
			$scope.buildTableBody = function() {
				return $scope.request.getTemplateUrl($scope.routeName) + '/grid';
			}
			/**
             * Function is used to delete records
             * 
             * @return void
             */
			$scope.deleteRecord = function() {
				if($scope.filterRecords == false ) {
					return false;
				}
				if($scope.deleteId.length > 0 ) {
					$scope.confirmationDeleteBox = true;
					$scope.alertBox = false;
					$scope.deleteParams = $scope.deleteId;
				}
				else {
					$scope.confirmationDeleteBox = false;
					$scope.alertBox = true;
				}
			}
			/**
             * Function is used to delete single record
             * 
             * @param int
             *            id
             * @return void
             */
			$scope.deleteSingleRecord = function(id) {
				$scope.deleteParams = [id];
				$scope.alertBox = false;
				$scope.confirmationDeleteBox = true;
			};
			/**
             * Function is used to add\remove record id to deleteId variable to
             * delete records
             * 
             * @param int
             *            id
             * @return void
             */
			$scope.addDeleteId = function(id) {
				if($scope.deleteId.indexOf(id) != -1 ) {
					var index = $scope.deleteId.indexOf(id)
					$scope.deleteId.splice(index,1);
				}
				else {
					$scope.deleteId.push(id);
				}
			};
			
			$scope.confirmDelete = function() {
				if($scope.deleteParams.length > 0 ) {
					gridObj.deleteRecords($scope,$http,$scope.deleteParams);
					$scope.confirmationDeleteBox = false;
					$scope.alertBox = false;
					$scope.deleteParams = '';
				}
				else {
					$scope.confirmationDeleteBox = false;
					$scope.alertBox = false;
					$scope.deleteParams = '';
				}
			};
			
			$scope.cancelDelete = function(){
				$scope.confirmationDeleteBox = false;
				$scope.alertBox = false;
				$scope.deleteParams = '';
			};
			/**
             * Function is used to toggle select all check box when user click
             * each record instead of select all checkbox
             * 
             * @param int
             *            id
             * @return void
             */
			$scope.totalCheckBox = function(id) {
				var totalCheckBoxLength = angular.element('.checkbox').length;
				var checkBoxLength = '';
				angular.element('.checkbox').each(function(){
					if(angular.element(this).is(':checked')) {
						checkBoxLength++;
					}
				});
				if(checkBoxLength == totalCheckBoxLength ) {
					angular.element('#selectall').attr('checked','checked');
				}
				else {
					angular.element('#selectall').removeAttr('checked');
				}
				$scope.addDeleteId(id);
			}
			/**
             * Change merchant product status to Active or Inactive
             */
			$scope.changePayoutStatus = function(value,index,rowId){				
				this.merchantPayoutInfo = {};				
				this.merchantPayoutInfo.row_id = rowId;
				this.merchantPayoutInfo.acknowledgement_status = value;
				this.request.post(this.request.getUrl('merchantpayouttransaction/update-payout-status'),this.merchantPayoutInfo,function(response){
					window.location.reload();
				},this.failure);
			}
			/**
             * Function is used to toggle select all check box
             * 
             * @return void
             */
			$scope.selectAll = function() {
				if(angular.element('#selectall').prop('checked')) {
					angular.element('.checkbox').each(function(){
						if(angular.element(this).is(':checked') === false ) {
							angular.element(this).attr('checked','checked');
							$scope.addDeleteId((angular.element(this).attr('value')));
						}
					});
				}
				else {
					angular.element('.checkbox').each(function(){
						angular.element(this).removeAttr('checked');
						$scope.addDeleteId((angular.element(this).attr('value')));
					});
				}
			}
			
			$scope.getSuggestion = function(suggestion) {
				$scope.showSuggestion = false;
				$scope.userSuggestion = suggestion;
				$scope.searchValue = suggestion;
			}

			$scope.removeSuggestion = function() {
				$scope.showSuggestion = false;
			}
			
			$document.on('click',function() {
		    	  if(document.getElementsByClassName('searchSuggestion').length > 0 ) {
		    		  
		    		  popup = document.getElementsByClassName('searchSuggestion')[0];
		    		  if(popup.contains(event.target) === false) {
		    			  $scope.showSuggestion = false;
		    			  $scope.$apply();
		    		  }
		    	  }
			      });
			
			$scope.getTooltip = function(e) {
				angular.element(event.target.parentElement).tooltip();
			}
		
		}],
		templateUrl : function(element,attr) {
			if(attr.hasOwnProperty('templateRoute') && attr.templateRoute != '') {
				var path =  attr.templateRoute + '/gridlist';
				return request.getTemplateUrl(path);
			}
		}
	};
}];

window.gridView  = gridView;
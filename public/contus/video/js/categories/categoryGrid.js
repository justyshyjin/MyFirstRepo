'use strict';

var CategoryGridController = ['$scope','requestFactory','$window','$sce','$timeout','$compile','$interval',function ( scope, requestFactory, $window, $sce, $timeout, $compile, $interval ) {
    var self = this;
    this.info = {};
    this.category = {};
    this.responseMessage = false;
    this.showResponseMessage = false;
    scope.errors = {};
    requestFactory.setThisArgument( this );
    angular.element( '.alert-success' ).fadeIn( 1000 ).delay( 5000 ).fadeOut( 1000 );

    this.fillError = function ( response ) {
        if ( response.status == 422 && response.data.hasOwnProperty( 'message' ) ) {
            angular.forEach( response.data.message, function ( message, key ) {
                if ( typeof message == 'object' && message.length > 0 ) {
                    scope.errors [key] = {has : true,message : message [0]};
                }
            } );
        }
    };

    this.closeCategoryEdit = function () {
        classie.remove( document.getElementById( 'st-container' ), 'st-menu-open' );
    };

    this.deleteCategoryImage = function () {
        requestFactory.toggleLoader();
        requestFactory.post( requestFactory.getUrl( 'categories/delete-category-image/' + this.category.id ), this.category, function ( response ) {
            requestFactory.toggleLoader();
            self.responseMessage = response.message;
            self.showResponseMessage = true;
            scope.getRecords( true );
            self.closeCategoryEdit();
            self.resetCategoryImageUpload();
        }, function () {
        } );
    };

    this.resetCategoryImageUpload = function () {
        if ( typeof window.CategoryImageUploadHandler == 'object' ) {
            $timeout( function () {
                angular.element( '[data-dismiss="fileupload"]' ).trigger( "click" );
            }, 0, true );
            self.category.image = '';
            self.category.image_url = '';
        }
    };

    this.defineProperties = function ( data ) {
        this.info = data.info;
        requestFactory.toggleLoader();
        baseValidator.setRules( data.info.rules );
    };

    this.fetchInfo = function () {
        requestFactory.get( requestFactory.getUrl( 'categories/info' ), this.defineProperties, function () {
        } );
    };

    this.fetchInfo();

    window.CategoryImageUploadHandler = new uploadHandler;
    window.CategoryImageUploadHandler.initate( {file : 'category-image',previewer : 'category-image-preview',deleteIcon : 'category-image-delete',progress : 'category-image-progress',beforeUpload : function () {
        if ( !scope.$$phase ) {
            scope.$apply();
        }
    },afterUpload : function ( response ) {
        self.category.image = response.info;
    }} );

    /**
     *  Function is used to add the category
     *  
     *  @param  $event
     */
    this.addCategory = function ( $event ) {
        self.resetCategoryImageUpload();
        angular.element( ".categoryList li" ).show();
        scope.errors = {};
        self.category = {};
        self.categoryFull = {};
        this.categoriesUniqueRoute = requestFactory.getUrl( 'categories/categories-unique' );
        this.category = {};
        this.categoryFull = {};
        this.category.is_active = String( 0 );
        this.category.is_active = String( 0 );
        this.category.preference_order = null;
        this.category.is_leaf_category = String( 0 );
        self.pref=0;
        self.pref='';
    }

    /**
     *  Function is used to edit the categories
     *  
     *  @param array records
     */
    this.editCategory = function ( records ) {
        self.resetCategoryImageUpload();
        angular.element( ".categoryList li" ).show();
        angular.element( "#category_id_" + records.id ).hide();
        scope.errors = {};
        this.categoryFull = records;
        self.categoryFull = records;
        this.categoriesUniqueRoute = requestFactory.getUrl( 'categories/categories-unique/' + records.id );
        this.category.title = records.title;
        this.category.parent_id = String( records.parent_id );
        this.category.is_active = String( records.is_active );
        this.category.preference_order = String((records.preference_order===null)?'':records.preference_order );
        self.pref=0;
        self.pref='';
        this.category.is_leaf_category = String( records.is_leaf_category );
        self.pref=((records.preference_order===null)?'0':'1')
        this.category.id = records.id;
        this.category.image_url = records.image_url;
    }
    scope.toggleTab = function ( tab ) {
        if ( scope.tabSelected == tab ) {
            scope.filters.tab = '';
            scope.tabSelected = '';
            scope.currentPage = 1;
            scope.showRecords = false;
            scope.gridLoadingBar = true;
            scope.getRecords( true );
        } else {
            scope.selectTab( 'live_videos' );
        }
    }

    /**
     *  Function is used to save the category
     *  
     *  @param  $event, id
     */
    this.categorySave = function ( $event, id ) {
        if ( baseValidator.validateAngularForm( $event.target, scope ) ) {
        	if(document.querySelector('select[data-ng-model="catgridCtrl.pref"]')){
            this.category.preference_order = (document.querySelector('select[data-ng-model="catgridCtrl.pref"]').value == '1')?this.category.preference_order:'';
        	}
            if ( id ) {
                requestFactory.post( requestFactory.getUrl( 'categories/edit/' + id ), this.category, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    scope.getRecords( true );
                    this.closeCategoryEdit();
                    self.pref=0;
                    self.pref='';
                    self.resetCategoryImageUpload();
                }, this.fillError );
            } else {
                requestFactory.post( requestFactory.getUrl( 'categories/add' ), this.category, function ( response ) {
                    this.responseMessage = response.message;
                    this.showResponseMessage = true;
                    scope.getRecords( true );
                    this.closeCategoryEdit();
                    self.resetCategoryImageUpload();
                    self.pref=0;
                    self.pref='';
                }, this.fillError );
            }
        }
    }

    /**
     * Function to update status of a preset,collection,category and video
     *
     * @param object record
     * @return void
     */
    this.updateStatus = function ( record ) {
        scope.routeName = 'categories';
        scope.updateStatus( record );
    };

    /**
     *  Listen to the records to update property
     *  
     */
    scope.$on( 'afterGetRecords', function ( e, data ) {
        if ( angular.isUndefined( scope.searchRecords.is_active ) ) {
            scope.searchRecords.is_active = 'all';
        }

        // Update categories in add/edit category form
        requestFactory.get( requestFactory.getUrl( 'categories/updated-details' ), function ( data ) {
            this.allCategoriesHTML = $sce.trustAsHtml( data.allCategoriesHTML );
            $timeout( function () {
                $compile( angular.element( ".categoryList" ).contents() )( scope );
            }, 100 );
        }, function () {
        } );

    } );
}];

window.gridControllers = {CategoryGridController : CategoryGridController};
window.gridDirectives = {baseValidator : validatorDirective,intializeSidebar : intializeSidebar};

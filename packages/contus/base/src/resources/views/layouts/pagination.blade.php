<div class="pagination_custom clearfix">
<div class="cs-showentry pull-left">
	<div class="show_entries" data-ng-if="!filters.collectionId && !filters.categoryId"> 
    <label for="" class="">{{trans('base::general.show')}}</label>
     <label for="" class="">
       <select data-ng-model="grid.rows" data-ng-change="changeRows()" class="form-control">
         <option value="10">10</option>
         <option value="50">50</option>
         <option value="100">100</option>
       </select>
      </label>
    <label class="">{{trans('base::general.entries')}}</label>
  
 </div>                        
</div>
    <div data-ng-if="totalRecords != 0">
        <ul class="pagination pagination-split nomargin pull-right" data-ng-if="links.length > 0">
            <li data-ng-repeat="link in links" data-ng-class="{'active': link.current}">
                <a href="javascript:void(0)" data-ng-click="loadRecords(link.pageNumber,false)" class ="pageLink" >@{{link.value}}</a>
            </li>
        </ul>
    </div>
</div>
@if(!isset($withOutDeleteModelHtml))   
<div class="modal fade" id="deleteModal" data-role="dialog" data-ng-if="requestParams.grid != 'video'">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">{{trans('base::gridlist.delete_record')}}</h5>
            </div>
            <div class="modal-body">
                <div data-ng-show="confirmationDeleteBox">
                    <p>{{trans('base::gridlist.delete_confirm')}}</p>
                </div>
            </div>
            <div class="clearfix modal-footer delete_footer">
                <span data-ng-click="cancelDelete()" class="btn btn-danger pull-right mr10"
                    data-dismiss="modal">{{trans('base::gridlist.cancel')}}</span>
                <span data-ng-click="confirmDelete()" class="btn btn-primary pull-right mr10"
                data-dismiss="modal">{{trans('base::gridlist.confirm')}}</span>
            </div>
        </div>
    </div>
</div>
@endif

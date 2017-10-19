<div class="panel main_container">
  <div class="tab-content">
    <div class="tab-pane active" id="latest_video">

      <div class="tab_search clearfix" ></div>
      
      <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
          <div class="loader"></div>
        </div>
      </div>
    
      <div class="table_responsive">
      <table class="table">
        <thead>
            <tr>
                <th class="center">{{trans('base::general.s_no')}}</th>
                <th data-ng-repeat = "field in heading">@{{field.name}}
                  <span data-ng-if="field.sort==true" id="" class="th-inner sortable both" data-ng-class="{showGridArrow:field.sort}" data-ng-click="fieldOrder($event,field.value)"></span>
                  <span data-ng-if="field.sort==false" data-ng-class="{showGridArrow:field.sort}"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr class="search_text">
             <td></td>
             <td class="search_product">
                  <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true" title="{{trans('video::presets.enter_preset_name')}}" >
             </td>
             <td class="search_product">
                  <input type="text" class="form-control" data-ng-model="searchRecords.aws_id" data-boot-tooltip="true" title="{{trans('video::presets.enter_aws_identifier')}}" >
             </td>
             <td class="search_product">
                  <input type="text" class="form-control" data-ng-model="searchRecords.format" data-boot-tooltip="true" title="{{trans('video::presets.enter_format')}}" >
             </td>
             <td>
             <div class="presets_action">
                 <select class="form-control mb15" data-ng-model="searchRecords.is_active" data-ng-change="search()" data-boot-tooltip="true" title="{{trans('base::general.select_status')}}">
                        <option value="all">{{trans('base::general.all')}}</option>
                        <option value='1'>{{trans('video::collection.banner.active')}}</option>
                        <option value='0'>{{trans('video::collection.banner.inactive')}}</option>
                 </select>
                 <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" title="{{trans('base::general.search_filter')}}">
                      <i class="fa fa-search"></i>
                  </button>
                  <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                      <i class="fa fa-refresh"></i>
                  </button>
                  </div>
             </td>
            </tr>
            <tr>
                <td data-ng-if="noRecords" colspan="@{{heading.length + 1}}" class="no-data">{{trans('base::general.not_found')}}</td>
            </tr>
            <tr data-ng-if="showRecords" data-ng-repeat = "record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
            	<td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
            	<td>@{{record.name}}</td>
            	<td>@{{record.aws_id}}</td>
            	<td>@{{record.format}}</td>
            	<td>
                    <span class="label label-success" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets > 1" style="cursor: pointer;" data-ng-click="pregridCtrl.updateStatus(record)"  title="{{trans('video::presets.deactivate_preset')}}" data-boot-tooltip="true" >{{trans('video::collection.message.active')}}</span>
                    <span class="label label-success" ng-if="record.is_active == 1 && pregridCtrl.numberOfActivePresets == 1" style="cursor: not-allowed;" title="{{trans('video::presets.minimum_preset_limit')}}" data-boot-tooltip="true" >{{trans('video::collection.message.active')}}</span>
                    <span class="label label-danger" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets < 30" style="cursor: pointer;" data-ng-click="pregridCtrl.updateStatus(record)"  title="{{trans('video::presets.activate_preset')}}" data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span>
                    <span class="label label-danger" ng-if="record.is_active != 1 && pregridCtrl.numberOfActivePresets >= 30" style="cursor: not-allowed;" title="{{trans('video::presets.preset_limit_exceeded')}}" data-boot-tooltip="true">{{trans('video::collection.message.inactive')}}</span>
                </td>
            </tr>
        </tbody>
      </table>
      </div>
      @include('base::layouts.pagination')
    </div>
  </div>
</div>
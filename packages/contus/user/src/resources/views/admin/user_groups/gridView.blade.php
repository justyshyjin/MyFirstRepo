<div class="panel main_container">
  <div class="tab-content">
    <div class="tab-pane active" id="latest_video">
        <div class="tab_search clearfix" >
          <div id="st-trigger-effects" class="search_upload_btn pull-right"  >
            <a href="{{url('/admin/groups/add')}}" class="btn btn-primary upload_video pull-right" ><i class="fa fa-plus-circle" aria-hidden="true"></i>Create Admin Group</a>
          </div>
        </div>

      <div id="table_loader" class="table_loader_container" data-ng-show="gridLoadingBar">
        <div class="table_loader">
          <div class="loader"></div>
        </div>
      </div>
      <table class="table" data-ng-init="usrCtrl.setQuery('{{auth()->user()->id}}')">
        <thead>
            <tr>
                <th class="center">{{trans('user::adminuser.serial_no')}}</th>
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
                                  <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_group_name')}}">
                             </td>
                              <td class="">
                                    <button type="button" class="btn search" data-ng-click="search()" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('base::general.search_filter')}}">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button" class="btn search" data-ng-click="gridReset()" data-boot-tooltip="true" title="{{trans('base::general.reset')}}">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td data-ng-if="noRecords" colspan="@{{heading.length + 1}}" class="no-data">{{trans('base::general.not_found')}}</td>
                            </tr>
                            <tr data-ng-if="showRecords" data-ng-repeat = "record in records track by $index" data-ng-show="showRecords" class="list-repeat" data-intialize-sidebar="">
                                <td class="center">@{{((currentPage - 1) * rowsPerPage) + $index +1}}</td>
                                <td>@{{record.name}}</td>
                                <td class="table-action">
                                    <div id="st-trigger-effects" class="column edit_table_icon">
                                      <a href="{{url('/admin/groups/edit')}}/@{{record.id}}" data-boot-tooltip="true" data-original-title="Edit"  class="table_action"><i class="fa fa-ellipsis-h"></i></a>
                                    </div>

                                    <span ng-mouseover="getTooltip($event)" data-ng-if="record.is_deletable == 1 && record.users.length == 0" title="{{trans('base::general.delete')}}" data-toggle="modal"  data-target="#deleteModal"  ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
                                        <i class="fa fa-trash-o"></i>
                                    </span>
                                    <span ng-mouseover="getTooltip($event)" data-ng-if="record.is_deletable == 1 && record.users.length > 0" title="{{trans('user::user.delete_disabled')}}" class="tooltips delete_table_icon delete_disabled" data-boot-tooltip="true">
                                        <i class="fa fa-trash-o"></i>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
      </table>
      @include('base::layouts.pagination')
    </div>
  </div>
</div>
<!-- To add or edit the user  -->
<nav class="st-menu st-effect-17" id="menu-17">
  <div class="pop_over_continer">
   <form name="userForm" method="POST" data-base-validator data-ng-submit="usrCtrl.save($event, usrCtrl.user.id)" enctype="multipart/form-data">
   {!! csrf_field() !!}
    <div class="video_form add_form">

        <h5 data-ng-if="!usrCtrl.user.id">{{trans('user::adminuser.user_heading')}} - {{trans('user::adminuser.add_new_user')}}</h5>
        <h5 data-ng-if="usrCtrl.user.id">{{trans('user::adminuser.user_heading')}} - {{trans('user::adminuser.edit_new_user')}}</h5>
        @include('base::partials.errors')

        <div class="form-group" data-ng-class="{'has-error': errors.name.has}">
          <label class="control-label">Name<span class="asterisk">*</span></label>
          <input type="text" name="name" data-ng-model="usrCtrl.user.name" class="form-control" placeholder="{{trans('user::adminuser.username_placeholder')}}" value="{{old('name')}}" />
          <p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
        </div>

        <div class="row">
            <div class="col-sm-12">
              <div class="form-group role-row">
                  <label class="control-label">Permissions<span class="asterisk">*</span></label>
                   <ul id="tree">
                       @foreach(Config::get('access.modules') as $key => $modules)
                           <li>
                               <label class="control-label">
                                                <input type="checkbox" /> {{$key}}
                                            </label>
                               <ul>
                               @foreach($modules as $eachModule => $moduleDetails)
                               <li>
                                                <label class="control-label">
                                                    <input type="checkbox" /> {{$moduleDetails['name']}}
                                                </label>
                              <ul>
                                  @foreach($moduleDetails['permission'] as $label => $permission)
                                     <li>
                                               <label><input type="checkbox" data-multicheck-validate="permissions" name="permissions[]" value="{{$permission}}"> {{$label}}</label>
                                         </li>
                                  @endForeach
                              </ul>
                               </li>
                             @endForeach
                             </ul>
                              </li>
                       @endForeach
                  </ul>
                <p class="help-block hide"></p>
              </div>
            </div>
          </div>

        <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
          <label class="control-label">Permissions<span class="asterisk">*</span></label>
          <input type="text" name="email" data-unique="@{{usrCtrl.uniqueRoute}}" data-ng-model="usrCtrl.user.email" class="form-control" placeholder="{{trans('user::adminuser.email_placeholder')}}" value="{{old('email')}}"/>
          <p class="help-block" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
        </div>
    </div>
    <div class="panel-footer clearfix">
      <button class="btn btn-primary pull-right">{{trans('base::general.submit')}}</button>
      &nbsp;
      <span class="btn btn-danger pull-right mr10" data-ng-click="usrCtrl.closeUserEdit()" >{{trans('base::general.cancel')}}</span>
    </div>
    </form>
  </div>
</nav>


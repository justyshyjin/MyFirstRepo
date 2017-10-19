<div class="panel main_container">
  <div class="tab-content">
    <div class="tab-pane active" id="latest_video">
        <div class="tab_search clearfix" >
          <div id="st-trigger-effects" class="search_upload_btn pull-right"  >
              <button data-effect="st-effect-17" data-ng-click="usrCtrl.addUser($event)" class="btn btn-primary upload_video pull-right"><i class="fa fa-plus-circle" aria-hidden="true"></i>{{trans('user::user.create_users')}}</button>
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
                                  <input type="text" class="form-control" data-ng-model="searchRecords.name" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_user_name')}}">
                             </td>
                             <td class="search_product">
                                 <input type="text" class="form-control" data-ng-model="searchRecords.email" data-boot-tooltip="true" data-toggle="tooltip" data-original-title="{{trans('user::adminuser.enter_email')}}">
                             </td>
                             <td></td>

                             <td>
                                 <select class="form-control mb15" data-boot-tooltip="true" data-ng-model="searchRecords.is_active" data-ng-change="search()" data-toggle="tooltip" data-original-title="{{trans('base::general.select_status')}}">
                                        <option value="all">{{trans('base::general.all')}}</option>
                                        <option value='1'>{{trans('user::banner.active')}}</option>
                                        <option value='0'>{{trans('user::banner.inactive')}}</option>
                                 </select>
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
                                <td>@{{record.email}}</td>
                                <td>@{{record.group.name}}</td>
                                <td>
                                    <span class="label label-success" ng-if="record.is_active == 1 && record.group.is_deletable == 1" style="cursor: pointer;" data-ng-click="updateStatus(record)"  title="{{trans('user::user.deactivate_user')}}" data-boot-tooltip="true" >{{trans('user::user.message.active')}}</span>
                                    <span class="label label-success" ng-if="record.is_active == 1 && record.group.is_deletable == 0" title="{{trans('user::user.cannot_deactivate_superadmin')}}" data-boot-tooltip="true" >{{trans('user::user.message.active')}}</span>
                                    <span class="label label-danger" ng-if="record.is_active != 1" style="cursor: pointer;" data-ng-click="updateStatus(record)"  title="{{trans('user::user.activate_user')}}" data-boot-tooltip="true">{{trans('user::user.message.inactive')}}</span>
                                </td>
                                <td class="table-action">
                                    <div id="st-trigger-effects" class="column edit_table_icon" data-ng-if="record.group.is_deletable == 1">
                                      <button data-effect="st-effect-17" data-boot-tooltip="true" data-original-title="Edit" class="table_action" data-ng-click="usrCtrl.editUser(record)"><i class="fa fa-ellipsis-h"></i></button>
                                    </div>

                                    <span ng-mouseover="getTooltip($event)" data-ng-if="record.id != usrCtrl.authId || record.group.is_deletable == 1"  title="{{trans('base::general.delete')}}" data-toggle="modal"  data-target="#deleteModal"  ng-click="deleteSingleRecord(record.id)" class="tooltips delete_table_icon" data-boot-tooltip="true" data-original-title="">
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
          <label class="control-label">{{trans('user::adminuser.username')}} <span class="asterisk">*</span></label>
          <input type="text" name="name" data-ng-model="usrCtrl.user.name" class="form-control" placeholder="{{trans('user::adminuser.username_placeholder')}}" value="{{old('name')}}" />
          <p class="help-block" data-ng-show="errors.name.has">@{{ errors.name.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.email.has}">
          <label class="control-label">{{trans('user::adminuser.email')}} <span class="asterisk">*</span></label>
          <input type="text" name="email" data-ng-model="usrCtrl.user.email" class="form-control" placeholder="{{trans('user::adminuser.email_placeholder')}}" value="{{old('email')}}"/>
          <p class="help-block" data-ng-show="errors.email.has">@{{ errors.email.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.phone.has}">
          <label class="control-label">{{trans('user::adminuser.phone')}} <span class="asterisk">*</span></label>
          <input type="text" name="phone" maxlength="15" class="form-control" data-ng-model="usrCtrl.user.phone" placeholder="{{trans('user::adminuser.phone_placeholder')}}" value="{{old('phone')}}"/>
          <p class="help-block" data-ng-show="errors.phone.has">@{{ errors.phone.message }}</p>
        </div>

        <div class="form-group" data-ng-class="{'has-error': errors.user_group_id.has}">
          <label class="control-label">{{trans('user::adminuser.user_group')}}</label><span class="asterisk">*</span>
          <select class="form-control mb10" data-validation-name="{{trans('user::adminuser.user_group')}}"  name="user_group_id" data-ng-model="usrCtrl.user.user_group_id" data-ng-options="key as value for (key, value) in usrCtrl.allUserGroups">
             <option value="">{{trans('user::adminuser.select_usergroup')}}</option>
          </select>
          <p class="help-block" data-ng-show="errors.user_group_id.has">@{{ errors.user_group_id.message }}</p>
        </div>

        <div class="form-group">
          <label class="control-label">{{trans('user::adminuser.status')}}</label>
          <select class="form-control mb10" name="is_active" data-ng-model="usrCtrl.user.is_active">
             <option value="1">{{trans('user::adminuser.active')}}</option>
              <option value="0">{{trans('user::adminuser.inactive')}}</option>
          </select>
        </div>

        <div class="form-group"  data-ng-class="{'has-error': errors.gender.has}">
          <label class="control-label">{{trans('user::adminuser.gender')}}<span class="asterisk">*</span></label>
           <select class="form-control mb10" name="gender" data-ng-model="usrCtrl.user.gender">
            <option value="" disabled>{{trans('user::adminuser.select_gender')}}</option>
            <option value="male">{{trans('user::adminuser.male')}}</option>
            <option value="female">{{trans('user::adminuser.female')}}</option>
          </select>
          <p class="help-block" data-ng-show="errors.gender.has">@{{ errors.gender.message }}</p>
          
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

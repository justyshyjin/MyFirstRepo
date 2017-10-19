@section('profilecontent')<div class="contentpanel product order_list">
        @include('base::partials.errors')
        <div data-grid-view data-rows-per-page="10"
            data-route-name="subscriptions"
            data-template-route="subscriptions" data-count="false"></div>
    </div>
</div> @endsection
@include('customer::user.account.index')

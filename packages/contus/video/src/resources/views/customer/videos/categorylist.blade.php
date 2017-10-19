<div class="container">
	<div class="row">
		<ul class="see-all-categories clearfix"
			ng-repeat="category in categories">
			<li ng-repeat="subcategory in category.child_category"><a
				class="btn btn-primary" role="button" data-toggle="collapse"
				data-target="#@{{subcategory.slug}}" href="javascript:;"
				aria-expanded="false" aria-controls="@{{subcategory.slug}}">
					@{{subcategory.title}} </a>
				<div class="collapse in" id="@{{subcategory.slug}}">
					<a ng-repeat="section in subcategory.child_category"
                        ui-sref="categorysection({category:category.slug,slug:section.slug})" class="">@{{section.title}}</a>
				</div></li>
		</ul>
	</div>
</div>
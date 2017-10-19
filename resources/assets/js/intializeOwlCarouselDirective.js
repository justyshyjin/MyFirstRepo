'use strict';

/**
 * directive method for intializer owlCarousel after data is feeded directive should be used for the
 * <li>
 */
var intializeOwlCarouselDirective = [function () {
    return {restrict : 'A',link : function ( scope, element, attr ) {
        if ( scope.$last === true ) {
            var parentElement = ( typeof element == 'object' && element.length > 0 ) ? element [0].parentElement : element.parentElement;
            var owlCarouselOptions = scope.owlCarouselOptions;
            if ( attr.hasOwnProperty( 'owlCarouselOptions' ) ) {
                owlCarouselOptions = angular.isObject( scope [attr.owlCarouselOptions] ) ? scope [attr.owlCarouselOptions] : scope.owlCarouselOptions;
            }
            var $owl = $( parentElement );
            if ( $owl.find( '.owl-stage-outer' ).length ) {
                $owl.trigger( 'destroy.owl.carousel' );
                $owl.find( '.owl-stage-outer' ).remove();
                $owl.removeClass( 'owl-loaded' );
                $owl.css( "visibility", "hidden" );
                setTimeout( function () {
                    $owl.owlCarousel( owlCarouselOptions );
                    $owl.css( "visibility", "visible" );
                    $owl.trigger( 'next.owl.carousel' );
                }, 50 );
            } else {
                $owl.css( "visibility", "hidden" );
                setTimeout( function () {
                    $owl.owlCarousel( owlCarouselOptions );
                    $owl.css( "visibility", "visible" );
                }, 50 );
            }
            if ( owlCarouselOptions.nav  && attr.showNav!=='true') {
                $owl.on( 'initialized.owl.carousel', function ( event ) {
                    $( event.target ).find( '.owl-next' ).bind( 'click', function () {
                        var parent = ( typeof this == 'object' && this.length > 0 ) ? this [0].parentElement.parentElement.parentElement : this.parentElement.parentElement.parentElement;
                        var totalItems = $( parent ).find( '.owl-stage>.owl-item' ).length;
                        var currentIndex = $( parent ).find( '.owl-item.active:last' ).index() + 1;
                        var parentslug = $( parent ).find( '.owl-stage>.owl-item' ).children( "[data-owl-parent]" ).attr( 'data-owl-parent' );
                        if ( totalItems === currentIndex && typeof parentslug === "string" && parentslug) {
                            scope.$emit( "triggerNextOwlcarosel", {'total' : totalItems,'current' : currentIndex,'parent-slug' : parentslug} );
                        }
                    } )
                    if ( $( event.target ).find( '.owl-item.active:first' ).index() === 0 ) {
                        $( event.target ).find( '.owl-prev' ).hide()
                    } else {
                        $( event.target ).find( '.owl-prev' ).show()
                    }
                    if ( $( event.target ).find( '.owl-item.active:last' ).index() + 1 === $( event.target ).find( '.owl-stage>.owl-item' ).length ) {
                        var check = $( event.target ).find( '.owl-item.active:last' ).children( "[data-owl-parent]" ).attr( 'data-owl-parent' );
                        if(!(typeof check === "string" && check)){
                            $( event.target ).find( '.owl-next' ).hide()
                        }
                    } else {
                        $( event.target ).find( '.owl-next' ).show()
                    }
                } )
                $owl.on( 'translated.owl.carousel', function ( event ) {
                    if ( $( event.target ).find( '.owl-item.active:first' ).index() === 0 ) {
                        $( event.target ).find( '.owl-prev' ).hide()
                    } else {
                        $( event.target ).find( '.owl-prev' ).show()
                    }
                    if ( $( event.target ).find( '.owl-item.active:last' ).index() + 1 === $( event.target ).find( '.owl-stage>.owl-item' ).length ) {
                        var check = $( event.target ).find( '.owl-item.active:last' ).children( "[data-owl-parent]" ).attr( 'data-owl-parent' );
                        if(!(typeof check === "string" && check)){
                            $( event.target ).find( '.owl-next' ).hide()
                        }
                    } else {
                        $( event.target ).find( '.owl-next' ).show()
                    }
                } )
            }
        }
    }}
}];

// import { getIconComponent } from '../icons';
			// console.log( getIconComponent( 'QuickLogin' ) );
// import { __ } from '@wordpress/i18n';
( function ( $ ) {
	class FutureWordPress_Frontend {
		/**
		 * Constructor
		 */
		constructor() {
			this.init();
			// console.log( 'frontend init...' );
		}
		init() {
			const thisClass = this;var theInterval, selector;
			selector = '.fwp-tabs__navs';
			theInterval = setInterval( () => {
				document.querySelectorAll( selector + ':not([data-handled])' ).forEach( ( e, i ) => {
					e.dataset.handled = true;
					thisClass.tabs( e );
				} );
			}, 1000 );
		}
		tabs( e ) {
			e.querySelectorAll( '.fwp-tabs__nav-item' ).forEach( ( tabEl, tabI ) => {
				tabEl.addEventListener( 'click', function( event ) {
					if( this.dataset.target ) {
						e.querySelector( '.active' ).classList.remove( 'active' );
						this.classList.add( 'active' );
						document.querySelector( this.dataset.target ).parentElement.querySelector( '.active' ).classList.remove( 'active' );
						document.querySelector( this.dataset.target ).classList.add( 'active' );
					}
				} );
			} );
		}
	}
	new FutureWordPress_Frontend();
} )( ( typeof jQuery !== 'undefined' ) ? jQuery : false );

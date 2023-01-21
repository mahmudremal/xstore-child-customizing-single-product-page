/**
 * Video layer using Plyr.io
 * @url https://github.com/sampotts/plyr
 * 
 * @package Future WordPress Inc.
 */
import Plyr from 'plyr';
import defaults from './defaults'
// import 'plyr/src/sass/plyr.scss';

( function ( $ ) {
  class FWPProject_VideoPlyr {
    constructor() {
      this.ajaxUrl     = fwpSiteConfig?.ajaxUrl ?? '';
      this.ajaxNonce   = fwpSiteConfig?.ajax_nonce ?? '';
      this.buildPath   = fwpSiteConfig?.buildPath ?? '';
      this.selector    = 'fwp-video-playing-field'; // is ID.
      var i18n         = fwpSiteConfig?.i18n ?? {};
      this.i18n        = {
        sureToSubmit: i18n?.sureToSubmit ?? 'Want to submit it?',
        uploading: i18n?.uploading ?? 'Uploading',
        click_here: i18n?.click_here ?? 'Click here'
      };
      this.players     = [];
      this.init();
      this.initPlayer();
    }
    init() {
      this.isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
      this.isEdge = /Edge/.test(navigator.userAgent);
      this.isOpera = !!window.opera || navigator.userAgent.indexOf('OPR/') !== -1;
    }
    initPlayer() {
      const thisClass = this;var theInterval, args;
      theInterval = setInterval( () => {
        if( typeof Plyr === 'function' ) {
          document.querySelectorAll( '.' + thisClass.selector + ':not([data-handled])' ).forEach( ( e, i ) => {
            args = ( ! e.dataset.plyrConfig ) ? defaults : false;
            thisClass.players.push( new Plyr( e, args ) );e.dataset.handled = true;
          } );
          // clearInterval( theInterval );
        }
      }, 5000 );
    }
  }
  new FWPProject_VideoPlyr();
} )( jQuery );

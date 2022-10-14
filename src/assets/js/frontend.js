import React from 'react';
import ReactDOM from 'react-dom';
import { ExampleOneQuestion } from './components/ExampleOneQuestion';

/**
 * JavaScript
 */

/* jQuery
(function ($) {
	// jQuery document ready
	console.log('test');
})(jQuery); */

// Vanilla JS.
( function() {
	const questions = document.querySelectorAll( '.question-container' );
	questions.forEach( function( div ) {
		const data = JSON.parse( div.querySelector( 'pre' ).innerText );
		ReactDOM.render( <ExampleOneQuestion { ...data } />, div );
	} );
}() );

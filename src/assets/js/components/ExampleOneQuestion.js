import React, { useState } from 'react';

export const ExampleOneQuestion = ( { question, options, answer } ) => {
	const [ correct, setCorrect ] = useState( '' );

	const checkAnswer = ( index ) => {
		if ( index === answer ) {
			setCorrect( true );
		} else {
			setCorrect( false );
		}
	};
	return (
		<div className="question-container">
			<p className="question">{ question }</p>
			<ul className="answers">
				{ options.map( function( option, index ) {
					return (
						<li key={ `${ question }-${ index }` } onClick={ () => checkAnswer( index ) }>
							{ option }
						</li>
					);
				} ) }
			</ul>
			{ ( correct === true || correct === false ) && (
				<div className="answer">
					{ correct === true && (
						<div className="answer-correct">
							<p>That is correct!</p>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16"
								height="16"
								fill="currentColor"
								className="bi bi-emoji-sunglasses"
								viewBox="0 0 16 16"
							>
								<path d="M4.968 9.75a.5.5 0 1 0-.866.5A4.498 4.498 0 0 0 8 12.5a4.5 4.5 0 0 0 3.898-2.25.5.5 0 1 0-.866-.5A3.498 3.498 0 0 1 8 11.5a3.498 3.498 0 0 1-3.032-1.75zM7 5.116V5a1 1 0 0 0-1-1H3.28a1 1 0 0 0-.97 1.243l.311 1.242A2 2 0 0 0 4.561 8H5a2 2 0 0 0 1.994-1.839A2.99 2.99 0 0 1 8 6c.393 0 .74.064 1.006.161A2 2 0 0 0 11 8h.438a2 2 0 0 0 1.94-1.515l.311-1.242A1 1 0 0 0 12.72 4H10a1 1 0 0 0-1 1v.116A4.22 4.22 0 0 0 8 5c-.35 0-.69.04-1 .116z" />
								<path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-1 0A7 7 0 1 0 1 8a7 7 0 0 0 14 0z" />
							</svg>
						</div>
					) }
					{ correct === false && (
						<div className="answer-wrong">
							<p>Not quite!</p>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16"
								height="16"
								fill="currentColor"
								className="bi bi-emoji-expressionless"
								viewBox="0 0 16 16"
							>
								<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
								<path d="M4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5zm5 0a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z" />
							</svg>
						</div>
					) }
				</div>
			) }
		</div>
	);
};

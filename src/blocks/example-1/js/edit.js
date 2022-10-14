import '../scss/editor.scss';
const { __ } = window.wp.i18n;
const { Flex, FlexBlock, FlexItem, TextControl, Button, Icon } = window.wp.components;
const { useBlockProps } = window.wp.blockEditor;

// Locking post save if an question answer has not been selected.
( function() {
	let locked = false;
	window.wp.data.subscribe( function() {
		const results = wp.data.select( 'core/block-editor' ).getBlocks().filter( function( block ) {
			return block.name === 'wpstarterplugin/example-1' && block.attributes.answer === -1;
		} );
		if ( results.length > 0 && locked === false ) {
			locked = true;
			window.wp.data.dispatch( 'core/editor' ).lockPostSaving( 'no answer' );
		}
		if ( results.length === 0 && locked === true ) {
			locked = false;
			window.wp.data.dispatch( 'core/editor' ).unlockPostSaving( 'no answer' );
		}
	} );
}() );

export const Edit = ( { attributes, setAttributes } ) => {
	const blockProps = useBlockProps( { className: 'example-1' } );
	const {
		question,
		options,
		answer,
	} = attributes;

	// Updates question when user types.
	const updateQuestion = ( value ) => {
		setAttributes( { question: value } );
	};

	// Updates option when user types.
	const updateOption = ( index, value ) => {
		const newOptions = [ ...options ];
		newOptions[ index ] = value;
		setAttributes( { options: newOptions } );
	};

	// Adds new option
	// called onclick by add answer button.
	const addOption = () => {
		const newOptions = [ ...options ];
		setAttributes( { options: newOptions.concat( [ '' ] ) } );
	};

	// Deletes an option
	// called onclick by delete option link.
	const deleteOption = ( index ) => {
		const newOptions = [ ...options ];
		newOptions.splice( index, 1 );
		setAttributes( { options: newOptions } );
		if ( answer === index ) {
			setAttributes( { answer: -1 } );
		}
	};

	// Updates the answer attribute
	// when answer is starred as the correct answer.
	const updateAnswer = ( index ) => {
		setAttributes( { answer: parseInt( index ) } );
	};

	return (
		<div { ...blockProps }>
			<TextControl className="question" label="Question:" value={ question } onChange={ ( value ) => updateQuestion( value ) } />
			<div className="answers">
				<p>{ __( 'Possible answers:' ) }</p>
				{ attributes.options.map( ( option, index ) => {
					return (
						<Flex key={ `answer-${ index }` }>
							<FlexBlock>
								<TextControl value={ options[ index ] } onChange={ ( value ) => updateOption( index, value ) } />
							</FlexBlock>
							<FlexItem>
								<Button>
									<Icon className="mark-answer-correct" onClick={ () => updateAnswer( index ) } icon={ parseInt( answer ) === index ? 'star-filled' : 'star-empty' }></Icon>
								</Button>
							</FlexItem>
							<FlexItem>
								<Button
									className="delete-answer"
									onClick={ () => deleteOption( index ) }
									isLink
								>{ __( 'Delete' ) }</Button>
							</FlexItem>
						</Flex>
					);
				} ) }
			</div>
			<Button
				onClick={ addOption }
				isPrimary>{ __( 'Add Answer' ) }</Button>
		</div>
	);
};

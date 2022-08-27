import '../scss/editor.scss';
const { __ } = window.wp.i18n;
const { RichText, useBlockProps } = window.wp.blockEditor;

export const Edit = ( { attributes, setAttributes } ) => {
	const blockProps = useBlockProps( { className: 'hello-world-editor' } );
	return (
		<div { ...blockProps }>
			<RichText
				tagName="h1"
				placeholder={ __( 'Write title…' ) }
				value={ attributes.title }
				onChange={ ( title ) => setAttributes( { title } ) }
				keepPlaceholderOnFocus={ true }
			/>
			<RichText
				tagName="h2"
				aplaceholder={ __( 'Write subtitle…' ) }
				value={ attributes.subtitle }
				onChange={ ( subtitle ) => setAttributes( { subtitle } ) }
				keepPlaceholderOnFocus={ true }
			/>
		</div>
	);
};

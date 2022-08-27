import '../scss/style.scss';
const { useBlockProps } = window.wp.blockEditor;

export const Save = ( { attributes } ) => {
	const blockProps = useBlockProps.save( { className: 'hello-world' } );
	const { title, subtitle } = attributes;
	return (
		<div { ...blockProps }>
			<h1>{ title }</h1>
			<h2>{ subtitle }</h2>
		</div>
	);
};

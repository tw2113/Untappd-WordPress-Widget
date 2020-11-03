import classnames from 'classnames';
const { Component } = wp.element;
const { TextControl } = wp.components;

const { __ } = wp.i18n;
const Edit = (props) => {
	const {
		attributes: {
			userName,
		},
		className,
		setAttributes,
	} = props;
	const saveSetting = (name, value) => {
		setAttributes({
			[name]: value,
		});
	};

	return (
		<div className={className}>
			<TextControl
				label={__('Untappd Username', 'indieweb-blocks')}
				value={userName || ''}
				onChange={(value) => saveSetting('userName', value)}
			/>
		</div>
	);
};
export default Edit;

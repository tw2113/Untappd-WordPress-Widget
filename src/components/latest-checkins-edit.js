import classnames from 'classnames';
const { Component } = wp.element;
const { TextControl } = wp.components;

const { __ } = wp.i18n;
const Edit = (props) => {
	const {
		attributes: {
			userName,
			title,
			limit,
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
			<p>{__('This block requires API keys set via our options page.', 'mb_untappd')}</p>
			<TextControl
				label={__('Title', 'mb_untappd')}
				value={title || ''}
				onChange={(value) => saveSetting('title', value)}
			/>
			<TextControl
				label={__('Untappd Username', 'mb_untappd')}
				value={userName || ''}
				onChange={(value) => saveSetting('userName', value)}
			/>
			<TextControl
				label={__('Listing limit (default: 25, max: 50)', 'mb_untappd')}
				value={limit || ''}
				onChange={(value) => saveSetting('limit', value)}
			/>
		</div>
	);
};
export default Edit;

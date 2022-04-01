import classnames from 'classnames';
const { Component } = wp.element;
const { TextControl } = wp.components;

const { __ } = wp.i18n;

export default class LatestCheckins extends Component {
	/**
	 * Constructor
	 * @param props
	 */
	constructor( props ) {
		super( props );
	}

    render() {
    	const {
            attributes: { username },
            className, setAttributes
        } = this.props;

		return (
			<div className={ className }>
				<p>{ __('User\'s Latest Checkins', 'mb_untappd' ) }</p>
				<TextControl
					label={ __('Username:', 'mb_untappd' ) }
					placeholder={ __( 'Enter Username here…' ) }
					value={ username }
					onChange={ username => setAttributes( { attributes: username } ) }
				/>
			</div>
		)
	}
}

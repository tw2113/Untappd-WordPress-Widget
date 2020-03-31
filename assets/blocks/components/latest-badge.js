import { Component } from '@wordpress/element';
import { Placeholder, TextControl } from '@wordpress/components';

const { __ } = wp.i18n;

class LatestCheckins extends Component {
	/**
	 * Constructor
	 * @param props
	 */
	constructor( props ) {
		super( props );

		this.onSubmitURL = this.onSubmitURL.bind( this );
	}

	onSubmitURL( event ) {
		event.preventDefault();

		const { feedURL } = this.props.attributes;
		if ( feedURL ) {
			this.setState( { editing: false } );
		}
	}

    render() {
    	let { thevalue } = this.props.attributes;
		return (
			<Placeholder label="RSS">
				<form
					onSubmit={ this.onSubmitURL }
					className="blocks-rss__placeholder-form"
				>
					<TextControl
						placeholder={ __( 'Enter URL here…' ) }
						value={ 'feedURL' }
						onChange={ ( value ) =>
							setAttributes( { feedURL: value } )
						}
						className="blocks-rss__placeholder-input"
					/>
				</form>
			</Placeholder>
		)
	}
}

export default LatestCheckins;

import {__} from '@wordpress/i18n';

/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

import Title from '../../components/title';
import Username from '../../components/username';
import ShowUserAvatar from '../../components/show-user-avatar';
import ShowUserLocation from "../../components/show-user-location";
import ShowUserTotalCheckins from "../../components/show-user-total-checkins";
import ShowUserTotalBeers from "../../components/show-user-total-beers";
import ShowUserTotalBadges from "../../components/show-user-total-badges";
import ShowUserTotalFriends from "../../components/show-user-total-friends";

export default function Edit(props) {
	const {
		attributes: {
			title,
			username,
			showavatar,
			showlocation,
			showtotal_badges,
			showtotal_checkins,
			showtotal_beers,
			showtotal_friends,
		},
		setAttributes,
		isSelected,
	} = props;

	return (
		<div {...useBlockProps()}>
			{isSelected ? (
				<div>
					<Title setAttributes={setAttributes} title={title} />
					<Username setAttributes={ setAttributes } username={ username } />
					<ShowUserAvatar setAttributes={ setAttributes } showavatar={showavatar}/>
					<ShowUserLocation setAttributes={ setAttributes } showlocation={ showlocation } />
					<ShowUserTotalCheckins setAttributes={ setAttributes } showtotal_checkins={ showtotal_checkins} />
					<ShowUserTotalBeers setAttributes={ setAttributes } showtotal_beers={ showtotal_beers }/>
					<ShowUserTotalBadges setAttributes={ setAttributes } showtotal_badges={ showtotal_badges }/>
					<ShowUserTotalFriends setAttributes={ setAttributes } showtotal_friends={ showtotal_friends }/>
				</div>
			) : (
				<div>
					<h3>{ __( 'Untapped User Profile Block', 'mb_untappd' ) }</h3>
				</div>
			)
			}
		</div>
	);
}

/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { notes, Icon } from '@woocommerce/icons';

export const BLOCK_TITLE = __(
	'Product Summary',
	'woocommerce'
);
export const BLOCK_ICON = (
	<Icon
		srcElement={ notes }
		className="wc-block-editor-components-block-icon"
	/>
);
export const BLOCK_DESCRIPTION = __(
	'Display a short description about a product.',
	'woocommerce'
);

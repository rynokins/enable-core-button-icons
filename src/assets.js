import { icons } from 'feather-icons';
import { SVG } from '@wordpress/primitives';
import htmr from 'htmr';

const dashToTitleCase = (str) => str
  .split( '-' )
  .map( word => word.charAt(0).toUpperCase() + word.slice(1) )
  .join(' ');

const ICONS = Object.values(icons).map(icon => ({
	label: dashToTitleCase(icon.name),
  tags: icon.tags,
	value: icon.name,
	icon: (<SVG xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18">{ htmr(icon.contents) }</SVG>)
}));

export default ICONS;
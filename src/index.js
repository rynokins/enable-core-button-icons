/**
 * External dependencies
 */
import classnames from 'classnames';
import React from 'react';
import htmr from 'htmr';
import { icons } from 'feather-icons';
/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { useState } from '@wordpress/element';
import { InspectorControls } from '@wordpress/block-editor';
import {
	Button,
	PanelBody,
	PanelRow,
	ToggleControl,
  SearchControl,
  __experimentalScrollable as Scrollable, // eslint-disable-line
	__experimentalGrid as Grid, // eslint-disable-line
} from '@wordpress/components';
/**
 * Internal dependencies
 */
import ICONS from './assets';
import useSearch from './useSearch';


/**
 * Add the attributes needed for button icons.
 *
 * @since 0.1.0
 * @param {Object} settings
 */
function addAttributes( settings ) {
	if ( 'core/button' !== settings.name ) {
		return settings;
	}

	// Add the block visibility attributes.
	const iconAttributes = {
		icon: {
			type: 'string',
		},
		iconPositionLeft: {
			type: 'boolean',
			default: false,
		},
		emptyContent: {
			type: 'boolean',
			default: false,
		},
	};

	const newSettings = {
		...settings,
		attributes: {
			...settings.attributes,
			...iconAttributes
		},
	};

	return newSettings;
}

addFilter(
	'blocks.registerBlockType',
	'core-button-icons/add-attributes',
	addAttributes
);

/**
 * Filter the BlockEdit object and add icon inspector controls to button blocks.
 *
 * @since 0.1.0
 * @param {Object} BlockEdit
 */
function addInspectorControls( BlockEdit ) {
	return ( props ) => {
		if ( props.name !== 'core/button' ) {
			return <BlockEdit { ...props } />;
		}

		const { attributes, setAttributes } = props;
		const {
      icon: currentIcon, iconPositionLeft, emptyContent } = attributes;

		const inputElement = React.useRef(null)
		const [ query, setQuery ] = useState( '' )


		const results = useSearch(query || '')

		return (
			<>
				<BlockEdit { ...props } />
				<InspectorControls>
					<PanelBody
						title={ __( 'Button Icons', 'core-button-icons' ) }
						className="button-icon-picker"
						initialOpen={ true }
					>
						<PanelRow>
              <SearchControl
								ref={inputElement}
                __nextHasNoMarginBottom
								placeholder={`Search ${
									Object.keys(ICONS).length
								} icons…`}
								value={query || ''}
								onChange={setQuery}
								size='compact'
              />
						</PanelRow>
						<PanelRow>

              <Scrollable
                scrollDirection="y"
                style={{
                  height: 400
                }}
              >
								{results.length > 0 ? (
									<Grid
										className="button-icon-picker__grid"
										columns="5"
										gap="0"
									>
										{ results.map( ( icon, index ) => (

											<Button
												key={ index }
												label={ icon?.label }
												isPressed={ currentIcon === icon.value }
												className="button-icon-picker__button"
												onClick={ () =>
													setAttributes( {
														// Allow user to disable icons.
														icon:
															currentIcon === icon.value
																? null
																: icon.value,
													} )
												}
											>
												{ icon.icon ?? icon.value }
											</Button>
										) ) }
									</Grid>
								) : (
									<div class="button-icon-picker__grid no-results">{htmr(icons['alert-triangle'].toSvg({'width':32,'height':32}))}<h2>No results found for &ldquo;{query}&rdquo;</h2></div>
								)}
              </Scrollable>
						</PanelRow>
						<PanelRow className='button-icon-options__row'>
							<ToggleControl
								label={ __(
									'Icon on left',
									'core-button-icons'
								) }
								checked={ iconPositionLeft }
								disabled={ emptyContent }
								onChange={ () => {
									setAttributes( {
										iconPositionLeft: ! iconPositionLeft,
									} );
								} }
							/>
							<ToggleControl
								label={ __(
									'Icon only',
									'core-button-icons'
								) }
								checked={ emptyContent }
								onChange={ () => {
									setAttributes( {
										emptyContent: ! emptyContent,
										text: emptyContent
										? undefined
										: '&#32;'
									} )
                  console.log(props.attributes);
                }
                }
							/>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			</>
		);
	};
}

addFilter(
	'editor.BlockEdit',
	'core-button-icons/add-inspector-controls',
	addInspectorControls
);

/**
 * Add icon and position classes in the Editor.
 *
 * @since 0.1.0
 * @param {Object} BlockListBlock
 */
function addClasses( BlockListBlock ) {
	return ( props ) => {
		const { name, attributes } = props;

		if ( 'core/button' !== name || ! attributes?.icon ) {
      return <BlockListBlock { ...props } />;
		}

		const classes = classnames( props?.className, {
			[ `has-icon__${ attributes?.icon }` ]: attributes?.icon,
			'has-icon-position__left': attributes?.iconPositionLeft,
			'is-icon-only': attributes?.emptyContent,
		} );

		return <BlockListBlock { ...props } className={ classes } />;
	};
}

addFilter(
	'editor.BlockListBlock',
	'core-button-icons/add-classes',
	addClasses
);
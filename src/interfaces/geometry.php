<?php
/**
 * File containing the ezcImageGeometryFilters interface.
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package ImageConversion
 * @version //autogentag//
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @filesource
 */

/**
 * This interface has to implemented by ezcImageFilters classes to
 * support geometry filters.
 *
 * @see ezcImageHandler
 * @see ezcImageTransformation
 * @see ezcImageFiltersInterface
 *
 * @package ImageConversion
 * @version //autogentag//
 */
interface ezcImageGeometryFilters
{
    /**
     * Scale up and down, as fits
     * @var int
     */
    public const SCALE_BOTH = 1;

    /**
     * Scale down only
     * @var int
     */
    public const SCALE_DOWN = 2;

    /**
     * Scale up only
     * @var int
     */
    public const SCALE_UP = 3;

    /**
     * Scale filter.
     * General scale filter. Scales the image to fit into a given box size, 
     * determined by a given width and height value, measured in pixel. This 
     * method maintains the aspect ratio of the given image. Depending on the
     * given direction value, this method performs the following scales:
     *
     * - ezcImageGeometryFilters::SCALE_BOTH:
     *      The image will be scaled to fit exactly into the given box 
     *      dimensions, no matter if it was smaller or larger as the box
     *      before.
     * - ezcImageGeometryFilters::SCALE_DOWN:
     *      The image will be scaled to fit exactly into the given box 
     *      only if it was larger than the given box dimensions before. If it
     *      is smaller, the image will not be scaled at all.
     * - ezcImageGeometryFilters::SCALE_UP:
     *      The image will be scaled to fit exactly into the given box 
     *      only if it was smaller than the given box dimensions before. If it
     *      is larger, the image will not be scaled at all. ATTENTION:
     *      In this case, the image does not necessarily fit into the given box
     *      afterwards.
     *
     * @param int $width     Scale to width
     * @param int $height    Scale to height
     * @param int $direction Scale to which direction.
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function scale( $width, $height, $direction = ezcImageGeometryFilters::SCALE_BOTH );

    /**
     * Scale after width filter.
     * Scales the image to a give width, measured in pixel. Scales the height 
     * automatically while keeping the ratio. The direction dictates, if an 
     * image may only be scaled {@link self::SCALE_UP}, {@link self::SCALE_DOWN} 
     * or if the scale may work in {@link self::SCALE_BOTH} directions.
     *
     * @param int $width     Scale to width
     * @param int $direction Scale to which direction
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function scaleWidth( $width, $direction );

    /**
     * Scale after height filter.
     * Scales the image to a give height, measured in pixel. Scales the width 
     * automatically while keeping the ratio. The direction dictates, if an 
     * image may only be scaled {@link self::SCALE_UP}, {@link self::SCALE_DOWN} 
     * or if the scale may work in {@link self::SCALE_BOTH} directions.
     *
     * @param int $height    Scale to height
     * @param int $direction Scale to which direction
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function scaleHeight( $height, $direction );

    /**
     * Scale percent measures filter.
     * Scale an image to a given percentage value size.
     *
     * @param int $width  Scale to width
     * @param int $height Scale to height
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function scalePercent( $width, $height );

    /**
     * Scale exact filter.
     * Scale the image to a fixed given pixel size, no matter to which 
     * direction.
     * 
     * @param int $width  Scale to width
     * @param int $height Scale to height
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function scaleExact( $width, $height );

    /**
     * Crop filter.
     * Crop an image to a given size. This takes cartesian coordinates of a 
     * rect area to crop from the image. The cropped area will replace the old 
     * image resource (not the input image immediately, if you use the 
     * {@link ezcImageConverter}).  Coordinates are given as integer values and
     * are measured from the top left corner.
     *
     * @param int $x      Start cropping, x coordinate.
     * @param int $y      Start cropping, y coordinate.
     * @param int $width  Width of cropping area.
     * @param int $height Height of cropping area.
     * @return void
     *
     * @throws ezcImageInvalidReferenceException
     *         If no valid resource for the active reference could be found.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a submitted parameter was out of range or type.
     */
    function crop( $x, $y, $width, $height );
}
?>

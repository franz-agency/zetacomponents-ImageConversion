<?php
/**
 * This file contains the ezcImageHandler interface.
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
 * Driver interface to access different image manipulation backends of PHP.
 * This interface has to be implemented by a handler class in order to be
 * used with the ImageConversion package.
 *
 * @see ezcImageConverter
 *
 * @package ImageConversion
 * @version //autogentag//
 */
abstract class ezcImageHandler
{
    /**
     * Container to hold the properties
     *
     * @var array(string=>mixed)
     */
    protected $properties;

    /**
     * Settings of the handlers 
     * 
     * @var ezcImageHandlerSettings
     */
    protected $settings;

    /**
     * Create a new image handler.
     * Creates an image handler. This should never be done directly,
     * but only through the manager for configuration reasons. One can
     * get a direct reference through manager afterwards. When overwriting
     * the constructor.
     *
     * @param ezcImageHandlerSettings $settings
     *        Settings for the handler.
     */
    public function __construct( ezcImageHandlerSettings $settings )
    {
        $this->properties['name'] = $settings->referenceName;
        $this->settings = $settings;
    }

    /**
     * Sets the property $name to $value.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @throws ezcBasePropertyReadOnlyException if the property cannot be modified.
     * @param string $name
     * @ignore
     */
    public function __set( $name, mixed $value )
    {
        switch ( $name )
        {
            case 'name':
                throw new ezcBasePropertyPermissionException( $name, ezcBasePropertyPermissionException::READ );
            default:
                throw new ezcBasePropertyNotFoundException( $name );
        }
    }

    /**
     * Returns the property $name.
     *
     * @throws ezcBasePropertyNotFoundException if the property does not exist.
     * @param string $name
     * @return mixed
     * @ignore
     */
    public function __get( $name )
    {
        return match ($name) {
            'name' => $this->properties[$name],
            default => throw new ezcBasePropertyNotFoundException( $name ),
        };
    }

    /**
     * Checks if the property $name exist and returns the result.
     *
     * @param string $name
     * @return bool
     * @ignore
     */
    public function __isset( $name )
    {
        return match ($name) {
            'name' => true,
            default => false,
        };
    }

    /**
     * Checks a file name for illegal characters.
     * Checks if a file name contains illegal characters, which are ", ' and $.
     * 
     * @param string $file The file name to check.
     * @return void
     *
     * @throws ezcImageFileNameInvalidException 
     *         If an invalid character (", ', $) is found in the file name.
     */
    protected function checkFileName( $file )
    {
        if ( str_contains( $file, "'" ) || str_contains( $file, "'" ) || str_contains( $file, '$' ) )
        {
            throw new ezcImageFileNameInvalidException( $file );
        }
    }

    /**
     * Returns if a MIME conversion needs transparent color replacement.
     *
     * In case a transparency supporting MIME type (like image/png) is
     * converted to one that does not support transparency, special steps need
     * to be performed. This method returns if the given conversion from
     * $inMime to $outMime is affected by this.
     *
     * @param string $inMime 
     * @param string $outMime 
     * @return bool
     */
    protected function needsTransparencyConversion( $inMime, $outMime )
    {
        $transparencyMimes = ['image/gif' => true, 'image/png' => true];
        return (
               $outMime !== null
            && $inMime !== $outMime
            && isset( $transparencyMimes[$inMime] )
            && !isset( $transparencyMimes[$outMime] )
        );
    }

    /**
     * Load an image file.
     * Loads an image file and returns a reference to it.
     *
     * For developers: The use of ezcImageHandler::loadCommon() is highly
     * recommended for the implementation of this method!
     *
     * @param string $file File to load.
     * @param string $mime The MIME type of the file.
     * @return string Reference to the file in this handler.
     */
    abstract public function load( $file, $mime = null );

    /**
     * Save an image file.
     * Saves a given open file. Can optionally save to a new file name.
     * The image reference is not freed automatically, so you need to call
     * the close() method explicitly to free the referenced data.
     *
     * @see ezcImageHandler::load()
     * @see ezcImageHandler::close()
     *
     * @param string $image                File reference created through.
     * @param string $newFile              Filename to save the image to.
     * @param string $mime                 New MIME type, if differs from
     *                                     initial one.
     * @param ezcImageSaveOptions $options Options for saving.
     * @return void
     */
    abstract public function save( $image, $newFile = null, $mime = null, ezcImageSaveOptions $options = null );

    /**
     * Close the file referenced by $image.
     * Frees the image reference. You should call close() before.
     *
     * @see ezcImageHandler::load()
     * @see ezcImageHandler::save()
     * @param string $reference The image reference.
     * @return void
     */
    abstract public function close( $reference );

    /**
     * Check wether a specific MIME type is allowed as input for this handler.
     *
     * @param string $mime MIME type to check if it's allowed.
     * @return bool
     */
    abstract public function allowsInput( $mime );

    /**
     * Checks wether a specific MIME type is allowed as output for this handler.
     *
     * @param string $mime MIME type to check if it's allowed.
     * @return bool
     */
    abstract public function allowsOutput( $mime );

    /**
     * Checks if a given filter is available in this handler.
     *
     * @param string $name Name of the filter to check for.
     * @return bool
     *
     */
    abstract public function hasFilter( $name );

    /**
     * Returns a list of filters this handler provides.
     * The list returned is in format:
     *
     * <code>
     * array(
     *  0 => <string filtername>,
     *  1 => <string filtername>,
     *  ...
     * )
     * </code>
     *
     * @return array(string)
     */
    abstract public function getFilterNames();

    /**
     * Applies a filter to a given image.
     *
     * @internal This method is the main one, which will dispatch the
     * filter action to the specific function of the backend.
     *
     * @see ezcImageHandler::load()
     * @see ezcImageHandler::save()
     *
     * @param string $image          Image reference to apply the filter on.
     * @param ezcImageFilter $filter Contains which filter operation to apply.
     * @return void
     * 
     * @throws ezcImageFilterNotAvailableException
     *         If the desired filter does not exist.
     * @throws ezcImageMissingFilterParameterException
     *         If a parameter for the filter is missing.
     * @throws ezcImageFilterFailedException
     *         If the operation performed by the the filter failed.
     * @throws ezcBaseValueException
     *         If a parameter was not within the expected range.
     */
    abstract public function applyFilter( $image, ezcImageFilter $filter );

    /**
     * Converts an image to another MIME type.
     *
     * Use {@link ezcImageHandler::allowsOutput()} to determine,
     * if the output MIME type is supported by this handler!
     *
     * @see ezcImageHandler::load()
     * @see ezcImageHandler::save()
     *
     * @param string $image Image reference to convert.
     * @param string $mime  MIME type to convert to.
     * @return void
     *
     * @throws ezcImageMimeTypeUnsupportedException
     *         If the given MIME type is not supported by the filter.
     */
    abstract public function convert( $image, $mime );
}
?>

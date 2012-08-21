<?php
/**
 * File containing the BinaryFileTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\FieldType\Tests;
use eZ\Publish\Core\FieldType\BinaryFile\Type as BinaryFileType,
    eZ\Publish\Core\FieldType\BinaryFile\Value as BinaryFileValue;

/**
 * @group fieldType
 * @group ezbinaryfile
 */
class BinaryFileTest extends BinaryBaseTest
{
    /**
     * Returns the field type under test.
     *
     * This method is used by all test cases to retrieve the field type under
     * test. Just create the FieldType instance using mocks from the provided
     * get*Mock() methods and/or custom get*Mock() implementations. You MUST
     * NOT take care for test case wide caching of the field type, just return
     * a new instance from this method!
     *
     * @return FieldType
     */
    protected function createFieldTypeUnderTest()
    {
        return new BinaryFileType(
            $this->getValidatorServiceMock(),
            $this->getFieldTypeToolsMock(),
            $this->getFileServiceMock(),
            $this->getMimeTypeDetectorMock()
        );
    }

    public function provideInvalidInputForAcceptValue()
    {
        $baseInput = parent::provideInvalidInputForAcceptValue();
        $binaryFileInput = array(
            array(
                new BinaryFileValue(),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ),
            array(
                new BinaryFileValue( array( 'path' => '/foo/bar' ) ),
                'eZ\\Publish\\Core\\Base\\Exceptions\\InvalidArgumentException',
            ),
        );
        return array_merge( $baseInput, $binaryFileInput );
    }

    public function provideValidInputForAcceptValue()
    {
        return array(
            array(
                null,
                null
            ),
            array(
                __FILE__,
                new BinaryFileValue( array(
                    'path' => __FILE__,
                    'fileName' => basename( __FILE__ ),
                    'fileSize' => filesize( __FILE__ ),
                    'downloadCount' => 0,
                    'mimeType' => 'text/plain',
                ) )
            ),
            array(
                array( 'path' => __FILE__ ),
                new BinaryFileValue( array(
                    'path' => __FILE__,
                    'fileName' => basename( __FILE__ ),
                    'fileSize' => filesize( __FILE__ ),
                    'downloadCount' => 0,
                    'mimeType' => 'text/plain',
                ) )
            ),
            array(
                array(
                    'path' => __FILE__,
                    'fileSize' => 23,
                ),
                new BinaryFileValue( array(
                    'path' => __FILE__,
                    'fileName' => basename( __FILE__ ),
                    'fileSize' => 23,
                    'downloadCount' => 0,
                    'mimeType' => 'text/plain',
                ) )
            ),
            array(
                array(
                    'path' => __FILE__,
                    'downloadCount' => 42,
                ),
                new BinaryFileValue( array(
                    'path' => __FILE__,
                    'fileName' => basename( __FILE__ ),
                    'fileSize' => filesize( __FILE__ ),
                    'downloadCount' => 42,
                    'mimeType' => 'text/plain',
                ) )
            ),
            array(
                array(
                    'path' => __FILE__,
                    'mimeType' => 'application/text+php',
                ),
                new BinaryFileValue( array(
                    'path' => __FILE__,
                    'fileName' => basename( __FILE__ ),
                    'fileSize' => filesize( __FILE__ ),
                    'downloadCount' => 0,
                    'mimeType' => 'application/text+php',
                ) )
            ),
        );
    }
}
<?php
/**
 * File contains Content Type Field (content class attribute) class
 *
 * @copyright Copyright (C) 1999-2011 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace ezp\Content\Type;
use ezp\Base\Model,
    ezp\Content\Type,
    ezp\Persistence\Content\Type\FieldDefinition as FieldDefinitionValue,
    ezp\Content\FieldType\Factory as FieldTypeFactory,
    ezp\Content\FieldType\Validator,
    ezp\Content\FieldType\Value as FieldValue,
    ezp\Persistence\Content\FieldValue as PersistenceFieldValue,
    ezp\Persistence\Content\FieldTypeConstraints;

/**
 * Content Type Field (content class attribute) class
 *
 * @property-read mixed $id
 * @property string[] $name
 * @property string[] $description
 * @property string $identifier
 * @property string $fieldGroup
 * @property int $position
 * @property-read string $fieldType
 * @property bool $isTranslatable
 * @property bool $isSearchable
 * @property bool $isRequired
 * @property bool $isInfoCollector
 * @property-read \ezp\Content\FieldTypeConstraints $fieldTypeConstraints
 * @property \ezp\Content\FieldType\Value $defaultValue
 * @property-read \ezp\Content\Type $contentType ContentType object
 * @property-read \ezp\Content\FieldType $type FieldType object
 * @property-read \ezp\Content\FieldType\Validator $validators Registered validators for this field definition
 */
class FieldDefinition extends Model
{
    /**
     * @var array Readable of properties on this object
     */
    protected $readWriteProperties = array(
        'id' => false,
        'name' => true,
        'description' => true,
        'identifier' => true,
        'fieldGroup' => true,
        'position' => true,
        'fieldType' => false,
        'isTranslatable' => true,
        'isSearchable' => true,
        'isRequired' => true,
        'isInfoCollector' => true,
        'fieldTypeConstraints' => false,
    );

    /**
     * @var array Dynamic properties on this object
     */
    protected $dynamicProperties = array(
        'contentType' => false,
        'type' => false,
        'defaultValue' => true,
        'validators' => false,
    );

    /**
     * @var \ezp\Content\Type
     */
    protected $contentType;

    /**
     * @var \ezp\Content\FieldType
     */
    protected $type;

    /**
     * @var \ezp\Content\FieldType\Value
     */
    protected $defaultValue;

    /**
     * @var \ezp\Content\FieldType\Validator[]
     */
    protected $validators;

    /**
     * Constructor, sets up value object, fieldType string and attach $contentType
     *
     * @param \ezp\Content\Type $contentType
     * @param string $fieldType
     */
    public function __construct( Type $contentType, $fieldType )
    {
        $this->contentType = $contentType;
        $this->type = FieldTypeFactory::build( $fieldType );
        $this->properties = new FieldDefinitionValue(
            array(
                'fieldType' => $fieldType,
                'fieldTypeConstraints' => new FieldTypeConstraints
            )
        );
        $this->properties->fieldTypeConstraints->fieldSettings = $this->type->getFieldTypeSettings();
        $this->defaultValue = $this->type->getValue();
        $this->attach( $this->type, 'field/setValue' );
    }

    /**
     * Return content type object
     *
     * @return \ezp\Content\Type
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Return field type object
     *
     * @return \ezp\Content\FieldType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Adds a validator to for this field.
     *
     * @param \ezp\Content\FieldType\Validator $validator
     * @return void
     */
    public function addValidator( Validator $validator )
    {
        // We'll initialize the map with constraints if it does not already exist.
        if ( !isset( $this->properties->fieldTypeConstraints->validators ) )
        {
            $this->properties->fieldTypeConstraints->validators = array();
            $this->validators = array();
        }
        else
        {
            if ( !isset( $this->validators ) )
                $this->validators = $this->getValidators();
        }

        $this->type->fillConstraintsFromValidator( $this->fieldTypeConstraints, $validator );
        $this->validators[] = $validator;
    }

    /**
     * Returns default value for current field definition
     *
     * @return \ezp\Content\FieldType\Value
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets a new default value for current field definition
     *
     * @param \ezp\Content\FieldType\Value $value
     */
    public function setDefaultValue( FieldValue $value )
    {
        $this->defaultValue = $value;
        $this->notify( 'field/setValue', array( 'value' => $value ) );
        $this->properties->defaultValue = $this->type->toFieldValue();
    }

    /**
     * Returns validators for current field definition
     *
     * @return \ezp\Content\FieldType\Validator[]
     */
    public function getValidators()
    {
        if ( !isset( $this->validators ) )
        {
            $this->validators = array();
            if ( isset( $this->properties->fieldTypeConstraints->validators ) )
            {
                foreach ( $this->properties->fieldTypeConstraints->validators as $validatorClass => $constraints )
                {
                    $validator = new $validatorClass;
                    $validator->initializeWithConstraints( $constraints );
                    $this->validators[] = $validator;
                }
            }
            else
            {
                $this->properties->fieldTypeConstraints->validators = array();
            }
        }

        return $this->validators;
    }

    /**
     * Sets a field setting, according to field type allowed settings
     *
     * @see \ezp\Content\FieldType::$allowedSettings
     * @param string $settingName
     * @param mixed $value
     */
    public function setFieldSetting( $settingName, $value )
    {
        $this->type->setFieldSetting( $settingName, $value );
    }

    /**
     * Gets a field setting, identified by $settingName
     *
     * @param string $settingName
     * @return mixed
     */
    public function getFieldSetting( $settingName )
    {
        return $this->type->getFieldSetting( $settingName );
    }
}

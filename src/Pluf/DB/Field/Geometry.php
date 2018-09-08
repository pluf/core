<?php

/**
 * Geometry value
 *
 * @author maso
 *
 */
class Pluf_DB_Field_Geometry extends Pluf_DB_Field
{

    public $type = 'geometry';

    public $extra = array();

    /**
     * Gets form field
     * 
     * {@inheritDoc}
     * @see Pluf_DB_Field::formField()
     */
    function formField ($def, $form_field = 'Pluf_Form_Field_Geometry')
    {
        return parent::formField($def, $form_field);
    }
}

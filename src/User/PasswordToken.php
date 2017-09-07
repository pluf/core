<?php

/**
 * Password token
 * 
 * A security token to update password without knowing old password.
 * 
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class User_PasswordToken extends Pluf_Model
{

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'user_password_token';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'blank' => true,
                'editable' => false
            ),
            'user' => array(
                'type' => 'Pluf_DB_Field_Foreignkey',
                'model' => 'Pluf_User',
                'blank' => false,
                'editable' => false
            ),
            'token' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'blank' => false,
                'size' => 50,
                'editable' => false
            ),
            'creationTime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false,
                'verbose' => 'creation date',
                'help_text' => 'Creation date of the avatar.',
                'editable' => false
            ),
            'expireTime' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'blank' => false,
                'verbose' => 'modification date',
                'help_text' => 'Modification date of the avatar.',
                'editable' => false
            )
        );
        
        $this->_a['idx'] = array(
            'user_token_idx' => array(
                'col' => 'user',
                'type' => 'unique'
            )
        );
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::preSave()
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creationTime = gmdate('Y-m-d H:i:s');
            $this->expireTime = gmdate('Y-m-d H:i:s', time() + 24 * 60 * 60);
            $this->token = chunk_split(substr(md5(time() . rand(10000, 99999)), 0, 20), 6, '');
        }
    }
    
    /**
     * Check if the token is expired
     * 
     * @return boolean
     */
    public  function isExpired(){
        return false;
    }
}
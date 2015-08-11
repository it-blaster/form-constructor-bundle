<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Request\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'fc_request' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.vendor.it-blaster.form-constructor-bundle.Fenrizbes.FormConstructorBundle.Propel.Model.Request.map
 */
class FcRequestTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'vendor.it-blaster.form-constructor-bundle.Fenrizbes.FormConstructorBundle.Propel.Model.Request.map.FcRequestTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('fc_request');
        $this->setPhpName('FcRequest');
        $this->setClassname('Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Request\\FcRequest');
        $this->setPackage('vendor.it-blaster.form-constructor-bundle.Fenrizbes.FormConstructorBundle.Propel.Model.Request');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'BIGINT', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 255, null);
        $this->getColumn('title', false)->setPrimaryString(true);
        $this->addColumn('ip', 'Ip', 'VARCHAR', false, 16, null);
        $this->addForeignKey('form_id', 'FormId', 'INTEGER', 'fc_form', 'id', false, null, null);
        $this->addColumn('data', 'Data', 'OBJECT', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('FcForm', 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Form\\FcForm', RelationMap::MANY_TO_ONE, array('form_id' => 'id', ), 'SET NULL', 'CASCADE');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'event_dispatcher' =>  array (
),
        );
    } // getBehaviors()

} // FcRequestTableMap

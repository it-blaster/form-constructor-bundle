<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \EventDispatcherAwareModelInterface;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelDateTime;
use \PropelException;
use \PropelPDO;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehavior;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorAction;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionPeer;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorQuery;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

abstract class BaseFcFormBehaviorAction extends BaseObject implements Persistent, EventDispatcherAwareModelInterface
{
    /**
     * Peer class name
     */
    const PEER = 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Behavior\\FcFormBehaviorActionPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        FcFormBehaviorActionPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the behavior_id field.
     * @var        int
     */
    protected $behavior_id;

    /**
     * The value for the action field.
     * @var        string
     */
    protected $action;

    /**
     * The value for the params field.
     * @var
     */
    protected $params;

    /**
     * The unserialized $params value - i.e. the persisted object.
     * This is necessary to avoid repeated calls to unserialize() at runtime.
     * @var        object
     */
    protected $params_unserialized;

    /**
     * The value for the check field.
     * Note: this column has a database default value of: true
     * @var        boolean
     */
    protected $check;

    /**
     * The value for the is_active field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_active;

    /**
     * The value for the created_at field.
     * @var        string
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     * @var        string
     */
    protected $updated_at;

    /**
     * @var        FcFormBehavior
     */
    protected $aFcFormBehavior;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // event_dispatcher behavior

    const EVENT_CONSTRUCT = 'propel.construct';

    const EVENT_POST_HYDRATE = 'propel.post_hydrate';

    const EVENT_PRE_SAVE = 'propel.pre_save';

    const EVENT_POST_SAVE = 'propel.post_save';

    const EVENT_PRE_UPDATE = 'propel.pre_update';

    const EVENT_POST_UPDATE = 'propel.post_update';

    const EVENT_PRE_INSERT = 'propel.pre_insert';

    const EVENT_POST_INSERT = 'propel.post_insert';

    const EVENT_PRE_DELETE = 'propel.pre_delete';

    const EVENT_POST_DELETE = 'propel.post_delete';

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    static private $dispatcher = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->check = true;
        $this->is_active = false;
    }

    /**
     * Initializes internal state of BaseFcFormBehaviorAction object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
        self::getEventDispatcher()->dispatch('propel.construct', new GenericEvent($this));
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [behavior_id] column value.
     *
     * @return int
     */
    public function getBehaviorId()
    {

        return $this->behavior_id;
    }

    /**
     * Get the [action] column value.
     *
     * @return string
     */
    public function getAction()
    {

        return $this->action;
    }

    /**
     * Get the [params] column value.
     *
     * @return
     */
    public function getParams()
    {
        if (null == $this->params_unserialized && null !== $this->params) {
            $this->params_unserialized = unserialize($this->params);
        }

        return $this->params_unserialized;
    }

    /**
     * Get the [check] column value.
     *
     * @return boolean
     */
    public function getCheck()
    {

        return $this->check;
    }

    /**
     * Get the [is_active] column value.
     *
     * @return boolean
     */
    public function getIsActive()
    {

        return $this->is_active;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = null)
    {
        if ($this->created_at === null) {
            return null;
        }

        if ($this->created_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->created_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->created_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00 00:00:00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = null)
    {
        if ($this->updated_at === null) {
            return null;
        }

        if ($this->updated_at === '0000-00-00 00:00:00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->updated_at);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->updated_at, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [behavior_id] column.
     *
     * @param  int $v new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setBehaviorId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->behavior_id !== $v) {
            $this->behavior_id = $v;
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::BEHAVIOR_ID;
        }

        if ($this->aFcFormBehavior !== null && $this->aFcFormBehavior->getId() !== $v) {
            $this->aFcFormBehavior = null;
        }


        return $this;
    } // setBehaviorId()

    /**
     * Set the value of [action] column.
     *
     * @param  string $v new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setAction($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->action !== $v) {
            $this->action = $v;
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::ACTION;
        }


        return $this;
    } // setAction()

    /**
     * Set the value of [params] column.
     *
     * @param   $v new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setParams($v)
    {
        if ($this->params_unserialized !== $v) {
            $this->params_unserialized = $v;
            $this->params = serialize($v);
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::PARAMS;
        }


        return $this;
    } // setParams()

    /**
     * Sets the value of the [check] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setCheck($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->check !== $v) {
            $this->check = $v;
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::CHECK;
        }


        return $this;
    } // setCheck()

    /**
     * Sets the value of the [is_active] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setIsActive($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_active !== $v) {
            $this->is_active = $v;
            $this->modifiedColumns[] = FcFormBehaviorActionPeer::IS_ACTIVE;
        }


        return $this;
    } // setIsActive()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            $currentDateAsString = ($this->created_at !== null && $tmpDt = new DateTime($this->created_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->created_at = $newDateAsString;
                $this->modifiedColumns[] = FcFormBehaviorActionPeer::CREATED_AT;
            }
        } // if either are not null


        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return FcFormBehaviorAction The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            $currentDateAsString = ($this->updated_at !== null && $tmpDt = new DateTime($this->updated_at)) ? $tmpDt->format('Y-m-d H:i:s') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d H:i:s') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->updated_at = $newDateAsString;
                $this->modifiedColumns[] = FcFormBehaviorActionPeer::UPDATED_AT;
            }
        } // if either are not null


        return $this;
    } // setUpdatedAt()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->check !== true) {
                return false;
            }

            if ($this->is_active !== false) {
                return false;
            }

        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->behavior_id = ($row[$startcol + 1] !== null) ? (int) $row[$startcol + 1] : null;
            $this->action = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->params = $row[$startcol + 3];
            $this->check = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->is_active = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->created_at = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->updated_at = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);
            // event_dispatcher behavior
            self::getEventDispatcher()->dispatch('propel.post_hydrate', new GenericEvent($this));


            return $startcol + 8; // 8 = FcFormBehaviorActionPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating FcFormBehaviorAction object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aFcFormBehavior !== null && $this->behavior_id !== $this->aFcFormBehavior->getId()) {
            $this->aFcFormBehavior = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorActionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = FcFormBehaviorActionPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aFcFormBehavior = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorActionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = FcFormBehaviorActionQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // event_dispatcher behavior
            self::getEventDispatcher()->dispatch('propel.pre_delete', new GenericEvent($this, array('connection' => $con)));

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                // event_dispatcher behavior
                self::getEventDispatcher()->dispatch('propel.post_delete', new GenericEvent($this, array('connection' => $con)));

                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorActionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // event_dispatcher behavior
            self::getEventDispatcher()->dispatch('propel.pre_save', new GenericEvent($this, array('connection' => $con)));

            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior
                if (!$this->isColumnModified(FcFormBehaviorActionPeer::CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(FcFormBehaviorActionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event_dispatcher behavior
                self::getEventDispatcher()->dispatch('propel.pre_insert', new GenericEvent($this, array('connection' => $con)));

            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(FcFormBehaviorActionPeer::UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
                // event_dispatcher behavior
                self::getEventDispatcher()->dispatch('propel.pre_update', new GenericEvent($this, array('connection' => $con)));

            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                    // event_dispatcher behavior
                    self::getEventDispatcher()->dispatch('propel.post_insert', new GenericEvent($this, array('connection' => $con)));

                } else {
                    $this->postUpdate($con);
                    // event_dispatcher behavior
                    self::getEventDispatcher()->dispatch('propel.post_update', new GenericEvent($this, array('connection' => $con)));

                }
                $this->postSave($con);
                // event_dispatcher behavior
                self::getEventDispatcher()->dispatch('propel.post_save', new GenericEvent($this, array('connection' => $con)));

                FcFormBehaviorActionPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aFcFormBehavior !== null) {
                if ($this->aFcFormBehavior->isModified() || $this->aFcFormBehavior->isNew()) {
                    $affectedRows += $this->aFcFormBehavior->save($con);
                }
                $this->setFcFormBehavior($this->aFcFormBehavior);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = FcFormBehaviorActionPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FcFormBehaviorActionPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FcFormBehaviorActionPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::BEHAVIOR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`behavior_id`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::ACTION)) {
            $modifiedColumns[':p' . $index++]  = '`action`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::PARAMS)) {
            $modifiedColumns[':p' . $index++]  = '`params`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::CHECK)) {
            $modifiedColumns[':p' . $index++]  = '`check`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::IS_ACTIVE)) {
            $modifiedColumns[':p' . $index++]  = '`is_active`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`created_at`';
        }
        if ($this->isColumnModified(FcFormBehaviorActionPeer::UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = '`updated_at`';
        }

        $sql = sprintf(
            'INSERT INTO `fc_form_behavior_action` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`behavior_id`':
                        $stmt->bindValue($identifier, $this->behavior_id, PDO::PARAM_INT);
                        break;
                    case '`action`':
                        $stmt->bindValue($identifier, $this->action, PDO::PARAM_STR);
                        break;
                    case '`params`':
                        $stmt->bindValue($identifier, $this->params, PDO::PARAM_STR);
                        break;
                    case '`check`':
                        $stmt->bindValue($identifier, (int) $this->check, PDO::PARAM_INT);
                        break;
                    case '`is_active`':
                        $stmt->bindValue($identifier, (int) $this->is_active, PDO::PARAM_INT);
                        break;
                    case '`created_at`':
                        $stmt->bindValue($identifier, $this->created_at, PDO::PARAM_STR);
                        break;
                    case '`updated_at`':
                        $stmt->bindValue($identifier, $this->updated_at, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aFcFormBehavior !== null) {
                if (!$this->aFcFormBehavior->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFcFormBehavior->getValidationFailures());
                }
            }


            if (($retval = FcFormBehaviorActionPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }



            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = FcFormBehaviorActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getBehaviorId();
                break;
            case 2:
                return $this->getAction();
                break;
            case 3:
                return $this->getParams();
                break;
            case 4:
                return $this->getCheck();
                break;
            case 5:
                return $this->getIsActive();
                break;
            case 6:
                return $this->getCreatedAt();
                break;
            case 7:
                return $this->getUpdatedAt();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['FcFormBehaviorAction'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['FcFormBehaviorAction'][$this->getPrimaryKey()] = true;
        $keys = FcFormBehaviorActionPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getBehaviorId(),
            $keys[2] => $this->getAction(),
            $keys[3] => $this->getParams(),
            $keys[4] => $this->getCheck(),
            $keys[5] => $this->getIsActive(),
            $keys[6] => $this->getCreatedAt(),
            $keys[7] => $this->getUpdatedAt(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aFcFormBehavior) {
                $result['FcFormBehavior'] = $this->aFcFormBehavior->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = FcFormBehaviorActionPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setBehaviorId($value);
                break;
            case 2:
                $this->setAction($value);
                break;
            case 3:
                $this->setParams($value);
                break;
            case 4:
                $this->setCheck($value);
                break;
            case 5:
                $this->setIsActive($value);
                break;
            case 6:
                $this->setCreatedAt($value);
                break;
            case 7:
                $this->setUpdatedAt($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = FcFormBehaviorActionPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setBehaviorId($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setAction($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setParams($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setCheck($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setIsActive($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setCreatedAt($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setUpdatedAt($arr[$keys[7]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(FcFormBehaviorActionPeer::DATABASE_NAME);

        if ($this->isColumnModified(FcFormBehaviorActionPeer::ID)) $criteria->add(FcFormBehaviorActionPeer::ID, $this->id);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::BEHAVIOR_ID)) $criteria->add(FcFormBehaviorActionPeer::BEHAVIOR_ID, $this->behavior_id);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::ACTION)) $criteria->add(FcFormBehaviorActionPeer::ACTION, $this->action);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::PARAMS)) $criteria->add(FcFormBehaviorActionPeer::PARAMS, $this->params);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::CHECK)) $criteria->add(FcFormBehaviorActionPeer::CHECK, $this->check);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::IS_ACTIVE)) $criteria->add(FcFormBehaviorActionPeer::IS_ACTIVE, $this->is_active);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::CREATED_AT)) $criteria->add(FcFormBehaviorActionPeer::CREATED_AT, $this->created_at);
        if ($this->isColumnModified(FcFormBehaviorActionPeer::UPDATED_AT)) $criteria->add(FcFormBehaviorActionPeer::UPDATED_AT, $this->updated_at);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(FcFormBehaviorActionPeer::DATABASE_NAME);
        $criteria->add(FcFormBehaviorActionPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of FcFormBehaviorAction (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setBehaviorId($this->getBehaviorId());
        $copyObj->setAction($this->getAction());
        $copyObj->setParams($this->getParams());
        $copyObj->setCheck($this->getCheck());
        $copyObj->setIsActive($this->getIsActive());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return FcFormBehaviorAction Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return FcFormBehaviorActionPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new FcFormBehaviorActionPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a FcFormBehavior object.
     *
     * @param                  FcFormBehavior $v
     * @return FcFormBehaviorAction The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFcFormBehavior(FcFormBehavior $v = null)
    {
        if ($v === null) {
            $this->setBehaviorId(NULL);
        } else {
            $this->setBehaviorId($v->getId());
        }

        $this->aFcFormBehavior = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FcFormBehavior object, it will not be re-added.
        if ($v !== null) {
            $v->addFcFormBehaviorAction($this);
        }


        return $this;
    }


    /**
     * Get the associated FcFormBehavior object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return FcFormBehavior The associated FcFormBehavior object.
     * @throws PropelException
     */
    public function getFcFormBehavior(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFcFormBehavior === null && ($this->behavior_id !== null) && $doQuery) {
            $this->aFcFormBehavior = FcFormBehaviorQuery::create()->findPk($this->behavior_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFcFormBehavior->addFcFormBehaviorActions($this);
             */
        }

        return $this->aFcFormBehavior;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->behavior_id = null;
        $this->action = null;
        $this->params = null;
        $this->params_unserialized = null;
        $this->check = null;
        $this->is_active = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->aFcFormBehavior instanceof Persistent) {
              $this->aFcFormBehavior->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        $this->aFcFormBehavior = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(FcFormBehaviorActionPeer::DEFAULT_STRING_FORMAT);
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     FcFormBehaviorAction The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[] = FcFormBehaviorActionPeer::UPDATED_AT;

        return $this;
    }

    // event_dispatcher behavior

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    static public function getEventDispatcher()
    {
        if (null === self::$dispatcher) {
            self::setEventDispatcher(new EventDispatcher());
        }

        return self::$dispatcher;
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    static public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        self::$dispatcher = $eventDispatcher;
    }

}

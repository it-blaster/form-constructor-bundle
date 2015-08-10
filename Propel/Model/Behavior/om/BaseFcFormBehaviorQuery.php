<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehavior;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorAction;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorPeer;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorQuery;
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;

/**
 * @method FcFormBehaviorQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FcFormBehaviorQuery orderByFormId($order = Criteria::ASC) Order by the form_id column
 * @method FcFormBehaviorQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method FcFormBehaviorQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FcFormBehaviorQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FcFormBehaviorQuery groupById() Group by the id column
 * @method FcFormBehaviorQuery groupByFormId() Group by the form_id column
 * @method FcFormBehaviorQuery groupByIsActive() Group by the is_active column
 * @method FcFormBehaviorQuery groupByCreatedAt() Group by the created_at column
 * @method FcFormBehaviorQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FcFormBehaviorQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FcFormBehaviorQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FcFormBehaviorQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FcFormBehaviorQuery leftJoinFcForm($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcForm relation
 * @method FcFormBehaviorQuery rightJoinFcForm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcForm relation
 * @method FcFormBehaviorQuery innerJoinFcForm($relationAlias = null) Adds a INNER JOIN clause to the query using the FcForm relation
 *
 * @method FcFormBehaviorQuery leftJoinFcFormBehaviorCondition($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcFormBehaviorCondition relation
 * @method FcFormBehaviorQuery rightJoinFcFormBehaviorCondition($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcFormBehaviorCondition relation
 * @method FcFormBehaviorQuery innerJoinFcFormBehaviorCondition($relationAlias = null) Adds a INNER JOIN clause to the query using the FcFormBehaviorCondition relation
 *
 * @method FcFormBehaviorQuery leftJoinFcFormBehaviorAction($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcFormBehaviorAction relation
 * @method FcFormBehaviorQuery rightJoinFcFormBehaviorAction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcFormBehaviorAction relation
 * @method FcFormBehaviorQuery innerJoinFcFormBehaviorAction($relationAlias = null) Adds a INNER JOIN clause to the query using the FcFormBehaviorAction relation
 *
 * @method FcFormBehavior findOne(PropelPDO $con = null) Return the first FcFormBehavior matching the query
 * @method FcFormBehavior findOneOrCreate(PropelPDO $con = null) Return the first FcFormBehavior matching the query, or a new FcFormBehavior object populated from the query conditions when no match is found
 *
 * @method FcFormBehavior findOneByFormId(int $form_id) Return the first FcFormBehavior filtered by the form_id column
 * @method FcFormBehavior findOneByIsActive(boolean $is_active) Return the first FcFormBehavior filtered by the is_active column
 * @method FcFormBehavior findOneByCreatedAt(string $created_at) Return the first FcFormBehavior filtered by the created_at column
 * @method FcFormBehavior findOneByUpdatedAt(string $updated_at) Return the first FcFormBehavior filtered by the updated_at column
 *
 * @method array findById(int $id) Return FcFormBehavior objects filtered by the id column
 * @method array findByFormId(int $form_id) Return FcFormBehavior objects filtered by the form_id column
 * @method array findByIsActive(boolean $is_active) Return FcFormBehavior objects filtered by the is_active column
 * @method array findByCreatedAt(string $created_at) Return FcFormBehavior objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return FcFormBehavior objects filtered by the updated_at column
 */
abstract class BaseFcFormBehaviorQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFcFormBehaviorQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Behavior\\FcFormBehavior';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FcFormBehaviorQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FcFormBehaviorQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FcFormBehaviorQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FcFormBehaviorQuery) {
            return $criteria;
        }
        $query = new FcFormBehaviorQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   FcFormBehavior|FcFormBehavior[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FcFormBehaviorPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 FcFormBehavior A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 FcFormBehavior A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `form_id`, `is_active`, `created_at`, `updated_at` FROM `fc_form_behavior` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new FcFormBehavior();
            $obj->hydrate($row);
            FcFormBehaviorPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return FcFormBehavior|FcFormBehavior[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|FcFormBehavior[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FcFormBehaviorPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FcFormBehaviorPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the form_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFormId(1234); // WHERE form_id = 1234
     * $query->filterByFormId(array(12, 34)); // WHERE form_id IN (12, 34)
     * $query->filterByFormId(array('min' => 12)); // WHERE form_id >= 12
     * $query->filterByFormId(array('max' => 12)); // WHERE form_id <= 12
     * </code>
     *
     * @see       filterByFcForm()
     *
     * @param     mixed $formId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByFormId($formId = null, $comparison = null)
    {
        if (is_array($formId)) {
            $useMinMax = false;
            if (isset($formId['min'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::FORM_ID, $formId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($formId['max'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::FORM_ID, $formId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorPeer::FORM_ID, $formId, $comparison);
    }

    /**
     * Filter the query on the is_active column
     *
     * Example usage:
     * <code>
     * $query->filterByIsActive(true); // WHERE is_active = true
     * $query->filterByIsActive('yes'); // WHERE is_active = true
     * </code>
     *
     * @param     boolean|string $isActive The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $isActive = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FcFormBehaviorPeer::IS_ACTIVE, $isActive, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related FcForm object
     *
     * @param   FcForm|PropelObjectCollection $fcForm The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcFormBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcForm($fcForm, $comparison = null)
    {
        if ($fcForm instanceof FcForm) {
            return $this
                ->addUsingAlias(FcFormBehaviorPeer::FORM_ID, $fcForm->getId(), $comparison);
        } elseif ($fcForm instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FcFormBehaviorPeer::FORM_ID, $fcForm->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFcForm() only accepts arguments of type FcForm or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FcForm relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function joinFcForm($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FcForm');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FcForm');
        }

        return $this;
    }

    /**
     * Use the FcForm relation FcForm object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery A secondary query class using the current class as primary query
     */
    public function useFcFormQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFcForm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FcForm', '\Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery');
    }

    /**
     * Filter the query by a related FcFormBehaviorCondition object
     *
     * @param   FcFormBehaviorCondition|PropelObjectCollection $fcFormBehaviorCondition  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcFormBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcFormBehaviorCondition($fcFormBehaviorCondition, $comparison = null)
    {
        if ($fcFormBehaviorCondition instanceof FcFormBehaviorCondition) {
            return $this
                ->addUsingAlias(FcFormBehaviorPeer::ID, $fcFormBehaviorCondition->getBehaviorId(), $comparison);
        } elseif ($fcFormBehaviorCondition instanceof PropelObjectCollection) {
            return $this
                ->useFcFormBehaviorConditionQuery()
                ->filterByPrimaryKeys($fcFormBehaviorCondition->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFcFormBehaviorCondition() only accepts arguments of type FcFormBehaviorCondition or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FcFormBehaviorCondition relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function joinFcFormBehaviorCondition($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FcFormBehaviorCondition');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FcFormBehaviorCondition');
        }

        return $this;
    }

    /**
     * Use the FcFormBehaviorCondition relation FcFormBehaviorCondition object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorConditionQuery A secondary query class using the current class as primary query
     */
    public function useFcFormBehaviorConditionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFcFormBehaviorCondition($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FcFormBehaviorCondition', '\Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorConditionQuery');
    }

    /**
     * Filter the query by a related FcFormBehaviorAction object
     *
     * @param   FcFormBehaviorAction|PropelObjectCollection $fcFormBehaviorAction  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcFormBehaviorQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcFormBehaviorAction($fcFormBehaviorAction, $comparison = null)
    {
        if ($fcFormBehaviorAction instanceof FcFormBehaviorAction) {
            return $this
                ->addUsingAlias(FcFormBehaviorPeer::ID, $fcFormBehaviorAction->getBehaviorId(), $comparison);
        } elseif ($fcFormBehaviorAction instanceof PropelObjectCollection) {
            return $this
                ->useFcFormBehaviorActionQuery()
                ->filterByPrimaryKeys($fcFormBehaviorAction->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFcFormBehaviorAction() only accepts arguments of type FcFormBehaviorAction or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FcFormBehaviorAction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function joinFcFormBehaviorAction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FcFormBehaviorAction');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FcFormBehaviorAction');
        }

        return $this;
    }

    /**
     * Use the FcFormBehaviorAction relation FcFormBehaviorAction object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionQuery A secondary query class using the current class as primary query
     */
    public function useFcFormBehaviorActionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFcFormBehaviorAction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FcFormBehaviorAction', '\Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   FcFormBehavior $fcFormBehavior Object to remove from the list of results
     *
     * @return FcFormBehaviorQuery The current query, for fluid interface
     */
    public function prune($fcFormBehavior = null)
    {
        if ($fcFormBehavior) {
            $this->addUsingAlias(FcFormBehaviorPeer::ID, $fcFormBehavior->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FcFormBehaviorQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorPeer::CREATED_AT);
    }
}

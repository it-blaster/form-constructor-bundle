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
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorCondition;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorConditionPeer;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorConditionQuery;

/**
 * @method FcFormBehaviorConditionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FcFormBehaviorConditionQuery orderByBehaviorId($order = Criteria::ASC) Order by the behavior_id column
 * @method FcFormBehaviorConditionQuery orderByCondition($order = Criteria::ASC) Order by the condition column
 * @method FcFormBehaviorConditionQuery orderByParams($order = Criteria::ASC) Order by the params column
 * @method FcFormBehaviorConditionQuery orderByOperator($order = Criteria::ASC) Order by the operator column
 * @method FcFormBehaviorConditionQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method FcFormBehaviorConditionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FcFormBehaviorConditionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FcFormBehaviorConditionQuery groupById() Group by the id column
 * @method FcFormBehaviorConditionQuery groupByBehaviorId() Group by the behavior_id column
 * @method FcFormBehaviorConditionQuery groupByCondition() Group by the condition column
 * @method FcFormBehaviorConditionQuery groupByParams() Group by the params column
 * @method FcFormBehaviorConditionQuery groupByOperator() Group by the operator column
 * @method FcFormBehaviorConditionQuery groupByIsActive() Group by the is_active column
 * @method FcFormBehaviorConditionQuery groupByCreatedAt() Group by the created_at column
 * @method FcFormBehaviorConditionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FcFormBehaviorConditionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FcFormBehaviorConditionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FcFormBehaviorConditionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FcFormBehaviorConditionQuery leftJoinFcFormBehavior($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcFormBehavior relation
 * @method FcFormBehaviorConditionQuery rightJoinFcFormBehavior($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcFormBehavior relation
 * @method FcFormBehaviorConditionQuery innerJoinFcFormBehavior($relationAlias = null) Adds a INNER JOIN clause to the query using the FcFormBehavior relation
 *
 * @method FcFormBehaviorCondition findOne(PropelPDO $con = null) Return the first FcFormBehaviorCondition matching the query
 * @method FcFormBehaviorCondition findOneOrCreate(PropelPDO $con = null) Return the first FcFormBehaviorCondition matching the query, or a new FcFormBehaviorCondition object populated from the query conditions when no match is found
 *
 * @method FcFormBehaviorCondition findOneByBehaviorId(int $behavior_id) Return the first FcFormBehaviorCondition filtered by the behavior_id column
 * @method FcFormBehaviorCondition findOneByCondition(string $condition) Return the first FcFormBehaviorCondition filtered by the condition column
 * @method FcFormBehaviorCondition findOneByParams( $params) Return the first FcFormBehaviorCondition filtered by the params column
 * @method FcFormBehaviorCondition findOneByOperator(boolean $operator) Return the first FcFormBehaviorCondition filtered by the operator column
 * @method FcFormBehaviorCondition findOneByIsActive(boolean $is_active) Return the first FcFormBehaviorCondition filtered by the is_active column
 * @method FcFormBehaviorCondition findOneByCreatedAt(string $created_at) Return the first FcFormBehaviorCondition filtered by the created_at column
 * @method FcFormBehaviorCondition findOneByUpdatedAt(string $updated_at) Return the first FcFormBehaviorCondition filtered by the updated_at column
 *
 * @method array findById(int $id) Return FcFormBehaviorCondition objects filtered by the id column
 * @method array findByBehaviorId(int $behavior_id) Return FcFormBehaviorCondition objects filtered by the behavior_id column
 * @method array findByCondition(string $condition) Return FcFormBehaviorCondition objects filtered by the condition column
 * @method array findByParams( $params) Return FcFormBehaviorCondition objects filtered by the params column
 * @method array findByOperator(boolean $operator) Return FcFormBehaviorCondition objects filtered by the operator column
 * @method array findByIsActive(boolean $is_active) Return FcFormBehaviorCondition objects filtered by the is_active column
 * @method array findByCreatedAt(string $created_at) Return FcFormBehaviorCondition objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return FcFormBehaviorCondition objects filtered by the updated_at column
 */
abstract class BaseFcFormBehaviorConditionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFcFormBehaviorConditionQuery object.
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
            $modelName = 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Behavior\\FcFormBehaviorCondition';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FcFormBehaviorConditionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FcFormBehaviorConditionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FcFormBehaviorConditionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FcFormBehaviorConditionQuery) {
            return $criteria;
        }
        $query = new FcFormBehaviorConditionQuery(null, null, $modelAlias);

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
     * @return   FcFormBehaviorCondition|FcFormBehaviorCondition[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FcFormBehaviorConditionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorConditionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 FcFormBehaviorCondition A model object, or null if the key is not found
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
     * @return                 FcFormBehaviorCondition A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `behavior_id`, `condition`, `params`, `operator`, `is_active`, `created_at`, `updated_at` FROM `fc_form_behavior_condition` WHERE `id` = :p0';
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
            $obj = new FcFormBehaviorCondition();
            $obj->hydrate($row);
            FcFormBehaviorConditionPeer::addInstanceToPool($obj, (string) $key);
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
     * @return FcFormBehaviorCondition|FcFormBehaviorCondition[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|FcFormBehaviorCondition[]|mixed the list of results, formatted by the current formatter
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
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $keys, Criteria::IN);
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
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the behavior_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBehaviorId(1234); // WHERE behavior_id = 1234
     * $query->filterByBehaviorId(array(12, 34)); // WHERE behavior_id IN (12, 34)
     * $query->filterByBehaviorId(array('min' => 12)); // WHERE behavior_id >= 12
     * $query->filterByBehaviorId(array('max' => 12)); // WHERE behavior_id <= 12
     * </code>
     *
     * @see       filterByFcFormBehavior()
     *
     * @param     mixed $behaviorId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByBehaviorId($behaviorId = null, $comparison = null)
    {
        if (is_array($behaviorId)) {
            $useMinMax = false;
            if (isset($behaviorId['min'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::BEHAVIOR_ID, $behaviorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($behaviorId['max'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::BEHAVIOR_ID, $behaviorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::BEHAVIOR_ID, $behaviorId, $comparison);
    }

    /**
     * Filter the query on the condition column
     *
     * Example usage:
     * <code>
     * $query->filterByCondition('fooValue');   // WHERE condition = 'fooValue'
     * $query->filterByCondition('%fooValue%'); // WHERE condition LIKE '%fooValue%'
     * </code>
     *
     * @param     string $condition The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByCondition($condition = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($condition)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $condition)) {
                $condition = str_replace('*', '%', $condition);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::CONDITION, $condition, $comparison);
    }

    /**
     * Filter the query on the params column
     *
     * @param     mixed $params The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByParams($params = null, $comparison = null)
    {
        if (is_object($params)) {
            $params = serialize($params);
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::PARAMS, $params, $comparison);
    }

    /**
     * Filter the query on the operator column
     *
     * Example usage:
     * <code>
     * $query->filterByOperator(true); // WHERE operator = true
     * $query->filterByOperator('yes'); // WHERE operator = true
     * </code>
     *
     * @param     boolean|string $operator The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByOperator($operator = null, $comparison = null)
    {
        if (is_string($operator)) {
            $operator = in_array(strtolower($operator), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::OPERATOR, $operator, $comparison);
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
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $isActive = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::IS_ACTIVE, $isActive, $comparison);
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
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorConditionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorConditionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related FcFormBehavior object
     *
     * @param   FcFormBehavior|PropelObjectCollection $fcFormBehavior The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcFormBehaviorConditionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcFormBehavior($fcFormBehavior, $comparison = null)
    {
        if ($fcFormBehavior instanceof FcFormBehavior) {
            return $this
                ->addUsingAlias(FcFormBehaviorConditionPeer::BEHAVIOR_ID, $fcFormBehavior->getId(), $comparison);
        } elseif ($fcFormBehavior instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FcFormBehaviorConditionPeer::BEHAVIOR_ID, $fcFormBehavior->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFcFormBehavior() only accepts arguments of type FcFormBehavior or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FcFormBehavior relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function joinFcFormBehavior($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FcFormBehavior');

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
            $this->addJoinObject($join, 'FcFormBehavior');
        }

        return $this;
    }

    /**
     * Use the FcFormBehavior relation FcFormBehavior object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorQuery A secondary query class using the current class as primary query
     */
    public function useFcFormBehaviorQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinFcFormBehavior($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FcFormBehavior', '\Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   FcFormBehaviorCondition $fcFormBehaviorCondition Object to remove from the list of results
     *
     * @return FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function prune($fcFormBehaviorCondition = null)
    {
        if ($fcFormBehaviorCondition) {
            $this->addUsingAlias(FcFormBehaviorConditionPeer::ID, $fcFormBehaviorCondition->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorConditionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorConditionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorConditionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorConditionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorConditionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FcFormBehaviorConditionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorConditionPeer::CREATED_AT);
    }
}

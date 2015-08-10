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
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionPeer;
use Fenrizbes\FormConstructorBundle\Propel\Model\Behavior\FcFormBehaviorActionQuery;

/**
 * @method FcFormBehaviorActionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FcFormBehaviorActionQuery orderByBehaviorId($order = Criteria::ASC) Order by the behavior_id column
 * @method FcFormBehaviorActionQuery orderByAction($order = Criteria::ASC) Order by the action column
 * @method FcFormBehaviorActionQuery orderByParams($order = Criteria::ASC) Order by the params column
 * @method FcFormBehaviorActionQuery orderByCheck($order = Criteria::ASC) Order by the check column
 * @method FcFormBehaviorActionQuery orderByIsActive($order = Criteria::ASC) Order by the is_active column
 * @method FcFormBehaviorActionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FcFormBehaviorActionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FcFormBehaviorActionQuery groupById() Group by the id column
 * @method FcFormBehaviorActionQuery groupByBehaviorId() Group by the behavior_id column
 * @method FcFormBehaviorActionQuery groupByAction() Group by the action column
 * @method FcFormBehaviorActionQuery groupByParams() Group by the params column
 * @method FcFormBehaviorActionQuery groupByCheck() Group by the check column
 * @method FcFormBehaviorActionQuery groupByIsActive() Group by the is_active column
 * @method FcFormBehaviorActionQuery groupByCreatedAt() Group by the created_at column
 * @method FcFormBehaviorActionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FcFormBehaviorActionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FcFormBehaviorActionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FcFormBehaviorActionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FcFormBehaviorActionQuery leftJoinFcFormBehavior($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcFormBehavior relation
 * @method FcFormBehaviorActionQuery rightJoinFcFormBehavior($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcFormBehavior relation
 * @method FcFormBehaviorActionQuery innerJoinFcFormBehavior($relationAlias = null) Adds a INNER JOIN clause to the query using the FcFormBehavior relation
 *
 * @method FcFormBehaviorAction findOne(PropelPDO $con = null) Return the first FcFormBehaviorAction matching the query
 * @method FcFormBehaviorAction findOneOrCreate(PropelPDO $con = null) Return the first FcFormBehaviorAction matching the query, or a new FcFormBehaviorAction object populated from the query conditions when no match is found
 *
 * @method FcFormBehaviorAction findOneByBehaviorId(int $behavior_id) Return the first FcFormBehaviorAction filtered by the behavior_id column
 * @method FcFormBehaviorAction findOneByAction(string $action) Return the first FcFormBehaviorAction filtered by the action column
 * @method FcFormBehaviorAction findOneByParams( $params) Return the first FcFormBehaviorAction filtered by the params column
 * @method FcFormBehaviorAction findOneByCheck(boolean $check) Return the first FcFormBehaviorAction filtered by the check column
 * @method FcFormBehaviorAction findOneByIsActive(boolean $is_active) Return the first FcFormBehaviorAction filtered by the is_active column
 * @method FcFormBehaviorAction findOneByCreatedAt(string $created_at) Return the first FcFormBehaviorAction filtered by the created_at column
 * @method FcFormBehaviorAction findOneByUpdatedAt(string $updated_at) Return the first FcFormBehaviorAction filtered by the updated_at column
 *
 * @method array findById(int $id) Return FcFormBehaviorAction objects filtered by the id column
 * @method array findByBehaviorId(int $behavior_id) Return FcFormBehaviorAction objects filtered by the behavior_id column
 * @method array findByAction(string $action) Return FcFormBehaviorAction objects filtered by the action column
 * @method array findByParams( $params) Return FcFormBehaviorAction objects filtered by the params column
 * @method array findByCheck(boolean $check) Return FcFormBehaviorAction objects filtered by the check column
 * @method array findByIsActive(boolean $is_active) Return FcFormBehaviorAction objects filtered by the is_active column
 * @method array findByCreatedAt(string $created_at) Return FcFormBehaviorAction objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return FcFormBehaviorAction objects filtered by the updated_at column
 */
abstract class BaseFcFormBehaviorActionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFcFormBehaviorActionQuery object.
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
            $modelName = 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Behavior\\FcFormBehaviorAction';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FcFormBehaviorActionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FcFormBehaviorActionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FcFormBehaviorActionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FcFormBehaviorActionQuery) {
            return $criteria;
        }
        $query = new FcFormBehaviorActionQuery(null, null, $modelAlias);

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
     * @return   FcFormBehaviorAction|FcFormBehaviorAction[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FcFormBehaviorActionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FcFormBehaviorActionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 FcFormBehaviorAction A model object, or null if the key is not found
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
     * @return                 FcFormBehaviorAction A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `behavior_id`, `action`, `params`, `check`, `is_active`, `created_at`, `updated_at` FROM `fc_form_behavior_action` WHERE `id` = :p0';
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
            $obj = new FcFormBehaviorAction();
            $obj->hydrate($row);
            FcFormBehaviorActionPeer::addInstanceToPool($obj, (string) $key);
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
     * @return FcFormBehaviorAction|FcFormBehaviorAction[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|FcFormBehaviorAction[]|mixed the list of results, formatted by the current formatter
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $keys, Criteria::IN);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $id, $comparison);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByBehaviorId($behaviorId = null, $comparison = null)
    {
        if (is_array($behaviorId)) {
            $useMinMax = false;
            if (isset($behaviorId['min'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::BEHAVIOR_ID, $behaviorId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($behaviorId['max'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::BEHAVIOR_ID, $behaviorId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::BEHAVIOR_ID, $behaviorId, $comparison);
    }

    /**
     * Filter the query on the action column
     *
     * Example usage:
     * <code>
     * $query->filterByAction('fooValue');   // WHERE action = 'fooValue'
     * $query->filterByAction('%fooValue%'); // WHERE action LIKE '%fooValue%'
     * </code>
     *
     * @param     string $action The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByAction($action = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($action)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $action)) {
                $action = str_replace('*', '%', $action);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::ACTION, $action, $comparison);
    }

    /**
     * Filter the query on the params column
     *
     * @param     mixed $params The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByParams($params = null, $comparison = null)
    {
        if (is_object($params)) {
            $params = serialize($params);
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::PARAMS, $params, $comparison);
    }

    /**
     * Filter the query on the check column
     *
     * Example usage:
     * <code>
     * $query->filterByCheck(true); // WHERE check = true
     * $query->filterByCheck('yes'); // WHERE check = true
     * </code>
     *
     * @param     boolean|string $check The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByCheck($check = null, $comparison = null)
    {
        if (is_string($check)) {
            $check = in_array(strtolower($check), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::CHECK, $check, $comparison);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByIsActive($isActive = null, $comparison = null)
    {
        if (is_string($isActive)) {
            $isActive = in_array(strtolower($isActive), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::IS_ACTIVE, $isActive, $comparison);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FcFormBehaviorActionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcFormBehaviorActionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related FcFormBehavior object
     *
     * @param   FcFormBehavior|PropelObjectCollection $fcFormBehavior The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcFormBehaviorActionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcFormBehavior($fcFormBehavior, $comparison = null)
    {
        if ($fcFormBehavior instanceof FcFormBehavior) {
            return $this
                ->addUsingAlias(FcFormBehaviorActionPeer::BEHAVIOR_ID, $fcFormBehavior->getId(), $comparison);
        } elseif ($fcFormBehavior instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FcFormBehaviorActionPeer::BEHAVIOR_ID, $fcFormBehavior->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
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
     * @param   FcFormBehaviorAction $fcFormBehaviorAction Object to remove from the list of results
     *
     * @return FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function prune($fcFormBehaviorAction = null)
    {
        if ($fcFormBehaviorAction) {
            $this->addUsingAlias(FcFormBehaviorActionPeer::ID, $fcFormBehaviorAction->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorActionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorActionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorActionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FcFormBehaviorActionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcFormBehaviorActionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FcFormBehaviorActionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcFormBehaviorActionPeer::CREATED_AT);
    }
}
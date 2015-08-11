<?php

namespace Fenrizbes\FormConstructorBundle\Propel\Model\Request\om;

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
use Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcForm;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequest;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestPeer;
use Fenrizbes\FormConstructorBundle\Propel\Model\Request\FcRequestQuery;

/**
 * @method FcRequestQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FcRequestQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method FcRequestQuery orderByIp($order = Criteria::ASC) Order by the ip column
 * @method FcRequestQuery orderByFormId($order = Criteria::ASC) Order by the form_id column
 * @method FcRequestQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method FcRequestQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method FcRequestQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method FcRequestQuery groupById() Group by the id column
 * @method FcRequestQuery groupByTitle() Group by the title column
 * @method FcRequestQuery groupByIp() Group by the ip column
 * @method FcRequestQuery groupByFormId() Group by the form_id column
 * @method FcRequestQuery groupByData() Group by the data column
 * @method FcRequestQuery groupByCreatedAt() Group by the created_at column
 * @method FcRequestQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method FcRequestQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FcRequestQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FcRequestQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FcRequestQuery leftJoinFcForm($relationAlias = null) Adds a LEFT JOIN clause to the query using the FcForm relation
 * @method FcRequestQuery rightJoinFcForm($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FcForm relation
 * @method FcRequestQuery innerJoinFcForm($relationAlias = null) Adds a INNER JOIN clause to the query using the FcForm relation
 *
 * @method FcRequest findOne(PropelPDO $con = null) Return the first FcRequest matching the query
 * @method FcRequest findOneOrCreate(PropelPDO $con = null) Return the first FcRequest matching the query, or a new FcRequest object populated from the query conditions when no match is found
 *
 * @method FcRequest findOneByTitle(string $title) Return the first FcRequest filtered by the title column
 * @method FcRequest findOneByIp(string $ip) Return the first FcRequest filtered by the ip column
 * @method FcRequest findOneByFormId(int $form_id) Return the first FcRequest filtered by the form_id column
 * @method FcRequest findOneByData( $data) Return the first FcRequest filtered by the data column
 * @method FcRequest findOneByCreatedAt(string $created_at) Return the first FcRequest filtered by the created_at column
 * @method FcRequest findOneByUpdatedAt(string $updated_at) Return the first FcRequest filtered by the updated_at column
 *
 * @method array findById(string $id) Return FcRequest objects filtered by the id column
 * @method array findByTitle(string $title) Return FcRequest objects filtered by the title column
 * @method array findByIp(string $ip) Return FcRequest objects filtered by the ip column
 * @method array findByFormId(int $form_id) Return FcRequest objects filtered by the form_id column
 * @method array findByData( $data) Return FcRequest objects filtered by the data column
 * @method array findByCreatedAt(string $created_at) Return FcRequest objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return FcRequest objects filtered by the updated_at column
 */
abstract class BaseFcRequestQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFcRequestQuery object.
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
            $modelName = 'Fenrizbes\\FormConstructorBundle\\Propel\\Model\\Request\\FcRequest';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FcRequestQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FcRequestQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FcRequestQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FcRequestQuery) {
            return $criteria;
        }
        $query = new FcRequestQuery(null, null, $modelAlias);

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
     * @return   FcRequest|FcRequest[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FcRequestPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FcRequestPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 FcRequest A model object, or null if the key is not found
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
     * @return                 FcRequest A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `ip`, `form_id`, `data`, `created_at`, `updated_at` FROM `fc_request` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_STR);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new FcRequest();
            $obj->hydrate($row);
            FcRequestPeer::addInstanceToPool($obj, (string) $key);
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
     * @return FcRequest|FcRequest[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|FcRequest[]|mixed the list of results, formatted by the current formatter
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FcRequestPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FcRequestPeer::ID, $keys, Criteria::IN);
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FcRequestPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FcRequestPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the ip column
     *
     * Example usage:
     * <code>
     * $query->filterByIp('fooValue');   // WHERE ip = 'fooValue'
     * $query->filterByIp('%fooValue%'); // WHERE ip LIKE '%fooValue%'
     * </code>
     *
     * @param     string $ip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByIp($ip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($ip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $ip)) {
                $ip = str_replace('*', '%', $ip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::IP, $ip, $comparison);
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByFormId($formId = null, $comparison = null)
    {
        if (is_array($formId)) {
            $useMinMax = false;
            if (isset($formId['min'])) {
                $this->addUsingAlias(FcRequestPeer::FORM_ID, $formId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($formId['max'])) {
                $this->addUsingAlias(FcRequestPeer::FORM_ID, $formId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::FORM_ID, $formId, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * @param     mixed $data The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {
        if (is_object($data)) {
            $data = serialize($data);
        }

        return $this->addUsingAlias(FcRequestPeer::DATA, $data, $comparison);
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(FcRequestPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(FcRequestPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(FcRequestPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(FcRequestPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FcRequestPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related FcForm object
     *
     * @param   FcForm|PropelObjectCollection $fcForm The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FcRequestQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFcForm($fcForm, $comparison = null)
    {
        if ($fcForm instanceof FcForm) {
            return $this
                ->addUsingAlias(FcRequestPeer::FORM_ID, $fcForm->getId(), $comparison);
        } elseif ($fcForm instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FcRequestPeer::FORM_ID, $fcForm->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function joinFcForm($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function useFcFormQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFcForm($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FcForm', '\Fenrizbes\FormConstructorBundle\Propel\Model\Form\FcFormQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   FcRequest $fcRequest Object to remove from the list of results
     *
     * @return FcRequestQuery The current query, for fluid interface
     */
    public function prune($fcRequest = null)
    {
        if ($fcRequest) {
            $this->addUsingAlias(FcRequestPeer::ID, $fcRequest->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(FcRequestPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcRequestPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcRequestPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(FcRequestPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(FcRequestPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     FcRequestQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(FcRequestPeer::CREATED_AT);
    }
}

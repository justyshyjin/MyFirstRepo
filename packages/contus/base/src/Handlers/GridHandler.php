<?php

/**
 * Trait for GridSearch
 *
 * @name GridSearch
 * @vendor Contus
 * @package Base
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Handlers;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Exception;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Exceptions\GridException;

trait GridHandler {
    /**
     * Class property to hold the grid model
     *
     * @vendor Contus
     *
     * @package Base
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $gridModel = null;
    /**
     * Class property to hold the related model loaded in eagarLoading
     *
     * @vendor Contus
     *
     * @package Base
     * @var mixed
     */
    protected $eagerLoadingModels = [ ];
    /**
     * Class property to hold the grid update status database field
     *
     * @vendor Contus
     *
     * @package Base
     * @var mixed
     */
    protected $statusDatabaseField = 'is_active';
    /**
     * Class property to hold the grid update mode database field
     *
     * @vendor Contus
     *
     * @package Base
     * @var mixed
     */
    protected $modeDatabaseField = 'is_test';
    /**
     * Prepare the grid
     * should be overridden
     *
     * @vendor Contus
     *
     * @package Base
     * @return $this
     */
    public function prepareGrid() {
        return $this;
    }
    /**
     * Set gridmodel property
     *
     * @vendor Contus
     *
     * @package Base
     * @param \Illuminate\Database\Eloquent\Model $gridModel 
     * @return $this
     */
    protected function setGridModel(Model $gridModel) {
        $this->gridModel = $gridModel;
        
        return $this;
    }
    /**
     * Set gridmodel related model loaded(eagar loading)
     *
     * @vendor Contus
     *
     * @package Base
     * @param mixed $eagerLoadingModels 
     * @return $this
     */
    protected function setEagerLoadingModels($eagerLoadingModels) {
        $this->eagerLoadingModels = $eagerLoadingModels;
        
        return $this;
    }
    /**
     * apply Search filter
     *
     * @param mixed $builder 
     * @param array $searchRecord
     * @vendor Contus
     * @package Base
     * @return mixed
     */
    protected function searchFilter($builder) {
        return $builder;
    }
    /**
     * update grid records collection query
     *
     * @vendor Contus
     *
     * @package Base
     * @param mixed $builder 
     * @return mixed
     */
    protected function updateGridQuery($builder) {
        return $builder;
    }
    /**
     * Act as manager for vaious action performed in the grid
     *
     * @vendor Contus
     *
     * @package Base
     * @return boolean
     */
    public function action() {
        if ($this->request->has ( StringLiterals::SELECTED_CHECKBOX ) && is_array ( $this->request->get ( StringLiterals::SELECTED_CHECKBOX ) )) {
            return $this->gridDelete ( $this->request->input ( StringLiterals::SELECTED_CHECKBOX ) );
        }
    }
    /**
     * Function used to retrieve total number of records
     *
     * @vendor Contus
     *
     * @package Base
     * @return int
     * @throws \Exception
     */
    public function getTotal() {
        $count = 0;
        $searchRecord = null;
        extract ( $this->request->all () );
        $query = $this->updateGridQuery ( $this->gridModel );
        
        try {
            if (is_array ( $searchRecord )) {
                $query = $this->searchFilter ( $query, $searchRecord );
            }
            
            if (! $query instanceof Builder && ! $query instanceof Model) {
                throw new GridException ( '[upateGridQuery/searchFilter] should return the Builder Instance' );
            }
            
            $count = $query->count ();
        }
        catch ( QueryException $e ) {
            $this->logger->error ( $e->getMessage () );
        }
        
        return $count;
    }
    /**
     * Function used to retrieve records with field sorting(asc/desc) from database
     *
     * @vendor Contus
     *
     * @package Base
     * @return \Illuminate\Database\Eloquent\Collection | array
     * @throws \Exception
     */
    public function getRecords() { 
        $orderByFieldName = $rowsPerPage = $sortOrder = null;
        extract ( $this->request->all () );
        $collection = [ ];
        $pageLimit = ( int ) ((is_numeric ( $rowsPerPage )) ? $rowsPerPage : config ( 'mara.limit.gridLimit' ));
        $query = $this->updateGridQuery ( $this->gridModel->with ( $this->eagerLoadingModels ) );
        
        try {
            
            $query = $this->searchFilter ( $query );
            
            if (! $query instanceof Builder && ! $query instanceof Model) {
                throw new GridException ( '[upateGridQuery/searchFilter] should return the Builder Instance' );
            }
            
            $collection = (is_null ( $orderByFieldName ) || is_null ( $sortOrder )) ? $query->orderBy ( 'created_at', 'desc' )->paginate ( $pageLimit ) : $query->orderBy ( $orderByFieldName, $sortOrder )->paginate ( $pageLimit );
        }
        catch ( QueryException $e ) {
            $this->logger->error ( $e->getMessage () );
        }
        catch ( MongoCursorException $e ) {
            $this->logger->error ( $e->getMessage () );
        }
        catch ( Exception $e ) {
            $this->logger->error ( $e->getMessage () );
        }
        
        
        return $this->getFormattedGridCollection ( $collection );
    }
    /**
     * do search the collection with request params
     *
     * @vendor Contus
     *
     * @package Base
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search() {
        return [ ];
    }
    /**
     * Get headings for grid
     *
     * @vendor Contus
     *
     * @package Base
     * @return array
     */
    public function getGridHeadings() {
        return [ ];
    }
    /**
     * Get additional information for grid
     * helper method helps to append more information to grid
     *
     * @vendor Contus
     *
     * @package Base
     * @return array
     */
    public function getGridAdditionalInformation() {
        return [ ];
    }
    /**
     * To get record counts
     *
     * @vendor Contus
     *
     * @package Base
     * @return array
     */
    public function getCount() {
        return [ ];
    }
    /**
     * Get Formatted grid response
     *
     * @vendor Contus
     *
     * @package Base
     * @param mixed $collection 
     * @return array
     */
    protected function getFormattedGridCollection($collection) {
        if ($collection instanceof LengthAwarePaginator) {
            $collection = $this->afterGridRecord ( $collection );
        }
        
        return ($collection instanceof LengthAwarePaginator) ? $collection->toArray () : [ ];
    }
    /**
     * after collection fetch
     * a helper method to update the collections
     *
     * @vendor Contus
     *
     * @package Base
     * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator $collection 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function afterGridRecord(LengthAwarePaginator $collection) {
        return $collection;
    }
    /**
     * delete the existing attribute
     * delete attribute in group
     *
     * @vendor Contus
     *
     * @package Base
     * @param mixed $ids 
     * @return boolean
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function gridDelete($ids) {
        $ids = is_array ( $ids ) ? $ids : [ $ids ];
        $isDeleted = false;
        if (! empty ( $ids )) {
            $isDeleted = $this->gridModel->whereIn ( 'id', $ids )->delete ();
        }
        return $isDeleted;
    }
    /**
     * update status
     *
     * @vendor Contus
     *
     * @package Base
     * @param int $id 
     * @return boolean
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function gridUpdateStatus($id) {
        $isUpdated = false;
        $status = $this->request->has ( 'status' ) ? $this->request->input ( 'status' ) : $isUpdated;
        
        if ($status !== false && in_array ( $status, [ 0,1 ] )) {
            $this->gridModel = $this->gridModel->findOrFail ( $id, [ 'id',$this->statusDatabaseField ] );
            
            if ($this->gridModel->{$this->statusDatabaseField} !== $status) {
                $this->gridModel->{$this->statusDatabaseField} = $status;
                $isUpdated = $this->gridModel->save ();
            }
        }
        
        return $isUpdated;
    }
    
    /**
     * update mode
     *
     * @vendor Contus
     *
     * @package Base
     * @param int $id 
     * @return boolean
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function gridUpdateMode($id) {
        $isUpdated = false;
        $mode = $this->request->has ( 'mode' ) ? $this->request->input ( 'mode' ) : $isUpdated;
        
        if ($mode !== false && in_array ( $mode, [ 0,1 ] )) {
            $this->gridModel = $this->gridModel->findOrFail ( $id, [ 'id',$this->modeDatabaseField ] );
            
            if ($this->gridModel->{$this->modeDatabaseField} !== $mode) {
                $this->gridModel->{$this->modeDatabaseField} = $mode;
                $isUpdated = $this->gridModel->save ();
            }
        }
        
        return $isUpdated;
    }
}
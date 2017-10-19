<?php

/**
 * Api Controller
 *
 * @vendor Contus
 * @package Base
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 *
 */
namespace Contus\Base;

use BadMethodCallException;
use Contus\Base\Controller;
use Contus\Base\Contracts\GridableRepository;
use Contus\Base\Helpers\StringLiterals;
use Contus\Base\Repositories\UploadRepository;
use Contus\Notification\Traits\NotificationTrait;
use Contus\Video\Models\Video;
use Illuminate\Support\Facades\Cache;

abstract class ApiController extends Controller {
    use NotificationTrait;
    /**
     * Class constants for holding request type handled by child controllers
     *
     * @vendor Contus
     *
     * @package Base
     * @var const
     */
    const REQUEST_TYPE = 'API';
    /**
     * The request registered on Base Controller.
     *
     * @vendor Contus
     *
     * @package Base
     * @var object
     */
    protected $request;
    /**
     * The auth registered on Base Controller.
     *
     * @vendor Contus
     *
     * @package Base
     * @var object
     */
    protected $auth;
    /**
     * The class property to hold the logger object
     *
     * @vendor Contus
     *
     * @package Base
     * @var object
     */
    protected $logger;
    /**
     * Class property to hold the upload repository object
     *
     * @vendor Contus
     *
     * @package Base
     * @var Contus\Base\Repository
     */
    protected $repository = null;
    /**
     * class property to hold the setting cache data
     *
     * @vendor Contus
     *
     * @package Base
     * @var array
     */
    public function __construct() {
        $this->request = app ()->make ( 'request' );
        $this->auth = app ()->make ( 'auth' );
        $this->logger = app ()->make ( 'log' );
    }
    /**
     * Get admin url
     *
     * @vendor Contus
     *
     * @package Base
     * @return string
     */
    protected function adminUrl($path) {
        return url ( ADMIN_PREFIX . '/' . $path );
    }
    /**
     * Common post Total request controller action
     * Get total record avaliable in model through repositorie class property
     *
     * @vendor Contus
     *
     * @package Base
     * @throws BadMethodCallException
     */
    public function postTotal() {
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {
            return $this->getSuccessJsonResponse ( [ 'total' => $this->repository->prepareGrid ()->getTotal (),'count' => ($this->request->get ( 'count' ) == 'true') ? $this->repository->getCount () : null,'heading' => $this->repository->getGridHeadings (),'searchFilter' => [ ] ] );
        }

        throw new BadMethodCallException ( "Method [postTotal] does not exist." );
    }
    /**
     * Common post Records request controller action
     * Get Records avaliable in model through repositorie class property
     * by offset
     *
     * @vendor Contus
     *
     * @package Base
     * @return \Illuminate\Http\Response
     *
     * @throws BadMethodCallException
     */
    public function postRecords() {
  
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {  
         
            $response = [ 'data' => $this->repository->prepareGrid()->getRecords() ];
            if ($this->request->input ( 'intialRequest' ) == 1) {
                $response ['heading'] = $this->repository->getGridHeadings ();
                $response ['moreInfo'] = $this->repository->getGridAdditionalInformation ();
                $response ['recordsCount'] = $this->repository->getCount ();
            }
            return $this->getSuccessJsonResponse ( $response );
        }

        throw new BadMethodCallException ( "Method [postRecords] does not exist." );
    }
    /**
     * Common post update status request controller action
     *
     * @vendor Contus
     *
     * @package Base
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     * @throws BadMethodCallException
     */
    public function postUpdateStatus($id) {
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {
            $checkNotifyVideo = Video::where ( 'id', $id )->where ( 'notification_status', 0 )->first ();
            if (count ( $checkNotifyVideo ) > 0) {
                $this->notify ( 'video', $id );
            }
            return $this->repository->prepareGrid ()->gridUpdateStatus ( $id ) ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.updated' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'base::general.updated_error' ), 403 );
        }
        throw new BadMethodCallException ( "Method [postUpdateStatus] does not exist." );
    }
    /**
     * Common post update mode request controller action
     *
     * @vendor Contus
     *
     * @package Base
     * @param int $id
     * @return \Illuminate\Http\Response
     *
     * @throws BadMethodCallException
     */
    public function postUpdateMode($id) {
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {
            return $this->repository->prepareGrid ()->gridUpdateMode ( $id ) ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.updated' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'base::general.updated_error' ), 403 );
        }

        throw new BadMethodCallException ( "Method [postUpdateMode] does not exist." );
    }

    /**
     * Common post Search request controller action
     *
     * @vendor Contus
     *
     * @package Base
     * @return object records
     *
     * @throws BadMethodCallException
     */
    public function postSearch() {
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {
            return $this->getSuccessJsonResponse ( [ 'data' => $this->repository->search () ] );
        }
        throw new BadMethodCallException ( "Method [postSearch] does not exist." );
    }
    /**
     * Common post Action request controller
     *
     * @vendor Contus
     *
     * @package Base
     * @return object records
     */
    public function postAction() {
        if (property_exists ( $this, StringLiterals::REPOSITORY ) && $this->repository instanceof GridableRepository) {
            return $this->repository->prepareGrid ()->action () ? $this->getSuccessJsonResponse ( [ ], trans ( 'base::general.success_delete' ) ) : $this->getErrorJsonResponse ( [ ], trans ( 'base::general.invalid_request' ), 403 );
        }

        throw new BadMethodCallException ( "Method [postAction] does not exist." );
    }
    /**
     * Common ImageUpload request controller
     *
     * @vendor Contus
     *
     * @package Base
     * @return array info
     */
    public function uploadImage() {
        $types = "";
        if($this->request->has ( 'types' )){
            $types = $this->request->types;
        }
        $UploadRepository = new UploadRepository ();
        $tempImageInfo = $UploadRepository->tempUploadImage ($types);
        return empty ( $tempImageInfo ) ? $this->getErrorJsonResponse ( [ ], trans ( 'video::videos.messsage.unable_to_upload' ) ) : $tempImageInfo;
    }
    /**
     * Function to set cache expire time
     *
     * @param string $type
     * @return number
     */
    public function getCacheExpiresTime($type) {
        return 50;
    }
    /**
     * Function to get cache data
     *
     * @param string $cacheName
     * @param object $modal
     * @param string $function
     * @param string $param
     * @return array
     */
    public function getCacheData($cacheName, $modal, $function, $param = "") {
        $expiresTime = $this->getCacheExpiresTime ( $cacheName );
        return Cache::remember ( $cacheName, $expiresTime, function () use ($modal, $function, $param) {
            return $modal->$function ( $param );
        } );
    }
    /**
     * Function to get bucket name
     *
     * @return string
     */
    public function getBucketName() {
        return config ()->get ( 'settings.aws-settings.aws-general.aws_s3_bucket' );
    }
    /**
     * Function to get formate size
     *
     * @param int $bytes
     * @return string
     */
    public function formatSize($bytes) {
        if ($bytes >= 1048576) {
            $bytes = number_format ( $bytes / 1048576, 2 ) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format ( $bytes / 1024, 2 ) . ' kB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
    /**
     * Function to get document size
     *
     * @param object $fetchedVideos
     * @param string $type
     * @return string
     */
    public function getDocumentSize($fetchedVideos, $type) {
        $s3Client = $this->awsRepository->getAWSClient ( 's3' );
        $getBucketName = $this->getBucketName ();
        $size = '';
        $doc = $fetchedVideos->$type;
        if ($doc !== "" && $doc != null) {
            $doc = explode ( "/", $doc );
            if ($type != 'mp3') {
                $docName = $doc [count ( $doc ) - 2] . '/' . $doc [count ( $doc ) - 1];
            } else {
                $docName = $doc [count ( $doc ) - 1];
            }
            $result = $s3Client->getObject ( array ('Bucket' => $getBucketName,'Key' => $docName ) );
            $size = $this->formatSize ( $result->get ( '@metadata' ) ['headers'] ['content-length'] );
        }
        return $size;
    }
    /**
     * function to check and download the file
     *
     * @return boolean
     */
    public function getDocumentDownload() {
        if ($this->request->has ( 'file' ) && !empty($this->request->file)) {
            $s3Client = $this->awsRepository->getAWSClient ( 's3' );
            $getBucketName = $this->getBucketName ();
            $filename = $this->request->file;
            try {
                // Get the object
                $result = $s3Client->getObject ( array ('Bucket' => $getBucketName,'Key' => $filename ) );
                header ( 'Content-Description: File Transfer' );
                header ( "Content-Type: {$result['ContentType']}" );
                header ( 'Content-Disposition: attachment; filename=' . basename ( $filename ) );
                header ( 'Content-Transfer-Encoding: binary' );
                header ( 'Expires: 0' );
                header ( 'Cache-Control: must-revalidate' );
                header ( 'Pragma: public' );
                echo $result ['Body'];
            }
            catch ( S3Exception $e ) {
                echo $e->getMessage () . "\n";
            }
            exit ();
        }else{
            return false;
        }
    }
}
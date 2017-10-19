<?php

/**
 * Base Upload Repository
 *
 * To manage the functionalities related to the file uploads.
 * @vendor Contus
 * @package Base
 * @name       BaseUploadRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Base\Repositories;

use Contus\Base\Repository as BaseRepository;
use Symfony\Component\HttpFoundation\File\File;
use Exception;
use Contus\Base\Exceptions\UploadException;
use Contus\Base\Model;

class BaseUploadRepository extends BaseRepository {

 /**
  * Prepare the file upload for temporary path
  * 1)validation
  * 2)temp upload path
  * 3)set the files from request to property
  *
  * @vendor Contus
  *
  * @package Base
  * @return UploadRepository
  *
  * @throws \Illuminate\Http\Exception\HttpResponseException
  */
 public function tempPrepare() {
  /**
   * abort the request with 404 if the model requested is not in the model allowed
   */
  if (! in_array ( $this->modelIdentifier, $this->allowedModelIdentifier )) {
   app ()->abort ( 404 );
  }

  $this->setConfig ()->defineRule ();

  $this->validate ( $this->request, $this->getRules () );

  $this->setTemporaryStoragePath ()->setUploadedFilesFromRequest ();

  return $this;
 }
 /**
  * define the validation rule
  * for the uploaded file
  *
  * @vendor Contus
  *
  * @package Base
  * @return UploadRepository
  */
 public function defineRule() {
  return $this->setRule ( $this->requestParamKey, (isset ( $this->config->is_file ) && ($this->config->is_file)) ? "required|mimes:{$this->config->supported_format}|max:" . ($this->config->fileSize * 1024) : "required|mimes:{$this->config->supported_format}|resolution:{$this->config->image_resolution}|max:" . ($this->config->fileSize * 1024) );
 }
 /**
  * set the image configuration by model
  * used by validation rules
  *
  * @return UploadRepository @vendor Contus
  * @package Base
  * @throws Exception
  */
 public function setConfig() {
  $this->config = $this->getFileConfigurationByModel ( $this->modelIdentifier );
  /**
   * if Config is not set throw the exception
   */
  if (is_null ( $this->config )) {
   throw new UploadException ( "Image configuration is not Found for {$this->modelIdentifier}", 1 );
  }

  return $this;
 }

 /**
  * set temporary as destination path
  *
  * @vendor Contus
  *
  * @package Base
  * @return UploadRepository
  */
 protected function setTemporaryStoragePath() {
  $this->path = $this->makeOSFriendlyPath ( storage_path ( (isset ( $this->config->is_file ) && ($this->config->is_file)) ? $this->config->temporary_storage_path : $this->config->temporary_image_storage_path ) );

  return $this;
 }
 /**
  * set storage path
  *
  * @vendor Contus
  *
  * @package Base
  * @param string $path
  * @return UploadRepository
  */
 protected function setStoragePath() {
  $this->path = $this->makeOSFriendlyPath ( public_path ( $this->config->storage_path ) );

  return $this;
 }
 /**
  * make path Operating System friedly
  * replace directory separator with DIRECTORY_SEPARATOR
  *
  * @vendor Contus
  *
  * @package Base
  * @param string $path
  * @return string
  */
 protected function makeOSFriendlyPath($path) {
  $path = str_replace ( '\\', DIRECTORY_SEPARATOR, $path );
  return str_replace ( '/', DIRECTORY_SEPARATOR, $path );
 }

 /**
  * Check the file is removed by the user after uploaded
  * removed flag is true it will unlink the file (removed from file system)
  *
  * @vendor Contus
  *
  * @package Base
  * @param array $file
  * @return boolean
  */
 protected function isRemovedFile(array $file) {
  return (array_key_exists ( $this->removedFlagParamKey, $file ) && ( int ) $file [$this->removedFlagParamKey] === 1);
 }
 /**
  * get temp storage path
  * Note : during actual file upload actual path class property is used
  *
  * @vendor Contus
  *
  * @package Base
  * @return string
  */
 protected function getTempPath() {
  if (is_null ( $this->tempPath )) {
   $this->tempPath = $this->makeOSFriendlyPath ( storage_path ( (isset ( $this->config->is_file ) && ($this->config->is_file)) ? $this->config->temporary_storage_path : $this->config->temporary_image_storage_path ) ) . DIRECTORY_SEPARATOR;
  }

  return $this->tempPath;
 }
}

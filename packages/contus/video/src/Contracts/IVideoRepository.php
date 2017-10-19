<?php

/**
 * Implements of IVideoRepository
 *
 * Inteface for implementing the VideoRepository modules and functions  
 * 
 * @name       IVideoRepository
 * @version    1.0
 * @author     Contus<developers@contus.in>
 * @copyright  Copyright (C) 2016 Contus. All rights reserved.
 * @license    GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Video\Contracts;

interface IVideoRepository {
 /**
  * Function to add a video.
  *
  * @return boolean|integer Video id if video is added successfully and False if not.
  */
 public function addVideo();
}

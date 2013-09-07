<?php

namespace Opesho\Upload\Storage;

use CF_Authentication;
use CF_Connection;
use CF_Container;
use CF_Object;
use Upload\File;
use Upload\Storage\Base as StorageBase;

/**
 * Clodo Storage
 * @author Dmitry Zelenetskiy <dmitry.zelenetskiy@gmail.com>
 */
class ClodoStorage extends StorageBase
{
    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var string
     */
    protected $container;

    protected $dir;

    /**
     * Constructor
     */
    public function __construct($username, $api_key, $container, $dir='')
    {
        $this->username = $username;
        $this->api_key = $api_key;
        $this->container = $container;
        $this->dir = $dir;
    }

    /**
     * Upload
     * @param  File $file The file object to upload
     * @return bool
     */
    public function upload(File $file)
    {
        $auth = new CF_Authentication($this->username, $this->api_key);
        $auth->authenticate();
        $conn = new CF_Connection($auth);

        /**
         * @var $cont CF_Container
         */
        $cont = $conn->get_container($this->container);

        // adding 'st' prefix to filename to detect whether it placed in cloud storage
        $newName = 'st'.md5(time().uniqid($file->getName(), true));
        $file->setName($newName);
        /**
         * @var $obj CF_Object
         */
        $obj  = $cont->create_object($this->dir . DIRECTORY_SEPARATOR . $file->getNameWithExtension());
        return $obj->load_from_filename($file->getPathname());
    }
}

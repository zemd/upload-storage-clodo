<?php

namespace Opesho\Upload\Storage;

class ClodoStorageTest extends \PHPUnit_Framework_TestCase {

    protected $assetsDirectory;

    protected $storage;

    public function setUp() {

        $this->assetsDirectory = dirname(__DIR__).'/../../assets';

        $this->storage = $this->getMock(
            'Opesho\Upload\Storage\ClodoStorage',
            array('upload'),
            array('user', 'key', 'container', 'storageDir')
        );

        $this->storage->expects($this->any())
            ->method('upload')
            ->will($this->returnValue('test.txt'));

        $_FILES['test_file'] = array(
            'name' => 'test.txt',
            'tmp_name' => $this->assetsDirectory . '/test.txt',
            'error' => 0
        );
    }

    public function testClodoAutoloaded() {
        $this->assertTrue(class_exists('CF_Authentication'));
        $this->assertTrue(class_exists('CF_Connection'));
        $this->assertTrue(class_exists('CF_Container'));
    }

    public function testStorage() {
        $file = $this->getMock('\Upload\File', array('validate'), array('test_file', $this->storage));

        $file->expects($this->any())
            ->method('validate')
            ->will($this->returnValue(true));
        $this->assertTrue($file->upload() === 'test.txt');
    }
}


<?php

class DummyStream implements \Psr\Http\Message\StreamInterface
{
    private $data;

    function __construct()
    {
        $this->data = array();
    }

    public function isWritable()
    {
        return true;
    }

    public function write($string)
    {
        $this->data []= $string;
    }

    public function __toString()
    {
        return implode('', $this->data);
    }

    public function close() {}

    public function detach() {}

    public function getSize() {}

    public function tell() {}

    public function eof() {}

    public function isSeekable() {}

    public function seek($offset, $whence = SEEK_SET) {}

    public function rewind()
    {
        $this->data = array();
    }

    public function isReadable() {}

    public function read($length) {}

    public function getContents() {}

    public function getMetadata($key = null) {}
}

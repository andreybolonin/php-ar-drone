<?php

namespace Joli\ArDrone\Buffer;

class Buffer
{
    /**
     * @var string
     */
    private $data;

    /**
     * @var int
     */
    private $offset;

    /**
     * Buffer constructor.
     * @param $binary
     */
    public function __construct($binary)
    {
        $this->data = $binary;
        $this->offset = 0;
    }

    /**
     * @return string
     */
    public function getUint32LE()
    {
        $value = unpack('V/', substr($this->data, $this->offset, ($this->offset + 4)));
        $this->moveOffset(4);

        return dechex($value[1]);
    }

    /**
     * @return string
     */
    public function getUint16LE()
    {
        $value = unpack('v/', substr($this->data, $this->offset, ($this->offset + 2)));
        $this->moveOffset(2);

        return dechex($value[1]);
    }

    /**
     * @return string
     */
    public function getFloat32()
    {
        $value = unpack('f/', substr($this->data, $this->offset, ($this->offset + 4)));
        $this->moveOffset(4);

        return dechex($value[1]);
    }

    /**
     * @return string
     */
    public function getUint8()
    {
        $value = unpack('C/', substr($this->data, $this->offset, ($this->offset + 1)));
        $this->moveOffset(1);

        return dechex($value[1]);
    }

    /**
     * @return string
     */
    public function getInt32()
    {
        $value = unpack('I/', substr($this->data, $this->offset, ($this->offset + 4)));
        $this->moveOffset(4);

        return dechex($value[1]);
    }

    /**
     * @param $masks
     * @return array
     */
    public function getMask32($masks)
    {
        return $this->mask($masks, $this->getUint32LE());
    }

    /**
     * @return array
     */
    public function getVector31()
    {
        return [
            'x' => $this->getFloat32(),
            'y' => $this->getFloat32(),
            'z' => $this->getFloat32(),
        ];
    }

    /**
     * @return array
     */
    public function getMatrix33()
    {
        return [
            'm11' => $this->getFloat32(),
            'm12' => $this->getFloat32(),
            'm13' => $this->getFloat32(),
            'm21' => $this->getFloat32(),
            'm22' => $this->getFloat32(),
            'm23' => $this->getFloat32(),
            'm31' => $this->getFloat32(),
            'm32' => $this->getFloat32(),
            'm33' => $this->getFloat32(),
        ];
    }

    /**
     * @param $nbBytes
     * @return bool|string
     */
    public function getBytes($nbBytes)
    {
        $value = substr($this->data, $this->offset, ($this->offset + $nbBytes));
        $this->moveOffset($nbBytes);

        return $value;
    }

    /**
     * @param $step
     */
    private function moveOffset($step)
    {
        $this->offset = $this->offset + $step;
    }

    /**
     * @todo move this function ?
     * @param $masks
     * @param $value
     * @return array
     */
    private function mask($masks, $value)
    {
        $flags = [];

        foreach ($masks as $name => $mask) {
            $flags[$name] = (hexdec($value) & ($mask)) ? 1 : 0;
        }

        return $flags;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return strlen($this->data);
    }
}

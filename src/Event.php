<?php
/**
 * Created by PhpStorm.
 * User: Sile
 * Date: 1/22/2019
 * Time: 1:25 AM
 */

class Event
{
    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $created;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $type
     * @return Event
     */
    public function setType(string $type): Event
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param array $data
     * @return Event
     */
    public function setData(array $data): Event
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $type
     * @param array $data
     */
    public function set(string $type, array $data)
    {
        $this->setType($type);
        $this->setData($data);
    }

    /**
     * Return event object properties as array
     *
     * @return array
     */
    public function toArray(): array
    {
        $properties = get_object_vars($this);

        if (!isset($properties['created'])) {
            $properties['created'] = date('Y-m-d H:i:s');
        }

        return $properties;
    }

}
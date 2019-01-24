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
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $performer;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $additional;

    /**
     * @var string
     */
    protected $created;

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
     * @param string $name
     * @return Event
     */
    public function setName(string $name): Event
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $performer
     * @return Event
     */
    public function setPerformer(string $performer): Event
    {
        $this->performer = $performer;

        return $this;
    }

    /**
     * @param string $subject
     * @return Event
     */
    public function setSubject(string $subject): Event
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @param array $additional
     * @return Event
     */
    public function setAdditional(array $additional): Event
    {
        $this->additional = json_encode($additional);

        return $this;
    }

    /**
     * Hydrate event class
     *
     * @param array $data
     * @return Event
     */
    public function set(array $data): Event
    {
        foreach ($data as $property => $value) {

            $method = 'set' . ucfirst($property);

            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getPerformer(): string
    {
        return $this->performer;
    }

    /**
     * @return array
     */
    public function getAdditional(): array
    {
        return json_decode($this->additional);
    }

    /**
     * @return string
     */
    public function getCreated(): string
    {
        return $this->created;
    }

    /**
     * Return event object properties as array
     *
     * @return array
     */
    public function toArray()
    {
        $arrayProperties = get_object_vars($this);

        $arrayProperties['created'] = isset($arrayProperties['created']) ?
            $this->getCreated()->format('Y-m-d H:i:s') :
            date('Y-m-d H:i:s');

        return $arrayProperties;
    }

}
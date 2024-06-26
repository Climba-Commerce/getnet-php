<?php
namespace Getnet\API;

trait TraitEntity
{

    /**
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $entity = clone $this;

        if (method_exists($entity, 'beforeSerialize')) {
            $entity->beforeSerialize();
        }

        return $entity->toArray();
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        $vars = get_object_vars($this);

        if ($this->hiddenNullValues()) {
            return array_filter($vars, function ($value) {
                return null !== $value;
            });
        }

        return $vars;
    }

    /**
     *
     * @return false|string
     */
    public function toJSON($hiddenNull = true)
    {
        if ($hiddenNull) {
            return json_encode($this);
        }

        return json_encode(get_object_vars($this));
    }

    /**
     *
     * @return bool
     */
    private function hiddenNullValues(): bool
    {
        return true;
    }
}
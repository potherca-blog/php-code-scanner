<?php

namespace Potherca\Scanner;

use PhpParser\Node;
use Potherca\Scanner\Exception\InvalidArgumentException;

class Identity
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    const ERROR_PROPERTY_NOT_SET = 'Can not get property that has not been set: "%s"';
    const ERROR_REQUIRED_NOT_SET = 'Not al required properties have been set.';
    const ERROR_UNSUPPORTED_KEYS = 'Unsupported keys "%s". Supported keys are: "%s"';

    /** @var array */
    private /** @noinspection PropertyCanBeStaticInspection */ $availableProperties = [
        'class' => '',
        'file' => 'REQUIRED',
        'function' => '',
        'identity' => 'REQUIRED',
        'namespace' => 'REQUIRED',
        'value' => 'REQUIRED',
    ];
    /** @var Node */
    private $node;
    /** @var array */
    private $properties;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @return array
     */
    final public function getIdentity()
    {
        /** @noinspection ExceptionsAnnotatingAndHandlingInspection */// Key is guarantied to be present.
        return $this->getProperty('identity');
    }

    /**
     * @return array
     */
    private function getProperties()
    {
        return $this->properties;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    final public function __construct(Node $node, array $properties)
    {
        $this->validate($properties);

        $this->node = $node;
        $this->properties = $properties;
    }

    final public function __toString()
    {
        try {
            $output = vsprintf('[%s] %s:%s %s%s%s = "%s" (%s)', [
                implode(',', $this->getProperty('identity')),
                $this->getProperty('file'),
                $this->getProperty('line'),
                $this->getProperty('namespace'),
                $this->getProperty('class'),
                $this->getProperty('function'),
                $this->getProperty('value'),
                $this->getProperty('node-type'),
            ]);
        } catch (\Exception $exception) {
            $output = 'ERROR: '.$exception->getMessage();
        }
        return (string) $output;
    }

    /**
     * @param $identity
     *
     * @return bool
     */
    final public function hasIdentity($identity)
    {
        return in_array($identity, $this->getIdentity(), true);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws \Potherca\Scanner\Exception\InvalidArgumentException
     */
    private function getProperty($key)
    {
        $property = null;

        switch ($key) {

            case 'class':
                $property = $this->properties['class']
                    ? $this->properties['class'] . '::'
                    : '';
                break;

            case 'function':
                $property = $this->properties['function']
                    ?: ''
                ;
                break;

            case 'line':
                $start = $this->node->getAttribute('startLine');
                $end = $this->node->getAttribute('endLine');

                if ($start === $end) {
                    $property = $start;
                } else {
                    $property = $start .'-'. $end;
                }
                break;

            case 'namespace':
                $property = $this->properties['namespace'] . '\\';
                break;

            case 'node-type':
                $property = $this->node->getType();
                break;

            default:
                $properties = $this->getProperties();

                if (array_key_exists($key, $properties) === false) {
                    $message = sprintf(self::ERROR_PROPERTY_NOT_SET, $key);

                    throw new InvalidArgumentException($message);
                } else {
                    $property = $properties[$key];

                }
                break;
        }

        return $property;
    }

    /**
     * @param array $properties
     *
     * @throws \Potherca\Scanner\Exception\InvalidArgumentException
     */
    private function validate(array $properties)
    {
        $message = null;

        $availableProperties = $this->availableProperties;
        $propertyDiff = array_diff_key($properties, $availableProperties);

        if (count($propertyDiff) !== 0) {
            $message = vsprintf(self::ERROR_UNSUPPORTED_KEYS, [
                implode('", "', array_keys($propertyDiff)),
                implode('", "', array_keys($availableProperties)),
            ]);
        } else {
            $required = array_filter($this->availableProperties, function ($value) {
                return $value === 'REQUIRED';
            });

            $requiredDiff = array_diff_key($required, $properties);

            if (count($requiredDiff) !== 0) {
                $message = vsprintf('%s Please provide: "%s"', [
                    self::ERROR_REQUIRED_NOT_SET,
                    implode('", "', array_keys($requiredDiff)),
                ]);
            }
        }

        if ($message !== null) {
            throw new InvalidArgumentException($message);
        }
    }
}

/*EOF*/

<?php

namespace DevNanny\Connector;

abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param $methodName
     * @param $parameterClassName
     *
     * @return string
     */
    public function regexMustImplementInterface($methodName, $parameterClassName)
    {
        $format = '/Argument [1-3]{1} passed to %s::%s\(\) must implement interface %s, [a-zA-Z\\\\]+ given/';

        return $this->buildRegexp($methodName, $parameterClassName, $format);

    }

    /**
     * @param $methodName
     * @param $parameterClassName
     *
     * @return string
     */
    public function regexMustBeAnInstanceOf($methodName, $parameterClassName)
    {
        $format = '/Argument [1-3]{1} passed to %s::%s\(\) must be an instance of %s, [a-zA-Z\\\\ ]+? given/';

        return $this->buildRegexp($methodName, $parameterClassName, $format);
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param $methodName
     * @param $parameterClassName
     * @param $format
     *
     * @return string
     */
    private function buildRegexp($methodName, $parameterClassName, $format)
    {
        $className = $this->getClassUnderTest();
        $className = $this->escapeForRegexp($className);
        $parameterClassName = $this->escapeForRegexp($parameterClassName);

        return sprintf($format, $className, $methodName, $parameterClassName);
    }

    /**
     * @param $className
     *
     * @return mixed
     */
    private function escapeForRegexp($className)
    {
        return str_replace('\\', '\\\\', $className);
    }

    /**
     * @return string
     */
    private function getClassUnderTest()
    {
        $className = get_called_class();

        return substr($className, 0, -strlen('Test'));
    }
}

/*EOF*/

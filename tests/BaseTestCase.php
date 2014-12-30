<?php

namespace DevNanny\Connector;

/**
 * Base TestCase
 */
abstract class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param string $methodName
     * @param string $parameterNumber
     *
     * @return string
     *
     */
    final public function regexMissingArgument($methodName, $parameterNumber)
    {
        $format = '/Missing argument %3$s for %1$s::%2$s\(\)/';

        return $this->buildRegexp($format, $methodName, $parameterNumber);

    }

    /**
     * @param string $methodName
     * @param string $parameterClassName
     *
     * @return string
     */
    final public function regexMustImplementInterface($methodName, $parameterClassName)
    {
        $format = '/Argument [1-3]{1} passed to %1$s::%2$s\(\) must implement interface %3$s, [a-zA-Z\\\\]+ given/';

        return $this->buildRegexp($format, $methodName, $parameterClassName);

    }

    /**
     * @param string $methodName
     * @param string $parameterClassName
     *
     * @return string
     */
    final public function regexMustBeAnInstanceOf($methodName, $parameterClassName)
    {
        $format = '/Argument [1-3]{1} passed to %1$s::%2$s\(\) must be an instance of %3$s, [a-zA-Z\\\\ ]+? given/';

        return $this->buildRegexp($format, $methodName, $parameterClassName);
    }

    /**
     * @param string $regex
     * @param string $delimiter
     *
     * @return string
     */
    final public function createRegexFromFormat($format)
    {
        $delimiter = '/';
        $parameters = func_get_args();
        $regex = call_user_func_array('sprintf', $parameters);

        return $delimiter . preg_quote($regex, $delimiter) . $delimiter;
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param string $methodName
     * @param string $parameterClassName
     * @param string $format
     *
     * @return string
     */
    private function buildRegexp($format, $methodName, $parameterClassName)
    {
        $className = $this->getClassUnderTest();
        $className = $this->escapeForRegexp($className);
        $parameterClassName = $this->escapeForRegexp($parameterClassName);

        return sprintf($format, $className, $methodName, $parameterClassName);
    }

    /**
     * @param string $className
     *
     * @return string
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

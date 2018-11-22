<?php
class PHPUnitUtils
{
	/**
	 * Get a private or protected method for testing/documentation purposes.
	 * How to use for MyClass->foo():
	 *      $cls = new MyClass();
	 *      $foo = PHPUnitUtil::getPrivateMethod($cls, 'foo');
	 *      $foo->invoke($cls, $...);
	 * @param 	object 	$obj The instantiated instance of your class
	 * @param 	string 	$name The name of your private/protected method
	 * @return 	ReflectionMethod
	 */
	public static function getProtectedMethod($obj, $name)
	{
		$class = new \ReflectionClass($obj);
		$method = $class->getMethod($name);
		$method->setAccessible(true);
		return $method;
	}

	/**
	 * basically the same as protected methods. Just for convenience
	 *
	 * @param 	object	$obj
	 * @param 	string	$name
	 * @return ReflectionMethod
	 */
	public static function getPrivateMethod($obj, $name)
	{
		return self::getProtectedMethod($obj, $name);
	}

	/**
	 * $property->getValue($obj)
	 *
	 * @param object    $obj
	 * @param string    $name
	 * @return ReflectionProperty
	 */
	public static function getProtectedProperty($obj, $name)
	{
		$class = new \ReflectionClass($obj);
		$property = $class->getProperty($name);
		$property->setAccessible( true );
		return $property;
	}

	/**
	 * basically the same as protected properties. Just for convenience
	 *
	 * @param object    $obj
	 * @param string    $name
	 * @return ReflectionProperty
	 */
	public static function getPrivateProperty($obj, $name)
	{
		return self::getProtectedProperty($obj, $name);
	}
}
<?php namespace Cartalyst\Sentry\Cookies;
/**
 * Part of the Sentry Package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    Sentry
 * @version    2.0
 * @author     Cartalyst LLC
 * @license    BSD License (3-clause)
 * @copyright  (c) 2011 - 2013, Cartalyst LLC
 * @link       http://cartalyst.com
 */

use Illuminate\Container\Container;
use Illuminate\Cookie\CookieJar;
use Symfony\Component\HttpFoundation\Cookie;

class IlluminateCookie implements CookieInterface {

	/**
	 * The application instance.
	 *
	 * @param Illuminate\Container\Container
	 */
	protected $app;

	/**
	 * The key used in the Cookie.
	 *
	 * @var string
	 */
	protected $key = 'cartalyst_sentry';

	/**
	 * The cookie object.
	 *
	 * @var Illuminate\Cookie\CookieJar
	 */
	protected $jar;

	/**
	 * The cookie to be stored.
	 *
	 * @var Symfony\Component\HttpFoundation\Cookie
	 */
	protected $cookie;

	/**
	 * Creates a new cookie instance.
	 *
	 * @param  Illuminate\Cookie\CookieJar  $jar
	 * @param  string  $key
	 * @return void
	 */
	public function __construct($app, CookieJar $jar, $key = null)
	{
		$this->app = $app;
		$this->jar = $jar;

		if (isset($key))
		{
			$this->key = $key;
		}

		// Set the cookie after the app runs
		$me = $this;
		$app->after(function($request, $response) use ($app, $me)
		{
			$response->headers->setCookie($me->getCookie());
		});
	}

	/**
	 * Returns the cookie key.
	 *
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Put a value in the Sentry cookie.
	 *
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
	public function put($value, $minutes)
	{
		$this->cookie = $this->jar->make($this->getKey(), $value, $minutes);
	}

	/**
	 * Put a value in the Sentry cookie forever.
	 *
	 * @param  mixed   $value
	 * @return void
	 */
	public function forever($value)
	{
		$this->cookie = $this->jar->forever($this->getKey(), $value);
	}

	/**
	 * Get the Sentry cookie value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		return $this->jar->get($this->getKey());
	}

	/**
	 * Remove the Sentry cookie.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function forget()
	{
		$this->jar->forget($this->getKey());
		$this->cookie = null;
	}

	/**
	 * Returns the Symfony cookie object associated
	 * with the illuminate cookie.
	 *
	 * @return Symfony\Component\HttpFoundation\Cookie
	 */
	public function getCookie()
	{
		return $this->cookie;
	}

}
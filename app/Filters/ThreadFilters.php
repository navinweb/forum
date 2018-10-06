<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ThreadFilters
{

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * ThreadFilters constructor.
	 *
	 * @param Request $request
	 */
	function __construct( Request $request )
	{

		$this->request = $request;
	}

	/**
	 * @param $builder
	 *
	 * @return mixed
	 */
	public function apply( $builder )
	{
		if ( ! $username = $this->request->by ) {
			return $builder;
		} else {
			$user = User::where( 'name', $username )->firstOrFail();

			return $builder->where( 'user_id', $user->id );
		}
	}
}

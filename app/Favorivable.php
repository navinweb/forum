<?php

namespace app;

use App\Favorite;

trait Favorivable
{

	public function favorites()
	{
		return $this->morphMany( Favorite::class, 'favorited' );
	}

	public function favorite()
	{
		$attributes = [ 'user_id' => auth()->id() ];

		if ( ! $this->favorites()->where( $attributes )->exists() ) {
			$this->favorites()->create( $attributes );
		}
	}

	public function isFavorite()
	{
		return ! ! $this->favorites->where( 'user_id', auth()->id() )->count();
	}

	public function getFavoritesCountAttribute()
	{
		return $this->favorites->count();
	}
}
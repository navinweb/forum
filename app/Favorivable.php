<?php

namespace app;

use App\Favorite;

trait Favorivable
{

	protected static function bootFavorivable()
	{
		static::deleting( function ( $model ) {
			$model->favorites->each->delete();
		} );
	}

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

	public function unfavorite()
	{
		$attributes = [ 'user_id' => auth()->id() ];

		$this->favorites()->where( $attributes )->get()->each( function ( $favorite ) {
			$favorite->delete();
		} );
	}

	public function isFavorite()
	{
		return ! ! $this->favorites->where( 'user_id', auth()->id() )->count();
	}

	public function getIsFavoriteAttribute()
	{
		return $this->isFavorite();
	}

	public function getFavoritesCountAttribute()
	{
		return $this->favorites->count();
	}
}
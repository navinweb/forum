<?php

namespace App;

trait RecordsActivity
{

	protected static function bootRecordsActivity()
	{
		foreach ( static::getRecordEvents() as $event ) {
			static::$event( function ( $model ) use ( $event ) {
				$model->recordActivity( $event );
			} );
		}
	}

	public static function getRecordEvents()
	{
		return [ 'created' ];
	}

	protected function recordActivity( $event )
	{
		$this->activity()->create( [
			'user_id' => auth()->id(),
			'type'    => $this->getActivityType( $event ),
		] );
	}

	public function activity()
	{
		return $this->morphMany( 'App\Activity', 'subject' );
	}

	protected function getActivityType( $event )
	{
		$type = strtolower( ( new \ReflectionClass( $this ) )->getShortName() );

		return "{$event}_{$type}";
	}
}
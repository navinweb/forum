<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
	protected $guarded = [];

	protected $with = [ 'creator', 'channel' ];

	public static function boot()
	{
		parent::boot();

		static::addGlobalScope( 'replyCount', function ( $builder ) {
			$builder->withCount( 'replies' );
		} );

		static::created( function ( $thread ) {
			$thread->recordActivity( 'created' );
		} );
	}

	protected function recordActivity( $event )
	{
		Activity::create( [
			'user_id'      => auth()->id(),
			'type'         => $this->getActivityType( $event ),
			'subject_id'   => $this->id,
			'subject_type' => get_class( $this )
		] );
	}

	protected function getActivityType( $event )
	{
		return $event . '_' . strtolower( ( new \ReflectionClass( $this ) )->getShortName() );
	}

	public function path()
	{
		return "/threads/{$this->channel->slug}/{$this->id}";
	}

	public function replies()
	{
		return $this->hasMany( Reply::class );
	}

	public function creator()
	{
		return $this->belongsTo( User::class, 'user_id' );
	}

	public function channel()
	{
		return $this->belongsTo( Channel::class );
	}

	public function addReply( $reply )
	{
		$this->replies()->create( $reply );
	}

	public function scopeFilter( $query, $filters )
	{
		return $filters->apply( $query );
	}
}

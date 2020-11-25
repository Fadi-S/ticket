<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models\EventType{
/**
 * App\Models\EventType\EventType
 *
 * @property int $id
 * @property string $name
 * @property string $arabic_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType whereArabicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EventType whereUpdatedAt($value)
 */
	class EventType extends \Eloquent {}
}

namespace App\Models\Mass{
/**
 * App\Models\Mass\Mass
 *
 * @property int $id
 * @property string $time
 * @property int $number_of_places
 * @property int $type_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Mass onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereNumberOfPlaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Mass withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Mass withoutTrashed()
 */
	class Mass extends \Eloquent {}
}

namespace App\Models\Reservation{
/**
 * App\Models\Reservation\Reservation
 *
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property string $reserved_at
 * @property string $secret
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereReservedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reservation whereUserId($value)
 */
	class Reservation extends \Eloquent {}
}

namespace App\Models\User{
/**
 * App\Models\User\User
 *
 * @property int $id
 * @property string $name
 * @property string $username
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $fcm_token
 * @property string|null $picture
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereFcmToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User\User withoutTrashed()
 */
	class User extends \Eloquent {}
}


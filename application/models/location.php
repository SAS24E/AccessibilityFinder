
<?php
class Location extends Model {
    protected $table = 'locations';

    protected $fillable = [
        'name', 'address', 'latitude', 'longitude', 'accessibility_features'
    ];

    public function reviews() {
        return $this->hasMany(Review::class);
    }
}

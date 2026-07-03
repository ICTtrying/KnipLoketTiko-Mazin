<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Klant extends Model
{
    use HasFactory;
 
    protected $table = 'Klant';
    protected $primaryKey = 'Id';
    public $timestamps = false;
 
    protected $fillable = [
        'UserId',
        'Voornaam',
        'Tussenvoegsel',
        'Achternaam',
        'Relatienummer',
        'Bijzonderheden',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd'
    ];
 
    /**
     * Relatie: Klant belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'Id');
    }
 
    /**
     * Relatie: Klant has many KlantPerContact
     */
    public function klantPerContact()
    {
        return $this->hasMany(KlantPerContact::class, 'KlantId', 'Id');
    }
 
    /**
     * Get contact through KlantPerContact
     */
    public function contacts()
    {
        return $this->hasManyThrough(
            Contact::class,
            KlantPerContact::class,
            'KlantId',
            'Id',
            'Id',
            'ContactId'
        );
    }
 
    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        $name = $this->Voornaam;
        
        if ($this->Tussenvoegsel) {
            $name .= ' ' . $this->Tussenvoegsel;
        }
        
        $name .= ' ' . $this->Achternaam;
        
        return $name;
    }
}
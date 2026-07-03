<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Contact extends Model
{
    use HasFactory;
 
    protected $table = 'Contact';
    protected $primaryKey = 'Id';
    public $timestamps = false;
 
    protected $fillable = [
        'Straatnaam',
        'Huisnummer',
        'Toevoeging',
        'Postcode',
        'Plaats',
        'Email',
        'Mobiel',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd'
    ];
 
    /**
     * Relatie: Contact has many KlantPerContact
     */
    public function klantPerContact()
    {
        return $this->hasMany(KlantPerContact::class, 'ContactId', 'Id');
    }
 
    /**
     * Get klanten through KlantPerContact
     */
    public function klanten()
    {
        return $this->hasManyThrough(
            Klant::class,
            KlantPerContact::class,
            'ContactId',
            'Id',
            'Id',
            'KlantId'
        );
    }
 
    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $address = $this->Straatnaam . ' ' . $this->Huisnummer;
        
        if ($this->Toevoeging) {
            $address .= ' ' . $this->Toevoeging;
        }
        
        return $address . ', ' . $this->Postcode . ' ' . $this->Plaats;
    }
}
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class KlantPerContact extends Model
{
    use HasFactory;
 
    protected $table = 'KlantPerContact';
    protected $primaryKey = 'Id';
    public $timestamps = false;
 
    protected $fillable = [
        'KlantId',
        'ContactId',
        'IsActief',
        'Opmerking',
        'DatumAangemaakt',
        'DatumGewijzigd'
    ];
 
    /**
     * Relatie: belongs to Klant
     */
    public function klant()
    {
        return $this->belongsTo(Klant::class, 'KlantId', 'Id');
    }
 
    /**
     * Relatie: belongs to Contact
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'ContactId', 'Id');
    }
}
 
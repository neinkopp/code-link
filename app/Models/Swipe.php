<?php

namespace App\Models; 

use Illuminate\Database\Eloquent\Factories\HasFactory; //wird verwendet um model faktories zu aktivieren. so kann ich testdaten generieren.
use Illuminate\Database\Eloquent\Model; //verbindung mit der Datebank 

class Swipe extends Model
{
    use HasFactory;

    protected $fillable = ['from_user_id','to_user_id','liked' ]; //hier definiere ich welche Felder erlaube ich wenn ich ein Swipe Objekt erstelle
    //Laravel schützt Models vor Massenbearbeitung 

    public function fromUser(){ //jeder Swipe gehört zu einem User,der ihn gesendet hat. 

        return $this->belongsTo(User::class, 'from_user_id');

    }
    public function toUser(){ //jeder swipe geört zu einem User der ihn erhalten hat. 
        return $this->belongsTo(User::class,'to_user_id');
    }

}

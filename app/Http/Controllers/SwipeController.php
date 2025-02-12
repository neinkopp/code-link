<?php

namespace App\Http\Controllers;

use App\Models\Swipe; //importiert das Swipe-Model, um Swipes in der Datenbank zu speichern.
use Illuminate\Support\Facades\Auth; //Verwaltet die Authentifizierung und gibt den eingeloggten Nutzer zurück.
use Illuminate\Http\Request; //ermöglicht den Zugriff auf Daten, die von einem HTTP-Request gesendet wurden.
use Illuminate\Support\Facades\DB; //importiert die DB Facade um direte SQL Queries zu schreiben 

class SwipeController extends Controller
{
    
    public function swipe(Request $request){ //request als Parameter entgegen nehmen
        $user = Auth::user(); //eingeloggten Nutzer zuruckgeben 
        $targetUserId = $request->input('to_user_id'); //id des Nutzers den der aktuelle User Swiped 
        $liked = $request->input('liked'); //gibt an ob es ein right oder left swipe ist

        $existingSwipe = Swipe::where('from_user_id',$user->id) //überprüft ob der Nutzer die andere Person schon geswiped hat
        ->where('to_user_id',$targetUserId)
        ->first(); //falls ja, wird der Swipe nicht doppelt gespeichert

        if ($existingSwipe){
            return response()->json(['message'=>'Swipe already exists'],400); //Fehlermeldung 400 Bad Request

        }

        Swipe::create([ // neuen eintrag in der swipes tabelle mit... 
            'from_user_id' => $user->id,
            'to_user_id' => $targetUserId,
            'liked' => $liked,

        ]);

        return response()->json(['message' => 'Swipe gespeichert'],201); //gibts eine json antwort zurück wenn der Swipe erolgreich gespeichert wurde
        //201 bedeutet created. 

    }
    public function getMatches(){ //alle Matches abrufen bzw ein match entsteht wenn sich zwei Nutzer gegenseitg liken 

        $userId = Auth::id(); //id holen 

        $matches = DB::table('swipes as s1') //s1 ist ein Alias für die erste Swipe Tabelle 
        ->join('swipes as s2',function ($join) { //s2 ist ein Alis für eine zweite Kopie der Swipe Tabl
            $join->on('s1.from_user_id','=','s2.to_user_id') //der join verbindet Swipes bei denen person a person b geliked und person b person a 
            ->on('s1.to_user_id','=','s2.from_user_id');
        })
        ->where('s1.liked',true) //nur liked bzw true berücksichtigen 
        ->where('s2.liked',true)
        ->where(function ($query) use ($userId){
            $query->where('s1.from_user_id',$userId) //der user kann entweder derjenige sein der den Swipe gemacht hat
                   ->orWhere('s1.to_user_id',$userId); //oder derjenige der geswiped wurde 
        })
        ->select('s1.from_user_id as user1','s1.to_user_id as user2') //die matches enthalten derjenige der den ersten swipe gemacht hat und derjenige der den zweiten gemacht hat
        ->get();

        return response()->json($matches);
    }
}


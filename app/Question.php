<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Question extends BaseModel
{
    //We dont want user as the name
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function answers(){
        return $this->hasMany(Answer::class);
    }

    public function favourites()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }


    /**
     * Morphed relation
     */

    public function votes()
    {
        return $this->morphToMany(User::class,'vote')->withTimestamps();
    }

    //Mutator, kyu use karre kyuki meko koi toh event chahye like apne case me title tha and slug is related to slug title: 'hi lraveel' slug 'hi-lraveel' iseleye relate karle

//    The syntax is as followed abhi hamne isko call kiya iseleye apne toh apni responsibility hai dalne ki title ko
    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }

    //accessor method

    public function getUrlAttribute(){
        return "/questions/{$this->slug}";
    }

    public function getCreatedDateAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getAnswersStylesAttribute()
    {
        if ($this->answers_count > 0)
        {
            if($this->best_answer_id)
            {
                return "has-best-answer";
            }
            return "answered";

        }
        return "unanswered";
    }

    public function markBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getIsFavouriteAttribute()
    {
        //checking current user marked it as fav or not
        return $this->favourites()->where('user_id',Auth::id())->count() > 0;
    }

    /*Using morph relation to vote a question using the vote */


    public function vote(int $vote){
        $this->votes()->attach(auth()->id(),['vote'=>$vote]);
        if($vote < 0){
            $this->decrement('votes_count');
        }
        else{
            $this->increment('votes_count');
        }
    }

    public function updateVote($vote){
        $this->votes()->updateExistingPivot(auth()->id(), ['vote'=>$vote]);
        if($vote < 0){

            $this->decrement('votes_count');
            $this->decrement('votes_count');
        }
        else{
            $this->increment('votes_count');
            $this->increment('votes_count');
        }
    }
}
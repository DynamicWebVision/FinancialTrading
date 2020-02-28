<?php namespace App\Model\Marketing;

use Illuminate\Database\Eloquent\Model;

class ScrapedLeads extends Model {

    protected $table = 'scraped_leads';

    protected $fillable = ['email'];

}

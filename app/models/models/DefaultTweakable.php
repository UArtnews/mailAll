<?php

class DefaultTweakable extends Eloquent{
    
    protected $guarded = array('id');

    protected $table = 'default_tweakable';

    public $timestamps = true;

}